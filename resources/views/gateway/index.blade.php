<!DOCTYPE html>
<html lang="id" class="theme-light">
<head>
    <meta charset="UTF-8">
    <title>Gateway Sistem | SDN Mekar Jaya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dark.css') }}">



    <style>
        /* =====================================================
           RESET & BASE
        ===================================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
            background: #f8fafc;
            color: #1f2937;
            line-height: 1.6;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* =====================================================
           GATEWAY WRAPPER (NAMESPACE)
        ===================================================== */
        #gateway section {
            padding: 80px 6%;
        }

        #gateway h2 {
            letter-spacing: -0.3px;
        }

        #gateway p {
            font-size: 1rem;
        }

        /* =====================================================
           HERO SECTION
        ===================================================== */
        #gateway #hero {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 48px;
            align-items: center;
        }
        
        #gateway #hero-slider {
            padding: 0;
        }

        #gateway .hero-bg {
            position: relative;
            width: 100%;
            aspect-ratio: 24 / 9;
            background-position: center;
            background-size: cover;
            background: url('/assets/images/gateway/sekolah1.jpg') center / cover no-repeat;
            overflow: hidden;
        }

        #gateway .hero-bg::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to top,
                rgba(0,0,0,0.6) 0%,
                rgba(0,0,0,0.35) 35%,
                rgba(0,0,0,0.1) 60%,
                rgba(0,0,0,0.0) 80%
            );
            z-index: 1;
        }


        /* KIRI BAWAH */
        #gateway .hero-content-left {
            position: absolute;
            left: 48px;
            bottom: 48px;
            color: #ffffff;
            max-width: 60%;
            z-index: 2;
        }

        #gateway .hero-content-left h1 {
            font-size: 2.6rem;
            letter-spacing: -0.5px;
        }

        #gateway .hero-content-left p {
            max-width: 520px;
            line-height: 1.7;
        }

        /* KANAN BAWAH */
        #gateway .hero-content-right {
            position: absolute;
            right: 48px;
            bottom: 48px;
            z-index: 2;
        }

        #gateway .btn-info {
            background: rgba(255,255,255,0.9);
            color: #1f2937;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 500;
        }

        /* progress line */
        #gateway .hero-progress {
            position: absolute;
            left: 0;
            bottom: 0;
            height: 4px;
            width: 0%;
            background: #2563eb;
            z-index: 3;
            animation: heroProgress 3s linear infinite;
        }

        @keyframes heroProgress {
            from { width: 0%; }
            to { width: 100%; }
        }

        #gateway .btn-info {
            backdrop-filter: blur(6px);
            transition: all 0.25s ease;
        }

        #gateway .btn-info:hover {
            background: #ffffff;
            transform: translateY(-2px);
        }
       
        /* ================= GAMBARAN UMUM ================= */

        #gateway #gambaran-umum {
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
        }

        #gateway .gu-container {
            padding-top: 12px;
            max-width: 900px;
            margin: 0 auto;
        }

        #gateway .gu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom:20px;
        }

        #gateway .gu-header h2 {
            font-size: 1.8rem;
            letter-spacing: -0.4px;
        }

        #gateway .gu-toggle {
            background: #f1f5f9;
            border: 1px solid #e5e7eb;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        /* icon */
        #gateway .gu-icon {
            transition: transform 0.35s ease;
        }

        /* saat aktif (panah ke atas) */
        #gateway #gambaran-umum.active .gu-icon {
            transform: rotate(180deg);
        }

        /* konten default hidden */
        #gateway .gu-content {
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transform: translateY(-8px);
            transition:
                max-height 0.45s ease,
                opacity 0.3s ease,
                transform 0.3s ease;
        }

        /* saat aktif */
        #gateway #gambaran-umum.active .gu-content {
            max-height: 600px; /* aman */
            opacity: 1;
            transform: translateY(0);
        }

        #gateway .gu-stats {
            margin-top: 32px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 24px;
        }
        #gateway .stat-card {
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 28px 24px;
            text-align: center;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
            background: linear-gradient(
                180deg,
                #ffffff 0%,
                #f9fafb 100%
            );
        }
        #gateway .stat-card::before {
            content: "";
            position: absolute;
            bottom : 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(
                90deg,
                #2563eb,
                #f6f33bff
            );
        }
        #gateway .stat-card strong {
            font-size: 2.6rem;
            font-weight: 700;
            color: #1f2937;
            display: block;
            margin-bottom: 6px;
        }
        #gateway .stat-card span {
            font-size: 0.95rem;
            color: #6b7280;
            letter-spacing: 0.3px;
        }

        #gateway .gu-content p {
            margin-bottom: 16px;
            color: #374151;
            line-height: 1.8;
            font-size: 1.02rem;
        }

        #gateway .gu-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 24px;
            margin-top: 32px;
            text-align: center;
        }

        #gateway .gu-stats .stat strong {
            font-size: 2rem;
            display: block;
        }

        #gateway .gu-stats .stat span {
            color: #6b7280;
        }

        /* ================= VISI & MISI ================= */

        #gateway #visi-misi {
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }

        #gateway .vm-container {
            max-width: 1100px;
            margin: 0 auto;
        }

        #gateway .vm-title {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 48px;
            letter-spacing: -0.4px;

        }

        #gateway .vm-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 32px;
        }

        /* CARD */
        #gateway .vm-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 28px;
            transition: all 0.25s ease;
        }

        /* HEADER */
        #gateway .vm-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        #gateway .vm-header h3 {
            font-size: 1.4rem;
        }

        /* TOGGLE */
        #gateway .vm-toggle {
            background: #f1f5f9;
            border: 1px solid #e5e7eb;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        #gateway .vm-icon {
            transition: transform 0.35s ease;
        }

        /* SUMMARY */
        #gateway .vm-summary {
            color: #374151;
            line-height: 1.7;
            font-size: 1.02rem;
        }

        /* CONTENT (PUSH DOWN) */
        #gateway .vm-content {
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transform: translateY(-6px);
            transition:
                max-height 0.45s ease,
                opacity 0.3s ease,
                transform 0.3s ease;
            margin-top: 0;
        }

        /* ACTIVE */
        #gateway .vm-card.active .vm-content {
            max-height: 500px;
            opacity: 1;
            transform: translateY(0);
            margin-top: 20px;
        }

        #gateway .vm-card.active .vm-icon {
            transform: rotate(180deg);
        }

        #gateway .vm-content ul {
            padding-left: 18px;
        }

        #gateway .vm-content li {
            margin-bottom: 10px;
            color: #374151;
        }

        /* ================= PRESTASI ================= */

        #gateway #prestasi {
            background: linear-gradient(
                180deg,
                #ffffff 0%,
                #f9fafb 100%
            );
        }

        #gateway .prestasi-container {
            max-width: 1100px;
            margin: 0 auto;
        }

        #gateway .prestasi-title {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 48px;
        }

        /* GRID */
        #gateway .prestasi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 28px;
        }

        /* CARD */
        #gateway .prestasi-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 28px;
            position: relative;
            transition: all 0.25s ease;
        }

        /* accent line */
        #gateway .prestasi-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            height: 4px;
            width: 100%;
            background: linear-gradient(90deg, #2563eb, #3b82f6);
        }

        
        /* image */
        #gateway .prestasi-image {
            aspect-ratio: 16 / 9;
            overflow: hidden;
        }

        #gateway .prestasi-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        /* hover image zoom (halus) */
        #gateway .prestasi-card:hover .prestasi-image img {
            transform: scale(1.05);
        }
        /* content */
        #gateway .prestasi-content {
            padding: 24px;
        }
        /* hover */
        #gateway .prestasi-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 28px rgba(0,0,0,0.06);
        }

        /* badge */
        #gateway .prestasi-badge {
            display: inline-block;
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1e40af;
            margin-bottom: 12px;
            font-weight: 500;
        }

        #gateway .prestasi-badge.non {
            background: #dcfce7;
            color: #166534;
        }

        /* text */
        #gateway .prestasi-card h3 {
            font-size: 1.1rem;
            margin-bottom: 6px;
            letter-spacing: -0.2px;
        }

        #gateway .prestasi-card p {
            color: #374151;
            margin-bottom: 12px;
            font-size: 0.95rem;
        }

        #gateway .prestasi-card small {
            color: #6b7280;
            font-size: 0.85rem;
        }
        /* ---------- RESPONSIVE FINISH ---------- */
        @media (max-width: 768px) {
            #gateway section {
                padding: 64px 5%;
            }

            #gateway .hero-content-left h1 {
                font-size: 1.9rem;
            }

            #gateway .vm-title,
            #gateway .prestasi-title {
                font-size: 1.7rem;
            }
        }

    </style>
