<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\DataKepegawaian;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credential = trim($this->input('username'));

        $user = null;

        /*
        |--------------------------------------------------------------------------
        | LOGIN PEGAWAI (NIP)
        |--------------------------------------------------------------------------
        */

        $pegawai = DataKepegawaian::with('user')
            ->where('nip', $credential)
            ->first();

        if ($pegawai && $pegawai->user) {

            $user = $pegawai->user;

        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN SISWA (NIS)
        |--------------------------------------------------------------------------
        */

        if (!$user) {

            $siswa = Siswa::with('user')
                ->where('nis', $credential)
                ->first();

            if ($siswa && $siswa->user) {

                $user = $siswa->user;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | USER TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */

        if (
            !$user ||
            !Hash::check(
                $this->password,
                $user->password
            )
        ) {

            RateLimiter::hit(
                $this->throttleKey()
            );

            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | STATUS APPROVAL
        |--------------------------------------------------------------------------
        */

        if ($user->approval_status === 'pending') {

            throw ValidationException::withMessages([
                'username' => 'Akun masih menunggu persetujuan Kepala Sekolah.',
            ]);

        }

        if ($user->approval_status === 'rejected') {

            throw ValidationException::withMessages([
                'username' => 'Akun Anda ditolak oleh Kepala Sekolah.',
            ]);

        }

        if ($user->approval_status === 'draft') {

            throw ValidationException::withMessages([
                'username' => 'Akun belum dikirim untuk persetujuan.',
            ]);

        }

        if (!$user->is_active) {

            throw ValidationException::withMessages([
                'username' => 'Akun sedang dinonaktifkan.',
            ]);

        }

        Auth::login(
            $user,
            $this->boolean('remember')
        );

        RateLimiter::clear(
            $this->throttleKey()
        );
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower(
                $this->string('username')
            ) . '|' . $this->ip()
        );  
    }
}
