@extends('layouts.camat')

@section('title', 'Profil Camat - PATEN SPACE Kecamatan Jatisari')

@section('camat_content')

<div style="
    max-width: 1050px;
    margin: 0 auto;
    padding-bottom: 25px;
">

    {{-- ALERT NOTIFIKASI SUKSES / ERROR --}}
    @if(session('success'))
        <div style="
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            padding: 13px 18px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.08);
        ">
            <span style="font-size: 1.15rem; line-height: 1;">✓</span>
            <div>
                <strong>Berhasil!</strong> {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div style="
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 13px 18px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 0.88rem;
            box-shadow: 0 2px 6px rgba(185, 28, 28, 0.08);
        ">
            <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; margin-bottom: 6px;">
                <span>⚠</span> Terdapat kesalahan saat menyimpan data profil:
            </div>
            <ul style="margin: 0; padding-left: 20px; font-size: 0.82rem; line-height: 1.5;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HEADER PROFIL --}}
    <div style="
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 8px 0 18px;
        flex-wrap: wrap;
    ">
        <div>
            <h2 style="
                margin: 0 0 5px;
                color: #1e3a5f;
                font-size: 1.35rem;
                font-weight: 750;
            ">
                Profil Camat
            </h2>
            <p style="
                margin: 0;
                color: #64748b;
                font-size: 0.85rem;
            ">
                Informasi profil dan akun pimpinan Kecamatan Jatisari
            </p>
        </div>

        {{-- BADGE ROLE --}}
        <div style="
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 12px;
            border-radius: 20px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #2563eb;
            font-size: 0.78rem;
            font-weight: 650;
        ">
            👤 Pimpinan / Camat
        </div>
    </div>

    {{-- PROFIL UTAMA --}}
    <div style="
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 0 3px 12px rgba(30, 58, 95, 0.05);
    ">
        <div style="
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        ">
            <div style="
                display: flex;
                align-items: center;
                gap: 17px;
            ">
                {{-- AVATAR / FOTO PROFIL --}}
                @if(!empty($profile['foto_url']))
                    <img src="{{ $profile['foto_url'] }}"
                         alt="{{ $profile['nama'] }}"
                         id="main-profile-photo"
                         style="
                            width: 78px;
                            height: 78px;
                            min-width: 78px;
                            border-radius: 50%;
                            object-fit: cover;
                            box-shadow: 0 6px 15px rgba(30, 58, 95, 0.16);
                            border: 3px solid #eff6ff;
                         ">
                @else
                    <div id="main-profile-initial" style="
                        width: 78px;
                        height: 78px;
                        min-width: 78px;
                        border-radius: 50%;
                        background: linear-gradient(135deg, #0870c9, #1e3a5f);
                        color: #ffffff;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 1.55rem;
                        font-weight: 800;
                        box-shadow: 0 6px 15px rgba(30, 58, 95, 0.16);
                    ">
                        {{ $profile['inisial'] }}
                    </div>
                @endif

                <div>
                    <h2 style="
                        margin: 0 0 5px;
                        color: #1e3a5f;
                        font-size: 1.25rem;
                        font-weight: 750;
                    ">
                        {{ $profile['nama'] }}
                    </h2>

                    <p style="
                        margin: 0 0 8px;
                        color: #64748b;
                        font-size: 0.84rem;
                    ">
                        {{ $profile['role'] }}
                    </p>

                    <span class="badge-camat success">
                        <span style="
                            display: inline-block;
                            width: 6px;
                            height: 6px;
                            border-radius: 50%;
                            background: #22c55e;
                            margin-right: 5px;
                        "></span>
                        Aktif
                    </span>
                </div>
            </div>

            {{-- AKSI PROFIL --}}
            <div style="
                display: flex;
                gap: 8px;
                flex-wrap: wrap;
            ">
                <button type="button"
                        id="btn-edit-profil"
                        onclick="toggleEditProfile()"
                        style="
                            border: 1px solid #bfdbfe;
                            background: #eff6ff;
                            color: #2563eb;
                            padding: 9px 15px;
                            border-radius: 8px;
                            font-size: 0.82rem;
                            font-weight: 700;
                            cursor: pointer;
                            display: inline-flex;
                            align-items: center;
                            gap: 6px;
                            transition: all 0.15s ease;
                        ">
                    ✎ Edit Profil
                </button>

                <a href="{{ route('account.profile.password') }}"
                   style="
                        border: 1px solid #e2e8f0;
                        background: #ffffff;
                        color: #475569;
                        padding: 9px 13px;
                        border-radius: 8px;
                        font-size: 0.78rem;
                        font-weight: 650;
                        cursor: pointer;
                        text-decoration: none;
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                   ">
                    🔒 Ubah Password
                </a>

                <a href="{{ route('account.profile') }}"
                   style="
                        border: 1px solid #e2e8f0;
                        background: #ffffff;
                        color: #475569;
                        padding: 9px 13px;
                        border-radius: 8px;
                        font-size: 0.78rem;
                        font-weight: 650;
                        cursor: pointer;
                        text-decoration: none;
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                   ">
                    ⚙ Pengaturan Akun
                </a>
            </div>
        </div>
    </div>

    {{-- FORM EDIT PROFIL (TERSEMBUNYI SECARA DEFAULT, MUNCUL SAAT EDIT DIKLIK / ERROR) --}}
    <div id="card-edit-profil" style="
        display: {{ $errors->any() ? 'block' : 'none' }};
        background: #ffffff;
        border: 1px solid #bfdbfe;
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.08);
    ">
        <div style="
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid #e2e8f0;
        ">
            <div>
                <h3 style="margin: 0 0 4px; color: #1e3a5f; font-size: 1.15rem; font-weight: 750;">
                    Formulir Edit Profil Camat
                </h3>
                <p style="margin: 0; color: #64748b; font-size: 0.82rem;">
                    Perbarui informasi nama lengkap, nomor telepon, alamat kantor, dan foto profil resmi
                </p>
            </div>
            <button type="button" onclick="cancelEditProfile()" style="background: none; border: none; font-size: 1.2rem; color: #94a3b8; cursor: pointer; line-height: 1;" title="Tutup Formulir">&times;</button>
        </div>

        <form method="POST" action="{{ route('camat.profil.update') }}" enctype="multipart/form-data" id="form-edit-profil">
            @csrf
            @method('PUT')

            {{-- 1. UPLOAD FOTO PROFIL DENGAN PREVIEW --}}
            <div style="
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: 16px;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 18px;
                flex-wrap: wrap;
            ">
                <div style="position: relative;">
                    @if(!empty($profile['foto_url']))
                        <img id="edit-foto-preview"
                             src="{{ $profile['foto_url'] }}"
                             alt="Preview Foto Profil"
                             style="width: 76px; height: 76px; border-radius: 50%; object-fit: cover; border: 3px solid #ffffff; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                    @else
                        <div id="edit-foto-placeholder"
                             style="width: 76px; height: 76px; border-radius: 50%; background: linear-gradient(135deg, #0870c9, #1e3a5f); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; border: 3px solid #ffffff; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                            {{ $profile['inisial'] }}
                        </div>
                        <img id="edit-foto-preview"
                             src=""
                             alt="Preview Foto Profil"
                             style="display: none; width: 76px; height: 76px; border-radius: 50%; object-fit: cover; border: 3px solid #ffffff; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                    @endif
                </div>

                <div style="flex: 1; min-width: 250px;">
                    <label for="foto_profil_input" style="display: block; font-size: 0.84rem; font-weight: 700; color: #1e3a5f; margin-bottom: 4px;">
                        Foto Profil Resmi
                    </label>
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <input type="file"
                               name="foto_profil"
                               id="foto_profil_input"
                               accept="image/png, image/jpeg, image/jpg"
                               onchange="handlePhotoChange(event)"
                               style="font-size: 0.8rem; color: #475569;">
                    </div>
                    <div style="font-size: 0.72rem; color: #64748b; margin-top: 5px;">
                        Format yang didukung: <strong>JPG, JPEG, PNG</strong>. Ukuran file maksimal: <strong>2 MB</strong>.
                    </div>
                </div>
            </div>

            {{-- 2. FIELD DATA YANG BOLEH DIEDIT --}}
            <div style="
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 16px;
                margin-bottom: 16px;
            ">
                {{-- Nama Lengkap Camat --}}
                <div>
                    <label for="input_name" style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                        Nama Lengkap Camat <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="input_name"
                           value="{{ old('name', $profile['nama']) }}"
                           required
                           style="
                                width: 100%;
                                padding: 10px 14px;
                                border: 1px solid #cbd5e1;
                                border-radius: 8px;
                                font-size: 0.88rem;
                                color: #1e293b;
                                box-sizing: border-box;
                                transition: border-color 0.15s;
                           "
                           onfocus="this.style.borderColor='#2563eb';"
                           onblur="this.style.borderColor='#cbd5e1';">
                    @error('name')
                        <div style="color: #dc2626; font-size: 0.74rem; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nomor Telepon --}}
                <div>
                    <label for="input_phone" style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                        Nomor Telepon Kantor / Dinas <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text"
                           name="phone"
                           id="input_phone"
                           value="{{ old('phone', $profile['telepon']) }}"
                           required
                           style="
                                width: 100%;
                                padding: 10px 14px;
                                border: 1px solid #cbd5e1;
                                border-radius: 8px;
                                font-size: 0.88rem;
                                color: #1e293b;
                                box-sizing: border-box;
                                transition: border-color 0.15s;
                           "
                           onfocus="this.style.borderColor='#2563eb';"
                           onblur="this.style.borderColor='#cbd5e1';">
                    @error('phone')
                        <div style="color: #dc2626; font-size: 0.74rem; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Alamat Kantor --}}
            <div style="margin-bottom: 20px;">
                <label for="input_alamat" style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                    Alamat Kantor Kecamatan
                </label>
                <textarea name="alamat_kantor"
                          id="input_alamat"
                          rows="2"
                          style="
                                width: 100%;
                                padding: 10px 14px;
                                border: 1px solid #cbd5e1;
                                border-radius: 8px;
                                font-size: 0.88rem;
                                color: #1e293b;
                                box-sizing: border-box;
                                resize: vertical;
                                line-height: 1.5;
                                transition: border-color 0.15s;
                          "
                          onfocus="this.style.borderColor='#2563eb';"
                          onblur="this.style.borderColor='#cbd5e1';">{{ old('alamat_kantor', $profile['alamat_kantor']) }}</textarea>
                @error('alamat_kantor')
                    <div style="color: #dc2626; font-size: 0.74rem; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>

            {{-- 3. INFORMASI SISTEM YANG TIDAK DAPAT DIEDIT (READ-ONLY) --}}
            <div style="
                background: #f1f5f9;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                padding: 14px 16px;
                margin-bottom: 22px;
            ">
                <div style="font-size: 0.76rem; font-weight: 700; color: #475569; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                    <span>🔒</span> Data Sistem Resmi (Tidak Dapat Diubah Melalui Halaman Ini)
                </div>

                <div style="
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 12px;
                ">
                    <div>
                        <span style="font-size: 0.7rem; color: #64748b; display: block;">NIP Pegawai:</span>
                        <strong style="font-size: 0.84rem; color: #334155;">{{ $profile['nip'] }}</strong>
                        <small style="display: block; font-size: 0.68rem; color: #94a3b8;">Identitas kepegawaian resmi</small>
                    </div>

                    <div>
                        <span style="font-size: 0.7rem; color: #64748b; display: block;">Email Akun:</span>
                        <strong style="font-size: 0.84rem; color: #334155;">{{ $profile['email'] }}</strong>
                        <small style="display: block; font-size: 0.68rem; color: #94a3b8;">Terkait autentikasi login akun</small>
                    </div>

                    <div>
                        <span style="font-size: 0.7rem; color: #64748b; display: block;">Jabatan & Peran:</span>
                        <strong style="font-size: 0.84rem; color: #334155;">Pimpinan / Camat</strong>
                        <small style="display: block; font-size: 0.68rem; color: #94a3b8;">Hak akses pengawasan & monitoring</small>
                    </div>

                    <div>
                        <span style="font-size: 0.7rem; color: #64748b; display: block;">Wilayah Kerja:</span>
                        <strong style="font-size: 0.84rem; color: #334155;">Kecamatan Jatisari, Kabupaten Karawang</strong>
                        <small style="display: block; font-size: 0.68rem; color: #94a3b8;">Wilayah tugas pemerintahan</small>
                    </div>
                </div>
            </div>

            {{-- 4. TOMBOL SIMPAN & BATAL --}}
            <div style="display: flex; gap: 10px; align-items: center;">
                <button type="submit"
                        id="btn-save-profile"
                        style="
                            background: #2563eb;
                            color: #ffffff;
                            padding: 10px 22px;
                            border: none;
                            border-radius: 8px;
                            font-size: 0.84rem;
                            font-weight: 700;
                            cursor: pointer;
                            display: inline-flex;
                            align-items: center;
                            gap: 6px;
                            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
                            transition: background-color 0.15s;
                        ">
                    Simpan Perubahan
                </button>

                <button type="button"
                        id="btn-cancel-edit"
                        onclick="cancelEditProfile()"
                        style="
                            background: #ffffff;
                            color: #64748b;
                            padding: 10px 18px;
                            border: 1px solid #cbd5e1;
                            border-radius: 8px;
                            font-size: 0.84rem;
                            font-weight: 600;
                            cursor: pointer;
                            transition: background-color 0.15s;
                        ">
                    Batal
                </button>
            </div>
        </form>
    </div>

    {{-- RINGKASAN --}}
    <div style="
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 15px;
    ">
        <div style="
            padding: 14px 16px;
            background: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 11px;
        ">
            <div style="
                font-size: 0.72rem;
                color: #64748b;
                margin-bottom: 5px;
            ">
                Peran Sistem
            </div>

            <div style="
                font-size: 0.9rem;
                font-weight: 700;
                color: #1e3a5f;
            ">
                Pimpinan / Camat
            </div>
        </div>

        <div style="
            padding: 14px 16px;
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            border-radius: 11px;
        ">
            <div style="
                font-size: 0.72rem;
                color: #64748b;
                margin-bottom: 5px;
            ">
                Status Akun
            </div>

            <div style="
                font-size: 0.9rem;
                font-weight: 700;
                color: #15803d;
            ">
                Aktif
            </div>
        </div>

        <div style="
            padding: 14px 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 11px;
        ">
            <div style="
                font-size: 0.72rem;
                color: #64748b;
                margin-bottom: 5px;
            ">
                Wilayah Kerja
            </div>

            <div style="
                font-size: 0.9rem;
                font-weight: 700;
                color: #1e3a5f;
            ">
                Kecamatan Jatisari
            </div>
        </div>
    </div>

    {{-- INFORMASI PROFIL LENGKAP --}}
    <div style="
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 19px;
        margin-bottom: 15px;
        box-shadow: 0 3px 12px rgba(30, 58, 95, 0.04);
    ">
        <div style="margin-bottom: 14px;">
            <h3 style="
                margin: 0 0 4px;
                color: #1e3a5f;
                font-size: 1.08rem;
                font-weight: 750;
            ">
                Informasi Profil
            </h3>

            <p style="
                margin: 0;
                color: #64748b;
                font-size: 0.8rem;
            ">
                Data informasi Camat Kecamatan Jatisari
            </p>
        </div>

        <div style="
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 11px;
        ">
            {{-- Nama --}}
            <div style="
                padding: 13px 15px;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                background: #f8fafc;
            ">
                <div style="font-size: 0.7rem; color: #64748b; margin-bottom: 5px;">
                    Nama
                </div>
                <div style="font-size: 0.88rem; font-weight: 700; color: #1e3a5f;">
                    {{ $profile['nama'] }}
                </div>
            </div>

            {{-- Jabatan --}}
            <div style="
                padding: 13px 15px;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                background: #f8fafc;
            ">
                <div style="font-size: 0.7rem; color: #64748b; margin-bottom: 5px;">
                    Jabatan
                </div>
                <div style="font-size: 0.88rem; font-weight: 700; color: #1e3a5f;">
                    {{ $profile['jabatan'] }}
                </div>
            </div>

            {{-- Kecamatan --}}
            <div style="
                padding: 13px 15px;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                background: #f8fafc;
            ">
                <div style="font-size: 0.7rem; color: #64748b; margin-bottom: 5px;">
                    Kecamatan
                </div>
                <div style="font-size: 0.88rem; font-weight: 700; color: #1e3a5f;">
                    {{ $profile['kecamatan'] }}
                </div>
            </div>

            {{-- Kabupaten --}}
            <div style="
                padding: 13px 15px;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                background: #f8fafc;
            ">
                <div style="font-size: 0.7rem; color: #64748b; margin-bottom: 5px;">
                    Kabupaten
                </div>
                <div style="font-size: 0.88rem; font-weight: 700; color: #1e3a5f;">
                    {{ $profile['kabupaten'] }}
                </div>
            </div>

            {{-- Instansi --}}
            <div style="
                padding: 13px 15px;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                background: #f8fafc;
            ">
                <div style="font-size: 0.7rem; color: #64748b; margin-bottom: 5px;">
                    Instansi
                </div>
                <div style="font-size: 0.88rem; font-weight: 700; color: #1e3a5f;">
                    {{ $profile['instansi'] }}
                </div>
            </div>

            {{-- NIP --}}
            <div style="
                padding: 13px 15px;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                background: #f8fafc;
            ">
                <div style="font-size: 0.7rem; color: #64748b; margin-bottom: 5px;">
                    NIP
                </div>
                <div style="font-size: 0.88rem; font-weight: 700; color: #1e3a5f;">
                    {{ $profile['nip'] }}
                </div>
            </div>

            {{-- Email --}}
            <div style="
                padding: 13px 15px;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                background: #f8fafc;
            ">
                <div style="font-size: 0.7rem; color: #64748b; margin-bottom: 5px;">
                    Email
                </div>
                <div style="font-size: 0.88rem; font-weight: 700; color: #1e3a5f;">
                    {{ $profile['email'] }}
                </div>
            </div>

            {{-- Telepon --}}
            <div style="
                padding: 13px 15px;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                background: #f8fafc;
            ">
                <div style="font-size: 0.7rem; color: #64748b; margin-bottom: 5px;">
                    Telepon
                </div>
                <div style="font-size: 0.88rem; font-weight: 700; color: #1e3a5f;">
                    {{ $profile['telepon'] }}
                </div>
            </div>
        </div>

        {{-- ALAMAT KANTOR --}}
        <div style="
            margin-top: 11px;
            padding: 13px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
        ">
            <div style="
                font-size: 0.7rem;
                color: #64748b;
                margin-bottom: 5px;
            ">
                Alamat Kantor
            </div>

            <div style="
                font-size: 0.88rem;
                font-weight: 600;
                color: #1e3a5f;
                line-height: 1.5;
            ">
                {{ $profile['alamat_kantor'] }}
            </div>
        </div>
    </div>

    {{-- TENTANG PERAN CAMAT --}}
    <div style="
        background: linear-gradient(135deg, #eff6ff, #f8fafc);
        border: 1px solid #dbeafe;
        border-radius: 14px;
        padding: 17px 19px;
        margin-bottom: 15px;
    ">
        <div style="
            display: flex;
            gap: 12px;
            align-items: flex-start;
        ">
            <div style="
                width: 34px;
                height: 34px;
                min-width: 34px;
                border-radius: 9px;
                background: #dbeafe;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #2563eb;
                font-size: 1rem;
            ">
                ✓
            </div>

            <div>
                <h3 style="
                    margin: 0 0 5px;
                    color: #1e3a5f;
                    font-size: 0.95rem;
                    font-weight: 750;
                ">
                    Peran dalam PATEN SPACE
                </h3>

                <p style="
                    margin: 0;
                    color: #64748b;
                    font-size: 0.79rem;
                    line-height: 1.6;
                ">
                    Camat memiliki akses untuk memantau perkembangan pelayanan,
                    melihat statistik, meninjau laporan, serta mengawasi aktivitas
                    pelayanan administrasi Kecamatan Jatisari.
                </p>
            </div>
        </div>
    </div>

    {{-- INFORMASI AKUN --}}
    <div style="
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 13px 17px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 11px;
        color: #64748b;
        font-size: 0.75rem;
        flex-wrap: wrap;
    ">
        <span>
            🔐 Akun Pimpinan Kecamatan
        </span>

        <span>
            PATEN SPACE • Kecamatan Jatisari
        </span>
    </div>

</div>

<script>
    function toggleEditProfile() {
        const card = document.getElementById('card-edit-profil');
        if (!card) return;

        if (card.style.display === 'none' || card.style.display === '') {
            card.style.display = 'block';
            card.scrollIntoView({ behavior: 'smooth', block: 'start' });
            const inputName = document.getElementById('input_name');
            if (inputName) inputName.focus();
        } else {
            card.style.display = 'none';
        }
    }

    function cancelEditProfile() {
        const card = document.getElementById('card-edit-profil');
        if (card) {
            card.style.display = 'none';
        }
        // Kembalikan form ke nilai awal
        const form = document.getElementById('form-edit-profil');
        if (form) {
            form.reset();
        }
        // Kembalikan preview foto jika sempat diganti
        const previewImg = document.getElementById('edit-foto-preview');
        const placeholder = document.getElementById('edit-foto-placeholder');
        const initialSrc = "{{ !empty($profile['foto_url']) ? $profile['foto_url'] : '' }}";
        if (initialSrc) {
            if (previewImg) {
                previewImg.src = initialSrc;
                previewImg.style.display = 'block';
            }
            if (placeholder) placeholder.style.display = 'none';
        } else {
            if (previewImg) previewImg.style.display = 'none';
            if (placeholder) placeholder.style.display = 'flex';
        }
    }

    function handlePhotoChange(event) {
        const input = event.target;
        if (!input.files || !input.files[0]) return;

        const file = input.files[0];

        // Validasi tipe file
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!validTypes.includes(file.type)) {
            alert('Format file tidak didukung! Harap unggah gambar JPG, JPEG, atau PNG.');
            input.value = '';
            return;
        }

        // Validasi ukuran file (maksimal 2 MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran foto terlalu besar! Maksimal ukuran foto adalah 2 MB.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('edit-foto-preview');
            const placeholder = document.getElementById('edit-foto-placeholder');
            if (previewImg) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
            }
            if (placeholder) {
                placeholder.style.display = 'none';
            }
        };
        reader.readAsDataURL(file);
    }
</script>

@endsection
