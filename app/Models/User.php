<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [

        'pegawai_id',
        'siswa_id',

        'username',
        'password',

        'role',

        'approval_status',
        'approved_at',
        'approved_by',

        'is_active',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at'       => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Cek apakah user punya role tertentu.
     * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }
        return $this->role === $roles;
    }

    /**
     * Alternatif: isRole
     */
    public function isRole(string $role)
    {
        return $this->role === $role;
    }
    public function getDashboardUrl()
    {
        return match ($this->role) {
            UserRole::SUPERADMIN => '/superadmin/dashboard',
            UserRole::ADMIN      => '/admin/dashboard',
            UserRole::TU         => '/tu/dashboard',
            UserRole::GURU       => '/guru/dashboard',
            UserRole::MURID      => '/siswa/dashboard',
            default              => '/dashboard',
        };
    }
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    public function guru()
    {
        return $this->hasOne(\App\Models\Guru::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(
            DataKepegawaian::class,
            'pegawai_id'
        );
    }

    public function siswa()
    {
        return $this->belongsTo(
            Siswa::class,
            'siswa_id'
        );
    }  
    public function getNameAttribute()
    {
        return $this->pegawai?->nama
            ?? $this->siswa?->nama;
    }

    public function getEmailAttribute()
    {
        return $this->pegawai?->email
            ?? $this->siswa?->email;
    }

    public function getTeleponAttribute()
    {
        return $this->pegawai?->telepon
            ?? $this->siswa?->telepon;
    }

    public function getAlamatAttribute()
    {
        return $this->pegawai?->alamat
            ?? $this->siswa?->alamat;
    }
}
