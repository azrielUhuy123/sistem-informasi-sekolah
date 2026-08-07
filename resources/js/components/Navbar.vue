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

@media (max-width: 992px) {

    .navbar-container{
        padding:12px 16px;
    }

    .nav-links{
        display:none;
        margin-top:auto;
    }

    .brand{
        gap:10px;
        flex:1;
        min-width:0;
    }

    .brand-logo{
        width:42px;
        height:42px;
    }

    .brand-text h1{
        margin-top:10px;
        font-size:14px;
        line-height:1.2;
    }

    .login-btn{
        padding:10px 14px;
        font-size:13px;
        white-space:nowrap;
        border-radius:10px;
    }

}

@media (max-width: 480px){

    .navbar-container{
        padding:10px 12px;
    }

    .brand-logo{
        width:38px;
        height:38px;
    }

    .brand-text h1{
        font-size:12px;
    }

    .login-btn{
        padding:8px 10px;
        font-size:12px;
    }

}
</style>