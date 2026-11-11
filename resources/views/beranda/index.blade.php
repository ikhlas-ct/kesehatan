@extends('layouts.front')

@section('title', 'SehatKita - Blog Kesehatan')

@section('content')

    {{-- ===== HERO ===== --}}
    <section class="hero">
        <div class="hero-panel">
            <svg class="pulse" viewBox="0 0 1000 90" preserveAspectRatio="none" aria-hidden="true">
                <path pathLength="100" vector-effect="non-scaling-stroke" d="M0 55H230l18-30 22 62 24-80 20 48h90l12-16 12 16H1000"/>
            </svg>

            <div>
                <h1>Informasi kesehatan yang jelas, tanpa istilah yang membingungkan</h1>
                <p class="lead">Tulisan tentang gizi, olahraga, tidur, dan kesehatan mental yang disusun dari sumber terpercaya dengan bahasa sehari-hari.</p>
                <div class="actions">
                    <a href="{{ $artikelUtama['url'] ?? route('publik.konten.index') }}" class="btn">Baca artikel terbaru</a>
                    <a href="#topik" class="btn alt">Jelajahi topik</a>
                </div>
            </div>

            {{-- Artikel unggulan --}}
            @if ($artikelUtama)
                <article class="featured">
                    <a class="thumb" href="{{ $artikelUtama['url'] }}" tabindex="-1" aria-hidden="true">
                        @if (!empty($artikelUtama['gambar']))
                            <img src="{{ $artikelUtama['gambar'] }}" alt="">
                        @else
                            <svg class="ico"><use href="#i-{{ $artikelUtama['ikon'] }}"/></svg>
                        @endif
                    </a>
                    <h2><a href="{{ $artikelUtama['url'] }}">{{ $artikelUtama['judul'] }}</a></h2>
                    <div class="meta">
                        <a class="chip" href="{{ route('publik.konten.index', ['kategori' => $artikelUtama['kategori']]) }}">{{ $artikelUtama['kategori'] }}</a>
                        <span>{{ $artikelUtama['tanggal'] }}</span>
                        <span>{{ $artikelUtama['waktu'] }}</span>
                    </div>
                </article>
            @endif
        </div>

        {{-- ===== TOPIK ===== --}}
        <nav class="topics" id="topik" aria-label="Topik">
            @foreach ($topik as $t)
                <a class="topic" href="{{ $t['url'] }}">
                    <span class="box {{ $t['warna'] }}"><svg class="ico"><use href="#i-{{ $t['ikon'] }}"/></svg></span>
                    <span><strong>{{ $t['nama'] }}</strong><small>{{ $t['ket'] }}</small></span>
                </a>
            @endforeach
        </nav>
    </section>

    {{-- ===== DAFTAR ARTIKEL + SIDEBAR KANAN ===== --}}
    <div class="layout">

        <section id="artikel" aria-labelledby="judul-terbaru">
            <h2 class="section-title" id="judul-terbaru">Artikel terbaru</h2>

            <div class="post-grid">
                @foreach ($artikelTerbaru as $artikel)
                    {{-- artikel pertama tampil sebagai kartu lebar --}}
                    <article class="card {{ $loop->first ? 'wide' : '' }}">
                        <a class="thumb {{ $artikel['warna'] }}" href="{{ $artikel['url'] }}" tabindex="-1" aria-hidden="true">
                            @if (!empty($artikel['gambar']))
                                <img src="{{ $artikel['gambar'] }}" alt="">
                            @else
                                <svg class="ico"><use href="#i-{{ $artikel['ikon'] }}"/></svg>
                            @endif
                        </a>

                        <div>
                            <div class="meta">
                                <a class="chip" href="{{ route('publik.konten.index', ['kategori' => $artikel['kategori']]) }}">{{ $artikel['kategori'] }}</a>
                                <span>{{ $artikel['tanggal'] }}</span>
                            </div>
                            <h3><a href="{{ $artikel['url'] }}">{{ $artikel['judul'] }}</a></h3>
                            <p>{{ $artikel['ringkasan'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>

            <p><a class="btn" href="{{ route('publik.konten.index') }}">Lihat semua artikel</a></p>

            {{-- Pagination: aktifkan setelah controller memakai paginate(), lalu: {{ $artikelTerbaru->links() }} --}}
        </section>

        <aside>
            <div class="widget">
                <h3>Tiga kebiasaan kecil hari ini</h3>
                <ul>
                    @foreach ($kebiasaan as $item)
                        <li><svg class="ico"><use href="#i-check"/></svg>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        </aside>

    </div>

@endsection
