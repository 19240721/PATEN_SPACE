@extends('layouts.camat')

@section('title', 'Notifikasi - PATEN SPACE Camat Jatisari')

@section('camat_content')
{{-- HEADING NOTIFIKASI --}}
<section class="dashboard-heading">
    <div>
        <h1>Notifikasi</h1>
        <p>Pusat informasi dan pemberitahuan penting pelayanan Kecamatan Jatisari</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <span class="badge-camat warning" style="font-size: 0.8rem; padding: 6px 14px;">
            🔔 {{ $unreadCount }} Notifikasi Baru
        </span>
    </div>
</section>

{{-- LIST NOTIFIKASI KHUSUS CAMAT --}}
<section class="dashboard-panel" style="padding: 20px; background: #ffffff;">
    <div class="panel-heading" style="margin-bottom: 20px;">
        <div>
            <h2>Pemberitahuan Pelayanan</h2>
            <p>Daftar informasi yang memerlukan pemantauan dan perhatian pimpinan</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="button" class="table-action" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; cursor: pointer; padding: 6px 12px; border-radius: 6px; font-weight: 600;">
                Tandai Sudah Dibaca
            </button>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 14px;">
        @foreach ($notifications as $item)
            <div style="display: flex; gap: 16px; padding: 18px 20px; border-radius: 8px; border: 1px solid {{ !$item['dibaca'] ? ($item['badge_class'] === 'danger' ? '#fecaca' : ($item['badge_class'] === 'warning' ? '#fed7aa' : '#bfdbfe')) : '#e2e8f0' }}; background: {{ !$item['dibaca'] ? ($item['badge_class'] === 'danger' ? '#fffdfd' : ($item['badge_class'] === 'warning' ? '#fffbf5' : '#f8fbff')) : '#ffffff' }}; transition: box-shadow 0.2s ease;">
                <div style="width: 44px; height: 44px; border-radius: 10px; display: grid; place-items: center; font-size: 1.3rem; flex-shrink: 0; background: {{ $item['badge_class'] === 'danger' ? '#fee2e2' : ($item['badge_class'] === 'warning' ? '#ffedd5' : ($item['badge_class'] === 'success' ? '#dcfce7' : '#e0f2fe')) }}; color: {{ $item['badge_class'] === 'danger' ? '#b91c1c' : ($item['badge_class'] === 'warning' ? '#c2410c' : ($item['badge_class'] === 'success' ? '#15803d' : '#0369a1')) }};">
                    @if ($item['badge_class'] === 'danger')
                        ⚠
                    @elseif ($item['badge_class'] === 'warning')
                        ⚠️
                    @elseif ($item['badge_class'] === 'success')
                        ✓
                    @else
                        ℹ
                    @endif
                </div>

                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px; flex-wrap: wrap; gap: 8px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <strong style="color: #0f172a; font-size: 0.95rem;">{{ $item['judul'] }}</strong>
                            <span class="badge-camat {{ $item['badge_class'] }}">{{ $item['kategori'] }}</span>
                            @if (!$item['dibaca'])
                                <span style="width: 8px; height: 8px; border-radius: 50%; background: #ef4444; display: inline-block;" title="Belum dibaca"></span>
                            @endif
                        </div>
                        <span style="font-size: 0.74rem; color: #64748b;">{{ $item['waktu'] }}</span>
                    </div>

                    <p style="margin: 0 0 12px; color: #475569; font-size: 0.84rem; line-height: 1.5;">
                        {{ $item['pesan'] }}
                    </p>

                    <div style="display: flex; align-items: center; gap: 12px;">
                        <a href="{{ $item['link'] }}" class="dashboard-primary-link" style="padding: 6px 12px; font-size: 0.76rem; border-radius: 5px;">
                            Lihat Rincian →
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
