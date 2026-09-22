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
