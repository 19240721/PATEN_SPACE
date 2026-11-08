@extends('layouts.app')

@section('title', 'Ubah Data Profil - PATEN SPACE')

@section('content')
<div class="app-shell dashboard-page account-profile-page">
    <main class="main-panel" style="width: 100%; max-width: 860px; margin: 0 auto; padding: 25px 20px;">
        <div class="dashboard-content">
            <header class="profile-page-heading" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 22px;">
                <div style="display: flex; align-items: center; gap: 14px;">
                    <span class="profile-heading-avatar" aria-hidden="true">✉</span>
                    <div>
                        <strong style="font-size: 1.25rem; color: #1e3a5f; display: block;">Ubah Data Profil</strong>
                        <small style="color: #64748b; font-size: 0.84rem;">Perbarui alamat email, nomor telepon, dan foto profil akun Anda</small>
                    </div>
                </div>
                <a href="{{ route('account.profile') }}" class="profile-dashboard-link" style="padding: 9px 15px; font-size: 0.82rem;">
                    Kembali ke Pengaturan Akun
                </a>
            </header>

            <form action="{{ route('account.profile.data.update') }}" method="POST" enctype="multipart/form-data" class="dashboard-panel account-profile-form" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                @csrf
                @method('PUT')

                {{-- 1. UNGGAH FOTO PROFIL --}}
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px; margin-bottom: 24px; display: flex; align-items: center; gap: 18px; flex-wrap: wrap;">
                    <div>
                        @if ($user->foto_profil_url)
                            <img id="preview-avatar" src="{{ $user->foto_profil_url }}" alt="{{ $user->name }}" style="width: 76px; height: 76px; border-radius: 50%; object-fit: cover; border: 3px solid #eee8d7; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                        @else
                            <div id="preview-placeholder" style="width: 76px; height: 76px; border-radius: 50%; background: linear-gradient(135deg, #796743, #5c4728); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; font-weight: 700; border: 3px solid #eee8d7; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                                {{ $user->inisial }}
                            </div>
                            <img id="preview-avatar" src="" alt="Preview Foto" style="display: none; width: 76px; height: 76px; border-radius: 50%; object-fit: cover; border: 3px solid #eee8d7; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                        @endif
                    </div>

                    <div style="flex: 1; min-width: 250px;">
                        <label for="foto_profil" style="display: block; font-size: 0.84rem; font-weight: 700; color: #1e3a5f; margin-bottom: 5px;">
                            Foto Profil Akun
                        </label>
                        <input type="file" name="foto_profil" id="foto_profil" accept="image/png, image/jpeg, image/jpg" onchange="previewNewPhoto(event)" style="font-size: 0.8rem; color: #475569;">
                        @error('foto_profil')
                            <div style="color: #dc2626; font-size: 0.74rem; margin-top: 4px;">{{ $message }}</div>
                        @enderror
                        <div style="font-size: 0.72rem; color: #64748b; margin-top: 4px;">
                            Format gambar yang didukung: <strong>JPG, JPEG, PNG</strong>. Ukuran file maksimal: <strong>2 MB</strong>.
                        </div>
                    </div>
                </div>

                {{-- 2. DATA IDENTITAS RESMI (READ-ONLY) --}}
                <div style="background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px 18px; margin-bottom: 24px;">
                    <div style="font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                        <span>🔒</span> Data Identitas Resmi Sistem (Tidak Dapat Diubah Mandiri)
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px;">
                        <div>
                            <span style="font-size: 0.7rem; color: #64748b; display: block; font-weight: 600;">Nama Lengkap:</span>
                            <div style="font-size: 0.88rem; font-weight: 700; color: #334155; margin-top: 2px;">{{ $user->name }}</div>
                            <small style="font-size: 0.68rem; color: #94a3b8;">Ditetapkan oleh sistem resmi</small>
                        </div>

                        <div>
                            <span style="font-size: 0.7rem; color: #64748b; display: block; font-weight: 600;">
                                {{ $user->role === 'masyarakat' ? 'NIK Kependudukan:' : 'NIK / Identitas Pegawai:' }}
                            </span>
                            <div style="font-size: 0.88rem; font-weight: 700; color: #334155; margin-top: 2px;">{{ $user->nik ?: ($user->nip ?: '—') }}</div>
                            <small style="font-size: 0.68rem; color: #94a3b8;">Nomor identitas terdaftar</small>
                        </div>

                        <div>
                            <span style="font-size: 0.7rem; color: #64748b; display: block; font-weight: 600;">Peran / Jabatan:</span>
                            <div style="font-size: 0.88rem; font-weight: 700; color: #334155; margin-top: 2px;">
                                @if ($user->role === 'masyarakat')
                                    Masyarakat (Warga Pemohon)
                                @elseif ($user->peran_layanan === 'Operator')
                                    Operator Kecamatan
                                @else
                                    Petugas Loket
                                @endif
                            </div>
                            <small style="font-size: 0.68rem; color: #94a3b8;">Hak akses dalam sistem</small>
                        </div>

                        <div>
                            <span style="font-size: 0.7rem; color: #64748b; display: block; font-weight: 600;">Instansi / Wilayah:</span>
                            <div style="font-size: 0.88rem; font-weight: 700; color: #334155; margin-top: 2px;">Kecamatan Jatisari, Karawang</div>
                            <small style="font-size: 0.68rem; color: #94a3b8;">Wilayah kerja / domisili</small>
                        </div>
                    </div>
                </div>

                {{-- 3. DATA KONTAK YANG DAPAT DIUBAH (EMAIL & NO HP) --}}
                <section class="profile-form-section" style="margin-bottom: 24px;">
                    <h2 style="font-size: 1.05rem; color: #1e3a5f; margin: 0 0 14px; font-weight: 750;">Informasi Kontak Akun</h2>

                    <div class="profile-fields" style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px;">
                        <div class="field-group">
                            <label for="email" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                Alamat Email Akun <span style="color: #dc2626;">*</span>
                            </label>
                            <input id="email"
                                   name="email"
                                   type="email"
                                   value="{{ old('email', $user->email) }}"
                                   autocomplete="email"
                                   required
                                   style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                            @error('email')<span class="profile-field-error" style="color: #dc2626; font-size: 0.75rem; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                            <small style="color: #64748b; font-size: 0.72rem; margin-top: 4px; display: block;">Digunakan untuk login dan notifikasi sistem.</small>
                        </div>

                        <div class="field-group">
                            <label for="phone" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                                Nomor Telepon / WhatsApp <span style="color: #dc2626;">*</span>
                            </label>
                            <input id="phone"
                                   name="phone"
                                   type="tel"
                                   value="{{ old('phone', $user->phone) }}"
                                   autocomplete="tel"
                                   required
                                   style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.88rem; box-sizing: border-box;">
                            @error('phone')<span class="profile-field-error" style="color: #dc2626; font-size: 0.75rem; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
                            <small style="color: #64748b; font-size: 0.72rem; margin-top: 4px; display: block;">Nomor kontak aktif yang dapat dihubungi.</small>
                        </div>
                    </div>
                </section>

                <div class="profile-form-actions" style="display: flex; gap: 12px; align-items: center; border-top: 1px solid #e2e8f0; padding-top: 18px;">
                    <button type="submit" class="dashboard-primary-link" style="border: none; padding: 10px 22px; font-size: 0.86rem; font-weight: 700; cursor: pointer; border-radius: 8px;">
                        💾 Simpan Perubahan
                    </button>
                    <a href="{{ route('account.profile') }}" class="profile-back-link" style="padding: 9px 18px; border: 1px solid #cbd5e1; border-radius: 8px; color: #64748b; text-decoration: none; font-size: 0.84rem; font-weight: 600;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
function previewNewPhoto(event) {
    const input = event.target;
    if (!input.files || !input.files[0]) return;

    const file = input.files[0];
    const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!validTypes.includes(file.type)) {
        alert('Format file tidak didukung! Harap unggah gambar JPG, JPEG, atau PNG.');
        input.value = '';
        return;
    }

    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran foto terlalu besar! Maksimal ukuran foto adalah 2 MB.');
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('preview-avatar');
        const placeholder = document.getElementById('preview-placeholder');
        if (preview) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        if (placeholder) {
            placeholder.style.display = 'none';
        }
    };
    reader.readAsDataURL(file);
}
</script>
@endsection
