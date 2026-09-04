@extends('layouts.app')

@section('title', 'Meu Dashboard — FundMe Angola')

@section('content')
<div class="bg-light py-4 border-bottom mb-4">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="badge bg-success bg-opacity-25 text-success p-2 mb-1 fw-bold">
                    <i class="bi bi-person-badge me-1"></i> Perfil: {{ ucfirst($user->role) }}
                </span>
                <h1 class="font-heading h2 mb-0">Olá, {{ $user->name }}</h1>
            </div>
            <div>
                <a href="{{ route('campaigns.create') }}" class="btn btn-gold-fundme shadow-sm me-2">
                    <i class="bi bi-plus-circle me-1"></i> Criar Nova Campanha
                </a>
                <a href="{{ route('dashboard.profile') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-gear me-1"></i> Perfil
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <!-- Stat Counters -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-box">
                <div class="stat-number text-success">{{ number_format($totalDonated, 0, ',', '.') }} Kz</div>
                <div class="stat-label"><i class="bi bi-heart-fill text-danger me-1"></i> Total Doado por Si</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box">
                <div class="stat-number text-primary">{{ $supportedCampaignsCount }}</div>
                <div class="stat-label"><i class="bi bi-hospital me-1"></i> Campanhas Médicas Apoiadas</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box">
                <div class="stat-number text-warning">{{ $myCampaigns->count() }}</div>
                <div class="stat-label"><i class="bi bi-folder-fill me-1"></i> Campanhas Criadas</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- My Campaigns Overview -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-heading mb-0"><i class="bi bi-folder2-open text-primary me-2"></i> Minhas Campanhas Médicas</h5>
                    <a href="{{ route('dashboard.campaigns') }}" class="btn btn-sm btn-outline-primary rounded-pill">Ver Todas</a>
                </div>

                @if($myCampaigns->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="table-light">
                                <tr>
                                    <th>Título</th>
                                    <th>Meta</th>
                                    <th>Angariado</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myCampaigns->take(5) as $camp)
                                    <tr>
                                        <td>
                                            <a href="{{ route('campaigns.show', $camp->slug) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ Str::limit($camp->title, 35) }}
                                            </a>
                                        </td>
                                        <td class="small">{{ $camp->formatted_target_amount }}</td>
                                        <td class="small text-success fw-bold">{{ number_format($camp->raised_amount, 0, ',', '.') }} Kz</td>
                                        <td><span class="badge {{ $camp->status_badge_class }}">{{ $camp->status_label }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4 text-muted small">
                        <p class="mb-2">Ainda não solicitou nenhuma campanha médica.</p>
                        <a href="{{ route('campaigns.create') }}" class="btn btn-primary-fundme btn-sm">Solicitar Campanha</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Donations Overview -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="font-heading mb-0"><i class="bi bi-heart-fill text-danger me-2"></i> Minhas Últimas Doações</h5>
                    <a href="{{ route('dashboard.donations') }}" class="btn btn-sm btn-outline-secondary rounded-pill">Histórico</a>
                </div>

                @if($donations->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($donations->take(5) as $don)
                            <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark small">{{ Str::limit($don->campaign->title ?? 'Campanha', 30) }}</h6>
                                    <span class="text-muted small" style="font-size: 0.75rem;">{{ $don->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold text-success d-block small">{{ number_format($don->amount, 0, ',', '.') }} Kz</span>
                                    <span class="badge {{ $don->status_badge_class }}" style="font-size: 0.65rem;">{{ $don->status_label }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small mb-0 py-3 text-center">Ainda não efetuou doações registadas nesta conta.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
