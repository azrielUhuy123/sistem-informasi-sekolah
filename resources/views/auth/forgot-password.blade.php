<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Siswa yang lupa password harus menghubungi TU/operator. Pengguna non-siswa dapat meminta tautan reset melalui email.
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                Kembali ke login
            </a>

            <x-primary-button>
                Kirim tautan
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>