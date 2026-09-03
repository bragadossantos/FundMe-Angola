@extends('layouts.app')

@section('title', 'Minhas Doações — FundMe Angola')

@section('content')
<div class="bg-light py-4 border-bottom mb-4">
    <div class="container">
        <h1 class="font-heading h2 mb-1">Histórico de Doações</h1>
        <p class="text-muted mb-0">Todas as contribuições solidárias efetuadas através da sua conta</p>
    </div>
</div>

<div class="container mb-5">
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        @if($donations->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light">
                        <tr>
                            <th>Campanha Apoiada</th>
                            <th>Data</th>
                            <th>Montante</th>
                            <th>Método</th>
                            <th>Referência</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donations as $don)
                            <tr>
                                <td>
                                    @if($don->campaign)
                                        <a href="{{ route('campaigns.show', $don->campaign->slug) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $don->campaign->title }}
                                        </a>
                                    @else
                                        <span class="text-muted">Campanha Removida</span>
                                    @endif
                                </td>
                                <td class="small">{{ $don->created_at->format('d/m/Y H:i') }}</td>
                                <td class="fw-bold text-success">{{ number_format($don->amount, 2, ',', '.') }} Kz</td>
                                <td class="small text-uppercase">{{ str_replace('_', ' ', $don->payment_method) }}</td>
                                <td class="small font-monospace">{{ $don->payment_reference }}</td>
                                <td><span class="badge {{ $don->status_badge_class }}">{{ $don->status_label }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $donations->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-heart fs-1 text-muted"></i>
                <h5 class="font-heading mt-3">Nenhuma doação encontrada</h5>
                <p class="text-muted small">Explore as campanhas ativas e faça a sua primeira doação solidária.</p>
                <a href="{{ route('campaigns.index') }}" class="btn btn-primary-fundme rounded-pill px-4">Ver Campanhas</a>
            </div>
        @endif
    </div>
</div>
@endsection
