@if(session('tu_mode') == 'ppdb')

    @include('layouts.sidebar.tu-ppdb')

@else

    @include('layouts.sidebar.tu-operasional')

@endif