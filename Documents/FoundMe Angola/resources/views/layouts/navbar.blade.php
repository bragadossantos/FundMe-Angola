<nav class="navbar navbar-expand-lg navbar-fundme sticky-top">
    <div class="container">
        <a class="brand-logo" href="{{ route('home') }}">
            <div class="logo-icon">
                <i class="bi bi-heart-pulse-fill"></i>
            </div>
            <span>FundMe <span class="highlight">Angola</span></span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Navegação">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-medium">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active text-primary fw-bold' : '' }}" href="{{ route('home') }}">Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('campaigns.index') ? 'active text-primary fw-bold' : '' }}" href="{{ route('campaigns.index') }}">Ver Campanhas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('how_it_works') ? 'active text-primary fw-bold' : '' }}" href="{{ route('how_it_works') }}">Como Funciona</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('campaigns.create') }}" class="btn btn-gold-fundme btn-sm me-2">
                    <i class="bi bi-plus-circle me-1"></i> Pedir Ajuda
                </a>

                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle rounded-pill px-3" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i> {{ Str::words(auth()->user()->name, 2, '') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userMenu">
                            <li><a class="dropdown-menu-item dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Meu Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard.campaigns') }}"><i class="bi bi-folder2-open me-2"></i> Minhas Campanhas</a></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard.donations') }}"><i class="bi bi-heart me-2"></i> Minhas Doações</a></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard.profile') }}"><i class="bi bi-gear me-2"></i> Definições de Perfil</a></li>

                            @if(auth()->user()->isAdmin() || auth()->user()->isVerifier())
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger fw-bold" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-lock me-2"></i> Painel Administrativo</a></li>
                            @endif

                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-secondary"><i class="bi bi-box-arrow-right me-2"></i> Sair da Conta</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 me-1">Entrar</a>
                    <a href="{{ route('register') }}" class="btn btn-primary-fundme btn-sm rounded-pill px-3">Criar Conta</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
