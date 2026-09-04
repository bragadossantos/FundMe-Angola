@extends('layouts.app')

@section('title', 'Minhas Campanhas — FundMe Angola')

@section('content')
<div class="bg-light py-4 border-bottom mb-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="font-heading h2 mb-1">Minhas Campanhas Médicas</h1>
                <p class="text-muted mb-0">Gestão e acompanhamento do estado das suas solicitações</p>
            </div>
            <a href="{{ route('campaigns.create') }}" class="btn btn-gold-fundme shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Nova Solicitação
            </a>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        @if($campaigns->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th>Título da Campanha</th>
                            <th>Paciente</th>
                            <th>Meta Total</th>
                            <th>Angariado</th>
                            <th>Progresso</th>
                            <th>Estado de Verificação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campaigns as $camp)
                            <tr>
                                <td>
                                    <a href="{{ route('campaigns.show', $camp->slug) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ Str::limit($camp->title, 40) }}
                                    </a>
                                </td>
                                <td class="small">{{ $camp->beneficiary ? $camp->beneficiary->public_display_name : 'Paciente' }}</td>
                                <td class="small">{{ $camp->formatted_target_amount }}</td>
                                <td class="fw-bold text-success small">{{ number_format($camp->raised_amount, 2, ',', '.') }} Kz</td>
                                <td>
                                    <div class="progress" style="height: 6px; width: 80px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $camp->progress_percentage }}%;"></div>
                                    </div>
                                    <span class="small text-muted">{{ $camp->progress_percentage }}%</span>
                                </td>
                                <td>
                                    <span class="badge {{ $camp->status_badge_class }}">{{ $camp->status_label }}</span>
                                    @if($camp->status === 'rejected' && $camp->rejection_reason)
                                        <div class="small text-danger mt-1">Motivo: {{ $camp->rejection_reason }}</div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('campaigns.show', $camp->slug) }}" class="btn btn-outline-primary btn-sm rounded-pill"><i class="bi bi-eye"></i> Ver</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $campaigns->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-folder-x fs-1 text-muted"></i>
                <h5 class="font-heading mt-3">Nenhuma campanha solicitada</h5>
                <p class="text-muted small">Crie a sua primeira campanha médica para receber apoio da comunidade angolana.</p>
                <a href="{{ route('campaigns.create') }}" class="btn btn-gold-fundme px-4">Criar Solicitação</a>
            </div>
        @endif
    </div>
</div>
@endsection
