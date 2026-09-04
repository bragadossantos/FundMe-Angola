@extends('layouts.admin')

@section('title', 'Dashboard Administrativo — FundMe Angola')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 font-heading fw-bold mb-1">Painel Administrativo</h1>
        <p class="text-muted mb-0">Gestão global de segurança, verificação documental e transparência financeira</p>
    </div>
    <span class="badge bg-success bg-opacity-25 text-success p-2 font-monospace">
        <i class="bi bi-shield-check me-1"></i> Estado do Sistema: 100% OPERACIONAL
    </span>
</div>

<!-- Metrics Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-25 text-warning p-3">
                    <i class="bi bi-hourglass-split fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $pendingCampaignsCount }}</h3>
                    <span class="text-muted small">Pendentes de Análise</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-25 text-success p-3">
                    <i class="bi bi-heart-pulse-fill fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $activeCampaignsCount }}</h3>
                    <span class="text-muted small">Campanhas Ativas</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-25 text-primary p-3">
                    <i class="bi bi-bank fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ number_format($totalRaised, 0, ',', '.') }} Kz</h3>
                    <span class="text-muted small">Total Angariado</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-danger bg-opacity-25 text-danger p-3">
                    <i class="bi bi-exclamation-octagon-fill fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $pendingReportsCount }}</h3>
                    <span class="text-muted small">Denúncias Pendentes</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Campaigns Table -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="font-heading mb-0"><i class="bi bi-folder-check text-primary me-2"></i> Solicitações Recentes de Campanhas</h5>
                <a href="{{ route('admin.campaigns') }}" class="btn btn-sm btn-outline-primary rounded-pill">Ver Todas</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th>Título</th>
                            <th>Solicitante</th>
                            <th>Meta (Kz)</th>
                            <th>Estado</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentCampaigns as $camp)
                            <tr>
                                <td><a href="{{ route('admin.campaigns.show', $camp->id) }}" class="fw-bold text-dark text-decoration-none">{{ Str::limit($camp->title, 30) }}</a></td>
                                <td class="small">{{ $camp->user ? $camp->user->name : 'N/A' }}</td>
                                <td class="small fw-bold">{{ number_format($camp->target_amount, 0, ',', '.') }}</td>
                                <td><span class="badge {{ $camp->status_badge_class }}">{{ $camp->status_label }}</span></td>
                                <td>
                                    <a href="{{ route('admin.campaigns.show', $camp->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">Analisar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Donations Stream -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="font-heading mb-0"><i class="bi bi-heart-fill text-danger me-2"></i> Doações Confirmadas</h5>
                <a href="{{ route('admin.donations') }}" class="btn btn-sm btn-outline-secondary rounded-pill">Ver Todas</a>
            </div>

            <div class="list-group list-group-flush">
                @foreach($recentDonations as $don)
                    <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 fw-bold text-dark small">{{ $don->public_donor_name }}</h6>
                            <span class="text-muted small d-block" style="font-size: 0.75rem;">{{ Str::limit($don->campaign->title ?? 'Campanha', 30) }}</span>
                        </div>
                        <span class="fw-bold text-success small">{{ number_format($don->amount, 0, ',', '.') }} Kz</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
