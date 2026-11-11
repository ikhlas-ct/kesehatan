<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    // Nama tabel otomatis "website_settings", jadi tidak perlu $table

    protected $fillable = [
        'nama',
        'slogan',
        'alamat',
        'email',
        'nomor_telepon',
        'logo',
        'title_pengantar',
        'paragraf_pengantar',
    ];

    // Alamat logo siap pakai di <img>, atau null kalau belum ada logo
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    // Pengaturan website hanya satu baris, jadi ambil (atau buat) baris pertama
    public static function ambil(): self
    {
        return static::first() ?? static::create([
            'nama' => 'SehatKita',
        ]);
    }
}
