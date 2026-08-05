<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{

    private function otpHash(string $email): string
    {
        return hash_hmac('sha256', $email, config('app.key'));
    }

    private function generateAndSendOtp($user)
    {
        $otp = rand(100000, 999999);
        $cacheKey = 'otp_' . $user->email;
        $hash = $this->otpHash($user->email);

        Cache::put($cacheKey, Hash::make($otp), now()->addMinutes(1));
        Cache::put('otp_hash_' . $hash, $user->email, now()->addMinutes(5));

        Mail::to($user->email)->send(new SendOtpMail($otp));

        return $hash;
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }
    public function loginWithPassword(Request $request) {
        $request->validate([
            'email'=> 'required|email',
            'password'=> 'required',
            'g-recaptcha-response' => 'required'
        ]);

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip()
        ]);

        $googleResult = $response->json();

        if (!$googleResult['success']) {
            return response()->json([
                'message' => 'Verifikasi Captcha gagal atau kadaluwarsa, silakan coba lagi.'
            ], 422);
        }

        if (!Auth::attempt($request->only('email','password'))) {
            return response()->json(['message'=> 'Invalid credentials'], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'=> 'Login successfully',
            'access_token'=> $token,
            ], 200);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        $hash = $this->generateAndSendOtp($user);

        return response()->json(['message' => 'OTP has been delivered', 'hash' => $hash], 200);
    }

    public function verifyOtp(Request $request) {

        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|string|digits:6',
            'hash' => 'required|string|size:64'
        ]);

        $email = $request->email;
        $hash = $request->hash;

        $cachedEmail = Cache::get('otp_hash_' . $hash);
        if (!$cachedEmail || $cachedEmail !== $email) {
            return response()->json(['message' => 'Invalid or expired OTP access'], 404);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        $cacheKey = 'otp_' . $email;
        $cachedOtp = Cache::get($cacheKey);

        if ($cachedOtp && Hash::check($request->otp_code, $cachedOtp)) {
            Cache::forget($cacheKey);
            Cache::forget('otp_hash_' . $hash);

            Auth::login($user);
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'OTP verified successfully',
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        }

        return response()->json(['message' => 'Invalid or expired OTP'], 400);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'User logged out successfully'], 200);
    }
}
