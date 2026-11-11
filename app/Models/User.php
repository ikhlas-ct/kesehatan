<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'bio',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Alamat foto siap pakai di <img>, atau null kalau belum ada foto
    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    // Inisial cadangan, misalnya "Nama Penulis" menjadi "NP"
    public function getInisialAttribute(): string
    {
        return collect(explode(' ', $this->username))
            ->take(2)
            ->map(fn ($kata) => strtoupper(mb_substr($kata, 0, 1)))
            ->join('');
    }

    public function konten()
    {
        return $this->hasMany(Konten::class);
    }
}
