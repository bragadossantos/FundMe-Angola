@extends('layouts.admin')

@section('title', 'Gestão de Campanhas — Admin FundMe Angola')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 font-heading fw-bold mb-1">Gestão e Análise de Campanhas</h1>
        <p class="text-muted mb-0">Verificação de legitimidade, relatórios médicos e estados de aprovação</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
    <!-- Filter form -->
    <form action="{{ route('admin.campaigns') }}" method="GET" class="row g-3 mb-4">
        <div class="col-md-5">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Pesquisar por título de campanha...">
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select">
                <option value="">Todos os Estados</option>
                <option value="pending_review" {{ request('status') === 'pending_review' ? 'selected' : '' }}>Pendente de Análise</option>
                <option value="waiting_documents" {{ request('status') === 'waiting_documents' ? 'selected' : '' }}>Aguardando Documentos</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprovada (Pronta p/ Publicar)</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicada (Ativa)</option>
                <option value="goal_reached" {{ request('status') === 'goal_reached' ? 'selected' : '' }}>Meta Atingida 🎯</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejeitada</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspensa</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i> Filtrar</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Título da Campanha</th>
                    <th>Solicitante</th>
                    <th>Meta (Kz)</th>
                    <th>Angariado</th>
                    <th>Destino Configurado</th>
                    <th>Estado</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campaigns as $camp)
                    <tr>
                        <td>#{{ $camp->id }}</td>
                        <td>
                            <a href="{{ route('admin.campaigns.show', $camp->id) }}" class="fw-bold text-dark text-decoration-none">
                                {{ Str::limit($camp->title, 40) }}
                            </a>
                        </td>
                        <td class="small">{{ $camp->user ? $camp->user->name : 'N/A' }}</td>
                        <td class="small">{{ number_format($camp->target_amount, 0, ',', '.') }} Kz</td>
                        <td class="fw-bold text-success small">{{ number_format($camp->raised_amount, 0, ',', '.') }} Kz</td>
                        <td class="small">
                            @if($camp->payment_destination_type === 'hospital_direct')
                                <span class="badge bg-success bg-opacity-25 text-success">🏥 Direto ao Hospital</span>
                            @elseif($camp->payment_destination_type === 'beneficiary_transfer')
                                <span class="badge bg-info bg-opacity-25 text-primary">👤 Beneficiário</span>
                            @else
                                <span class="badge bg-secondary">Pendente Config.</span>
                            @endif
                        </td>
                        <td><span class="badge {{ $camp->status_badge_class }}">{{ $camp->status_label }}</span></td>
                        <td>
                            <a href="{{ route('admin.campaigns.show', $camp->id) }}" class="btn btn-sm btn-primary rounded-pill px-3"><i class="bi bi-search me-1"></i> Rever</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $campaigns->links() }}
    </div>
</div>
@endsection
