@props([
    'colspan' => 1,
    'state' => 'empty',
    'title' => null,
    'description' => null,
])

@php
    [$defaultTitle, $defaultDescription, $icon] = match ($state) {
        'empty' => [
            'Belum ada data',
            'Data belum tersedia.',
            'bi-folder2-open',
        ],
        'no-results' => [
            'Tidak ada hasil yang cocok',
            'Ubah kata kunci atau filter, lalu coba lagi.',
            'bi-search',
        ],
    };

    $descriptionText = $description ?? $defaultDescription;
@endphp

<tr class="dt-empty-row">
    <td colspan="{{ max(1, (int) $colspan) }}" class="text-center">
        <div class="dt-empty-state" role="status" aria-live="polite">
            <i class="bi {{ $icon }} dt-empty-state-icon" aria-hidden="true"></i>
            <p class="dt-empty-state-title mb-1">{{ $title ?? $defaultTitle }}</p>

            @if ($descriptionText !== '')
                <p class="dt-empty-state-description mb-0">{{ $descriptionText }}</p>
            @endif

            @if ($slot->isNotEmpty())
                <div class="dt-empty-state-actions">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </td>
</tr>