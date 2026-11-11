<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    /**
     * Tampilkan form edit profil milik user yang sedang login.
     */
    public function edit(Request $request)
    {
        return view('pages.profil.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Simpan perubahan profil (data diri, foto, dan password opsional).
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'username'         => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'email'            => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'bio'              => ['nullable', 'string', 'max:500'],
            'foto'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_foto'       => ['nullable', 'boolean'],

            // Password bersifat opsional: hanya diproses kalau diisi
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password'         => ['nullable', 'confirmed', Password::min(8)],
        ], [
            'username.required'             => 'Username wajib diisi.',
            'username.unique'               => 'Username sudah dipakai pengguna lain.',
            'email.required'                => 'Email wajib diisi.',
            'email.email'                   => 'Format email tidak valid.',
            'email.unique'                  => 'Email sudah dipakai pengguna lain.',
            'bio.max'                       => 'Bio maksimal 500 karakter.',
            'foto.image'                    => 'File harus berupa gambar.',
            'foto.mimes'                    => 'Foto harus berformat JPG, PNG, atau WEBP.',
            'foto.max'                      => 'Ukuran foto maksimal 2 MB.',
            'current_password.required_with'=> 'Password lama wajib diisi untuk mengganti password.',
            'current_password.current_password' => 'Password lama tidak sesuai.',
            'password.confirmed'            => 'Konfirmasi password baru tidak cocok.',
            'password.min'                  => 'Password baru minimal 8 karakter.',
        ]);

        $payload = [
            'username' => $data['username'],
            'email'    => $data['email'],
            'bio'      => $data['bio'] ?? null,
        ];

        // ── Foto ────────────────────────────────────────────
        if ($request->hasFile('foto')) {
            // Ganti foto: hapus yang lama, simpan yang baru
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $payload['foto'] = $request->file('foto')->store('profil', 'public');
        } elseif ($request->boolean('hapus_foto') && $user->foto) {
            // Hanya hapus foto tanpa mengganti
            Storage::disk('public')->delete($user->foto);
            $payload['foto'] = null;
        }

        // ── Password (cast 'hashed' di model otomatis meng-hash) ──
        if (! empty($data['password'])) {
            $payload['password'] = $data['password'];
        }

        $user->update($payload);

        return redirect()
            ->route('profil.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
