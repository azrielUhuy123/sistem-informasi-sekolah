@props([
    'href',
    'label' => 'Tambah',
])

{{-- Desktop / Tablet --}}
<a href="{{ $href }}"
   class="btn btn-primary rounded-3 add-btn-desktop">
    <i class="bi bi-plus-lg me-1"></i>
    <span>{{ $label }}</span>
</a>

{{-- Mobile --}}
<a href="{{ $href }}"
   class="add-fab-mobile"
   aria-label="{{ $label }}"
   title="{{ $label }}">
    <i class="bi bi-plus-lg"></i>
</a>