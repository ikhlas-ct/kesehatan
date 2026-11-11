<?php

namespace App\Http\Controllers;

use App\Models\Konten;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    // Ikon per kategori (nama = id <symbol> di sprite layout, tanpa "i-").
    // Kategori yang tidak ada di daftar ini memakai ikon 'heart'.
    private const IKON = [
        'Kesehatan' => 'heart',
        'Gizi'      => 'apple',
        'Olahraga'  => 'run',
        'Tidur'     => 'moon',
    ];

    private const WARNA = ['', 'sun', 'sky', 'blush'];

    /* =========================================================
     |  BERANDA  ( / )
     ========================================================= */
    public function index()
    {
        $semua = Konten::terbit()->latest('tanggal_terbit')->take(6)->get();

        $utama   = $semua->first();
        $terbaru = $semua->count() > 1 ? $semua->skip(1) : $semua;

        $topik = Konten::terbit()
            ->select('kategori', DB::raw('COUNT(*) as total'))
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->take(4)
            ->get()
            ->map(fn ($t) => [
                'nama'  => $t->kategori,
                'ket'   => $t->total . ' artikel',
                'ikon'  => $this->ikon($t->kategori),
                'warna' => $this->warna($t->kategori),
                'url'   => route('publik.konten.index', ['kategori' => $t->kategori]),
            ]);

        return view('beranda.index', [
            'settings'       => WebsiteSetting::ambil(),
            'artikelUtama'   => $utama ? $this->ubah($utama) : null,
            'artikelTerbaru' => $terbaru->map(fn ($k) => $this->ubah($k))->values(),
            'topik'          => $topik,
            'kebiasaan'      => [
                'Minum air putih 8 gelas',
                'Jalan kaki 20 menit',
                'Tidur sebelum jam 11 malam',
            ],
        ]);
    }

    /* =========================================================
     |  DAFTAR ARTIKEL  ( /informasi )
     ========================================================= */
    public function daftar(Request $request)
    {
        $kategori = $request->query('kategori');

        $artikel = Konten::terbit()
            ->when($kategori, fn ($q) => $q->where('kategori', $kategori))
            ->latest('tanggal_terbit')
            ->paginate(6)
            ->withQueryString();

        $artikel->through(fn ($k) => $this->ubah($k));

        $semuaKategori = Konten::terbit()
            ->select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');

        return view('beranda.daftar', [
            'settings'      => WebsiteSetting::ambil(),
            'artikel'       => $artikel,
            'semuaKategori' => $semuaKategori,
            'kategori'      => $kategori,
        ]);
    }

    /* =========================================================
     |  HALAMAN BACA  ( /informasi/{slug} )
     ========================================================= */
    public function detail(string $slug)
    {
        $konten = Konten::with('penulis')
            ->terbit()
            ->where('slug', $slug)
            ->firstOrFail();

        [$isi, $daftarIsi] = $this->susunIsi($konten->isi);

        // 3 artikel lain, yang sekategori diutamakan
        $terkait = Konten::terbit()
            ->where('id', '!=', $konten->id)
            ->orderByRaw('kategori = ? DESC', [$konten->kategori])
            ->latest('tanggal_terbit')
            ->take(3)
            ->get()
            ->map(fn ($k) => $this->ubah($k));

        return view('beranda.detail', [
            'settings'  => WebsiteSetting::ambil(),
            'artikel'   => $this->ubah($konten),
            'penulis'   => $konten->penulis,
            'isi'       => $isi,
            'daftarIsi' => $daftarIsi,
            'terkait'   => $terkait,
        ]);
    }

    /* =========================================================
     |  Helper
     ========================================================= */

    // Bentuk array yang dipakai view (sama dengan yang dipakai index.blade.php)
    private function ubah(Konten $k): array
    {
        return [
            'judul'     => $k->judul,
            'ringkasan' => $k->ringkasan,
            'kategori'  => $k->kategori,
            'gambar'    => $k->sampul_url,
            'ikon'      => $this->ikon($k->kategori),
            'warna'     => $this->warna($k->kategori),
            'tanggal'   => $k->tanggal_terbit->locale('id')->translatedFormat('j F Y'),
            'waktu'     => $this->waktuBaca($k->isi),
            'url'       => route('publik.konten.show', $k->slug),
        ];
    }

    private function ikon(string $kategori): string
    {
        return self::IKON[$kategori] ?? 'heart';
    }

    private function warna(string $kategori): string
    {
        return self::WARNA[crc32($kategori) % count(self::WARNA)];
    }

    private function waktuBaca(string $html): string
    {
        $kata = preg_match_all('/\p{L}+/u', strip_tags($html));

        return max(1, (int) ceil($kata / 200)) . ' menit baca';
    }

    /**
     * Beri id pada subjudul isi artikel (h2, atau h3 kalau tidak ada h2)
     * supaya bisa dipakai sebagai "Daftar isi" di samping artikel.
     *
     * @return array{0: string, 1: array<int, array{id: string, teks: string}>}
     */
    private function susunIsi(string $html): array
    {
        $tag    = str_contains($html, '<h2') ? 'h2' : 'h3';
        $daftar = [];
        $no     = 0;

        $isi = preg_replace_callback(
            "#<{$tag}\b([^>]*)>(.*?)</{$tag}>#is",
            function ($m) use (&$daftar, &$no, $tag) {
                $teks = html_entity_decode(strip_tags($m[2]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $teks = trim(preg_replace('/\s+/u', ' ', $teks));

                if ($teks === '') {
                    return $m[0];
                }

                $id       = 'bagian-' . (++$no);
                $daftar[] = ['id' => $id, 'teks' => $teks];

                return "<{$tag}{$m[1]} id=\"{$id}\">{$m[2]}</{$tag}>";
            },
            $html
        );

        return [$isi ?? $html, $daftar];
    }
}
