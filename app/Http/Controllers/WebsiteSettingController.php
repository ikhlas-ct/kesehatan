<?php

namespace App\Http\Controllers;

use App\Models\WebsiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WebsiteSettingController extends Controller
{
    /**
     * Tampilkan form edit pengaturan website.
     * Datanya hanya satu baris, jadi tidak ada index/create/show/destroy.
     */
    public function edit(): View
    {
        $setting = WebsiteSetting::ambil();

        return view('website-setting.edit', compact('setting'));
    }

    /**
     * Simpan perubahan pengaturan website.
     */
    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama'               => ['required', 'string', 'max:100'],
            'slogan'             => ['nullable', 'string', 'max:255'],
            'alamat'             => ['nullable', 'string', 'max:500'],
            'email'              => ['nullable', 'email', 'max:100'],
            'nomor_telepon'      => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'title_pengantar'    => ['nullable', 'string', 'max:150'],
            'paragraf_pengantar' => ['nullable', 'string', 'max:3000'],
            'logo'               => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'hapus_logo'         => ['nullable', 'boolean'],
        ], [
            'nama.required'         => 'Nama website wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'nomor_telepon.regex'   => 'Nomor telepon hanya boleh berisi angka, spasi, +, -, dan tanda kurung.',
            'logo.image'            => 'File logo harus berupa gambar.',
            'logo.mimes'            => 'Logo harus berformat JPG, PNG, WEBP, atau SVG.',
            'logo.max'              => 'Ukuran logo maksimal 2 MB.',
        ]);

        $setting = WebsiteSetting::ambil();

        // Logo ditangani terpisah supaya tidak tertimpa saat tidak ada perubahan
        unset($data['logo'], $data['hapus_logo']);

        // Hapus logo lama kalau dicentang "hapus logo"
        if ($request->boolean('hapus_logo') && $setting->logo) {
            Storage::disk('public')->delete($setting->logo);
            $data['logo'] = null;
        }

        // Upload logo baru (menggantikan yang lama)
        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $data['logo'] = $request->file('logo')->store('logo', 'public');
        }

        $setting->update($data);

        return redirect()
            ->route('website-setting.edit')
            ->with('success', 'Pengaturan website berhasil diperbarui.');
    }
}
