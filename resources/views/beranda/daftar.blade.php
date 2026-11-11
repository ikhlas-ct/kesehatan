@extends('layouts.front')

@section('title', ($kategori ? $kategori . ' - ' : '') . 'Artikel - SehatKita')

@section('content')

    <div class="layout single">
        <section aria-labelledby="judul-daftar">
            <h1 class="section-title" id="judul-daftar">{{ $kategori ? 'Artikel ' . $kategori : 'Semua artikel' }}</h1>

            <div class="meta filter">
                <a class="chip" href="{{ route('publik.konten.index') }}" @unless ($kategori) aria-current="page" @endunless>Semua</a>
                @foreach ($semuaKategori as $nama)
                    <a class="chip" href="{{ route('publik.konten.index', ['kategori' => $nama]) }}" @if ($kategori === $nama) aria-current="page" @endif>{{ $nama }}</a>
                @endforeach
            </div>

            @forelse ($artikel as $item)
                @if ($loop->first)
                    <div class="post-grid">
                @endif

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
                        <span>{{ $item['waktu'] }}</span>
                    </div>
                    <h3><a href="{{ $item['url'] }}">{{ $item['judul'] }}</a></h3>
                    <p>{{ $item['ringkasan'] }}</p>
                </article>

                @if ($loop->last)
                    </div>
                @endif
            @empty
                <p>Belum ada artikel{{ $kategori ? ' untuk kategori ini' : '' }}.</p>
            @endforelse

            @if ($artikel->hasPages())
                <nav class="pagination" aria-label="Halaman">
                    @foreach (range(1, $artikel->lastPage()) as $halaman)
                        <a href="{{ $artikel->url($halaman) }}" @if ($halaman === $artikel->currentPage()) aria-current="page" @endif>{{ $halaman }}</a>
                    @endforeach
                </nav>
            @endif
        </section>
    </div>

@endsection
