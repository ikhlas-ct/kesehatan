@extends('layouts.user.user')

@section('title', 'Profil Saya')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, label, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

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

    /* Cards */
    .main-card { border:none; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.07); overflow:hidden; margin-bottom:1.25rem; }
    .main-card .card-header { background:#fff; border-bottom:1px solid #f1f5f9; padding:16px 22px; }
    .main-card .card-header h5 { font-size:.93rem; font-weight:700; color:#1e293b; margin:0; }
    .main-card .card-body { padding:22px; }

    /* Avatar */
    .avatar-wrap { position:relative; width:120px; height:120px; margin:0 auto 14px; }
    .avatar-img, .avatar-fallback { width:120px; height:120px; border-radius:50%; border:4px solid #fff; box-shadow:0 4px 16px rgba(0,0,0,.12); }
    .avatar-img { object-fit:cover; display:block; }
    .avatar-fallback { background:linear-gradient(135deg,#1a73e8,#1558b0); color:#fff; font-size:2.2rem; font-weight:800; display:flex; align-items:center; justify-content:center; letter-spacing:1px; }
    .profile-name { font-size:1.05rem; font-weight:700; color:#1e293b; margin-bottom:2px; }
    .profile-email { font-size:.8rem; color:#94a3b8; word-break:break-all; }
    .profile-bio { font-size:.82rem; color:#64748b; margin-top:12px; line-height:1.55; }

    /* Form controls */
    label.form-label { font-size:.78rem; font-weight:600; color:#475569; margin-bottom:5px; }
    .form-control { border-radius:10px; border:1.5px solid #e2e8f0; font-size:.84rem; padding:9px 12px; color:#334155; background:#f8fafc; transition:border-color .2s, box-shadow .2s; }
    .form-control:focus { border-color:var(--accent); background:#fff; box-shadow:0 0 0 3px var(--accent-shadow); }
    .form-control::placeholder { color:#94a3b8; }
    .form-control.is-invalid { border-color:#dc2626; background-image:none; }
    .invalid-feedback { font-size:.74rem; }
    .form-text { font-size:.73rem; color:#94a3b8; }

    .section-divider { display:flex; align-items:center; gap:10px; margin:6px 0 18px; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8; }
    .section-divider::after { content:''; flex:1; height:1px; background:#f1f5f9; }

    /* Buttons */
    .btn-primary { background:linear-gradient(135deg,#1a73e8,#1558b0); border:none; border-radius:10px; font-weight:600; font-size:.83rem; padding:9px 20px; transition:all .2s; box-shadow:0 2px 8px rgba(26,115,232,.35); }
    .btn-primary:hover { transform:translateY(-1px); filter:brightness(1.07); }
    .btn-outline-secondary { border-radius:10px; font-size:.82rem; border-color:#e2e8f0; color:#64748b; padding:8px 14px; }

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
            <div class="ph-icon"><i class="fas fa-user-circle"></i></div>
            <div>
                <h5 class="ph-title">Profil Saya</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><span class="bc-active">Profil</span></li>
                </ol>
            </div>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-exclamation-circle"></i> Ada isian yang belum benar, silakan periksa kembali.
        </div>
    @endif

    <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">

            {{-- Kolom kiri: foto --}}
            <div class="col-lg-4">
                <div class="card main-card">
                    <div class="card-header">
                        <h5><i class="fas fa-image me-2" style="color:var(--accent)"></i> Foto Profil</h5>
                    </div>
                    <div class="card-body text-center">

                        <div class="avatar-wrap">
                            <img id="avatar-preview" class="avatar-img {{ $user->foto_url ? '' : 'd-none' }}"
                                 src="{{ $user->foto_url }}" alt="Foto {{ $user->username }}">
                            <div id="avatar-fallback" class="avatar-fallback {{ $user->foto_url ? 'd-none' : '' }}">
                                {{ $user->inisial }}
                            </div>
                        </div>

                        <div class="profile-name">{{ $user->username }}</div>
                        <div class="profile-email">{{ $user->email }}</div>
                        @if($user->bio)
                            <div class="profile-bio">{{ $user->bio }}</div>
                        @endif

                        <hr class="my-4" style="border-color:#f1f5f9;">

                        <div class="text-start">
                            <label for="foto" class="form-label">Ganti Foto</label>
                            <input type="file" name="foto" id="foto" accept="image/png,image/jpeg,image/webp"
                                   class="form-control @error('foto') is-invalid @enderror">
                            @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">JPG, PNG, atau WEBP. Maksimal 2 MB.</div>

                            @if($user->foto)
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="hapus_foto" id="hapus_foto" value="1"
                                       {{ old('hapus_foto') ? 'checked' : '' }}>
                                <label class="form-check-label" for="hapus_foto" style="font-size:.8rem;color:#64748b;">
                                    Hapus foto saat ini
                                </label>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom kanan: data diri + password --}}
            <div class="col-lg-8">
                <div class="card main-card">
                    <div class="card-header">
                        <h5><i class="fas fa-user-edit me-2" style="color:var(--accent)"></i> Edit Profil</h5>
                    </div>
                    <div class="card-body">

                        <div class="section-divider">Informasi Akun</div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" id="username"
                                       class="form-control @error('username') is-invalid @enderror"
                                       value="{{ old('username', $user->username) }}" required>
                                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea name="bio" id="bio" rows="4" maxlength="500"
                                      class="form-control @error('bio') is-invalid @enderror"
                                      placeholder="Ceritakan sedikit tentang dirimu…">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text"><span id="bio-count">0</span>/500 karakter</div>
                        </div>

                        <div class="section-divider">Ubah Password <span style="text-transform:none;letter-spacing:0;font-weight:500;">(opsional)</span></div>

                        <div class="row g-3 mb-2">
                            <div class="col-12">
                                <label for="current_password" class="form-label">Password Lama</label>
                                <input type="password" name="current_password" id="current_password"
                                       autocomplete="current-password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       placeholder="Isi hanya jika ingin ganti password">
                                @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" name="password" id="password"
                                       autocomplete="new-password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Minimal 8 karakter">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       autocomplete="new-password" class="form-control"
                                       placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end gap-2"
                         style="background:#fff;border-top:1px solid #f1f5f9;padding:14px 22px;">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Pratinjau foto sebelum disimpan
    (function () {
        var input    = document.getElementById('foto');
        var preview  = document.getElementById('avatar-preview');
        var fallback = document.getElementById('avatar-fallback');
        var hapus    = document.getElementById('hapus_foto');

        input.addEventListener('change', function () {
            var file = this.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                fallback.classList.add('d-none');
            };
            reader.readAsDataURL(file);
            if (hapus) hapus.checked = false; // pilih foto baru = batal hapus
        });

        if (hapus) {
            hapus.addEventListener('change', function () {
                if (this.checked) {
                    input.value = '';
                    preview.classList.add('d-none');
                    fallback.classList.remove('d-none');
                } else if (preview.getAttribute('src')) {
                    preview.classList.remove('d-none');
                    fallback.classList.add('d-none');
                }
            });
        }
    })();

    // Penghitung karakter bio
    (function () {
        var bio = document.getElementById('bio');
        var out = document.getElementById('bio-count');
        var update = function () { out.textContent = bio.value.length; };
        bio.addEventListener('input', update);
        update();
    })();
</script>
@endsection
