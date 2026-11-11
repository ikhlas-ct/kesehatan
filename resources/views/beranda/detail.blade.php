@extends('layouts.front')

@section('title', $artikel['judul'] . ' - SehatKita')

@section('content')

    <article class="paper">

        <nav class="crumbs" aria-label="Breadcrumb">
            <a href="{{ route('beranda') }}">Beranda</a> /
            <a href="{{ route('publik.konten.index') }}">Artikel</a> /
            <span>{{ $artikel['kategori'] }}</span>
        </nav>

        <a class="chip" href="{{ route('publik.konten.index', ['kategori' => $artikel['kategori']]) }}">{{ $artikel['kategori'] }}</a>

        <h1>{{ $artikel['judul'] }}</h1>

        <div class="meta">
            @if ($penulis)
                @if ($penulis->foto_url)
                    <img class="avatar" src="{{ $penulis->foto_url }}" alt="{{ $penulis->username }}">
                @else
                    <span class="avatar">{{ $penulis->inisial }}</span>
                @endif
                <span>{{ $penulis->username }}</span>
            @endif
            <span>{{ $artikel['tanggal'] }}</span>
            <span>{{ $artikel['waktu'] }}</span>
        </div>

        <figure class="cover">
            <div class="thumb {{ $artikel['warna'] }}">
                @if ($artikel['gambar'])
                    <img src="{{ $artikel['gambar'] }}" alt="{{ $artikel['judul'] }}">
                @else
                    <svg class="ico"><use href="#i-{{ $artikel['ikon'] }}"/></svg>
                @endif
            </div>
        </figure>

        <div class="article-grid">

            <div>
                <div class="article">
                    {{-- Isi dari editor Summernote (HTML), hanya diisi admin --}}
                    {!! $isi !!}
                </div>

                @if ($penulis)
                    <div class="author">
                        @if ($penulis->foto_url)
                            <img class="avatar" src="{{ $penulis->foto_url }}" alt="{{ $penulis->username }}">
                        @else
                            <span class="avatar">{{ $penulis->inisial }}</span>
                        @endif
                        <div>
                            <strong>{{ $penulis->username }}</strong>
                            @if ($penulis->bio)
                                <p>{{ $penulis->bio }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                <a class="to-top" href="#">Kembali ke atas</a>
            </div>

            @if (count($daftarIsi))
                <aside class="toc" aria-label="Daftar isi">
                    <strong>Di artikel ini</strong>
                    @foreach ($daftarIsi as $bagian)
                        <a href="#{{ $bagian['id'] }}">{{ $bagian['teks'] }}</a>
                    @endforeach
                </aside>
            @endif

        </div>

        @if ($terkait->isNotEmpty())
            <section class="related" aria-labelledby="judul-terkait">
                <h2 class="section-title" id="judul-terkait">Baca juga</h2>

                <div class="post-grid">
                    @foreach ($terkait as $item)
                        <article class="card">
                            <a class="thumb {{ $item['warna'] }}" href="{{ $item['url'] }}" tabindex="-1" aria-hidden="true">
                                @if ($item['gambar'])
                                    <img src="{{ $item['gambar'] }}" alt="">
                                @else
                                    <svg class="ico"><use href="#i-{{ $item['ikon'] }}"/></svg>
                                @endif
                            </a>
                            <div class="meta">
                                <a class="chip" href="{{ route('publik.konten.index', ['kategori' => $item['kategori']]) }}">{{ $item['kategori'] }}</a>
                                <span>{{ $item['tanggal'] }}</span>
                            </div>
                            <h3><a href="{{ $item['url'] }}">{{ $item['judul'] }}</a></h3>
                            <p>{{ $item['ringkasan'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

    </article>

@endsection
