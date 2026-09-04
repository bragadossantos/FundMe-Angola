<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Painel Administrativo — FundMe Angola')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark sticky-top shadow-sm px-3">
        <div class="container-fluid">
            <a class="navbar-brand font-heading text-white fw-bold d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
                <span class="badge bg-danger p-2"><i class="bi bi-shield-lock-fill"></i> ADMIN</span> FundMe Angola
            </a>
            <div class="d-flex align-items-center gap-3">
                <span class="text-light small"><i class="bi bi-person-badge me-1"></i> {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span>
                <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm" target="_blank"><i class="bi bi-box-arrow-up-right me-1"></i> Ver Site Público</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-power"></i> Sair</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 admin-sidebar p-3 d-none d-md-block">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-grid-fill"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.campaigns*') ? 'active' : '' }}" href="{{ route('admin.campaigns') }}">
                            <i class="bi bi-folder-check"></i> Campanhas Médicas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}" href="{{ route('admin.payments') }}">
                            <i class="bi bi-bank"></i> Destino dos Fundos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.donations*') ? 'active' : '' }}" href="{{ route('admin.donations') }}">
                            <i class="bi bi-heart-fill text-danger"></i> Doações
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.utilizadores*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                            <i class="bi bi-people-fill"></i> Utilizadores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}" href="{{ route('admin.reports') }}">
                            <i class="bi bi-exclamation-octagon-fill text-warning"></i> Denúncias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.documents*') ? 'active' : '' }}" href="{{ route('admin.documents') }}">
                            <i class="bi bi-file-earmark-lock-fill text-info"></i> Documentos Privados
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.logs*') ? 'active' : '' }}" href="{{ route('admin.logs') }}">
                            <i class="bi bi-journal-text"></i> Audit Logs
                        </a>
                    </li>
                </ul>
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

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
