<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Painel Administrativo — FundMe Angola')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha384-XGjxtQfXaH2tnPFa9x+ruJTuLE3Aa6LhHSWRr1XeTyhezb4abCG4ccI5AkVDxqC+" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark sticky-top shadow-sm px-3">
        <div class="container-fluid">
            <button class="btn btn-outline-light btn-sm d-md-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminOffcanvas" aria-controls="adminOffcanvas" aria-label="Abrir menu de navegação">
                <i class="bi bi-list fs-5"></i>
            </button>
            <a class="navbar-brand font-heading text-white fw-bold d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
                <span class="badge bg-danger p-2"><i class="bi bi-shield-lock-fill"></i> ADMIN</span> FundMe Angola
            </a>
            <div class="d-flex align-items-center gap-3">
                <span class="text-light small d-none d-sm-inline"><i class="bi bi-person-badge me-1"></i> {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span>
                <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm" target="_blank"><i class="bi bi-box-arrow-up-right me-1"></i> Ver Site Público</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-power"></i> Sair</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Mobile navigation drawer (below the md breakpoint, where the sidebar column is hidden) -->
    <div class="offcanvas offcanvas-start admin-sidebar" tabindex="-1" id="adminOffcanvas" aria-labelledby="adminOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-white" id="adminOffcanvasLabel">Navegação</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
        </div>
        <div class="offcanvas-body">
            @include('layouts.admin_nav')
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (md and up) -->
            <div class="col-md-3 col-lg-2 admin-sidebar p-3 d-none d-md-block">
                @include('layouts.admin_nav')
            </div>

            <!-- Content Area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
