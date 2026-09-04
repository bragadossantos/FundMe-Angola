@extends('layouts.app')

@section('title', 'FundMe Angola — Crowdfunding Solidário para Tratamentos Médicos em Angola')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="badge bg-success bg-opacity-25 text-warning fw-bold mb-3 px-3 py-2 rounded-pill border border-warning">
                    <i class="bi bi-shield-check me-1"></i> Plataforma Oficial de Crowdfunding Solidário
                </span>
                <h1 class="hero-title">
                    Transforme a sua Solidariedade na Cura de Alguém em <span class="text-warning">Angola</span>.
                </h1>
                <p class="hero-subtitle">
                    Apoie de forma segura tratamentos médicos, cirurgias e exames comprovados. Todas as campanhas passam por verificação rigorosa antes de receberem apoio.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('campaigns.index') }}" class="btn btn-gold-fundme btn-lg px-4 py-3 shadow">
                        <i class="bi bi-heart-fill me-2"></i> Ver Campanhas para Apoiar
                    </a>
                    <a href="{{ route('campaigns.create') }}" class="btn btn-outline-light btn-lg px-4 py-3 rounded-md">
                        <i class="bi bi-plus-circle me-2"></i> Solicitar Campanha Médica
                    </a>
                </div>

                <div class="mt-4 pt-3 d-flex align-items-center gap-4 text-white-50 small">
                    <span><i class="bi bi-check-circle-fill text-success me-1"></i> Transparência Total</span>
                    <span><i class="bi bi-lock-fill text-warning me-1"></i> Proteção de Dados Médicos</span>
                    <span><i class="bi bi-building-check text-info me-1"></i> Hospitais Angolanos</span>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white text-dark p-2">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success p-3">
                                <i class="bi bi-heart-pulse-fill fs-2"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Impacto da Solidariedade</h6>
                                <span class="text-muted small">Métricas atualizadas em tempo real</span>
                            </div>
                        </div>

                        <div class="row g-3 text-center my-2">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="fs-3 fw-bold text-success">{{ number_format($totalRaised, 0, ',', '.') }} Kz</div>
                                    <span class="text-muted small">Total Angariado</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="fs-3 fw-bold text-primary">{{ $totalDonationsCount }}</div>
                                    <span class="text-muted small">Doações Confirmadas</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-emerald-subtle rounded-3 border border-success border-opacity-25 mt-3">
                            <div class="d-flex align-items-center gap-2 text-success fw-bold small">
                                <i class="bi bi-shield-lock-fill"></i> Garantia FundMe Angola
                            </div>
                            <p class="mb-0 small text-muted mt-1">
                                Os fundos são transferidos diretamente para as instituições hospitalares ou beneficiários com comprovativa pública de transparência.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Highlights -->
<section class="py-5 bg-white border-bottom">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-3">
                    <div class="rounded-circle bg-emerald-subtle text-success d-inline-flex p-3 mb-3">
                        <i class="bi bi-patch-check-fill fs-2"></i>
                    </div>
                    <h5 class="fw-bold">1. Verificação Rigorosa</h5>
                    <p class="text-muted small mb-0">Cada pedido passa por verificação documental médica e de identidade antes de ser publicado.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-inline-flex p-3 mb-3">
                        <i class="bi bi-bank fs-2"></i>
                    </div>
                    <h5 class="fw-bold">2. Destinação Segura</h5>
                    <p class="text-muted small mb-0">Preferência de pagamento direto ao hospital ou instituição de saúde para garantir o uso correto dos fundos.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex p-3 mb-3">
                        <i class="bi bi-eye-fill fs-2"></i>
                    </div>
                    <h5 class="fw-bold">3. Transparência & Prestação</h5>
                    <p class="text-muted small mb-0">Acompanhamento contínuo da evolução clínica e relatórios públicos de desembolso.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Campaigns -->
@if($featuredCampaigns->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="text-success fw-bold text-uppercase small tracking-wide"><i class="bi bi-star-fill me-1"></i> Destaques da Semana</span>
                <h2 class="font-heading mb-0">Campanhas Verificadas em Destaque</h2>
            </div>
            <a href="{{ route('campaigns.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">Ver Todas <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="row g-4">
            @foreach($featuredCampaigns as $campaign)
                <div class="col-md-6 col-lg-4">
                    @include('components.campaign_card', ['campaign' => $campaign])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Recent Active Campaigns -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="text-primary fw-bold text-uppercase small"><i class="bi bi-heart-pulse-fill me-1"></i> Precisa do Seu Apoio</span>
                <h2 class="font-heading mb-0">Campanhas Médicas Recentes</h2>
            </div>
            <a href="{{ route('campaigns.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Explorar Filtros</a>
        </div>

        <div class="row g-4">
            @foreach($recentCampaigns as $campaign)
                <div class="col-md-6 col-lg-4">
                    @include('components.campaign_card', ['campaign' => $campaign])
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-5 bg-primary-dark text-white position-relative overflow-hidden">
    <div class="container py-4 text-center position-relative z-1">
        <h2 class="text-white font-heading display-6 fw-bold mb-3">Conhece Alguém que Precisa de Ajuda Médica Urgente?</h2>
        <p class="text-light lead mx-auto mb-4" style="max-width: 700px;">
            A nossa missão é garantir que nenhuma vida seja perdida por falta de recursos financeiros para tratamentos essenciais em Angola.
        </p>
        <a href="{{ route('campaigns.create') }}" class="btn btn-gold-fundme btn-lg px-5 py-3 shadow">
            <i class="bi bi-heart-pulse-fill me-2"></i> Criar Campanha Médica Gratuita
        </a>
    </div>
</section>
@endsection
