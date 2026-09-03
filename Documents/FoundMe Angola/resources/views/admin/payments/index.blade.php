@extends('layouts.admin')

@section('title', 'Destino dos Fundos & Desembolsos — Admin FundMe Angola')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 font-heading fw-bold mb-1">Destinação e Liquidação de Fundos</h1>
        <p class="text-muted mb-0">Processamento final das transferências para hospitais e beneficiários de campanhas concluídas</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-light">
                <tr>
                    <th>Campanha</th>
                    <th>Meta / Angariado</th>
                    <th>Modalidade Destino</th>
                    <th>Favorecido / Banco</th>
                    <th>Estado</th>
                    <th>Ações de Liquidação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campaigns as $camp)
                    <tr>
                        <td>
                            <a href="{{ route('admin.campaigns.show', $camp->id) }}" class="fw-bold text-dark text-decoration-none">
                                {{ Str::limit($camp->title, 40) }}
                            </a>
                        </td>
                        <td>
                            <span class="d-block small text-muted">Meta: {{ $camp->formatted_target_amount }}</span>
                            <span class="fw-bold text-success">{{ number_format($camp->raised_amount, 2, ',', '.') }} Kz</span>
                        </td>
                        <td>
                            @if($camp->paymentDestination)
                                <span class="badge bg-primary">{{ $camp->paymentDestination->destination_type_label }}</span>
                            @else
                                <span class="badge bg-secondary">Não Configurado</span>
                            @endif
                        </td>
                        <td class="small">
                            @if($camp->paymentDestination)
                                <strong>{{ $camp->paymentDestination->institution_or_payee_name }}</strong><br>
                                <span class="text-muted">{{ $camp->paymentDestination->bank_name }} — {{ $camp->paymentDestination->account_number }}</span>
                            @else
                                <span class="text-danger">Pendente de Configuração</span>
                            @endif
                        </td>
                        <td><span class="badge {{ $camp->status_badge_class }}">{{ $camp->status_label }}</span></td>
                        <td>
                            @if($camp->status !== 'completed')
                                <button type="button" class="btn btn-sm btn-success rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#disburseModal{{ $camp->id }}">
                                    <i class="bi bi-send-check me-1"></i> Registar Pagamento
                                </button>
                            @else
                                <span class="badge bg-success"><i class="bi bi-check-all"></i> Liquidação Concluída</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Disbursement Modal -->
                    <div class="modal fade" id="disburseModal{{ $camp->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <form action="{{ route('admin.payments.disburse', $camp->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-header-title modal-title font-heading fw-bold">
                                            <i class="bi bi-bank me-2"></i> Registar Desembolso de Fundos
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="alert alert-info border-0 mb-4">
                                            <h6 class="fw-bold mb-1">Campanha: {{ $camp->title }}</h6>
                                            <p class="small mb-0">Total Angariado a Transferir: <strong>{{ number_format($camp->raised_amount, 2, ',', '.') }} Kz</strong></p>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small">Montante Efetivo Transferido (Kz) *</label>
                                                <input type="number" name="amount" value="{{ $camp->raised_amount }}" class="form-control" step="0.01" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-bold small">Nº de Referência / Comprovativo Bancário *</label>
                                                <input type="text" name="transaction_reference" class="form-control" placeholder="Ex: TR-2026-BAI-009988" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold small">Comprovativo Bancário (PDF / Imagem)</label>
                                                <input type="file" name="proof_file" class="form-control">
                                                <span class="form-text text-muted small">Anexe o talão ou borderô bancário original.</span>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-bold small">Nota Pública de Transparência (Atualização para Doadores) *</label>
                                                <textarea name="public_summary_update" class="form-control" rows="3" placeholder="Informação pública a ser partilhada com os doadores na página da campanha confirmando o pagamento ao hospital..." required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-success px-4">Confirmar e Finalizar Campanha</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $campaigns->links() }}
    </div>
</div>
@endsection
