{{-- Modal Konfirmasi Logout Kustom PATEN SPACE --}}
<div id="logout-confirm-modal"
     class="logout-modal-backdrop"
     role="dialog"
     aria-modal="true"
     aria-labelledby="logout-modal-title"
     aria-describedby="logout-modal-desc"
     style="display: none;">
    <div class="logout-modal-dialog">
        {{-- Ikon Logout di Tengah --}}
        <div class="logout-modal-icon-badge" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </div>

        {{-- Judul Terpusat --}}
        <h3 id="logout-modal-title" class="logout-modal-title">
            Yakin ingin keluar?
        </h3>

        {{-- Deskripsi Terpusat --}}
        <p id="logout-modal-desc" class="logout-modal-desc">
            Apakah kamu yakin ingin mengakhiri sesi dan keluar dari akun PATEN SPACE?
        </p>

        {{-- Tombol Aksi --}}
        <form id="logout-modal-form" action="{{ route('logout') }}" method="POST" style="margin: 0; padding: 0;">
            @csrf
            <div class="logout-modal-actions">
                <button type="button"
                        id="logout-modal-btn-cancel"
                        class="modal-btn-cancel"
                        onclick="closeLogoutModal()">
                    Batal
                </button>
                <button type="submit"
                        id="logout-modal-btn-confirm"
                        class="modal-btn-confirm">
                    Ya, Keluar
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Styling Modal Konfirmasi Logout PATEN SPACE */
.logout-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 18px;
    box-sizing: border-box;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.logout-modal-backdrop.is-active {
    opacity: 1;
}

.logout-modal-dialog {
    background: #ffffff;
    width: 100%;
    max-width: 430px;
    border-radius: 16px;
    padding: 30px 24px 26px;
    box-shadow: 0 20px 45px rgba(15, 23, 42, 0.22), 0 8px 18px rgba(15, 23, 42, 0.08);
    border: 1px solid #e5dece;
    text-align: center;
    box-sizing: border-box;
    transform: scale(0.94);
    transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.logout-modal-backdrop.is-active .logout-modal-dialog {
    transform: scale(1);
}

.logout-modal-icon-badge {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #fbf8f2;
    border: 2px solid #e8decf;
    color: #796743;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 4px 12px rgba(121, 103, 67, 0.12);
}

.logout-modal-icon-badge svg {
    width: 28px;
    height: 28px;
    stroke: #796743;
}

.logout-modal-title {
    margin: 0 0 8px;
    color: #1e3a5f;
    font-size: 1.25rem;
    font-weight: 750;
    line-height: 1.3;
}

.logout-modal-desc {
    margin: 0 0 24px;
    color: #64748b;
    font-size: 0.88rem;
    line-height: 1.5;
    padding: 0 6px;
}

.logout-modal-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
}

.modal-btn-cancel {
    flex: 1;
    padding: 11px 18px;
    background: #ffffff;
    color: #475569;
    border: 1px solid #d5cebf;
    border-radius: 10px;
    font-size: 0.88rem;
    font-weight: 650;
    cursor: pointer;
    transition: all 0.15s ease;
    font-family: inherit;
}

.modal-btn-cancel:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    color: #1e293b;
}

.modal-btn-confirm {
    flex: 1;
    padding: 11px 18px;
    background: #796743;
    color: #ffffff;
    border: 1px solid #675736;
    border-radius: 10px;
    font-size: 0.88rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(121, 103, 67, 0.25);
    transition: all 0.15s ease;
    font-family: inherit;
}

.modal-btn-confirm:hover {
    background: #655535;
    border-color: #544629;
    box-shadow: 0 6px 16px rgba(121, 103, 67, 0.35);
}

.modal-btn-confirm:disabled,
.modal-btn-cancel:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}
</style>

<script>
function openLogoutModal(e) {
    if (e && e.preventDefault) e.preventDefault();
    const modal = document.getElementById('logout-confirm-modal');
    if (!modal) return;
    modal.style.display = 'flex';
    requestAnimationFrame(() => {
        modal.classList.add('is-active');
    });
    document.body.style.overflow = 'hidden';
    const confirmBtn = document.getElementById('logout-modal-btn-confirm');
    if (confirmBtn) confirmBtn.focus();
}

function closeLogoutModal() {
    const modal = document.getElementById('logout-confirm-modal');
    if (!modal) return;
    modal.classList.remove('is-active');
    setTimeout(() => {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }, 180);
}

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('logout-confirm-modal');
    if (!modal) return;

    // Klik di luar dialog (backdrop) untuk menutup tanpa logout
    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            closeLogoutModal();
        }
    });

    // Tekan tombol Escape untuk menutup tanpa logout
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            closeLogoutModal();
        }
    });

    // Cegah submit ganda saat Ya, Keluar diklik
    const form = document.getElementById('logout-modal-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            const confirmBtn = document.getElementById('logout-modal-btn-confirm');
            const cancelBtn = document.getElementById('logout-modal-btn-cancel');
            if (confirmBtn && confirmBtn.disabled) {
                e.preventDefault();
                return;
            }
            if (confirmBtn) {
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Memproses...';
            }
            if (cancelBtn) {
                cancelBtn.disabled = true;
            }
        });
    }

    // Tangkap klik pada semua tombol / form logout yang ada di halaman
    const logoutTriggers = document.querySelectorAll('.btn-trigger-logout, form[action$="/logout"] button[type="submit"]');
    logoutTriggers.forEach(function (btn) {
        if (btn.id === 'logout-modal-btn-confirm') return;

        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            openLogoutModal(e);
        });
    });
});
</script>
