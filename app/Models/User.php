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
        'name',
        'email',
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

    public function featurePermissions()
    {
        return $this->hasMany(UserFeaturePermission::class);
    }

    public function hasFeature(string $featureKey): bool
    {
        if ($this->role === UserRole::SUPERADMIN) {
            return true;
        }

        $activeAssignmentKeys = collect();
        if ($this->pegawai_id) {
            $activeAssignmentKeys = PenugasanMenu::query()
                ->whereHas('item.penugasan', function ($query) {
                    $query->where('status', 'active')
                        ->where('data_kepegawaian_id', $this->pegawai_id);
                })
                ->pluck('menu_key');
        }

        if ($activeAssignmentKeys->contains($featureKey)) {
            return true;
        }

        $assignmentAliases = [
            'tu.academic' => ['guru.academic', 'tu.academic'],
            'tu.documents' => ['tu.documents'],
            'tu.ppdb' => ['tu.ppdb'],
            'guru.academic' => ['guru.academic'],
            'guru.extracurricular' => ['guru.extracurricular'],
        ];

        foreach ($assignmentAliases[$featureKey] ?? [] as $menuKey) {
            if ($activeAssignmentKeys->contains($menuKey)) {
                return true;
            }
        }

        if ($featureKey === 'guru.academic' && $this->role === UserRole::GURU) {
            $guru = $this->guru()->first();
            if ($guru) {
                $hasLegacyAcademic = WaliKelas::where('guru_id', $guru->id)
                    ->where('tahun_ajaran_id', current_tahun_id())
                    ->exists()
                    || PengampuMapel::where('guru_id', $guru->id)
                        ->where('tahun_ajaran_id', current_tahun_id())
                        ->exists();
                if (! $hasLegacyAcademic) {
                    return false;
                }
            } else {
                return false;
            }
        }

        if ($featureKey === 'guru.extracurricular' && $this->role === UserRole::GURU) {
            $guru = $this->guru()->first();

            if (! $guru) {
                return false;
            }

            $isPembina = \App\Models\PembinaEkskul::where('guru_id', $guru->id)
                ->where('tahun_ajaran_id', current_tahun_id())
                ->exists();

            if (! $isPembina) {
                return false;
            }
        }

        $override = $this->featurePermissions()
            ->where('feature_key', $featureKey)
            ->first();

        if ($override) {
            return (bool) $override->allowed;
        }

        $features = config('feature_access.features', []);
        $feature = $features[$featureKey] ?? null;

        return is_array($feature)
            ? in_array($this->role, $feature['roles'] ?? [], true)
            : false;
    }

    public function getNameAttribute()
    {
        if (array_key_exists('name', $this->attributes)) {
            return $this->attributes['name'];
        }

        return $this->pegawai?->nama
            ?? $this->siswa?->nama;
    }

    public function getEmailAttribute()
    {
        if (array_key_exists('email', $this->attributes)) {
            return $this->attributes['email'];
        }

        return $this->pegawai?->email
            ?? $this->siswa?->email;
    }

    public function getTeleponAttribute()
    {
        if (array_key_exists('telepon', $this->attributes)) {
            return $this->attributes['telepon'];
        }

        return $this->pegawai?->telepon
            ?? $this->siswa?->telepon;
    }

    public function getAlamatAttribute()
    {
        if (array_key_exists('alamat', $this->attributes)) {
            return $this->attributes['alamat'];
        }

        return $this->pegawai?->alamat
            ?? $this->siswa?->alamat;
    }
}