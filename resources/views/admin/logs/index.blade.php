@extends('layouts.admin')

@section('title', 'Audit Logs — Admin FundMe Angola')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 font-heading fw-bold mb-1">Registos de Auditoria (Audit Logs)</h1>
        <p class="text-muted mb-0">Rastreabilidade completa de todas as operações administrativas e transacionais do sistema</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-light">
                <tr>
                    <th>Data / Hora</th>
                    <th>Utilizador</th>
                    <th>Ação Executada</th>
                    <th>Entidade</th>
                    <th>ID Entidade</th>
                    <th>Endereço IP</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td class="small font-monospace">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td class="fw-bold small">{{ $log->user ? $log->user->name : 'Sistema / Anónimo' }}</td>
                        <td><span class="badge bg-dark">{{ $log->action }}</span></td>
                        <td class="small font-monospace text-secondary">{{ class_basename($log->entity_type) }}</td>
                        <td class="small">#{{ $log->entity_id ?: 'N/A' }}</td>
                        <td class="small font-monospace">{{ $log->ip_address }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $logs->links() }}
    </div>
</div>
@endsection
