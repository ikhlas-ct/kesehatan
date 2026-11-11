@extends('layouts.user.user')

@section('title', 'Pengaturan Website')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, label, .btn, .form-control { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root { --accent:#1a73e8; --accent-light:#e8f0fe; --accent-shadow:rgba(26,115,232,.12); }

    /* Page header */
    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:16px 20px; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:1.25rem; position:relative; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:var(--accent); }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; background:var(--accent-light); color:var(--accent); }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; letter-spacing:-.2px; line-height:1.2; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; flex-wrap:wrap; margin:4px 0 0; list-style:none; padding:0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* Main card */
    .main-card { border:none; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.07); overflow:hidden; margin-bottom:1.25rem; }
    .main-card .card-header { background:#fff; border-bottom:1px solid #f1f5f9; padding:16px 22px; display:flex; align-items:center; gap:10px; }
    .main-card .card-header h5 { font-size:.93rem; font-weight:700; color:#1e293b; margin:0; }
    .main-card .card-header .hd-icon { width:30px; height:30px; border-radius:8px; background:var(--accent-light); color:var(--accent); display:inline-flex; align-items:center; justify-content:center; font-size:.8rem; }
    .main-card .card-body { padding:22px; }

    /* Form controls */
    .form-label { font-size:.78rem; font-weight:700; color:#475569; margin-bottom:5px; }
    .form-label .req { color:#dc2626; }
    .form-control, .form-select { border-radius:10px; border:1.5px solid #e2e8f0; font-size:.82rem; padding:8px 12px; color:#334155; background:#f8fafc; transition:border-color .2s, box-shadow .2s; }
    .form-control:focus, .form-select:focus { border-color:var(--accent); background:#fff; box-shadow:0 0 0 3px var(--accent-shadow); }
    .form-control::placeholder { color:#94a3b8; }
    .form-control.is-invalid { border-color:#dc2626; background-image:none; }
    .invalid-feedback { font-size:.74rem; }
    .input-group-text { background:#f8fafc; border:1.5px solid #e2e8f0; border-right:none; border-radius:10px 0 0 10px; font-size:.8rem; color:#94a3b8; }
    .input-group .form-control { border-left:none; border-radius:0 10px 10px 0; }
    .form-hint { font-size:.72rem; color:#94a3b8; margin-top:4px; }
    .char-count { font-size:.72rem; color:#94a3b8; text-align:right; margin-top:4px; }

    /* Logo */
    .logo-preview { width:100%; height:170px; border:2px dashed #e2e8f0; border-radius:14px; background:#f8fafc; display:flex; align-items:center; justify-content:center; overflow:hidden; margin-bottom:14px; }
    .logo-preview img { max-width:100%; max-height:100%; object-fit:contain; padding:10px; }
    .logo-placeholder { text-align:center; color:#94a3b8; font-size:.78rem; }
    .logo-placeholder i { font-size:1.8rem; display:block; margin-bottom:6px; }
    .form-check-label { font-size:.8rem; color:#64748b; }

    /* Buttons */
    .btn-primary { background:linear-gradient(135deg,#1a73e8,#1558b0); border:none; border-radius:10px; font-weight:600; font-size:.83rem; padding:8px 18px; transition:all .2s; box-shadow:0 2px 8px rgba(26,115,232,.35); }
    .btn-primary:hover { transform:translateY(-1px); filter:brightness(1.07); }
    .btn-outline-secondary { border-radius:10px; font-size:.82rem; border-color:#e2e8f0; color:#64748b; padding:7px 14px; }

    /* Alert */
    .alert { border:none; border-radius:12px; font-size:.84rem; }
    .alert-success { background:#dcfce7; color:#166534; }
    .alert-danger  { background:#fee2e2; color:#991b1b; }
    .alert-danger ul { margin:6px 0 0; padding-left:18px; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-cog"></i></div>
            <div>
                <h5 class="ph-title">Pengaturan Website</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><span class="bc-active">Pengaturan Website</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('beranda') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-globe me-1"></i> Lihat Beranda
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
    @if($errors->any())
        <div class="alert alert-danger mb-3">
            <div class="fw-semibold"><i class="fas fa-exclamation-circle me-1"></i> Periksa kembali isian Anda:</div>
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('website-setting.update') }}" method="POST" enctype="multipart/form-data" id="form-setting">
        @csrf
        @method('PUT')

        <div class="row g-3">

            {{-- Kolom kiri --}}
            <div class="col-lg-8">

                {{-- Identitas --}}
                <div class="card main-card">
                    <div class="card-header">
                        <span class="hd-icon"><i class="fas fa-id-card"></i></span>
                        <h5>Identitas Website</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama Website <span class="req">*</span></label>
                                <input type="text" name="nama" id="nama"
                                       class="form-control @error('nama') is-invalid @enderror"
                                       value="{{ old('nama', $setting->nama) }}" placeholder="Contoh: SehatKita" maxlength="100">
                                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="slogan" class="form-label">Slogan</label>
                                <input type="text" name="slogan" id="slogan"
                                       class="form-control @error('slogan') is-invalid @enderror"
                                       value="{{ old('slogan', $setting->slogan) }}" placeholder="Contoh: Sehat Bersama Keluarga" maxlength="255">
                                @error('slogan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kontak --}}
                <div class="card main-card">
                    <div class="card-header">
                        <span class="hd-icon"><i class="fas fa-address-book"></i></span>
                        <h5>Kontak</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $setting->email) }}" placeholder="info@contoh.com" maxlength="100">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" name="nomor_telepon" id="nomor_telepon"
                                           class="form-control @error('nomor_telepon') is-invalid @enderror"
                                           value="{{ old('nomor_telepon', $setting->nomor_telepon) }}" placeholder="08xxxxxxxxxx" maxlength="20">
                                    @error('nomor_telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3"
                                          class="form-control @error('alamat') is-invalid @enderror"
                                          placeholder="Alamat lengkap" maxlength="500">{{ old('alamat', $setting->alamat) }}</textarea>
                                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pengantar --}}
                <div class="card main-card">
                    <div class="card-header">
                        <span class="hd-icon"><i class="fas fa-align-left"></i></span>
                        <h5>Pengantar Beranda</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="title_pengantar" class="form-label">Judul Pengantar</label>
                                <input type="text" name="title_pengantar" id="title_pengantar"
                                       class="form-control @error('title_pengantar') is-invalid @enderror"
                                       value="{{ old('title_pengantar', $setting->title_pengantar) }}"
                                       placeholder="Contoh: Selamat Datang di SehatKita" maxlength="150">
                                @error('title_pengantar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label for="paragraf_pengantar" class="form-label">Paragraf Pengantar</label>
                                <textarea name="paragraf_pengantar" id="paragraf_pengantar" rows="6"
                                          class="form-control @error('paragraf_pengantar') is-invalid @enderror"
                                          placeholder="Tulis pengantar singkat yang tampil di halaman beranda..."
                                          maxlength="3000">{{ old('paragraf_pengantar', $setting->paragraf_pengantar) }}</textarea>
                                @error('paragraf_pengantar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="char-count"><span id="count-paragraf">0</span> / 3000 karakter</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom kanan: Logo --}}
            <div class="col-lg-4">
                <div class="card main-card">
                    <div class="card-header">
                        <span class="hd-icon"><i class="fas fa-image"></i></span>
                        <h5>Logo</h5>
                    </div>
                    <div class="card-body">
                        <div class="logo-preview" id="logo-preview">
                            @if($setting->logo_url)
                                <img src="{{ $setting->logo_url }}" alt="Logo {{ $setting->nama }}">
                            @else
                                <div class="logo-placeholder"><i class="fas fa-image"></i>Belum ada logo</div>
                            @endif
                        </div>

                        <label for="logo" class="form-label">Ganti Logo</label>
                        <input type="file" name="logo" id="logo" accept="image/png,image/jpeg,image/webp,image/svg+xml"
                               class="form-control @error('logo') is-invalid @enderror">
                        @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-hint">Format JPG, PNG, WEBP, atau SVG. Maksimal 2 MB.</div>
                        <div class="text-danger mt-1" id="logo-error" style="font-size:.74rem;display:none;"></div>

                        @if($setting->logo)
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="hapus_logo" id="hapus_logo" value="1"
                                       {{ old('hapus_logo') ? 'checked' : '' }}>
                                <label class="form-check-label" for="hapus_logo">Hapus logo saat ini</label>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Aksi --}}
                <div class="card main-card">
                    <div class="card-body d-grid gap-2">
                        <button type="submit" class="btn btn-primary" id="btn-simpan">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        var MAX_SIZE   = 2 * 1024 * 1024; // 2 MB
        var preview    = document.getElementById('logo-preview');
        var inputLogo  = document.getElementById('logo');
        var cekHapus   = document.getElementById('hapus_logo');
        var errBox     = document.getElementById('logo-error');
        var originalSrc = @json($setting->logo_url);
        var placeholder = '<div class="logo-placeholder"><i class="fas fa-image"></i>Belum ada logo</div>';

        function tampilkan(src) {
            preview.innerHTML = src ? '<img src="' + src + '" alt="Logo">' : placeholder;
        }

        function pesan(teks) {
            errBox.textContent = teks || '';
            errBox.style.display = teks ? 'block' : 'none';
        }

        // Preview logo baru
        inputLogo.addEventListener('change', function () {
            pesan('');
            var file = this.files[0];

            if (!file) {
                tampilkan(cekHapus && cekHapus.checked ? null : originalSrc);
                return;
            }
            if (file.size > MAX_SIZE) {
                pesan('Ukuran file melebihi 2 MB.');
                this.value = '';
                tampilkan(cekHapus && cekHapus.checked ? null : originalSrc);
                return;
            }

            var reader = new FileReader();
            reader.onload = function (e) { tampilkan(e.target.result); };
            reader.readAsDataURL(file);

            if (cekHapus) cekHapus.checked = false;
        });

        // Centang "hapus logo"
        if (cekHapus) {
            cekHapus.addEventListener('change', function () {
                if (this.checked) {
                    inputLogo.value = '';
                    pesan('');
                    tampilkan(null);
                } else {
                    tampilkan(originalSrc);
                }
            });
            if (cekHapus.checked) tampilkan(null);
        }

        // Penghitung karakter paragraf
        var paragraf = document.getElementById('paragraf_pengantar');
        var counter  = document.getElementById('count-paragraf');
        function hitung() { counter.textContent = paragraf.value.length; }
        paragraf.addEventListener('input', hitung);
        hitung();

        // Cegah klik ganda saat simpan
        document.getElementById('form-setting').addEventListener('submit', function () {
            var btn = document.getElementById('btn-simpan');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
        });
    })();
</script>
@endsection
