<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Pastikan user hanya bisa akses profilnya sendiri
    private function authorizeUser(Request $request, string $id): User
    {
        $user = User::findOrFail($id);

        if ($request->user()->id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        return $user;
    }

    public function show(Request $request, string $id)
    {
        $user = $this->authorizeUser($request, $id);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar ? Storage::url($user->avatar) : null,
            'country' => $user->country,
            'province' => $user->province,
            'city' => $user->city,
            'district' => $user->district,
            'postal_code' => $user->postal_code,
        ]);
    }

    public function updateInfo(Request $request, string $id)
    {
        $user = $this->authorizeUser($request, $id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'avatar' => $user->avatar ? Storage::url($user->avatar) : null,
        ]);
    }

    public function updatePassword(Request $request, string $id)
    {
        $user = $this->authorizeUser($request, $id);

        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'Password lama tidak sesuai.'], 422);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['message' => 'Password berhasil diubah.']);
    }

    public function updateAddress(Request $request, string $id)
    {
        $user = $this->authorizeUser($request, $id);

        $validated = $request->validate([
            'country' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|min:5|max:10',
        ]);

        $user->update($validated);

        return response()->json(['message' => 'Alamat berhasil disimpan.']);
    }
}
