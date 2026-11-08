<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Halaman Informasi Profil Pengguna
     */
    public function show(Request $request)
    {
        $user = $request->user();

        // Camat diarahkan ke halaman profil khusus Camat
        if ($user->role === 'camat') {
            return redirect()->route('camat.profil');
        }

        return view('profile.show', [
            'user' => $user,
        ]);
    }

    /**
     * Halaman Pengaturan Akun & Keamanan
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Halaman Ubah Email dan Kontak Akun
     */
    public function editData(Request $request)
    {
        return view('profile.edit-data', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Menyimpan Perubahan Email, Nomor Telepon, dan Foto Profil
     */
    public function updateData(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9\+\-\s\(\)\.\/]+$/'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'name' => ['nullable', 'string', 'max:255'],
            'nik' => ['nullable', 'string', 'max:20'],
        ], [
            'email.required' => 'Email akun tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'phone.required' => 'Nomor telepon tidak boleh kosong.',
            'phone.regex' => 'Format nomor telepon tidak valid. Gunakan format angka yang wajar.',
            'phone.max' => 'Nomor telepon maksimal 30 karakter.',
            'foto_profil.image' => 'File yang diunggah harus berupa gambar.',
            'foto_profil.mimes' => 'Format foto profil yang diizinkan hanya JPG, JPEG, dan PNG.',
            'foto_profil.max' => 'Ukuran foto profil maksimal adalah 2 MB.',
        ]);

        // Simpan foto profil baru jika ada file yang diunggah
        if ($request->hasFile('foto_profil') && $request->file('foto_profil')->isValid()) {
            $oldPhoto = $user->foto_profil;
            $newPhotoPath = $request->file('foto_profil')->store('profile-photos', 'public');
            $user->foto_profil = $newPhotoPath;

            // Hapus foto lama hanya setelah foto baru berhasil disimpan
            if ($oldPhoto && Storage::disk('public')->exists($oldPhoto)) {
                Storage::disk('public')->delete($oldPhoto);
            }
        }

        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        if ($request->filled('name')) {
            $user->name = $validated['name'];
        }
        if ($request->filled('nik')) {
            $user->nik = $validated['nik'];
        }
        $user->save();

        return redirect()
            ->route('account.profile')
            ->with('success', 'Informasi email, nomor telepon, dan foto profil berhasil diperbarui.');
    }

    /**
     * Halaman Ubah Password
     */
    public function editPassword()
    {
        return view('profile.edit-password');
    }

    /**
     * Menyimpan Perubahan Password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini harus diisi.',
            'current_password.current_password' => 'Password saat ini tidak cocok.',
            'password.required' => 'Password baru harus diisi.',
            'password.min' => 'Password baru minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('account.profile')
            ->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Halaman Pengaturan Preferensi Akun
     */
    public function settings()
    {
        return view('profile.settings');
    }
}
