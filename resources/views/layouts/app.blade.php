<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FundMe Angola — Crowdfunding Solidário para Causas Médicas')</title>
    <meta name="description" content="@yield('meta_description', 'Plataforma profissional angolana de crowdfunding solidário para tratamentos médicos, cirurgias e exames com verificação rigorosa e transparência total.')">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
</head>
<body>
    @include('layouts.navbar')

    <!-- Flash Messages Container -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>
