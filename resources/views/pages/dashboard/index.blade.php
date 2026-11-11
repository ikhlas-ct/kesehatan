@extends('layouts.user.user')

@section('title', 'Dashboard')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, label, .btn, .table { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root { --accent:#1a73e8; --accent-light:#e8f0fe; --accent-shadow:rgba(26,115,232,.12); }

    /* Stat cards */
    .stat-card { border:none; border-radius:16px; padding:18px 20px; position:relative; overflow:hidden; transition:transform .2s, box-shadow .2s; box-shadow:0 2px 12px rgba(0,0,0,.07); }
    .stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,.12); }
    .stat-card::after { content:''; position:absolute; right:-16px; top:-16px; width:72px; height:72px; border-radius:50%; opacity:.14; }
    .stat-card.blue   { background:linear-gradient(135deg,#e8f0fe,#dbeafe); } .stat-card.blue::after   { background:#1a73e8; }
    .stat-card.amber  { background:linear-gradient(135deg,#fffbeb,#fef3c7); } .stat-card.amber::after  { background:#d97706; }
    .stat-card.green  { background:linear-gradient(135deg,#e6f9f0,#d1fae5); } .stat-card.green::after  { background:#16a34a; }
    .stat-card.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); } .stat-card.purple::after { background:#7c3aed; }
    .stat-icon { width:44px; height:44px; border-radius:11px; flex-shrink:0; display:inline-flex; align-items:center; justify-content:center; font-size:1.1rem; color:#fff; }
    .stat-icon.blue { background:#1a73e8; } .stat-icon.amber { background:#d97706; } .stat-icon.green { background:#16a34a; } .stat-icon.purple { background:#7c3aed; }
    .stat-value { font-size:1.7rem; font-weight:800; color:#1e293b; line-height:1; }
    .stat-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#64748b; margin-top:2px; }

    /* Page header */
    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:16px 20px; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:1.25rem; position:relative; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:var(--accent); }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; background:var(--accent-light); color:var(--accent); }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; letter-spacing:-.2px; line-height:1.2; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; flex-wrap:wrap; margin:4px 0 0; list-style:none; padding:0; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* Welcome */
    .welcome-card { border:none; border-radius:16px; padding:22px 26px; background:linear-gradient(135deg,#1a73e8,#1558b0); color:#fff; box-shadow:0 4px 16px rgba(26,115,232,.3); margin-bottom:1.25rem; }
    .welcome-card h4 { font-weight:800; margin:0 0 4px; color:#fff; }
    .welcome-card p { margin:0; font-size:.85rem; opacity:.9; }

    /* Main card */
    .main-card { border:none; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.07); overflow:hidden; }
    .main-card .card-header { background:#fff; border-bottom:1px solid #f1f5f9; padding:16px 22px; }
    .main-card .card-header h5 { font-size:.93rem; font-weight:700; color:#1e293b; }

    /* Table */
    .table { font-size:.82rem; margin-bottom:0; }
    .table thead th { background:#f8fafc; color:#475569; font-weight:700; font-size:.7rem; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e2e8f0; padding:11px 14px; white-space:nowrap; border-top:none; }
    .table tbody td { padding:12px 14px; vertical-align:middle; border-bottom:1px solid #f1f5f9; color:#334155; }
    .table tbody tr:hover td { background:#f8fafc; }
    .table tbody tr:last-child td { border-bottom:none; }

    .thumb { width:68px; height:50px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0; flex-shrink:0; }
    .thumb-placeholder { width:68px; height:50px; border-radius:8px; border:1px solid #e2e8f0; background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#94a3b8; font-size:.7rem; }

    .badge { font-size:.7rem; font-weight:600; padding:4px 9px; border-radius:20px; }
    .badge-kategori { background:#dbeafe; color:#1d4ed8; }

    .btn-primary { background:linear-gradient(135deg,#1a73e8,#1558b0); border:none; border-radius:10px; font-weight:600; font-size:.83rem; padding:8px 18px; transition:all .2s; box-shadow:0 2px 8px rgba(26,115,232,.35); }
    .btn-primary:hover { transform:translateY(-1px); filter:brightness(1.07); }
    .btn-outline-secondary { border-radius:10px; font-size:.82rem; border-color:#e2e8f0; color:#64748b; padding:7px 12px; }

    .empty-state { padding:48px 24px; text-align:center; }
    .empty-icon { width:60px; height:60px; border-radius:14px; background:#f1f5f9; color:#94a3b8; display:flex; align-items:center; justify-content:center; font-size:1.4rem; margin:0 auto 14px; }

    .alert { border:none; border-radius:12px; font-size:.84rem; }
    .alert-success { background:#dcfce7; color:#166534; }
    .alert-danger  { background:#fee2e2; color:#991b1b; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-home"></i></div>
            <div>
                <h5 class="ph-title">Dashboard</h5>
                <ol class="ph-breadcrumb">
                    <li><span class="bc-active">Dashboard</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('konten.create') }}" class="btn btn-primary btn-sm px-3">
            <i class="fas fa-plus me-1"></i> Tambah Konten
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Welcome --}}
    <div class="welcome-card">
        <h4>Halo, {{ auth()->user()->username }}!</h4>
        <p>Selamat datang di panel admin. Kelola konten website dari sini.</p>
    </div>

    {{-- Stat cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card blue">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon blue"><i class="fas fa-newspaper"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['total'] }}</div>
                        <div class="stat-label">Total Konten</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card green">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon green"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['bulan_ini'] }}</div>
                        <div class="stat-label">Terbit Bulan Ini</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card amber">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon amber"><i class="fas fa-tags"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['kategori'] }}</div>
                        <div class="stat-label">Kategori</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card purple">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon purple"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="stat-value">{{ $stats['penulis'] }}</div>
                        <div class="stat-label">Pengguna</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Konten terbaru --}}
    <div class="card main-card">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="mb-0"><i class="fas fa-clock me-2" style="color:var(--accent)"></i> Konten Terbaru</h5>
            <a href="{{ route('konten.index') }}" class="btn btn-outline-secondary btn-sm">
                Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="80">Sampul</th>
                        <th>Judul & Penulis</th>
                        <th>Kategori</th>
                        <th>Terbit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kontenTerbaru as $item)
                    <tr>
                        <td>
                            @if($item->gambar_sampul)
                                <img src="{{ asset('storage/' . $item->gambar_sampul) }}" alt="{{ $item->judul }}" class="thumb">
                            @else
                                <div class="thumb-placeholder"><i class="fas fa-image"></i></div>
                            @endif
                        </td>

                        <td>
                            <div class="fw-semibold" style="font-size:.84rem;color:#1e293b;max-width:320px;">
                                {{ \Illuminate\Support\Str::limit($item->judul, 60) }}
                            </div>
                            <div style="font-size:.73rem;color:#94a3b8;margin-top:2px;">
                                <i class="fas fa-user me-1"></i>{{ $item->penulis?->username ?? '-' }}
                            </div>
                        </td>

                        <td>
                            @if($item->kategori)
                                <span class="badge badge-kategori">{{ $item->kategori }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <td style="white-space:nowrap;">
                            {{ $item->tanggal_terbit?->translatedFormat('d M Y') ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-0">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-newspaper"></i></div>
                                <div class="fw-semibold text-secondary mb-1">Belum ada konten</div>
                                <div class="text-muted" style="font-size:.8rem;">Mulai dengan menambahkan konten pertama</div>
                                <a href="{{ route('konten.create') }}" class="btn btn-primary btn-sm mt-3">
                                    <i class="fas fa-plus me-1"></i> Tambah Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
