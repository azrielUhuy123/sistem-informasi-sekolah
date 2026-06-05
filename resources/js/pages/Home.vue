<template>
    <PublicLayout>

        <!-- HERO -->
        <section class="hero">

            <div class="hero-overlay"></div>

            <div class="hero-content">

                <span class="hero-badge">
                    SDN Mekar Jaya
                </span>

                <h1>
                    Sekolah Dasar Unggul,<br>
                    Berkarakter dan Berprestasi
                </h1>

                <p>
                    Mewujudkan generasi yang cerdas, berakhlak,
                    berprestasi, dan siap menghadapi era digital.
                </p>

                <div class="hero-actions">

                    <router-link
                        to="/ppdb"
                        class="btn-primary"
                    >
                        PPDB Online
                    </router-link>

                    <router-link
                        to="/informasi"
                        class="btn-secondary"
                    >
                        Profil Sekolah
                    </router-link>

                </div>

            </div>

        </section>

        <!-- STATISTIK -->
        <section class="stats">

            <div class="stat-card">
                <h2>{{ statistik?.siswa ?? 0 }}</h2>
                <p>Siswa Aktif</p>
            </div>

            <div class="stat-card">
                <h2>{{ statistik?.guru ?? 0 }}</h2>
                <p>Guru & Staff</p>
            </div>

            <div class="stat-card">
                <h2>{{ statistik?.kelas ?? 0 }}</h2>
                <p>Kelas</p>
            </div>

            <div class="stat-card">
                <h2>15+</h2>
                <p>Prestasi</p>
            </div>

        </section>

        <!-- SAMBUTAN -->
        <section class="section">

            <div class="section-header">
                <h2>Sambutan Kepala Sekolah</h2>
            </div>

            <div class="welcome-card">

                <div class="welcome-image">
                    👨‍🏫
                </div>

                <div>

                    <p>
                        Selamat datang di Sistem Informasi Akademik
                        SDN Mekar Jaya.
                    </p>

                    <p>
                        Kami berkomitmen menghadirkan pendidikan
                        berkualitas yang membentuk karakter,
                        kreativitas, dan prestasi peserta didik.
                    </p>

                </div>

            </div>

        </section>

        <!-- PROGRAM -->
        <section class="section">

            <div class="section-header">
                <h2>Program Unggulan</h2>
            </div>

            <div class="program-grid">

                <div class="program-card">
                    <h3>Literasi Digital</h3>
                    <p>
                        Pembelajaran berbasis teknologi informasi.
                    </p>
                </div>

                <div class="program-card">
                    <h3>Pendidikan Karakter</h3>
                    <p>
                        Membentuk siswa yang disiplin dan berintegritas.
                    </p>
                </div>

                <div class="program-card">
                    <h3>Ekstrakurikuler</h3>
                    <p>
                        Pengembangan minat dan bakat siswa.
                    </p>
                </div>

                <div class="program-card">
                    <h3>Prestasi Akademik</h3>
                    <p>
                        Pembinaan siswa berprestasi.
                    </p>
                </div>

            </div>

        </section>

        <!-- BERITA -->
        <section class="section">

            <div class="section-header">
                <h2>Informasi Terbaru</h2>
            </div>

            <div class="news-grid">

                <div
                    v-for="item in berita"
                    :key="item.id"
                    class="news-card"
                >

                    <h4>
                        {{ item.judul }}
                    </h4>

                    <p>
                        {{ item.ringkasan }}
                    </p>

                </div>

            </div>

        </section>


    </PublicLayout>
</template>

<script setup>
import PublicLayout from '../layouts/PublicLayout.vue'
import { ref, onMounted } from 'vue'

const statistik = ref(null)

onMounted(async () => {

    const response = await fetch('/api/gateway/statistik')

    statistik.value = await response.json()

})

const berita = ref([])

onMounted(async () => {

    const response =
        await fetch('/api/gateway/berita')

    berita.value =
        await response.json()
})

</script>

<style scoped>

.hero{
    height:85vh;
    position:relative;

    display:flex;
    align-items:center;
    justify-content:center;

    background-image:
        linear-gradient(
            rgba(15,23,42,.65),
            rgba(15,23,42,.65)
        ),
        url('https://images.unsplash.com/photo-1509062522246-3755977927d7');

    background-size:cover;
    background-position:center;
}

.hero-content{
    text-align:center;
    color:white;
    max-width:800px;
    padding:20px;
}

.hero-badge{
    display:inline-block;
    padding:8px 16px;
    border-radius:999px;
    background:rgba(255,255,255,.15);
    margin-bottom:20px;
}

.hero h1{
    font-size:4rem;
    margin-bottom:20px;
}

.hero p{
    font-size:1.1rem;
    opacity:.9;
}

.hero-actions{
    margin-top:30px;
    display:flex;
    gap:15px;
    justify-content:center;
}

.btn-primary{
    background:#2563eb;
    color:white;
    text-decoration:none;
    padding:14px 24px;
    border-radius:12px;
}

.btn-secondary{
    background:white;
    color:#111827;
    text-decoration:none;
    padding:14px 24px;
    border-radius:12px;
}

.stats{
    max-width:1200px;
    margin:-70px auto 80px;
    padding:0 20px;

    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;

    position:relative;
    z-index:10;
}

.stat-card{
    background:white;
    border-radius:18px;
    padding:30px;
    text-align:center;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.stat-card h2{
    color:#2563eb;
}

.section{
    max-width:1200px;
    margin:80px auto;
    padding:0 20px;
}

.section-header{
    text-align:center;
    margin-bottom:40px;
}

.welcome-card{
    display:grid;
    grid-template-columns:220px 1fr;
    gap:30px;
    align-items:center;
}

.welcome-image{
    font-size:120px;
    text-align:center;
}

.program-grid,
.news-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
}

.program-card,
.news-card{
    background:white;
    padding:24px;
    border-radius:16px;
    box-shadow:0 8px 20px rgba(0,0,0,.08);
}

@media(max-width:992px){

    .hero h1{
        font-size:2.5rem;
    }

    .stats,
    .program-grid,
    .news-grid{
        grid-template-columns:1fr;
    }

    .welcome-card{
        grid-template-columns:1fr;
    }
}
</style>

