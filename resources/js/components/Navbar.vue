<template>

<header class="navbar-wrapper">

    <nav class="navbar-container">

        <!-- Logo -->
        <div class="brand">

            <img
                src="http://127.0.0.1:8000/assets/images/logo-kolaan.png"
                alt="Logo Sekolah"
                class="brand-logo"
            >

            <div class="brand-text">

                <h1>SDN Mekar Jaya</h1>

            </div>

        </div>

        <!-- Menu -->
        <div class="nav-links">

            <router-link to="/">
                Beranda
            </router-link>

            <router-link to="/informasi">
                Informasi
            </router-link>

            <router-link to="/prestasi">
                Prestasi
            </router-link>

            <router-link to="/ppdb">
                PPDB
            </router-link>

            <router-link to="/kontak">
                Kontak
            </router-link>

        </div>

        <!-- Action -->
        <div>

            <a
                href="/login"
                class="login-btn"
            >
                Masuk Sistem
            </a>

        </div>

    </nav>

</header>

</template>

<style scoped>

.navbar-wrapper{
    position: sticky;
    top:0;
    z-index:1000;

    background: rgba(255,255,255,.9);
    backdrop-filter: blur(12px);

    border-bottom:1px solid #e5e7eb;
}

.navbar-container{
    max-width:1400px;
    margin:auto;

    padding:16px 32px;

    display:flex;
    align-items:center;
    justify-content:space-between;
}

.brand{
    display:flex;
    align-items:center;
    gap:14px;
}

.brand-logo{
    width:52px;
    height:52px;
    object-fit:contain;
}

.brand-text h1{
    margin-top:10px;
    font-size:1.2rem;
    font-weight:700;
    color:#6b7280;
}


.nav-links{
    display:flex;
    gap:30px;
}

.nav-links a{
    text-decoration:none;
    color:#374151;
    font-weight:500;
    transition:.2s;
}

.nav-links a:hover{
    color:#2563eb;
}

.router-link-active{
    color:#2563eb !important;
    font-weight:700;
}

.login-btn{
    text-decoration:none;

    background:#2563eb;
    color:white;

    padding:10px 18px;
    border-radius:12px;

    font-weight:600;

    transition:.2s;
}

.login-btn:hover{
    background:#1d4ed8;
}

@media(max-width:992px){

    .nav-links{
        display:none;
    }

}

</style>