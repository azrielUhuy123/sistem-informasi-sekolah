<template>

<header class="navbar-wrapper">

    <nav class="navbar-container">

        <!-- Logo -->
        <div class="brand">

            <img :src="'/assets/images/logo-kolaan.png'" alt="Logo Sekolah"
                class="brand-logo"
            >

            <div class="brand-text">

                <h1>SDN Mekar Jaya</h1>
                
            </div>

        </div>

        <!-- Action -->
        <div>

            <a
                href="/login"
                class="login-btn"
            >
                Masuk
            </a>

        </div>

    </nav>

</header>

</template>

<style scoped>

/* =========================
   Navbar
========================= */

.navbar-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 24px;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
    position: sticky;
    top: 0;
    z-index: 100;
}

/* =========================
   Brand
========================= */

.brand {
    display: flex;
    align-items: center;
    gap: 10px;
}

.brand-logo {
    width: 42px;
    height: 42px;
    object-fit: contain;
    display: block;
    flex-shrink: 0;
}

.brand-text {
    display: flex;
    align-items: center;
}

.brand-text h1 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    line-height: 1.2;
    color: #1f2937;
    white-space: nowrap;
}

/* =========================
   Login Button
========================= */

.login-btn {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    padding: 10px 18px;
    border-radius: 8px;
    background: #2563eb;
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: .3s;
}

.login-btn:hover {
    background: #1d4ed8;
}

/* =========================
   Tablet
========================= */

@media (max-width: 992px) {

    .navbar-container {
        padding: 12px 18px;
    }

    .brand-logo {
        width: 38px;
        height: 38px;
    }

    .brand-text h1 {
        font-size: 16px;
    }

    .login-btn {
        padding: 8px 16px;
        font-size: 13px;
    }
}

/* =========================
   Mobile
========================= */

@media (max-width: 480px) {

    .navbar-container {
        padding: 10px 14px;
    }

    .brand {
        gap: 8px;
    }

    .brand-logo {
        width: 34px;
        height: 34px;
    }

    .brand-text h1 {
        font-size: 14px;
        line-height: 1.2;
        margin: 0;
    }

    .login-btn {
        padding: 7px 14px;
        font-size: 12px;
    }

}
</style>