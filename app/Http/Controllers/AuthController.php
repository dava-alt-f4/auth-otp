<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{

    private function generateAndSendOtp($user) {
        $otp = rand(100000, 999999);

        $cacheKey = 'otp_' . $user->email;

        Cache::put($cacheKey, $otp, now()->addMinutes(1));

        Mail::to($user->email)->send(new SendOtpMail($otp));
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
        $credentials = $request->validate([
            'email'=> 'required|email',
            'password'=> 'required',
        ]);

        if (!Auth::attempt($credentials)) {
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

        $this->generateAndSendOtp($user);

        return response()->json(['message'=> 'OTP has been delivered'], 200);
    }

        public function verifyOtp(Request $request) {

        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|string|digits:6'
        ]);
        $email = $request->email;

        $user = User::where('email', $email)->first();


        if (!$user) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        if (!$email) {
            return redirect()->route('login')->withErrors(['otp_code' => 'No email found in session. Please login again.']);
        }

        $cacheKey = 'otp_' . $email;
        $cachedOtp = Cache::get($cacheKey);

        if ($cachedOtp && $cachedOtp == $request->otp_code) {
            Cache::forget($cacheKey);

            Auth::login($user);
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'OTP verified successfully',
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        } else {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }
    }

    public function forgotPassword(Request $request) {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::ResetLinkSent
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'User logged out successfully'], 200);
    }
}
