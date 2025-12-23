<div id="helpModal" class="help-overlay">
    <div class="help-modal">

        <!-- HEADER -->
        <div class="help-header">
            <h3>Ada Masalah Dalam Login?</h3>
            <button class="help-close" onclick="closeHelpModal()">✕</button>
        </div>

        <p class="help-desc">
            Jika Anda mengalami kendala saat login, silakan datang langsung ke lokasi berikut:
        </p>

        <!-- LOKASI -->
        <div class="help-box help-location">
            <div class="help-box-header">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="#2563eb" stroke-width="2">
                    <path d="M12 21s8-4.5 8-11a8 8 0 1 0-16 0c0 6.5 8 11 8 11z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                <h4>Lokasi</h4>
            </div>

            <div class="help-item">
                <span>Ruang Tata Usaha</span>
                <span>SDN Mekar Jaya</span>
            </div>
        </div>

        <!-- JAM -->
        <div class="help-box help-time">
            <div class="help-box-header">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="#d97706" stroke-width="2">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 7v5l3 3"/>
                </svg>
                <h4>Jam Pelayanan</h4>
            </div>

            <ul>
                <li>Senin – Jumat : 08.00 – 14.00 WIB</li>
                <li>Sabtu – Minggu : Tutup</li>
            </ul>
        </div>

        <!-- KONTAK -->
        <div class="help-box help-contact">
            <div class="help-box-header">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="#16a34a" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2
                        19.79 19.79 0 0 1-8.63-3.07
                        19.5 19.5 0 0 1-6-6
                        19.79 19.79 0 0 1-3.07-8.67
                        A2 2 0 0 1 4.11 2h3
                        a2 2 0 0 1 2 1.72
                        12.84 12.84 0 0 0 .7 2.81
                        2 2 0 0 1-.45 2.11L8.09 9.91
                        a16 16 0 0 0 6 6
                        l1.27-1.27
                        a2 2 0 0 1 2.11-.45
                        12.84 12.84 0 0 0 2.81.7
                        a2 2 0 0 1 1.72 2z"/>
                </svg>
                <h4>Kontak Bantuan</h4>
            </div>

            <div class="help-item">
                <span>Hubungi Kami :</span>
                <span class="help-phone">0855-1298-491</span>
                <a
                    href="https://wa.me/628551298491?text=Halo%20Tata%20Usaha%20SDN%20Mekar%20Jaya,%20saya%20mengalami%20kendala%20login%20pada%20sistem."
                    target="_blank"
                    class="help-wa"
                >
                    Hubungi via WhatsApp
                </a>

                

            </div>
        </div>

        <!-- ACTION -->
        <div class="help-actions">
            <button onclick="closeHelpModal()">Sudah Mengerti</button>
        </div>

    </div>
</div>


<style>
.help-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.6);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.help-modal {
    background: #ffffff;
    width: 92%;
    max-width: 420px;
    border-radius: 18px;
    padding: 22px;
    box-shadow: 0 40px 90px rgba(0,0,0,.45);
    font-family: 'Poppins', sans-serif;
}

/* HEADER */
.help-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.help-header h3 {
    font-size: 18px;
    font-weight: 600;
}

.help-close {
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: #555;
}

/* DESC */
.help-desc {
    font-size: 13px;
    color: #555;
    margin-bottom: 16px;
    line-height: 1.5;
}

/* BOX */
.help-box {
    border-radius: 12px;
    padding: 14px;
    margin-bottom: 12px;
    background: #f8fafc;
}

.help-box-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}

.help-box h4 {
    font-size: 14px;
    font-weight: 600;
}

/* LOKASI */
.help-location {
    border-left: 4px solid #2563eb;
}

/* JAM */
.help-time {
    border-left: 4px solid #d97706;
}

.help-item span {
    display: block;
    font-size: 13px;
    color: #333;
}

.help-box ul {
    padding-left: 18px;
    font-size: 13px;
    color: #333;
}

/* ACTION */
.help-actions {
    text-align: right;
    margin-top: 16px;
}

.help-actions button {
    background: #2563eb;
    border: none;
    color: #fff;
    padding: 9px 18px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
}

.help-actions button:hover {
    background: #1e40af;
}
/* KONTAK */
.help-contact {
    border-left: 4px solid #16a34a;
}

.help-wa {
    display: inline-block;
    margin: 6px 0;
    background: #22c55e;
    color: #fff;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
}

.help-wa:hover {
    background: #16a34a;
}

.help-phone {
    font-size: 13px;
    color: #333;
}

</style>

<script>
function openHelpModal() {
    document.getElementById('helpModal').style.display = 'flex';
}
function closeHelpModal() {
    document.getElementById('helpModal').style.display = 'none';
}
</script>