</head>

<body>
<div id="gateway">

    {{-- NAVBAR --}}
     @include('gateway.navbar')

    {{-- HERO --}}
    <section id="hero-slider">
        <div class="hero-bg" id="heroBg">
            <div class="hero-content-left">
                <h1>SDN Mekar Jaya</h1>
                <p>Pusat layanan digital untuk warga sekolah</p>
            </div>

            <div class="hero-content-right">
                <a href="#gambaran-umum" class="btn-info">
                    Informasi Selengkapnya
                </a>
            </div>

            <div class="hero-progress" id="heroProgress"></div>
        </div>
    </section>

    <section id="gambaran-umum" class="collapsed" data-aos="fade-up" data-aos-delay="150">
        <div class="gu-container">

            <div class="gu-header">
                <h2>Gambaran Umum Sekolah</h2>
                <button id="toggleGu" class="gu-toggle" aria-label="Toggle Gambaran Umum">
                    <svg class="gu-icon" width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
            </div>

            <div class="gu-content">
                <p>
                    SDN Mekkar Jaya merupakan sekolah dasar negeri yang berkomitmen
                    memberikan pendidikan berkualitas, berkarakter, dan berwawasan
                    lingkungan bagi seluruh peserta didik.
                </p>

                <p>
                    Dengan dukungan tenaga pendidik profesional dan lingkungan
                    belajar yang kondusif, sekolah terus berupaya menciptakan
                    generasi yang cerdas, berakhlak, dan berprestasi.
                </p>
            </div>

            <!-- RINGKASAN (SELALU TERLIHAT) -->
            <div class="gu-stats">
                <div class="stat-card">
                    <strong class="count" data-target="40">0</strong>
                    <span>Guru</span>
                </div>

                <div class="stat-card">
                    <strong class="count" data-target="750">0</strong>
                    <span>Siswa</span>
                </div>
            </div>
        </div>
    </section>

    <section id="visi-misi" data-aos="fade-up" data-aos-delay="150">
        <div class="vm-container">

            <h2 class="vm-title">Visi & Misi Sekolah</h2>

            <div class="vm-grid">

                <!-- VISI -->
                <div class="vm-card">
                    <div class="vm-header">
                        <h3>Visi</h3>
                        <button class="vm-toggle" aria-label="Toggle Visi">
                            <svg class="vm-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                    </div>

                    <p class="vm-summary">
                        Terwujudnya peserta didik yang beriman, berakhlak mulia,
                        cerdas, dan berprestasi.
                    </p>

                    <div class="vm-content">
                        <ul>
                            <li>Mengembangkan potensi peserta didik secara optimal</li>
                            <li>Mewujudkan lingkungan belajar yang aman dan kondusif</li>
                            <li>Menanamkan nilai karakter dan kepedulian sosial</li>
                        </ul>
                    </div>
                </div>

                <!-- MISI -->
                <div class="vm-card">
                    <div class="vm-header">
                        <h3>Misi</h3>
                        <button class="vm-toggle" aria-label="Toggle Misi">
                            <svg class="vm-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                    </div>

                    <p class="vm-summary">
                        Mewujudkan proses pendidikan yang berkualitas dan berkarakter.
                    </p>

                    <div class="vm-content">
                        <ul>
                            <li>Meningkatkan profesionalisme tenaga pendidik</li>
                            <li>Mengembangkan kegiatan akademik dan non-akademik</li>
                            <li>Menjalin kerja sama dengan orang tua dan masyarakat</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="prestasi" data-aos="fade-up" data-aos-delay="150">
        <div class="prestasi-container">

            <h2 class="prestasi-title">Prestasi Sekolah</h2>

            <div class="prestasi-grid">

                <div class="prestasi-card">
                    <div class="prestasi-image">
                        <img src="/assets/images/gateway/prestasi1.jpg" alt="Prestasi Olimpiade">
                    </div>

                    <div class="prestasi-content">
                        <span class="prestasi-badge">Akademik</span>
                        <h3>Juara 1 Olimpiade Matematika</h3>
                        <p>Tingkat Kecamatan</p>
                        <small>2024</small>
                    </div>
                </div>

                <div class="prestasi-card">
                    <div class="prestasi-image">
                        <img src="/assets/images/Gateway/prestasi2.jpg" alt="Prestasi Seni">
                    </div>

                    <div class="prestasi-content">
                        <span class="prestasi-badge non">Non-Akademik</span>
                        <h3>Juara 2 Lomba Tari Daerah</h3>
                        <p>Tingkat Kabupaten</p>
                        <small>2023</small>
                    </div>
                </div>

                <div class="prestasi-card">
                    <div class="prestasi-image">
                        <img src="/assets/images/Gateway/prestasi3.jpg" alt="Prestasi IPA">
                    </div>

                    <div class="prestasi-content">
                        <span class="prestasi-badge">Akademik</span>
                        <h3>Juara Harapan IPA</h3>
                        <p>Tingkat Provinsi</p>
                        <small>2022</small>
                    </div>
                </div>

            </div>
        </div>
    </section>



    {{-- FOOTER --}}
    @include('gateway.footer')

