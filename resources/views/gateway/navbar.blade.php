<style>
    /* =====================================================
           NAVBAR
        ===================================================== */
        html {
            scroll-behavior: smooth;
        }

        #gateway #navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 5%;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 999;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: box-shadow 0.25s ease;
        }

        /* shadow muncul saat scroll */
        #gateway .navbar.scrolled {
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }
        #gateway .nav-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        #gateway .nav-left img {
            height: 40px;
        }

        #gateway .nav-center {
            display: flex;
            gap: 24px;
        }

        #gateway .nav-center a {
            font-weight: 500;
            color: #374151;
        }

</style>
<header id="navbar">
    <div class="nav-left">
        <img src="{{ asset('assets/images/logo-kolaan.png') }}" alt="Logo Sekolah">
        <strong>SDN Mekar Jaya</strong>
    </div>

    <nav class="nav-center">
        <a href="#hero-slider">Beranda</a>
        <a href="#gambaran-umum">Informasi</a>
        <a href="#prestasi">Prestasi</a>
        <a href="#kontak">Kontak</a>
    </nav>

    <div class="nav-right">
        <button id="toggle-theme" title="Dark Mode">
            🌙
        </button>
        <a href="{{ route('login') }}" class="btn-login">Masuk Sistem</a>
    </div>

</header>

<script>
const navbar = document.querySelector('#gateway .navbar');

window.addEventListener('scroll', () => {
    if (window.scrollY > 10) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});
</script>

<script>
document.querySelectorAll('#navbar a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});
</script>


