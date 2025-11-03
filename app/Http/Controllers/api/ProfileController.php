<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'phone'         => 'required|numeric',
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:1024', // max 1MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            // Simpan foto jika ada upload
            if ($request->hasFile('profile_photo')) {
                // hapus foto lama jika ada
                if ($user->profile_photo_path && Storage::exists($user->profile_photo_path)) {
                    Storage::delete($user->profile_photo_path);
                }

                // simpan foto baru ke storage/app/public/profile
                $path = $request->file('profile_photo')->store('profile', 'public');
                $user->profile_photo_path = 'storage/' . $path;
            }

            // Update data user
            $user->name  = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            return response()->json([
                'message' => 'Profile berhasil diperbarui',
                'user'    => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan server',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
