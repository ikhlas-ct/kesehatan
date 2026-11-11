<?php

namespace App\Http\Controllers;

use App\Models\Konten;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total'     => Konten::count(),
            'bulan_ini' => Konten::whereYear('tanggal_terbit', now()->year)
                                 ->whereMonth('tanggal_terbit', now()->month)
                                 ->count(),
            'kategori'  => Konten::whereNotNull('kategori')
                                 ->where('kategori', '!=', '')
                                 ->distinct()
                                 ->count('kategori'),
            'penulis'   => User::count(),
        ];

        $kontenTerbaru = Konten::with('penulis')
            ->orderByDesc('tanggal_terbit')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('pages.dashboard.index', compact('stats', 'kontenTerbaru'));
    }
}
