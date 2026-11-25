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
        'must_change_password',
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
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
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
        if ($this->role === UserRole::GURU) {
            foreach ([
                'wali.dashboard' => '/guru/dashboard-walikelas',
                'operator.dashboard' => '/tu/dashboard',
                'ppdb.dashboard' => '/tu/ppdb/dashboard',
                'ekskul.dashboard' => '/guru/ekskul/dashboard',
                'guru.dashboard' => '/guru/dashboard-gurubidang',
                'operator.employees' => '/tu/pegawai/perubahan',
                'operator.accounts' => '/tu/akun',
            ] as $menu => $url) {
                if ($this->hasFeature($menu)) {
                    return $url;
                }
            }

            if ($this->pegawai_id && $this->guru()->exists()) {
                return '/guru/pegawai/perubahan';
            }

            return '/';
        }

        return match ($this->role) {
            UserRole::SUPERADMIN => '/superadmin/dashboard',
            UserRole::ADMIN => '/admin/dashboard',
            UserRole::TU => '/',
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

        if ($this->role === UserRole::GURU) {
            if (! $this->pegawai_id || ! $this->guru()->exists()) {
                return false;
            }

            $knownAssignmentMenus = collect(config('assignment_menus.types', []))
                ->flatMap(fn ($type) => array_keys($type['menus'] ?? []));
            if (! $knownAssignmentMenus->contains($featureKey)) {
                return false;
            }

            return PenugasanMenu::query()
                ->where('menu_key', $featureKey)
                ->whereHas('item.penugasan', function ($query) {
                    $query->where('status', 'active')
                        ->whereNotNull('published_at')
                        ->where('data_kepegawaian_id', $this->pegawai_id);
                })
                ->exists();
        }

        if ($this->role === UserRole::TU) {
            return false;
        }

        $features = config('feature_access.features', []);
        $feature = $features[$featureKey] ?? null;
        if (! is_array($feature)) {
            return false;
        }

        $override = $this->featurePermissions()
            ->where('feature_key', $featureKey)
            ->first();

        if ($override) {
            return (bool) $override->allowed;
        }

        return in_array($this->role, $feature['roles'] ?? [], true);
    }

    public function hasAnyAssignmentMenu(array $menuKeys): bool
    {
        foreach ($menuKeys as $menuKey) {
            if ($this->hasFeature($menuKey)) {
                return true;
            }
        }

        return false;
    }

    public function getAssignmentModes(): array
    {
        if ($this->role !== UserRole::GURU) {
            return [];
        }

        $modes = [];
        foreach (config('assignment_menus.modes', []) as $mode => $definition) {
            $menuKeys = array_keys(config("assignment_menus.types.{$definition['type']}.menus", []));
            if (! $this->hasAnyAssignmentMenu($menuKeys)) {
                continue;
            }

            $dashboardRoute = $definition['dashboard'];
            $url = $this->hasFeature($this->dashboardMenuForMode($definition['type']))
                ? route($dashboardRoute)
                : null;

            if (! $url) {
                foreach ($definition['routes'] as $menuKey => $routeName) {
                    if ($this->hasFeature($menuKey) && \Illuminate\Support\Facades\Route::has($routeName)) {
                        $url = route($routeName);
                        break;
                    }
                }
            }

            if ($url) {
                $modes[$mode] = [
                    'label' => $definition['label'],
                    'url' => $url,
                ];
            }
        }

        return $modes;
    }

    private function dashboardMenuForMode(string $type): string
    {
        return match ($type) {
            'operator' => 'operator.dashboard',
            'ppdb' => 'ppdb.dashboard',
            'wali' => 'wali.dashboard',
            'guru' => 'guru.dashboard',
            'ekskul' => 'ekskul.dashboard',
            default => '',
        };
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