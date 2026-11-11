<?php

namespace App\Http\Controllers;

use App\Models\Konten;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KontenController extends Controller
{
    // Folder di disk "public" (storage/app/public/...)
    private const FOLDER_SAMPUL = 'konten/sampul';
    private const FOLDER_ISI    = 'konten/isi';

    // ── Daftar ───────────────────────────────────────────────────
    public function index(Request $request): View
    {
        $konten = Konten::with('penulis')
            ->when($request->filled('search'), function ($q) use ($request) {
                $kata = $request->search;
                $q->where(fn ($w) => $w
                    ->where('judul', 'like', "%{$kata}%")
                    ->orWhere('ringkasan', 'like', "%{$kata}%"));
            })
            ->when($request->filled('kategori'), fn ($q) => $q->where('kategori', $request->kategori))
            ->orderByDesc('tanggal_terbit')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total'      => Konten::count(),
            'bulan_ini'  => Konten::whereYear('tanggal_terbit', now()->year)
                                  ->whereMonth('tanggal_terbit', now()->month)
                                  ->count(),
            'kategori'   => Konten::whereNotNull('kategori')->where('kategori', '!=', '')
                                  ->distinct()->count('kategori'),
        ];

        return view('pages.konten.index', [
            'konten'         => $konten,
            'stats'          => $stats,
            'daftarKategori' => $this->daftarKategori(),
        ]);
    }

    // ── Tambah ───────────────────────────────────────────────────
    public function create(): View
    {
        return view('pages.konten.create', [
            'daftarKategori' => $this->daftarKategori(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validasi($request);

        $data['user_id']        = Auth::id();
        $data['slug']           = $this->buatSlug($data['judul']);
        $data['tanggal_terbit'] = $data['tanggal_terbit'] ?? now();

        if ($request->hasFile('gambar_sampul')) {
            $data['gambar_sampul'] = $request->file('gambar_sampul')
                ->store(self::FOLDER_SAMPUL, 'public');
        }

        Konten::create($data);

        return redirect()->route('konten.index')
            ->with('success', 'Konten berhasil ditambahkan.');
    }

    // ── Edit ─────────────────────────────────────────────────────
    public function edit(Konten $konten): View
    {
        return view('pages.konten.edit', [
            'konten'         => $konten,
            'daftarKategori' => $this->daftarKategori(),
        ]);
    }

    public function update(Request $request, Konten $konten): RedirectResponse
    {
        $data = $this->validasi($request);

        // Slug sengaja tidak diubah supaya link lama tidak rusak.
        $data['tanggal_terbit'] = $data['tanggal_terbit'] ?? $konten->tanggal_terbit ?? now();

        // Gambar sampul: ganti, atau hapus bila dicentang
        if ($request->hasFile('gambar_sampul')) {
            $this->hapusFile($konten->gambar_sampul);
            $data['gambar_sampul'] = $request->file('gambar_sampul')
                ->store(self::FOLDER_SAMPUL, 'public');
        } elseif ($request->boolean('hapus_sampul')) {
            $this->hapusFile($konten->gambar_sampul);
            $data['gambar_sampul'] = null;
        }

        // Gambar di dalam isi yang sudah tidak dipakai lagi -> hapus dari storage
        $lama  = $this->gambarDalamIsi($konten->isi);
        $baru  = $this->gambarDalamIsi($data['isi']);
        foreach (array_diff($lama, $baru) as $path) {
            $this->hapusFile($path);
        }

        $konten->update($data);

        return redirect()->route('konten.index')
            ->with('success', 'Konten berhasil diperbarui.');
    }

    // ── Hapus ────────────────────────────────────────────────────
    public function destroy(Konten $konten): RedirectResponse
    {
        $this->hapusFile($konten->gambar_sampul);

        foreach ($this->gambarDalamIsi($konten->isi) as $path) {
            $this->hapusFile($path);
        }

        $konten->delete();

        return redirect()->route('konten.index')
            ->with('success', 'Konten berhasil dihapus.');
    }

    // ── Upload gambar dari Summernote ────────────────────────────
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:3072'],
        ]);

        $path = $request->file('image')->store(self::FOLDER_ISI, 'public');

        return response()->json([
            'url' => asset('storage/' . $path),
        ]);
    }

    // ═════════════════════════════ Helper ═════════════════════════

    private function validasi(Request $request): array
    {
        return $request->validate([
            'judul'          => ['required', 'string', 'max:255'],
            'kategori'       => ['nullable', 'string', 'max:100'],
            'ringkasan'      => ['nullable', 'string', 'max:255'],
            'isi'            => ['required', 'string'],
            'tanggal_terbit' => ['nullable', 'date'],
            'gambar_sampul'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ], [
            'judul.required'         => 'Judul wajib diisi.',
            'isi.required'           => 'Isi konten wajib diisi.',
            'gambar_sampul.image'    => 'Gambar sampul harus berupa gambar.',
            'gambar_sampul.mimes'    => 'Format gambar sampul harus JPG, PNG, atau WEBP.',
            'gambar_sampul.max'      => 'Ukuran gambar sampul maksimal 3 MB.',
            'tanggal_terbit.date'    => 'Format tanggal terbit tidak valid.',
        ]);
    }

    private function daftarKategori()
    {
        return Konten::whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');
    }

    // Slug unik: "judul-konten", "judul-konten-2", dst.
    private function buatSlug(string $judul): string
    {
        $dasar = Str::slug($judul) ?: 'konten';
        $slug  = $dasar;
        $i     = 2;

        while (Konten::where('slug', $slug)->exists()) {
            $slug = $dasar . '-' . $i++;
        }

        return $slug;
    }

    private function hapusFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Ambil path (relatif ke disk "public") semua gambar upload Summernote
     * yang dipakai di dalam HTML isi konten.
     */
    private function gambarDalamIsi(?string $html): array
    {
        if (! $html) {
            return [];
        }

        preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $cocok);

        $penanda = '/storage/' . self::FOLDER_ISI . '/';

        return collect($cocok[1])
            ->map(fn ($src) => parse_url($src, PHP_URL_PATH) ?: '')
            ->filter(fn ($p) => str_contains($p, $penanda) && ! str_contains($p, '..'))
            ->map(fn ($p) => substr($p, strpos($p, $penanda) + strlen('/storage/')))
            ->unique()
            ->values()
            ->all();
    }
}
