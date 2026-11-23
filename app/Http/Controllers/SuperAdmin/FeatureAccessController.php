<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserFeaturePermission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class FeatureAccessController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('pegawai')
            ->where('role', 'siswa')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $features = $this->studentFeatures();

        return view('superadmin.feature-access.index', compact('users', 'features'));
    }

    public function edit(User $user)
    {
        abort_unless($user->role === 'siswa', 403);

        $features = $this->studentFeatures();
        $permissions = UserFeaturePermission::where('user_id', $user->id)
            ->get()
            ->keyBy('feature_key');

        $effective = collect($features)->mapWithKeys(function ($feature, $key) use ($permissions, $user) {
            $override = $permissions->get($key);

            return [$key => $override
                ? (bool) $override->allowed
                : in_array($user->role, $feature['roles'] ?? [], true)];
        });

        return view(
            'superadmin.feature-access.edit',
            compact('user', 'features', 'permissions', 'effective')
        );
    }

    public function update(Request $request, User $user)
    {
        abort_unless($user->role === 'siswa', 403);

        $featureKeys = array_keys($this->studentFeatures());

        $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['nullable', Rule::in(['1'])],
        ]);

        $selected = $request->input('permissions', []);

        DB::transaction(function () use ($user, $featureKeys, $selected) {
            $features = $this->studentFeatures();

            UserFeaturePermission::where('user_id', $user->id)
                ->whereIn('feature_key', $featureKeys)
                ->delete();

            foreach ($featureKeys as $featureKey) {
                $roleAllowed = in_array(
                    $user->role,
                    $features[$featureKey]['roles'] ?? [],
                    true
                );

                $allowed = array_key_exists($featureKey, $selected);

                if ($allowed !== $roleAllowed) {
                    UserFeaturePermission::create([
                        'user_id' => $user->id,
                        'feature_key' => $featureKey,
                        'allowed' => $allowed,
                    ]);
                }
            }
        });

        return redirect()
            ->route('superadmin.feature-access.edit', $user)
            ->with('success', 'Akses fitur berhasil diperbarui.');
    }

    private function studentFeatures(): array
    {
        return array_filter(
            config('feature_access.features', []),
            fn ($feature, $key) => str_starts_with($key, 'siswa.'),
            ARRAY_FILTER_USE_BOTH
        );
    }
}
