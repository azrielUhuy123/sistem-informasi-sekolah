<x-guest-layout>


<style>
    * {
        font-family: 'Poppins', sans-serif;
    }
    .login-box {
        background: #ffffff;
        width: 100%;
        height: 100% ;
        max-width: 1000px;
        min-height: 520px;
        max-height: 560px ;
        display: flex;
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 25px 55px rgba(0,0,0,.25);
    }

    .login-left {
        width: 45%;
        padding: 55px 50px;
    }

    .login-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }
    
    .login-logo {
        width: 60px;
        margin-bottom: 30px;
    }

    .login-school {
        color: #0881c7;
        font-size: 14px;
        line-height: 1.2;
    }

    .login-left h2 {
        font-size: 26px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .login-left p {
        font-size: 14px;
        color: #777;
        margin-bottom: 30px;
    }

    .login-input {
        width: 100%;
        padding: 14px 16px;
        border-radius: 12px;
        border: 1px solid #ddd;
        margin-bottom: 14px;
        outline: none;
        font-size: 14px ;
    }

    .login-extra {
        text-align: right;
        margin-bottom: 20px;
    }

    .login-extra a {
        font-size: 13px;
        color: #888;
        text-decoration: none;
    }

    .password-wrapper {
    position: relative;
    width: 100%;
}

.password-wrapper .login-input {
    padding-right: 46px; /* ruang icon mata */
}

.toggle-password {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-70%);
    cursor: pointer;
    font-size: 16px;
    color: #666;
    line-height: 1;
}

.toggle-password:hover {
    color: #17416e;
}


    .btn-login {
        width: 100%;
        padding: 14px;
        border-radius: 14px;
        background: #17416eff;
        color:#fff;
        border: none;
        font-weight: 600;
        cursor: pointer;
    }

    .login-right {
        width: 80%;
        position: relative;
        overflow:hidden;
        
    }

    .wave-bg {
        position: absolute;
        inset: -5% -10% -5% -25%;
        z-index: 1;
    }

    .illustration-stage {
        position: absolute;
        inset: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 2;
    }
    
    .blob {
        position: absolute;
        
    }
    .blob-white {
        width: 70%;
        transform: translate(20%, -2%);
    }
    .blob-soft {
        width: 95%;
        transform: translate(15%, 5%);
    }


    .illustration {
        position: relative;
        width: 40%;
        z-index: 3;
        transform: translate(40%, 0);
    }
    

    @media (max-width: 768px) {
        .login-right {
            display: none;
        }
        .login-left {
            width: 100%;
            padding: 40px;
        }
    }

    .input-error {
    font-size: 12.5px;
    color: #d14343;
    margin-top: -8px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.input-error::before {
    content: "⚠";
    font-size: 13px;
}

</style>

<div class="login-box">

    {{-- LEFT --}}
    <div class="login-left">
        <div class="login-header">
            <img src="{{ asset('assets/images/logo-kolaan.png') }}" class="login-logo">
            <div class="login-school">
                Sekolah Dasar Negeri<br>Mekar Jaya
            </div>
        </div>
        <h2>Selamat Datang!</h2>
        <p>Silakan login untuk melanjutkan</p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf
 
            {{-- WARNING LOGIN GAGAL --}}
            @if ($errors->has('username'))
                <div class="input-error" style="margin-bottom:16px">
                    Username atau kata sandi salah
                </div>
            @endif
            <!-- Username -->
            
            <input
                type="text"
                name="username"
                class="login-input"
                placeholder="Masukkan NIS/NIP/Username"
                value="{{ old('username') }}"
                required
                autofocus
            >
           
            <!-- Password -->
            <div class="password-wrapper">
                <input
                    type="password"
                    name="password"
                    id="passwordInput"
                    class="login-input"
                    placeholder="Masukkan Kata Sandi"
                    required
                >
                <span class="toggle-password" onclick="togglePassword()">
                    👁
                </span>
            </div>

            <!-- Forgot -->
            <div class="login-extra">
                <a href="javascript:void(0)" onclick="openHelpModal()">
                    Butuh Bantuan?
                </a>
            </div>

            <!--button login-->
            <button type="submit" class="btn-login">
                Login
            </button>
        </form>
    </div>

    {{-- RIGHT --}}
    <div class="login-right">
        <svg viewBox="0 0 1000 900"
            preserveAspectRatio="none"
            class="wave-bg"
            style="position:absolute; inset:0; z-index:1">

            <defs>
                <linearGradient id="waveGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#17416e"/>
                    <stop offset="100%" stop-color="#0f2f52"/>
                </linearGradient>
            </defs>

            <path d="
                M220 0
                C120 120 140 260 200 380
                C260 500 260 650 340 780
                C420 900 520 900 520 900
                L1000 900
                L1000 0
                Z
            " fill="url(#waveGrad)"/>
        </svg>

        <div class="illustration-stage">
            <img src="{{ asset('assets/waves/blob-1.png') }}" class="blob blob-white" alt="">
            <img src="{{ asset('assets/waves/blob-2.png') }}" class="blob blob-soft" alt="">
            <img src="{{ asset('assets/images/bocil-dan-ortu.png') }}" class="illustration" alt="Illustration">
        </div>
    </div>

</div>
@include('auth.help-modal')


</x-guest-layout>

<!--icon mata-->
<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    if (!input) return;

    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}
</script>

