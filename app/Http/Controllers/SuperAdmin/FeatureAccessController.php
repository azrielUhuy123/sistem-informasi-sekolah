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
            ->where('role', '!=', 'superadmin')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $features = config('feature_access.features', []);

        return view('superadmin.feature-access.index', compact('users', 'features'));
    }

    public function edit(User $user)
    {
        if ($user->role === 'superadmin') {
            abort(403);
        }

        $features = config('feature_access.features', []);
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
        if ($user->role === 'superadmin') {
            abort(403);
        }

        $featureKeys = array_keys(config('feature_access.features', []));

        $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['nullable', Rule::in(['1'])],
        ]);

        $selected = $request->input('permissions', []);

        DB::transaction(function () use ($user, $featureKeys, $selected) {
            $features = config('feature_access.features', []);

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
}
