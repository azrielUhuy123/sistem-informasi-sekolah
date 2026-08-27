<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }

        return $this->role === $roles;
    }

    public function isRole(string $role)
    {
        return $this->role === $role;
    }

    public function getDashboardUrl()
    {
        return match ($this->role) {
            UserRole::SUPERADMIN => '/superadmin/dashboard',
            UserRole::ADMIN => '/admin/dashboard',
            UserRole::TU => '/tu/dashboard',
            UserRole::GURU => '/guru/dashboard',
            UserRole::MURID => '/siswa/dashboard',
            default => '/dashboard',
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
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    public function guru()
    {
        return $this->hasOne(
            Guru::class,
            'data_kepegawaian_id',
            'pegawai_id'
        );
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