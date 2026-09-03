@extends('layouts.admin')

@section('title', 'Análise de Campanha #' . $campaign->id . ' — Admin FundMe Angola')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.campaigns') }}" class="btn btn-outline-secondary btn-sm mb-2"><i class="bi bi-arrow-left me-1"></i> Voltar à Lista</a>
        <h1 class="h2 font-heading fw-bold mb-1">Análise de Solicitação de Campanha Médica</h1>
        <span class="badge {{ $campaign->status_badge_class }} fs-6">{{ $campaign->status_label }}</span>
    </div>

    @if($campaign->status === 'published')
        <a href="{{ route('campaigns.show', $campaign->slug) }}" class="btn btn-outline-primary shadow-sm" target="_blank">
            <i class="bi bi-eye-fill me-1"></i> Ver Página Pública da Campanha
        </a>
    @endif
</div>

<div class="row g-4">
    <!-- Left Column: Details & Private Documents -->
    <div class="col-lg-7">
        <!-- Main Info -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h4 class="font-heading mb-3 text-primary">{{ $campaign->title }}</h4>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <span class="text-muted small d-block">Solicitante:</span>
                    <strong class="text-dark">{{ $campaign->user ? $campaign->user->name : 'N/A' }}</strong> ({{ $campaign->user ? $campaign->user->email : '' }})
                </div>
                <div class="col-md-6">
                    <span class="text-muted small d-block">Paciente (Beneficiário):</span>
                    <strong class="text-dark">{{ $campaign->beneficiary ? $campaign->beneficiary->full_name : 'N/A' }}</strong>
                    @if($campaign->beneficiary && $campaign->beneficiary->is_identity_hidden)
                        <span class="badge bg-warning text-dark ms-1">Identidade Protegida</span>
                    @endif
                </div>
                <div class="col-md-6">
                    <span class="text-muted small d-block">Meta Solicitada:</span>
                    <strong class="text-success fs-5">{{ $campaign->formatted_target_amount }}</strong>
                </div>
                <div class="col-md-6">
                    <span class="text-muted small d-block">Unidade Hospitalar Indicada:</span>
                    <strong class="text-dark">{{ $campaign->hospital ? $campaign->hospital->name : ($campaign->hospital_name ?: 'Não Especificado') }}</strong>
                </div>
            </div>

            <h6 class="fw-bold mt-4 mb-2">História e Justificação Clínica:</h6>
            <div class="p-3 bg-light rounded-3 text-secondary lh-lg mb-3">
                {!! nl2br(e($campaign->story)) !!}
            </div>
        </div>

        <!-- Private Confidential Documents (Strict Security Access) -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4 border-start border-4 border-warning">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="font-heading mb-0 text-warning"><i class="bi bi-file-earmark-lock-fill me-2"></i> Cofre de Documentos Privados Confidenciais</h5>
                <span class="badge bg-danger">Acesso Restrito Verificadores</span>
            </div>

            <p class="text-muted small mb-3">
                Os ficheiros abaixo foram submetidos para comprovação de identidade e diagnósticos médicos. O download é encriptado e registado no sistema de audit log.
            </p>

            @if($campaign->documents->count() > 0)
                <div class="list-group">
                    @foreach($campaign->documents as $doc)
                        <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-file-earmark-pdf-fill fs-2 text-danger"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $doc->document_type_name }}</h6>
                                    <span class="text-muted small">{{ $doc->original_name }} ({{ number_format($doc->file_size / 1024, 1) }} KB)</span>
                                </div>
                            </div>
                            <a href="{{ route('documents.download', $doc->id) }}" class="btn btn-outline-primary btn-sm rounded-pill">
                                <i class="bi bi-download me-1"></i> Descarregar Seguro
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-danger mb-0 small">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Nenhum documento privado foi anexado a esta campanha. Solcite ao utilizador o envio dos comprovativos.
                </div>
            @endif
        </div>

        <!-- Verification Log History -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="font-heading mb-3"><i class="bi bi-journal-text text-secondary me-2"></i> Histórico de Auditoria & Verificações</h5>
            @if($campaign->verifications->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($campaign->verifications as $ver)
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-dark small"><i class="bi bi-person-check me-1"></i> {{ $ver->verifier ? $ver->verifier->name : 'Sistema' }}</strong>
                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $ver->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <span class="badge bg-secondary mb-1">Decisão: {{ strtoupper($ver->action) }}</span>
                            <p class="mb-0 text-muted small">{{ $ver->internal_notes }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted small mb-0">Ainda não existem registos de auditoria prévios para esta campanha.</p>
            @endif
        </div>
    </div>

    <!-- Right Column: Verification Decision & Payment Destination Config -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-lg rounded-4 p-4 bg-white mb-4">
            <h4 class="font-heading mb-3 text-success"><i class="bi bi-check-circle-fill me-2"></i> Tomada de Decisão & Destino dos Fundos</h4>

            <form action="{{ route('admin.campaigns.update_status', $campaign->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold small">Alterar Estado da Campanha *</label>
                    <select name="status" class="form-select form-select-lg fw-bold" required>
                        <option value="approved" {{ $campaign->status === 'approved' ? 'selected' : '' }}>Aprovar (Validada)</option>
                        <option value="published" {{ $campaign->status === 'published' ? 'selected' : '' }}>Publicar Imediatamente (Receber Doações)</option>
                        <option value="waiting_documents" {{ $campaign->status === 'waiting_documents' ? 'selected' : '' }}>Solicitar Mais Documentos Comprovativos</option>
                        <option value="rejected" {{ $campaign->status === 'rejected' ? 'selected' : '' }}>Rejeitar Solicitação</option>
                        <option value="suspended" {{ $campaign->status === 'suspended' ? 'selected' : '' }}>Suspender Campanha</option>
                        <option value="closed" {{ $campaign->status === 'closed' ? 'selected' : '' }}>Encerrar Campanha</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small">Modalidade de Destino dos Fundos Accumulados *</label>
                    <select name="payment_destination_type" class="form-select" required>
                        <option value="hospital_direct" {{ optional($campaign->paymentDestination)->destination_type === 'hospital_direct' ? 'selected' : '' }}>🏥 Pagamento Direto ao Hospital / Clínica</option>
                        <option value="beneficiary_transfer" {{ optional($campaign->paymentDestination)->destination_type === 'beneficiary_transfer' ? 'selected' : '' }}>👤 Transferência Direta ao Beneficiário / Solicitante</option>
                        <option value="split_payment" {{ optional($campaign->paymentDestination)->destination_type === 'split_payment' ? 'selected' : '' }}>💊 Pagamento Dividido (Hospital & Fornecedores)</option>
                    </select>
                </div>

                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <h6 class="fw-bold small mb-2 text-dark">Dados Bancários / Institucionais Privados (Escrow):</h6>

                    <div class="mb-2">
                        <label class="form-label small text-muted mb-0">Instituição ou Favorecido Oficial</label>
                        <input type="text" name="institution_or_payee_name" value="{{ old('institution_or_payee_name', optional($campaign->paymentDestination)->institution_or_payee_name) }}" class="form-control form-control-sm" placeholder="Ex: Complexo Hospitalar Cardeal Dom Alexandre">
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small text-muted mb-0">Banco</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name', optional($campaign->paymentDestination)->bank_name) }}" class="form-control form-control-sm" placeholder="Ex: BAI, BPC, BFA">
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-0">Nº de Conta</label>
                            <input type="text" name="account_number" value="{{ old('account_number', optional($campaign->paymentDestination)->account_number) }}" class="form-control form-control-sm" placeholder="Nº Conta">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small text-muted mb-0">IBAN Oficial</label>
                        <input type="text" name="iban" value="{{ old('iban', optional($campaign->paymentDestination)->iban) }}" class="form-control form-control-sm" placeholder="AO06.0000.0000.0000.0000.0000.0">
                    </div>

                    <div class="mb-2">
                        <label class="form-label small text-muted mb-0">Nº Fatura Proforma / Referência Hospitalar</label>
                        <input type="text" name="invoice_reference" value="{{ old('invoice_reference', optional($campaign->paymentDestination)->invoice_reference) }}" class="form-control form-control-sm" placeholder="Ex: FAT-2026-084">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small">Motivo da Rejeição (Caso aplicável)</label>
                    <textarea name="rejection_reason" class="form-control form-control-sm" rows="2" placeholder="Explicação visível para o solicitante em caso de rejeição...">{{ $campaign->rejection_reason }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small">Notas de Auditoria Interna (Obrigatório) *</label>
                    <textarea name="internal_notes" class="form-control" rows="3" placeholder="Registe os detalhes das verificações efetuadas (ex: contacto com hospital, autenticidade do BI)..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary-fundme btn-lg w-100 py-3 shadow">
                    <i class="bi bi-shield-check me-2"></i> Gravar Decisão & Configurar Destino
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
