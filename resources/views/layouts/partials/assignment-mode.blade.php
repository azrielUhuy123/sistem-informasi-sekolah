@php
    $modes = $user->getAssignmentModes();
    $mode = session('assignment_mode');
    if (! isset($modes[$mode])) {
        $mode = array_key_first($modes);
    }
@endphp

@if($mode)
    <div class="px-3 mb-3">
        <div class="sidebar-title">Penugasan Aktif</div>
        @if(count($modes) > 1)
            <div class="dropdown">
                <button class="btn btn-light border w-100 text-center dropdown-toggle" data-bs-toggle="dropdown">
                    {{ $modes[$mode]['label'] }}
                </button>
                <ul class="dropdown-menu w-100">
                    @foreach($modes as $modeKey => $assignment)
                        <li>
                            <a class="dropdown-item" href="{{ route('guru.switch-assignment', $modeKey) }}">
                                {{ $assignment['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="btn btn-light border w-100 text-center">{{ $modes[$mode]['label'] }}</div>
        @endif
    </div>
@endif
