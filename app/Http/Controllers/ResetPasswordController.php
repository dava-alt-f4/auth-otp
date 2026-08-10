<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    private function translateStatus($status) {
        return match($status) {
            Password::RESET_LINK_SENT => 'Tautan reset password telah dikirim ke email Anda.',
            Password::PASSWORD_RESET => 'Password berhasil direset.',
            Password::INVALID_USER => 'Email tidak ditemukan.',
            Password::INVALID_TOKEN => 'Token reset password tidak valid atau sudah kedaluwarsa.',
            'passwords.throttled' => 'Harap tunggu sebelum mencoba lagi.',
            default => 'Terjadi kesalahan.'
        };
    }

    public function sendResetUrl(Request $request) {
        $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => $this->translateStatus($status)])
        : back()->withErrors(['email' => $this->translateStatus($status)]);
    }

    public function resetPassword(Request $request) {
        $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ], [
        'token.required' => 'Token wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.'
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('passwordLogin')->with('status', $this->translateStatus($status))
        : back()->withErrors(['email' => [$this->translateStatus($status)]]);

    }
}
