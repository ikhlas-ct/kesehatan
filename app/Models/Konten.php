<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Konten extends Model
{
    protected $table = 'konten';

    protected $fillable = [
        'user_id', 'judul', 'slug', 'kategori', 'ringkasan',
        'isi', 'gambar_sampul', 'tanggal_terbit',
    ];

    protected $casts = [
        'tanggal_terbit' => 'datetime',
    ];

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Hanya konten yang tanggal terbitnya sudah tiba
    public function scopeTerbit(Builder $q): Builder
    {
        return $q->where('tanggal_terbit', '<=', now());
    }

    public function getSampulUrlAttribute(): ?string
    {
        return $this->gambar_sampul ? asset('storage/' . $this->gambar_sampul) : null;
    }
}
