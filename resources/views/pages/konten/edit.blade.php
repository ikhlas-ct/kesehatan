@extends('layouts.user.user')

@section('title', 'Edit Konten – ' . \Illuminate\Support\Str::limit($konten->judul, 40))

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body, .card, h4, h5, label, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

    :root {
        --accent:        #1a73e8;
        --accent-light:  #e8f0fe;
        --accent-shadow: rgba(26,115,232,.12);
    }

    .container { padding-left: 28px; padding-right: 24px; }

    /* ── Page Header ── */
    .ph-card {
        background:#fff; border:1px solid #e9ecef; border-radius:14px;
        padding:16px 22px; display:flex; align-items:center;
        justify-content:space-between; gap:16px; flex-wrap:wrap;
        margin-bottom:1.5rem; position:relative; overflow:hidden;
        box-shadow:0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:var(--accent); }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:44px; height:44px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:1.05rem; flex-shrink:0; background:var(--accent-light); color:var(--accent); }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; margin:0; line-height:1.2; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; list-style:none; padding:0; margin:4px 0 0; flex-wrap:wrap; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb a:hover { text-decoration:underline; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* ── Section cards ── */
    .section-card { border:none; border-radius:14px; box-shadow:0 1px 8px rgba(0,0,0,.06); margin-bottom:1.25rem; }
    .section-card .card-body { padding:22px 24px; }
    .section-divider {
        border-left:4px solid var(--accent); background:#f8f9fa;
        padding:7px 13px; border-radius:0 6px 6px 0;
        font-weight:700; font-size:.82rem; color:var(--accent);
        display:flex; align-items:center; gap:8px; margin-bottom:1.1rem;
    }

    /* ── Form controls ── */
    label { font-size:.83rem; font-weight:600; color:#475569; }
    .required-mark { color:#dc3545; }
    .form-control, .form-select {
        border-radius:10px; border:1.5px solid #e2e8f0; font-size:.85rem;
        padding:8px 12px; color:#334155; background:#f8fafc;
        transition:border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus { border-color:var(--accent); background:#fff; box-shadow:0 0 0 3px var(--accent-shadow); }
    .form-control::placeholder { color:#b0bec5; }
    .input-group-text { background:#f8fafc; border:1.5px solid #e2e8f0; border-right:none; border-radius:10px 0 0 10px; font-size:.85rem; color:#94a3b8; }
    .input-group .form-control { border-left:none; border-radius:0 10px 10px 0; }
    .form-text { font-size:.75rem; color:#94a3b8; margin-top:4px; }

    /* ── Summernote frame ── */
    .note-editor.note-frame { border-radius:10px; border:1.5px solid #e2e8f0; overflow:hidden; }
    .note-editor.note-frame .note-toolbar { background:#f8fafc; border-bottom:1px solid #e2e8f0; }
    .note-editor.note-frame.focus { border-color:var(--accent); box-shadow:0 0 0 3px var(--accent-shadow); }
    .note-editor .note-editable { font-family:'Plus Jakarta Sans',sans-serif; font-size:.9rem; min-height:320px; }

    /* ── Gambar sampul ── */
    .gambar-drop-zone {
        width:100%; height:190px; border:2px dashed #ced4da; border-radius:12px;
        display:flex; flex-direction:column; align-items:center; justify-content:center;
        cursor:pointer; overflow:hidden; transition:border-color .2s, background .2s;
        background:#fafbfc; position:relative;
    }
    .gambar-drop-zone:hover, .gambar-drop-zone.drag-over { border-color:var(--accent); background:var(--accent-light); }
    #gambar-preview { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; display:none; }
    .gambar-placeholder { text-align:center; color:#94a3b8; pointer-events:none; }
    .gambar-placeholder i { font-size:2rem; margin-bottom:6px; display:block; }
    .gambar-placeholder span { font-size:.78rem; }

    /* ── Char counter ── */
    .char-counter { font-size:.72rem; color:#94a3b8; text-align:right; margin-top:3px; }
    .char-counter.warning { color:#d97706; }
    .char-counter.danger  { color:#dc2626; }

    /* ── Meta info ── */
    .meta-box { background:#fafbfc; border:1px solid #f1f5f9; border-radius:10px; padding:12px 14px; }
    .meta-item { display:flex; gap:8px; font-size:.78rem; color:#64748b; margin-bottom:6px; }
    .meta-item:last-child { margin-bottom:0; }
    .meta-item i { width:14px; color:#94a3b8; flex-shrink:0; margin-top:1px; }

    /* ── Buttons ── */
    .btn-accent {
        background:linear-gradient(135deg, var(--accent), color-mix(in srgb,var(--accent),#000 18%));
        border:none; border-radius:10px; font-weight:600; font-size:.85rem;
        padding:9px 22px; color:#fff; box-shadow:0 2px 8px var(--accent-shadow); transition:all .2s;
    }
    .btn-accent:hover { transform:translateY(-1px); filter:brightness(1.07); color:#fff; }
    .btn-outline-secondary { border-radius:10px; font-size:.83rem; border-color:#e2e8f0; color:#64748b; }
    .btn-outline-secondary:hover { background:#f1f5f9; }
</style>
@endsection

@section('content')
@php
    $sampulUrl = $konten->gambar_sampul ? asset('storage/' . $konten->gambar_sampul) : null;
    $tglTerbit = old('tanggal_terbit', $konten->tanggal_terbit?->format('Y-m-d\TH:i'));
@endphp
<div class="container">

    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-pen-to-square"></i></div>
            <div>
                <h5 class="ph-title">Edit Konten</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}">Dashboard</a></li>
                    <li><a href="{{ route('konten.index') }}">Konten</a></li>
                    <li><span class="bc-active">Edit</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('konten.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- ══════════ FORM EDIT KONTEN ══════════ --}}
    <form action="{{ route('konten.update', $konten) }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- ══════════ KOLOM KIRI ══════════ --}}
            <div class="col-lg-8">

                {{-- Informasi Dasar --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-file-alt"></i> Informasi Dasar</div>

                        <div class="mb-3">
                            <label class="form-label" for="judul">Judul <span class="required-mark">*</span></label>
                            <input type="text" name="judul" id="judul"
                                   class="form-control @error('judul') is-invalid @enderror"
                                   value="{{ old('judul', $konten->judul) }}"
                                   placeholder="Tulis judul yang menarik…" required maxlength="255">
                            <div class="char-counter" id="judul-counter">0 / 255</div>
                            @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="kategori">Kategori</label>
                            <input type="text" name="kategori" id="kategori" list="daftar-kategori"
                                   class="form-control @error('kategori') is-invalid @enderror"
                                   value="{{ old('kategori', $konten->kategori) }}"
                                   placeholder="Pilih yang sudah ada atau ketik kategori baru" maxlength="100"
                                   autocomplete="off">
                            <datalist id="daftar-kategori">
                                @foreach($daftarKategori as $kat)
                                    <option value="{{ $kat }}">
                                @endforeach
                            </datalist>
                            @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label" for="ringkasan">Ringkasan</label>
                            <textarea name="ringkasan" id="ringkasan" rows="2"
                                      class="form-control @error('ringkasan') is-invalid @enderror"
                                      placeholder="Ringkasan singkat yang tampil di halaman daftar…"
                                      maxlength="255">{{ old('ringkasan', $konten->ringkasan) }}</textarea>
                            <div class="char-counter" id="ringkasan-counter">0 / 255</div>
                            @error('ringkasan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- Isi Konten --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="section-divider">
                            <i class="fas fa-pen-nib"></i> Isi Konten <span class="required-mark ms-1">*</span>
                        </div>
                        <textarea name="isi" id="isi"
                                  class="form-control summernote @error('isi') is-invalid @enderror"
                                  rows="10">{{ old('isi', $konten->isi) }}</textarea>
                        @error('isi')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

            </div>{{-- end col-lg-8 --}}

            {{-- ══════════ KOLOM KANAN ══════════ --}}
            <div class="col-lg-4">

                {{-- Gambar Sampul --}}
                <div class="card section-card mb-3">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-image"></i> Gambar Sampul</div>

                        <div class="gambar-drop-zone" id="gambar-drop-zone"
                             onclick="document.getElementById('gambar-input').click()">
                            <img id="gambar-preview" alt=""
                                 @if($sampulUrl) src="{{ $sampulUrl }}" style="display:block;" @endif>
                            <div class="gambar-placeholder" id="gambar-placeholder"
                                 @if($sampulUrl) style="display:none;" @endif>
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Klik atau seret gambar ke sini</span>
                                <span class="d-block mt-1" style="font-size:.7rem;">JPG / PNG / WEBP — maks. 3 MB</span>
                            </div>
                        </div>

                        <input type="file" name="gambar_sampul" id="gambar-input" accept="image/*" class="d-none">

                        <button type="button" class="btn btn-sm w-100 mt-2"
                                style="border:1.5px dashed var(--accent);color:var(--accent);border-radius:10px;background:var(--accent-light);"
                                onclick="document.getElementById('gambar-input').click()">
                            <i class="fas fa-{{ $sampulUrl ? 'sync' : 'upload' }} me-1"></i>
                            {{ $sampulUrl ? 'Ganti Gambar' : 'Pilih Gambar' }}
                        </button>
                        @error('gambar_sampul')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                        @if($sampulUrl)
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="hapus_sampul" id="hapus_sampul" value="1"
                                       {{ old('hapus_sampul') ? 'checked' : '' }}>
                                <label class="form-check-label" for="hapus_sampul" style="font-weight:500;">
                                    Hapus gambar sampul saat ini
                                </label>
                            </div>
                            <div class="form-text">Kosongkan jika tidak ingin mengubah gambar.</div>
                        @endif
                    </div>
                </div>

                {{-- Publikasi --}}
                <div class="card section-card mb-3">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-calendar-alt"></i> Publikasi</div>
                        <label class="form-label" for="tanggal_terbit">Tanggal Terbit</label>
                        <input type="datetime-local" name="tanggal_terbit" id="tanggal_terbit"
                               class="form-control @error('tanggal_terbit') is-invalid @enderror"
                               value="{{ $tglTerbit }}">
                        @error('tanggal_terbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Info Konten --}}
                <div class="card section-card mb-3">
                    <div class="card-body">
                        <div class="section-divider"><i class="fas fa-info-circle"></i> Info Konten</div>
                        <div class="meta-box">
                            <div class="meta-item">
                                <i class="fas fa-user"></i>
                                <span>Penulis: <strong>{{ $konten->penulis?->username ?? '-' }}</strong></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <span>Dibuat: <strong>{{ $konten->created_at?->translatedFormat('d M Y') ?? '-' }}</strong></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span>Diperbarui: <strong>{{ $konten->updated_at?->translatedFormat('d M Y') ?? '-' }}</strong></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-link"></i>
                                <span class="text-break" style="font-size:.72rem;">Slug: {{ $konten->slug }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Simpan --}}
                <div class="card section-card">
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2">
                            <button type="submit" class="btn btn-accent w-100">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('konten.index') }}" class="btn btn-outline-secondary w-100">Batal</a>
                        </div>
                    </div>
                </div>

            </div>{{-- end col-lg-4 --}}
        </div>
    </form>
</div>
@endsection

@section('scripts')
    @include('pages.konten.konten_summernote')
@endsection
