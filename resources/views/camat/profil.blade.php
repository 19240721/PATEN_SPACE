@extends('layouts.camat')

@section('title', 'Profil Camat - PATEN SPACE Kecamatan Jatisari')

@section('camat_content')

<div style="
    max-width: 1050px;
    margin: 0 auto;
    padding-bottom: 25px;
">

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

                {{-- AVATAR --}}
                <div style="
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

                <button type="button" style="
                    border: 1px solid #bfdbfe;
                    background: #eff6ff;
                    color: #2563eb;
                    padding: 9px 13px;
                    border-radius: 8px;
                    font-size: 0.78rem;
                    font-weight: 650;
                    cursor: pointer;
                ">
                    ✎ Edit Profil
                </button>

                <button type="button" style="
                    border: 1px solid #e2e8f0;
                    background: #ffffff;
                    color: #475569;
                    padding: 9px 13px;
                    border-radius: 8px;
                    font-size: 0.78rem;
                    font-weight: 650;
                    cursor: pointer;
                ">
                    🔒 Ubah Password
                </button>

            </div>

        </div>

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


    {{-- INFORMASI PROFIL --}}
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


        {{-- ALAMAT --}}
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

@endsection