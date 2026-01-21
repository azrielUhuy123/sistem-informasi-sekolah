<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - Kepala Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary px-4">
    <span class="navbar-brand">Kepala Sekolah</span>

    <div class="text-white">
        {{ Auth::user()->name }}
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button class="btn btn-sm btn-light ms-2">Logout</button>
        </form>
    </div>
</nav>

<div class="container py-4">
    @yield('content')
</div>

</body>
</html>
