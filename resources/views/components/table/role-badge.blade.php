@props([
    'role',
])

@php
    $config = match (strtolower((string) $role)) {
        'superadmin' => [
            'label' => 'Super Admin',
            'class' => 'role-badge-superadmin',
            'icon'  => 'bi-gem',
        ],

        'tu' => [
            'label' => 'Operator',
            'class' => 'role-badge-tu', 
            'icon'  => 'bi-person-fill-gear',           
        ],

        'guru' => [
            'label' => 'Guru',
            'class' => 'role-badge-guru',   
            'icon'  => 'bi-person-fill',         
        ],

        'siswa' => [
            'label' => 'Siswa',
            'class' => 'role-badge-siswa',
            'icon'  => 'bi-person-fill',
        ],

        default => [
            'label' => ucfirst(str_replace('_', ' ', (string) $role)),
            'class' => 'role-badge-default',
            'icon'  => 'bi-person-fill',
        ],
    };
@endphp

<span {{ $attributes->merge([
    'class' => 'role-badge ' . $config['class']
]) }}>
    <i class="bi {{ $config['icon'] }}"></i>
    <span>{{ $config['label'] }}</span>
</span>