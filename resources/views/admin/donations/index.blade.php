@extends('layouts.admin')

@section('title', 'Todas as Doações — Admin FundMe Angola')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 font-heading fw-bold mb-1">Registro Geral de Doações</h1>
        <p class="text-muted mb-0">Controlo de entrada de fundos e validações bancárias</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-light">
                <tr>
                    <th>Referência</th>
                    <th>Campanha</th>
                    <th>Doador</th>
                    <th>Montante</th>
                    <th>Método</th>
                    <th>Data de Confirmação</th>
                    <th>Estado</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donations as $don)
                    <tr>
                        <td class="font-monospace fw-bold small">{{ $don->payment_reference }}</td>
                        <td>
                            @if($don->campaign)
                                <a href="{{ route('admin.campaigns.show', $don->campaign->id) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ Str::limit($don->campaign->title, 35) }}
                                </a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td class="small">{{ $don->public_donor_name }}</td>
                        <td class="fw-bold text-success">{{ number_format($don->amount, 2, ',', '.') }} Kz</td>
                        <td class="small text-uppercase">{{ str_replace('_', ' ', $don->payment_method) }}</td>
                        <td class="small">{{ $don->paid_at ? $don->paid_at->format('d/m/Y H:i') : 'Pendente' }}</td>
                        <td><span class="badge {{ $don->status_badge_class }}">{{ $don->status_label }}</span></td>
                        <td>
                            @if($don->status !== 'paid' && $don->payment_method !== 'sandbox')
                                <form action="{{ route('admin.donations.confirm_manual', $don) }}" method="POST" onsubmit="return confirm('Confirma que verificou a receção real deste pagamento?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check-circle"></i> Confirmar
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $donations->links() }}
    </div>
</div>
@endsection
