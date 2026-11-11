<!-- Sidebar -->
<div class="sidebar-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
        <a href="{{ route('admin.dashboard') }}" class="logo d-flex align-items-center">
            {{-- Gunakan accessor logo_url agar path storage/ selalu benar --}}
            @if($settings->logo_url)
                <img src="{{ $settings->logo_url }}"
                    alt="navbar brand" class="navbar-brand" height="50" />
            @endif
            <span class="ms-2 text-white">{{ $settings->nama ?? 'Nama Website' }}</span>
        </a>

        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
            </button>
            <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
            </button>
        </div>
        <button class="topbar-toggler more">
            <i class="gg-more-vertical-alt"></i>
        </button>
    </div>
    <!-- End Logo Header -->
</div>

<div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
        <ul class="nav nav-secondary">

            <!-- Dashboard -->
            <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-home"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <!-- Konten -->
            <li class="nav-item {{ request()->routeIs('konten.*') ? 'active' : '' }}">
                <a href="{{ route('konten.index') }}">
                    <i class="fas fa-newspaper"></i>
                    <p>Konten</p>
                </a>
            </li>

            <!-- Pengaturan Website -->
            <li class="nav-item {{ request()->routeIs('website-setting.*') ? 'active' : '' }}">
                <a href="{{ route('website-setting.edit') }}">
                    <i class="fas fa-cog"></i>
                    <p>Pengaturan Website</p>
                </a>
            </li>

            <!-- Profil -->
            <li class="nav-item {{ request()->routeIs('profil.*') ? 'active' : '' }}">
                <a href="{{ route('profil.edit') }}">
                    <i class="fas fa-user-circle"></i>
                    <p>Profil</p>
                </a>
            </li>

            <!-- Lihat Beranda (halaman publik) -->
            <li class="nav-item">
                <a href="{{ route('beranda') }}" target="_blank" rel="noopener">
                    <i class="fas fa-globe"></i>
                    <p>Lihat Beranda</p>
                </a>
            </li>

            <!-- Logout -->
            <li class="nav-item mt-4">
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <p>Logout</p>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>

        </ul>
    </div>
</div>
