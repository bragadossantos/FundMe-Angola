@extends('layouts.app')

@section('title', 'Campanhas Médicas Verificadas — FundMe Angola')

@section('content')
<div class="bg-light py-4 border-bottom mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="font-heading h2 mb-1">Campanhas Médicas Verificadas</h1>
                <p class="text-muted mb-0">Explore e apoie causas de saúde urgentes em Angola</p>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <a href="{{ route('campaigns.create') }}" class="btn btn-gold-fundme shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Solicitar Apoio Médico
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <!-- Filter Bar Form -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 p-3 bg-white">
        <form action="{{ route('campaigns.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Pesquisar por Título ou Província</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 bg-light" placeholder="Ex: Cirurgia, Luanda, Maria...">
                </div>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">Província</label>
                <select name="province" class="form-select bg-light">
                    <option value="">Todas as Províncias</option>
                    @foreach($provinces as $prov)
                        <option value="{{ $prov }}" {{ request('province') === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">Categoria</label>
                <select name="category" class="form-select bg-light">
                    <option value="">Todas</option>
                    <option value="cirurgia" {{ request('category') === 'cirurgia' ? 'selected' : '' }}>Cirurgia</option>
                    <option value="tratamento" {{ request('category') === 'tratamento' ? 'selected' : '' }}>Tratamento</option>
                    <option value="medicamentos" {{ request('category') === 'medicamentos' ? 'selected' : '' }}>Medicamentos</option>
                    <option value="exames" {{ request('category') === 'exames' ? 'selected' : '' }}>Exames</option>
                    <option value="outro" {{ request('category') === 'outro' ? 'selected' : '' }}>Outro</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">Estado</label>
                <select name="status" class="form-select bg-light">
                    <option value="">Todas Ativas</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Em Curso (Aberto)</option>
                    <option value="goal_reached" {{ request('status') === 'goal_reached' ? 'selected' : '' }}>Meta Atingida 🎯</option>
                    <option value="featured" {{ request('status') === 'featured' ? 'selected' : '' }}>Destaques ⭐</option>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary-fundme w-100"><i class="bi bi-funnel-fill me-1"></i> Filtrar</button>
            </div>
        </form>
    </div>

    <!-- Campaigns Grid -->
    @if($campaigns->count() > 0)
        <div class="row g-4 mb-4">
            @foreach($campaigns as $campaign)
                <div class="col-md-6 col-lg-4">
                    @include('components.campaign_card', ['campaign' => $campaign])
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $campaigns->links() }}
        </div>
    @else
        <div class="text-center py-5 bg-white rounded-4 shadow-sm my-4">
            <i class="bi bi-folder-x fs-1 text-muted"></i>
            <h4 class="font-heading mt-3">Nenhuma campanha encontrada</h4>
            <p class="text-muted">Tente ajustar os filtros de pesquisa para encontrar mais causas médicas.</p>
            <a href="{{ route('campaigns.index') }}" class="btn btn-outline-primary rounded-pill px-4">Limpar Filtros</a>
        </div>
    @endif
</div>
@endsection
