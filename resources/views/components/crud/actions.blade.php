@php $isDeleted = !empty($row->deleted_at); @endphp

{{-- DETAIL --}}
@if(isset($actions['detail']))
    <a class="icon-btn {{ $isDeleted ? 'disabled' : '' }}"
       @if(!$isDeleted) href="{{ route($actions['detail'], $row->id) }}" @endif>
        <svg fill="none" viewBox="0 0 24 24">
            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>
    </a>
@endif

{{-- EDIT --}}
@if(isset($actions['edit']))
    <a class="icon-btn {{ $isDeleted ? 'disabled' : '' }}"
       @if(!$isDeleted) href="{{ route($actions['edit'], $row->id) }}" @endif>
        <svg fill="none" viewBox="0 0 24 24">
            <path d="M12 20h9"/>
            <path d="M16.5 3.5a2 2 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
        </svg>
    </a>
@endif

{{-- DELETE --}}
@if(isset($actions['delete']))
    @if($isDeleted)
        <span class="icon-btn disabled">
            <svg fill="none" viewBox="0 0 24 24">
                <path d="M3 6h18"/>
                <path d="M8 6V4h8v2"/>
                <path d="M6 6v14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V6"/>
            </svg>
        </span>
    @else
        <form class="d-inline" method="POST" action="{{ route($actions['delete'], $row->id) }}">
            @csrf @method('DELETE')
            <button class="icon-btn" onclick="return confirm('Yakin hapus?')">
                <svg fill="none" viewBox="0 0 24 24">
                    <path d="M3 6h18"/>
                    <path d="M8 6V4h8v2"/>
                    <path d="M6 6v14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V6"/>
                </svg>
            </button>
        </form>
    @endif
@endif
{{-- RESTORE --}}
@if(isset($actions['restore']) && $isDeleted)
    <form class="d-inline" method="POST" action="{{ route($actions['restore'], $row->id) }}">
        @csrf
        <button class="icon-btn" title="Restore">
            <svg fill="none" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4" />
            </svg>
        </button>
    </form>
@endif

{{-- FORCE DELETE --}}
@if(isset($actions['forceDelete']) && $isDeleted)
    <form class="d-inline" 
          method="POST"
          action="{{ route($actions['forceDelete'], $row->id) }}"
          onsubmit="return confirm('Hapus permanen? Tidak bisa dikembalikan!')">
        @csrf
        @method('DELETE')
        <button class="icon-btn" title="Hapus Permanen">
            <svg fill="none" viewBox="0 0 24 24">
                <path d="M3 6h18"/>
                <path d="M6 6v14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V6"/>
            </svg>
        </button>
    </form>
@endif