</div>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 700,
    easing: 'ease-out-cubic',
    once: true
  });

  const html = document.documentElement;

const savedTheme = localStorage.getItem('theme');
if (savedTheme) html.className = savedTheme;

document.getElementById('toggle-theme')?.addEventListener('click', () => {
    const isDark = html.classList.contains('theme-dark');
    html.className = isDark ? 'theme-light' : 'theme-dark';
    localStorage.setItem('theme', html.className);
});

</script>

</body>
<script>
const slides = [
    "/assets/images/gateway/sekolah2.jfif",
    "/assets/images/gateway/sekolah3.jfif",
    "/assets/images/gateway/prestasi4.jfif"
];

let current = 0;
const heroBg = document.getElementById("heroBg");
const progress = document.getElementById("heroProgress");

function changeSlide() {
    heroBg.style.backgroundImage = `url('${slides[current]}')`;

    // reset progress animation
    progress.style.animation = "none";
    progress.offsetHeight;
    progress.style.animation = "heroProgress 3s linear infinite";

    current = (current + 1) % slides.length;
}

// init
changeSlide();

// auto slide tiap 3 detik
setInterval(changeSlide, 3000);
</script>

<script>
const guSection = document.getElementById("gambaran-umum");
const toggleBtn = document.getElementById("toggleGu");

