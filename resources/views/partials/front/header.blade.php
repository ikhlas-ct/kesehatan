<header class="site-header">
    <div class="wrap">
        <a href="{{ route('beranda') }}" class="logo">
            <span class="logo-mark"><svg class="ico"><use href="#i-plus"/></svg></span>SehatKita
        </a>
        <nav class="nav" aria-label="Navigasi utama">
            <a href="{{ route('beranda') }}" @if (request()->routeIs('beranda')) aria-current="page" @endif>Beranda</a>
            <a href="{{ route('beranda') }}#artikel">Artikel</a>
        </nav>
    </div>
</header>
