@extends('layouts.app')

@section('title', 'Ajukan Permohonan - PATEN SPACE')

@section('content')
@php
    $isCitizen = true;
    $pendaftaran = $pendaftaran ?? new \App\Models\Pendaftaran();
    $isEdit = isset($pendaftaran->id);
    $actionUrl = $isCitizen ? route('masyarakat.permohonan.store') : route('pendaftaran.store');
    $citizen = auth()->user();
    $fixedService = request('jenis_layanan');
    $hasFixedService = in_array($fixedService, ['PRR','KTP','KK','Akta Kematian','Akta Lahir','Kedatangan','Pindah'], true);
    $selectedService = old('jenis_layanan', $fixedService ?? '');
@endphp

<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { background: #f0f4f8; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }

    .mob-shell {
        max-width: 480px; margin: 0 auto;
        min-height: 100dvh; display: flex; flex-direction: column;
        background: #f0f4f8;
    }

    /* ── Header ── */
    .mob-header {
        background: linear-gradient(135deg, #1a56db 0%, #2563eb 100%);
        padding: 16px 16px 20px;
        color: #fff; display: flex; align-items: center; gap: 14px;
        position: sticky; top: 0; z-index: 50;
    }
    .mob-back {
        width: 38px; height: 38px; border-radius: 50%;
        background: rgba(255,255,255,.2); border: none; color: #fff;
        display: grid; place-items: center; font-size: 1.2rem;
        cursor: pointer; text-decoration: none; flex-shrink: 0;
    }
    .mob-header-title { font-size: 1rem; font-weight: 700; }
    .mob-header-sub   { font-size: .72rem; opacity: .8; margin-top: 2px; }

    /* ── Step indicator ── */
    .mob-steps {
        display: flex; align-items: center;
        padding: 16px 16px 12px;
        gap: 0;
    }
    .mob-step {
        display: flex; flex-direction: column; align-items: center; gap: 4px;
        flex: 1;
    }
    .mob-step-dot {
        width: 28px; height: 28px; border-radius: 50%;
        background: #e5e7eb; color: #9ca3af;
        display: grid; place-items: center;
        font-size: .72rem; font-weight: 700;
        transition: all .2s;
    }
    .mob-step-dot.active { background: #1a56db; color: #fff; box-shadow: 0 2px 8px rgba(26,86,219,.4); }
    .mob-step-dot.done   { background: #10b981; color: #fff; }
    .mob-step-label { font-size: .6rem; color: #6b7280; font-weight: 600; text-align: center; }
    .mob-step-label.active { color: #1a56db; }
    .mob-step-line { flex: 1; height: 2px; background: #e5e7eb; margin-bottom: 18px; }
    .mob-step-line.done { background: #10b981; }

    /* ── Cards ── */
    .mob-card {
        background: #fff; border-radius: 16px;
        margin: 0 16px 16px; padding: 20px 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,.06);
    }
    .mob-card-title {
        font-size: .85rem; font-weight: 700; color: #111827;
        margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
    }
    .mob-card-title-icon { font-size: 1.1rem; }

    /* ── Fields ── */
    .mob-field { margin-bottom: 14px; }
    .mob-label {
        display: block; font-size: .78rem; font-weight: 600;
        color: #374151; margin-bottom: 6px;
    }
    .mob-label span { color: #ef4444; }
    .mob-input, .mob-select, .mob-textarea {
        width: 100%; padding: 12px 14px;
        border: 1.5px solid #e5e7eb; border-radius: 10px;
        font-size: .88rem; color: #111827;
        background: #fafafa;
        transition: border-color .15s, box-shadow .15s;
        -webkit-appearance: none;
    }
    .mob-input:focus, .mob-select:focus, .mob-textarea:focus {
        outline: none; border-color: #1a56db;
        box-shadow: 0 0 0 3px rgba(26,86,219,.12);
        background: #fff;
    }
    .mob-input:read-only { background: #f3f4f6; color: #6b7280; }
    .mob-input.error, .mob-select.error { border-color: #ef4444; }
    .mob-error { color: #ef4444; font-size: .72rem; margin-top: 4px; }

    /* ── 2-col grid ── */
    .mob-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

    /* ── Service pills ── */
    .mob-services {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;
        margin-bottom: 4px;
    }
    .mob-service-pill {
        padding: 12px 10px; border: 2px solid #e5e7eb; border-radius: 12px;
        cursor: pointer; text-align: center; font-size: .78rem; font-weight: 600;
        color: #374151; background: #fafafa; transition: all .15s;
        line-height: 1.3;
    }
    .mob-service-pill:hover { border-color: #1a56db; background: #eff6ff; color: #1a56db; }
    .mob-service-pill.selected { border-color: #1a56db; background: #1a56db; color: #fff; }
    .mob-service-icon { font-size: 1.3rem; margin-bottom: 4px; display: block; }

    /* ── Section toggle (untuk section layanan) ── */
    .mob-service-section { display: none; }
    .mob-service-section.show { display: block; }

    /* ── Camera scan area ── */
    .mob-scan-area {
        border: 2px dashed #10b981; border-radius: 16px;
        padding: 20px 16px; text-align: center;
        background: #f0fdf4; margin-bottom: 16px;
        cursor: pointer; transition: all .15s;
    }
    .mob-scan-area:hover { background: #dcfce7; border-color: #059669; }
    .mob-scan-icon { font-size: 2.5rem; margin-bottom: 8px; }
    .mob-scan-title { font-weight: 700; font-size: .9rem; color: #065f46; margin-bottom: 4px; }
    .mob-scan-desc  { font-size: .75rem; color: #16a34a; }
    #scan-camera-input { display: none; }

    /* ── File preivew list ── */
    .mob-file-list { display: flex; flex-direction: column; gap: 8px; margin-top: 12px; }
    .mob-file-item {
        display: flex; align-items: center; gap: 10px;
        background: #f9fafb; border: 1px solid #e5e7eb;
        border-radius: 10px; padding: 10px 12px;
    }
    .mob-file-thumb {
        width: 44px; height: 44px; border-radius: 8px;
        object-fit: cover; flex-shrink: 0;
        background: #e5e7eb; display: grid; place-items: center;
        font-size: 1.4rem;
    }
    .mob-file-thumb img { width: 44px; height: 44px; border-radius: 8px; object-fit: cover; }
    .mob-file-info { flex: 1; min-width: 0; }
    .mob-file-name { font-size: .78rem; font-weight: 600; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .mob-file-size { font-size: .66rem; color: #6b7280; margin-top: 2px; }
    .mob-file-del  { background: #fee2e2; border: none; border-radius: 6px; color: #dc2626; cursor: pointer; padding: 4px 8px; font-size: .8rem; flex-shrink: 0; }

    /* ── Additional upload button ── */
    .mob-upload-btn {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%; padding: 12px;
        border: 2px dashed #d1d5db; border-radius: 10px;
        background: #f9fafb; color: #6b7280;
        font-size: .82rem; font-weight: 600; cursor: pointer;
        transition: all .15s;
    }
    .mob-upload-btn:hover { border-color: #1a56db; color: #1a56db; background: #eff6ff; }
    #scan-file-input { display: none; }

    /* ── Submit button ── */
    .mob-submit {
        margin: 0 16px 24px;
        padding: 16px;
        background: linear-gradient(135deg, #1a56db, #2563eb);
        color: #fff; border: none; border-radius: 14px;
        font-size: 1rem; font-weight: 700; cursor: pointer;
        box-shadow: 0 4px 14px rgba(26,86,219,.35);
        transition: transform .15s, box-shadow .15s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .mob-submit:active { transform: scale(.98); }
    .mob-submit:disabled { opacity: .7; cursor: not-allowed; }

    /* ── Alert error ── */
    .mob-alert-error {
        margin: 0 16px 16px; padding: 12px 14px;
        background: #fee2e2; border: 1px solid #fca5a5;
        color: #991b1b; border-radius: 12px;
        font-size: .8rem;
    }
    .mob-alert-error ul { margin: 6px 0 0 16px; }

    /* ── Camera modal ── */
    .cam-modal {
        display: none; position: fixed; inset: 0; z-index: 200;
        background: #000; flex-direction: column;
    }
    .cam-modal.open { display: flex; }
    .cam-topbar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px; background: rgba(0,0,0,.6);
        color: #fff; position: absolute; top: 0; left: 0; right: 0; z-index: 10;
    }
    .cam-close { background: none; border: none; color: #fff; font-size: 1.4rem; cursor: pointer; }
    #cam-video { width: 100%; height: 100%; object-fit: cover; }
    .cam-controls {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 24px 40px env(safe-area-inset-bottom, 24px);
        display: flex; align-items: center; justify-content: space-between;
        background: rgba(0,0,0,.5);
    }
    .cam-switch {
        width: 44px; height: 44px; border-radius: 50%;
        background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.3);
        color: #fff; font-size: 1.3rem; cursor: pointer;
        display: grid; place-items: center;
    }
    .cam-shutter {
        width: 70px; height: 70px; border-radius: 50%;
        background: #fff; border: 4px solid rgba(255,255,255,.4);
        cursor: pointer; transition: transform .1s;
    }
    .cam-shutter:active { transform: scale(.93); }
    .cam-flash {
        width: 44px; height: 44px; border-radius: 50%;
        background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.3);
        color: #fff; font-size: 1.3rem; cursor: pointer;
        display: grid; place-items: center;
    }
    #cam-canvas { display: none; }

    @media (min-width: 481px) {
        .mob-shell { box-shadow: 0 0 0 1px #e5e7eb, 0 20px 60px rgba(0,0,0,.12); }
        body { padding: 20px 0; }
    }
</style>

<div class="mob-shell">
    {{-- Header --}}
    <div class="mob-header">
        <a href="{{ route('masyarakat.dashboard') }}" class="mob-back">←</a>
        <div>
            <div class="mob-header-title">Ajukan Permohonan</div>
            <div class="mob-header-sub">Kecamatan Jatisari · PATEN SPACE</div>
        </div>
    </div>

    {{-- Step indicator --}}
    <div class="mob-steps">
        <div class="mob-step">
            <div class="mob-step-dot active" id="step-dot-1">1</div>
            <div class="mob-step-label active" id="step-lbl-1">Layanan</div>
        </div>
        <div class="mob-step-line" id="step-line-1"></div>
        <div class="mob-step">
            <div class="mob-step-dot" id="step-dot-2">2</div>
            <div class="mob-step-label" id="step-lbl-2">Data Diri</div>
        </div>
        <div class="mob-step-line" id="step-line-2"></div>
        <div class="mob-step">
            <div class="mob-step-dot" id="step-dot-3">3</div>
            <div class="mob-step-label" id="step-lbl-3">Detail</div>
        </div>
        <div class="mob-step-line" id="step-line-3"></div>
        <div class="mob-step">
            <div class="mob-step-dot" id="step-dot-4">4</div>
            <div class="mob-step-label" id="step-lbl-4">Dokumen</div>
        </div>
    </div>

    {{-- Error summary --}}
    @if($errors->any())
        <div class="mob-alert-error">
            <strong>⚠️ Mohon periksa kembali:</strong>
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data" id="mob-form">
        @csrf
        <input type="hidden" name="tanggal_daftar" value="{{ now()->format('Y-m-d') }}">
        <input type="hidden" id="hidden-jenis-layanan" name="jenis_layanan" value="{{ old('jenis_layanan', $selectedService) }}">

        {{-- STEP 1: Pilih Layanan ── --}}
        <div id="mob-step-1">
            <div class="mob-card">
                <div class="mob-card-title">
                    <span class="mob-card-title-icon">🗂️</span> Pilih Jenis Layanan
                </div>
                <div class="mob-services">
                    @php
                        $services = [
                            ['value'=>'KTP',          'icon'=>'🪪', 'label'=>'KTP Pembaharuan'],
                            ['value'=>'PRR',          'icon'=>'📋', 'label'=>'PRR / KTP Baru'],
                            ['value'=>'KK',           'icon'=>'👨‍👩‍👧‍👦', 'label'=>'Kartu Keluarga'],
                            ['value'=>'Akta Lahir',   'icon'=>'👶', 'label'=>'Akta Lahir'],
                            ['value'=>'Akta Kematian','icon'=>'📜', 'label'=>'Akta Kematian'],
                            ['value'=>'Kedatangan',   'icon'=>'🏘️', 'label'=>'Kedatangan'],
                            ['value'=>'Pindah',       'icon'=>'🚚', 'label'=>'Pindah'],
                        ];
                    @endphp
                    @foreach($services as $svc)
                        <button type="button"
                            class="mob-service-pill {{ old('jenis_layanan', $selectedService) === $svc['value'] ? 'selected' : '' }}"
                            data-service="{{ $svc['value'] }}"
                            onclick="selectService('{{ $svc['value'] }}')">
                            <span class="mob-service-icon">{{ $svc['icon'] }}</span>
                            {{ $svc['label'] }}
                        </button>
                    @endforeach
                </div>
                @error('jenis_layanan')
                    <p class="mob-error">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin: 0 16px 16px; display: flex; gap: 10px;">
                <button type="button" onclick="goStep(2)"
                    style="flex:1;padding:14px;background:#1a56db;color:#fff;border:none;border-radius:12px;font-size:.9rem;font-weight:700;cursor:pointer;">
                    Lanjut →
                </button>
            </div>
        </div>

        {{-- STEP 2: Data Diri ── --}}
        <div id="mob-step-2" style="display:none;">
            <div class="mob-card">
                <div class="mob-card-title"><span class="mob-card-title-icon">👤</span> Data Diri Pemohon</div>

                <div class="mob-field">
                    <label class="mob-label">Nama Lengkap <span>*</span></label>
                    <input class="mob-input {{ $errors->has('nama_lengkap') ? 'error' : '' }}"
                        name="nama_lengkap" type="text"
                        placeholder="Nama sesuai KTP"
                        value="{{ old('nama_lengkap', $citizen->name ?? '') }}" required>
                    @error('nama_lengkap')<p class="mob-error">{{ $message }}</p>@enderror
                </div>

                <div class="mob-field">
                    <label class="mob-label">NIK <span>*</span></label>
                    <input class="mob-input" name="nik" type="text"
                        placeholder="16 digit NIK"
                        value="{{ old('nik', $citizen->nik ?? '') }}" readonly required>
                </div>

                <div class="mob-grid-2">
                    <div class="mob-field">
                        <label class="mob-label">Jenis Kelamin</label>
                        <select class="mob-select" name="jenis_kelamin">
                            <option value="">Pilih</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">No. Telepon</label>
                        <input class="mob-input" name="no_telepon" type="tel"
                            placeholder="08xx..."
                            value="{{ old('no_telepon', $citizen->no_telepon ?? '') }}">
                    </div>
                </div>

                <div class="mob-grid-2">
                    <div class="mob-field">
                        <label class="mob-label">Tempat Lahir</label>
                        <input class="mob-input" name="tempat_lahir" type="text"
                            placeholder="Kota lahir"
                            value="{{ old('tempat_lahir') }}">
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Tanggal Lahir</label>
                        <input class="mob-input" name="tanggal_lahir" type="text"
                            placeholder="dd-mm-yyyy"
                            value="{{ old('tanggal_lahir') }}">
                    </div>
                </div>

                <div class="mob-field">
                    <label class="mob-label">Jalan / Alamat</label>
                    <input class="mob-input" name="jalan" type="text"
                        placeholder="Nama jalan" value="{{ old('jalan') }}">
                </div>

                <div class="mob-grid-2">
                    <div class="mob-field">
                        <label class="mob-label">RT</label>
                        <input class="mob-input" name="rt" type="text" placeholder="RT" value="{{ old('rt') }}">
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">RW</label>
                        <input class="mob-input" name="rw" type="text" placeholder="RW" value="{{ old('rw') }}">
                    </div>
                </div>

                <div class="mob-field">
                    <label class="mob-label">Desa</label>
                    <select class="mob-select" name="desa">
                        <option value="">Pilih Desa</option>
                        @foreach(['Barugbug','Situdam','Balonggandu','Kalijati','Jatisari','Mekarsari','Cirejag','Cikalongsari','Jatiragas','Jatiwangi','Jatibaru','Telarsari','Sukamekar','Pacing'] as $desa)
                            <option value="{{ $desa }}" {{ old('desa') == $desa ? 'selected' : '' }}>{{ $desa }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin: 0 16px 16px; display: flex; gap: 10px;">
                <button type="button" onclick="goStep(1)"
                    style="flex:0 0 48px;padding:14px;background:#f3f4f6;color:#374151;border:none;border-radius:12px;font-size:.9rem;font-weight:700;cursor:pointer;">←</button>
                <button type="button" onclick="goStep(3)"
                    style="flex:1;padding:14px;background:#1a56db;color:#fff;border:none;border-radius:12px;font-size:.9rem;font-weight:700;cursor:pointer;">
                    Lanjut →
                </button>
            </div>
        </div>

        {{-- STEP 3: Detail Layanan ── --}}
        <div id="mob-step-3" style="display:none;">

            {{-- KTP --}}
            <div class="mob-service-section" data-for="KTP">
                <div class="mob-card">
                    <div class="mob-card-title"><span class="mob-card-title-icon">🪪</span> Detail KTP Pembaharuan</div>
                    <div class="mob-field">
                        <label class="mob-label">Alasan Pembaharuan</label>
                        <select class="mob-select" name="alasan_pembaruan">
                            <option value="">Pilih alasan</option>
                            <option value="Hilang" {{ old('alasan_pembaruan')=='Hilang'?'selected':'' }}>Hilang</option>
                            <option value="Rusak" {{ old('alasan_pembaruan')=='Rusak'?'selected':'' }}>Rusak</option>
                            <option value="Perubahan Data" {{ old('alasan_pembaruan')=='Perubahan Data'?'selected':'' }}>Perubahan Data</option>
                        </select>
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Keterangan Tambahan</label>
                        <textarea class="mob-textarea" name="keterangan" rows="3" placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- PRR --}}
            <div class="mob-service-section" data-for="PRR">
                <div class="mob-card">
                    <div class="mob-card-title"><span class="mob-card-title-icon">📋</span> Detail PRR / KTP Baru</div>
                    <div class="mob-grid-2">
                        <div class="mob-field">
                            <label class="mob-label">Bulan</label>
                            <input class="mob-input" name="bulan" type="text" placeholder="Contoh: JAN" value="{{ old('bulan') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Minggu Ke</label>
                            <input class="mob-input" name="minggu_ke" type="text" placeholder="1" value="{{ old('minggu_ke') }}">
                        </div>
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Tanggal Kedatangan</label>
                        <input class="mob-input" name="tanggal_kedatangan" type="date" value="{{ old('tanggal_kedatangan') }}">
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Alamat Asal</label>
                        <input class="mob-input" name="alamat_asal" type="text" placeholder="Alamat asal" value="{{ old('alamat_asal') }}">
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Alamat Tujuan</label>
                        <input class="mob-input" name="alamat_tujuan" type="text" placeholder="Alamat tujuan" value="{{ old('alamat_tujuan') }}">
                    </div>
                </div>
            </div>

            {{-- KK --}}
            <div class="mob-service-section" data-for="KK">
                <div class="mob-card">
                    <div class="mob-card-title"><span class="mob-card-title-icon">👨‍👩‍👧‍👦</span> Detail Kartu Keluarga</div>
                    <div class="mob-field">
                        <label class="mob-label">Nomor KK</label>
                        <input class="mob-input" name="nomor_kk" type="text" placeholder="Nomor KK" value="{{ old('nomor_kk') }}">
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Nama Kepala Keluarga</label>
                        <input class="mob-input" name="kepala_keluarga" type="text" placeholder="Nama KK" value="{{ old('kepala_keluarga') }}">
                    </div>
                    <div class="mob-grid-2">
                        <div class="mob-field">
                            <label class="mob-label">Hubungan</label>
                            <input class="mob-input" name="hubungan" type="text" placeholder="Hubungan" value="{{ old('hubungan') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Jumlah Anggota</label>
                            <input class="mob-input" name="jumlah_anggota" type="text" placeholder="Jumlah" value="{{ old('jumlah_anggota') }}">
                        </div>
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Keterangan</label>
                        <textarea class="mob-textarea" name="keterangan_kk" rows="2" placeholder="Keterangan">{{ old('keterangan_kk') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Akta Lahir --}}
            <div class="mob-service-section" data-for="Akta Lahir">
                <div class="mob-card">
                    <div class="mob-card-title"><span class="mob-card-title-icon">👶</span> Detail Akta Lahir</div>
                    <div class="mob-field">
                        <label class="mob-label">Nama Anak</label>
                        <input class="mob-input" name="nama_anak_lahir" type="text" placeholder="Nama anak" value="{{ old('nama_anak_lahir') }}">
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Nama Ayah</label>
                        <input class="mob-input" name="nama_ayah_lahir" type="text" placeholder="Nama ayah" value="{{ old('nama_ayah_lahir') }}">
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Nama Ibu</label>
                        <input class="mob-input" name="nama_ibu_lahir" type="text" placeholder="Nama ibu" value="{{ old('nama_ibu_lahir') }}">
                    </div>
                    <div class="mob-grid-2">
                        <div class="mob-field">
                            <label class="mob-label">Berat Badan (kg)</label>
                            <input class="mob-input" name="berat_badan" type="text" placeholder="Berat" value="{{ old('berat_badan') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Panjang Badan (cm)</label>
                            <input class="mob-input" name="panjang_badan" type="text" placeholder="Panjang" value="{{ old('panjang_badan') }}">
                        </div>
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Tempat Lahir Detail</label>
                        <input class="mob-input" name="tempat_lahir_detail" type="text" placeholder="Tempat lahir" value="{{ old('tempat_lahir_detail') }}">
                    </div>
                </div>
            </div>

            {{-- Akta Kematian --}}
            <div class="mob-service-section" data-for="Akta Kematian">
                <div class="mob-card">
                    <div class="mob-card-title"><span class="mob-card-title-icon">📜</span> Detail Akta Kematian</div>
                    <div class="mob-field">
                        <label class="mob-label">Nama Almarhum/ah</label>
                        <input class="mob-input" name="nama_alm" type="text" placeholder="Nama almarhum" value="{{ old('nama_alm') }}">
                    </div>
                    <div class="mob-grid-2">
                        <div class="mob-field">
                            <label class="mob-label">Nama Ayah</label>
                            <input class="mob-input" name="nama_ayah" type="text" placeholder="Nama ayah" value="{{ old('nama_ayah') }}">
                        </div>
                        <div class="mob-field">
                            <label class="mob-label">Nama Ibu</label>
                            <input class="mob-input" name="nama_ibu" type="text" placeholder="Nama ibu" value="{{ old('nama_ibu') }}">
                        </div>
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Tempat Meninggal</label>
                        <input class="mob-input" name="tempat_meninggal" type="text" placeholder="Tempat meninggal" value="{{ old('tempat_meninggal') }}">
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Tanggal Meninggal</label>
                        <input class="mob-input" name="tanggal_meninggal" type="date" value="{{ old('tanggal_meninggal') }}">
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Keterangan</label>
                        <textarea class="mob-textarea" name="keterangan_kematian" rows="2" placeholder="Keterangan">{{ old('keterangan_kematian') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Kedatangan --}}
            <div class="mob-service-section" data-for="Kedatangan">
                <div class="mob-card">
                    <div class="mob-card-title"><span class="mob-card-title-icon">🏘️</span> Detail Kedatangan</div>
                    <div class="mob-field">
                        <label class="mob-label">Nama</label>
                        <input class="mob-input" name="nama_kedatangan" type="text" placeholder="Nama" value="{{ old('nama_kedatangan') }}">
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">NIK</label>
                        <input class="mob-input" name="nik_kedatangan" type="text" placeholder="NIK" value="{{ old('nik_kedatangan') }}">
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Alamat Asal</label>
                        <input class="mob-input" name="alamat_asal_kedatangan" type="text" placeholder="Alamat asal" value="{{ old('alamat_asal_kedatangan') }}">
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Alamat Tujuan</label>
                        <input class="mob-input" name="alamat_tujuan_kedatangan" type="text" placeholder="Alamat tujuan" value="{{ old('alamat_tujuan_kedatangan') }}">
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Keterangan</label>
                        <textarea class="mob-textarea" name="keterangan_kedatangan" rows="2">{{ old('keterangan_kedatangan') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Pindah --}}
            <div class="mob-service-section" data-for="Pindah">
                <div class="mob-card">
                    <div class="mob-card-title"><span class="mob-card-title-icon">🚚</span> Detail Pindah</div>
                    <div class="mob-field">
                        <label class="mob-label">Nama</label>
                        <input class="mob-input" name="nama_pindah" type="text" placeholder="Nama" value="{{ old('nama_pindah') }}">
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">NIK</label>
                        <input class="mob-input" name="nik_pindah" type="text" placeholder="NIK" value="{{ old('nik_pindah') }}">
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Alamat Asal</label>
                        <input class="mob-input" name="alamat_asal_pindah" type="text" placeholder="Alamat asal" value="{{ old('alamat_asal_pindah') }}">
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Alamat Tujuan</label>
                        <input class="mob-input" name="alamat_tujuan_pindah" type="text" placeholder="Alamat tujuan" value="{{ old('alamat_tujuan_pindah') }}">
                    </div>
                    <div class="mob-field">
                        <label class="mob-label">Alasan Pindah</label>
                        <textarea class="mob-textarea" name="alasan_pindah" rows="2" placeholder="Alasan pindah">{{ old('alasan_pindah') }}</textarea>
                    </div>
                </div>
            </div>

            <div style="margin: 0 16px 16px; display: flex; gap: 10px;">
                <button type="button" onclick="goStep(2)"
                    style="flex:0 0 48px;padding:14px;background:#f3f4f6;color:#374151;border:none;border-radius:12px;font-size:.9rem;font-weight:700;cursor:pointer;">←</button>
                <button type="button" onclick="goStep(4)"
                    style="flex:1;padding:14px;background:#1a56db;color:#fff;border:none;border-radius:12px;font-size:.9rem;font-weight:700;cursor:pointer;">
                    Lanjut → Dokumen
                </button>
            </div>
        </div>

        {{-- STEP 4: Upload Dokumen & Camera Scan ── --}}
        <div id="mob-step-4" style="display:none;">
            <div class="mob-card" id="dokumen">

                <div class="mob-card-title"><span class="mob-card-title-icon">📎</span> Upload Dokumen Persyaratan</div>
                <p style="font-size:.78rem;color:#6b7280;margin-bottom:16px;line-height:1.5;">
                    Unggah foto atau scan dokumen pendukung (KTP, KK, Akta, dll). Maks 5 file, 5 MB per file.
                </p>

                {{-- Camera scan button (primary) --}}
                <div class="mob-scan-area" onclick="openCamera()">
                    <div class="mob-scan-icon">📷</div>
                    <div class="mob-scan-title">Foto Dokumen dengan Kamera</div>
                    <div class="mob-scan-desc">Ketuk untuk membuka kamera dan foto dokumen</div>
                </div>

                {{-- File picker (gallery/PDF) --}}
                <label class="mob-upload-btn" for="scan-file-input">
                    <span>🖼️</span> Pilih dari Galeri / PDF
                </label>
                <input id="scan-file-input" type="file" accept="image/*,.pdf" multiple>

                {{-- Hidden inputs container --}}
                <div id="hidden-file-inputs"></div>

                {{-- File preview --}}
                <div id="mob-file-list" class="mob-file-list"></div>

                @error('documents')  <p class="mob-error">{{ $message }}</p> @enderror
                @error('documents.*')<p class="mob-error">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="mob-submit" id="mob-submit-btn">
                <span>✅</span> Kirim Permohonan
            </button>

            <div style="margin: 0 16px 40px; display: flex; gap: 10px;">
                <button type="button" onclick="goStep(3)"
                    style="flex:1;padding:12px;background:#f3f4f6;color:#374151;border:none;border-radius:12px;font-size:.85rem;font-weight:600;cursor:pointer;">← Kembali ke Detail</button>
            </div>
        </div>
    </form>
</div>

{{-- Camera Modal ── --}}
<div class="cam-modal" id="cam-modal">
    <div class="cam-topbar">
        <span style="font-weight:700;font-size:.95rem;">📷 Kamera</span>
        <button class="cam-close" onclick="closeCamera()">✕</button>
    </div>
    <video id="cam-video" autoplay playsinline muted></video>
    <canvas id="cam-canvas"></canvas>
    <div class="cam-controls">
        <button type="button" class="cam-switch" onclick="switchCamera()" title="Ganti kamera">🔄</button>
        <button type="button" class="cam-shutter" onclick="capturePhoto()" title="Ambil foto"></button>
        <button type="button" class="cam-flash" id="cam-flash-btn" onclick="toggleFlash()" title="Flash">🔦</button>
    </div>
</div>

<script>
// ── Step navigation ──────────────────────────────────────────────
let currentStep = {{ $errors->any() ? 4 : 1 }};
const totalSteps = 4;

function goStep(n) {
    if (n === 2 && !validateStep1()) return;
    document.getElementById('mob-step-' + currentStep).style.display = 'none';
    currentStep = n;
    document.getElementById('mob-step-' + n).style.display = 'block';
    updateStepUI();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateStepUI() {
    for (let i = 1; i <= totalSteps; i++) {
        const dot = document.getElementById('step-dot-' + i);
        const lbl = document.getElementById('step-lbl-' + i);
        const line = document.getElementById('step-line-' + i);
        dot.className = 'mob-step-dot' + (i < currentStep ? ' done' : i === currentStep ? ' active' : '');
        dot.textContent = i < currentStep ? '✓' : i;
        lbl.className = 'mob-step-label' + (i === currentStep ? ' active' : '');
        if (line) line.className = 'mob-step-line' + (i < currentStep ? ' done' : '');
    }
}

function validateStep1() {
    const svc = document.getElementById('hidden-jenis-layanan').value;
    if (!svc) { alert('Pilih jenis layanan terlebih dahulu.'); return false; }
    return true;
}

// ── Service selection ────────────────────────────────────────────
function selectService(value) {
    document.getElementById('hidden-jenis-layanan').value = value;
    document.querySelectorAll('.mob-service-pill').forEach(p => {
        p.classList.toggle('selected', p.dataset.service === value);
    });
    // Update detail sections (step 3)
    document.querySelectorAll('.mob-service-section').forEach(s => {
        s.classList.toggle('show', s.dataset.for === value);
    });
}

// Init on load
(function() {
    const saved = '{{ old('jenis_layanan', $selectedService) }}';
    if (saved) selectService(saved);
    if ({{ $errors->any() ? 'true' : 'false' }}) {
        for (let i = 1; i <= totalSteps; i++) {
            document.getElementById('mob-step-' + i).style.display = 'none';
        }
        document.getElementById('mob-step-4').style.display = 'block';
        currentStep = 4;
        updateStepUI();
    }
})();

// ── File management ──────────────────────────────────────────────
let fileStore = []; // { file, dataUrl, inputEl }
const MAX_FILES = 5;

function formatBytes(b) {
    return b < 1024*1024 ? (b/1024).toFixed(1)+' KB' : (b/(1024*1024)).toFixed(1)+' MB';
}

function addFile(file) {
    if (fileStore.length >= MAX_FILES) { alert('Maksimal 5 dokumen.'); return; }
    if (file.size > 5 * 1024 * 1024) { alert(file.name + ' melebihi batas 5 MB.'); return; }

    const reader = new FileReader();
    reader.onload = function(e) {
        const item = { file, dataUrl: e.target.result };
        fileStore.push(item);
        renderFileList();
        syncHiddenInputs();
    };
    reader.readAsDataURL(file);
}

function removeFile(idx) {
    fileStore.splice(idx, 1);
    renderFileList();
    syncHiddenInputs();
}

function renderFileList() {
    const list = document.getElementById('mob-file-list');
    list.innerHTML = '';
    fileStore.forEach((item, idx) => {
        const isPdf = item.file.type === 'application/pdf';
        const thumb = isPdf
            ? '<div class="mob-file-thumb">📄</div>'
            : `<div class="mob-file-thumb"><img src="${item.dataUrl}" alt=""></div>`;
        const div = document.createElement('div');
        div.className = 'mob-file-item';
        div.innerHTML = `
            ${thumb}
            <div class="mob-file-info">
                <div class="mob-file-name">${item.file.name}</div>
                <div class="mob-file-size">${formatBytes(item.file.size)}</div>
            </div>
            <button type="button" class="mob-file-del" onclick="removeFile(${idx})">✕</button>
        `;
        list.appendChild(div);
    });
}

function syncHiddenInputs() {
    // We use a DataTransfer trick to assign files to a hidden input
    const container = document.getElementById('hidden-file-inputs');
    container.innerHTML = '';
    fileStore.forEach((item, idx) => {
        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'documents[]';
        input.style.display = 'none';
        container.appendChild(input);
        // Assign file via DataTransfer
        const dt = new DataTransfer();
        dt.items.add(item.file);
        input.files = dt.files;
    });
}

// Gallery / PDF picker
document.getElementById('scan-file-input').addEventListener('change', function(e) {
    Array.from(e.target.files).forEach(addFile);
    this.value = '';
});

// ── Camera ───────────────────────────────────────────────────────
let camStream = null;
let facingMode = 'environment'; // back camera default
let flashOn = false;
let flashTrack = null;

async function openCamera() {
    const modal = document.getElementById('cam-modal');
    modal.classList.add('open');
    await startStream();
}

function closeCamera() {
    document.getElementById('cam-modal').classList.remove('open');
    stopStream();
}

async function startStream() {
    stopStream();
    try {
        camStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode, width: { ideal: 1920 }, height: { ideal: 1080 } },
            audio: false
        });
        const video = document.getElementById('cam-video');
        video.srcObject = camStream;
        // Get flash track
        flashTrack = camStream.getVideoTracks()[0];
    } catch(err) {
        alert('Tidak dapat mengakses kamera: ' + err.message);
        closeCamera();
    }
}

function stopStream() {
    if (camStream) { camStream.getTracks().forEach(t => t.stop()); camStream = null; }
    flashOn = false;
}

async function switchCamera() {
    facingMode = facingMode === 'environment' ? 'user' : 'environment';
    await startStream();
}

async function toggleFlash() {
    if (!flashTrack) return;
    flashOn = !flashOn;
    try {
        await flashTrack.applyConstraints({ advanced: [{ torch: flashOn }] });
        document.getElementById('cam-flash-btn').textContent = flashOn ? '⚡' : '🔦';
    } catch(e) { flashOn = false; }
}

function capturePhoto() {
    const video  = document.getElementById('cam-video');
    const canvas = document.getElementById('cam-canvas');
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);

    canvas.toBlob(blob => {
        const ts   = new Date().toISOString().replace(/[:.]/g,'').slice(0,15);
        const file = new File([blob], `scan-${ts}.jpg`, { type: 'image/jpeg' });
        closeCamera();
        addFile(file);
        goStep(4);
    }, 'image/jpeg', 0.92);
}

// ── Form submit guard ────────────────────────────────────────────
document.getElementById('mob-form').addEventListener('submit', function(e) {
    const btn = document.getElementById('mob-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<span style="display:inline-block;animation:spin 1s linear infinite">⏳</span> Mengirim...';
});
</script>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
@endsection