// klik tombol di section
toggleBtn.addEventListener("click", () => {
    guSection.classList.toggle("active");
});

// klik dari hero
const heroInfoBtn = document.querySelector(".btn-info");
heroInfoBtn.addEventListener("click", (e) => {
    e.preventDefault();
    guSection.classList.add("active");
    guSection.scrollIntoView({ behavior: "smooth" });
});
</script>
<script>
const counters = document.querySelectorAll('.count');
let counterPlayed = false;

function animateCount(el, target) {
    let current = 0;
    const duration = 1200; // ms
    const stepTime = Math.max(Math.floor(duration / target), 10);

    const timer = setInterval(() => {
        current += 1;
        el.textContent = current;

        if (current >= target) {
            el.textContent = target;
            clearInterval(timer);
        }
    }, stepTime);
}

const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting && !counterPlayed) {
            counterPlayed = true;

            counters.forEach(counter => {
                const target = parseInt(counter.dataset.target, 10);
                animateCount(counter, target);
            });
        }
    });
}, { threshold: 0.5 });

observer.observe(document.querySelector('.gu-stats'));
</script>

<script>
document.querySelectorAll('.vm-toggle').forEach(btn => {
    btn.addEventListener('click', () => {
        const card = btn.closest('.vm-card');
        card.classList.toggle('active');
    });
});
</script>

</html>
