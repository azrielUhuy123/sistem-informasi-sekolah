@if(session('guru_mode') == 'ekskul')

    @include('layouts.sidebar.guru-ekskul')

@else

    @include('layouts.sidebar.guru-akademik')

@endif