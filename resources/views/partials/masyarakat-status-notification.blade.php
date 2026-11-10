<div class="status-notification-backdrop" role="presentation">
    <section class="status-notification-dialog" role="dialog" aria-modal="true" aria-labelledby="status-notification-title">
        <div class="status-notification-icon" aria-hidden="true">
            @switch($notification->data['status'] ?? '')
                @case('Baru') ✓ @break
                @case('Proses') ↻ @break
                @case('Selesai') ✓ @break
                @default !
            @endswitch
        </div>
        <p class="status-notification-eyebrow">Pembaruan Permohonan</p>
        <h2 id="status-notification-title">{{ $notification->data['title'] ?? 'Status Permohonan Diperbarui' }}</h2>
        <p class="status-notification-message">{{ $notification->data['message'] ?? '' }}</p>
        @if(($notification->data['status'] ?? '') === 'Ditolak' && !empty($notification->data['alasan_penolakan']))
            <p class="status-notification-note"><strong>Alasan:</strong> {{ $notification->data['alasan_penolakan'] }}</p>
        @endif
        @if(!empty($notification->data['catatan_petugas']))
            <p class="status-notification-note"><strong>Catatan petugas:</strong> {{ $notification->data['catatan_petugas'] }}</p>
        @endif
        <p class="status-notification-reference">
            {{ $notification->data['jenis_layanan'] ?? 'Permohonan' }}
            @if(!empty($notification->data['pendaftaran_id']))
                &middot; No. {{ str_pad($notification->data['pendaftaran_id'], 5, '0', STR_PAD_LEFT) }}
            @endif
        </p>
        <form method="POST" action="{{ route('masyarakat.notifikasi.read', $notification->id) }}">
            @csrf
            <button type="submit" class="status-notification-button">Mengerti</button>
        </form>
    </section>
</div>

<style>
    .status-notification-backdrop {
        position: fixed;
        inset: 0;
        z-index: 1000000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(36, 30, 20, 0.58);
        backdrop-filter: blur(3px);
    }

    .status-notification-dialog {
        width: min(100%, 420px);
        padding: 30px 26px 24px;
        border: 1px solid #e8dfce;
        border-radius: 18px;
        background: #fffefa;
        color: #292524;
        text-align: center;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.25);
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .status-notification-icon {
        display: grid;
        width: 58px;
        height: 58px;
        margin: 0 auto 14px;
        place-items: center;
        border: 2px solid #d9c9a8;
        border-radius: 50%;
        background: #f7f1e4;
        color: #796743;
        font-size: 30px;
        font-weight: 800;
    }

    .status-notification-eyebrow {
        margin: 0 0 6px;
        color: #796743;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .status-notification-dialog h2 {
        margin: 0;
        font-size: 1.25rem;
        line-height: 1.35;
    }

    .status-notification-message {
        margin: 12px 0;
        color: #57534e;
        font-size: 0.92rem;
        line-height: 1.55;
    }

    .status-notification-note {
        margin: 10px 0;
        padding: 10px 12px;
        border-radius: 9px;
        background: #f8f4eb;
        color: #57534e;
        font-size: 0.84rem;
        line-height: 1.5;
        text-align: left;
        overflow-wrap: anywhere;
    }

    .status-notification-reference {
        margin: 14px 0 18px;
        color: #78716c;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .status-notification-button {
        width: 100%;
        min-height: 44px;
        border: 0;
        border-radius: 9px;
        background: #796743;
        color: #fff;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
    }

    .status-notification-button:hover {
        background: #635435;
    }
</style>
