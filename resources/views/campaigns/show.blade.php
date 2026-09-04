@extends('layouts.app')

@section('title', $campaign->title . ' — FundMe Angola')
@section('meta_description', Str::limit($campaign->short_description, 150))

@section('content')
<!-- Header Banner -->
<div class="bg-light py-4 border-bottom mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Início</a></li>
                <li class="breadcrumb-item"><a href="{{ route('campaigns.index') }}">Campanhas</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($campaign->title, 40) }}</li>
            </ol>
        </nav>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="badge bg-primary text-white mb-2"><i class="bi bi-tag-fill me-1"></i> {{ ucfirst($campaign->category) }}</span>
                @if($campaign->verification_badge)
                    <span class="badge bg-success text-white mb-2 ms-1"><i class="bi bi-patch-check-fill text-warning me-1"></i> Campanha Verificada</span>
                @endif
                <h1 class="font-heading h2 mb-0">{{ $campaign->title }}</h1>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm rounded-pill btn-copy-link" data-link="{{ url()->current() }}">
                    <i class="bi bi-share-fill me-1"></i> Partilhar Link
                </button>
                <button class="btn btn-outline-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#reportModal">
                    <i class="bi bi-flag-fill me-1"></i> Denunciar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4">
        <!-- Main Details Column -->
        <div class="col-lg-8">
            <!-- Featured Image -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                @if($campaign->featured_image)
                    <img src="{{ asset('storage/' . $campaign->featured_image) }}" class="w-100 img-fluid" style="max-height: 420px; object-fit: cover;" alt="{{ $campaign->title }}">
                @else
                    <div class="py-5 text-center bg-emerald-subtle text-success">
                        <i class="bi bi-hospital display-1"></i>
                        <p class="mt-2 text-muted fw-medium">Imagem Médica de Apoio Verificada</p>
                    </div>
                @endif
            </div>

            <!-- Patient & Medical Context -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h4 class="font-heading mb-3"><i class="bi bi-person-lines-fill text-primary me-2"></i> Informações do Paciente & Local</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted small d-block">Beneficiário / Paciente:</span>
                            <span class="fw-bold fs-6">
                                @if($campaign->beneficiary)
                                    {{ $campaign->beneficiary->public_display_name }}
                                @else
                                    Paciente Em Apoio
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted small d-block">Localização do Tratamento:</span>
                            <span class="fw-bold fs-6">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                {{ $campaign->location_province }} {{ $campaign->location_municipality ? '('.$campaign->location_municipality.')' : '' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="p-3 bg-emerald-subtle border border-success border-opacity-25 rounded-3 d-flex align-items-center gap-3">
                            <i class="bi bi-hospital fs-2 text-success"></i>
                            <div>
                                <span class="text-muted small d-block">Unidade Hospitalar Indicada:</span>
                                <span class="fw-bold text-dark">
                                    {{ $campaign->hospital ? $campaign->hospital->name : ($campaign->hospital_name ?: 'Hospital / Clínica Licenciada em Angola') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaign Story -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h4 class="font-heading mb-3"><i class="bi bi-journal-medical text-success me-2"></i> História e Situação Clínica</h4>
                <div class="story-content lh-lg text-secondary">
                    {!! nl2br(e($campaign->story)) !!}
                </div>
            </div>

            <!-- Financial Itemized Plan -->
            @if($campaign->fundPlans->count() > 0)
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h4 class="font-heading mb-3"><i class="bi bi-calculator-fill text-warning me-2"></i> Plano Orçamental dos Fundos</h4>
                <p class="text-muted small mb-3">Discriminação detalhada dos custos médicos essenciais orçamentados para esta campanha:</p>

                <div class="table-responsive">
                    <table class="table table-hover align-middle border">
                        <thead class="table-light">
                            <tr>
                                <th>Item / Despesa Médica</th>
                                <th>Notas Explicativas</th>
                                <th class="text-end">Estimativa (Kz)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($campaign->fundPlans as $plan)
                                <tr>
                                    <td class="fw-bold">{{ $plan->item_name }}</td>
                                    <td class="small text-muted">{{ $plan->notes ?: 'Despesa médica comprovada' }}</td>
                                    <td class="text-end fw-bold text-success">{{ number_format($plan->estimated_amount, 2, ',', '.') }} Kz</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-group-divider">
                            <tr class="fw-bold">
                                <td colspan="2">Total Orçamentado da Meta</td>
                                <td class="text-end text-primary fs-5">{{ $campaign->formatted_target_amount }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif

            <!-- Updates Timeline -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h4 class="font-heading mb-3"><i class="bi bi-clock-history text-primary me-2"></i> Linha do Tempo e Atualizações</h4>
                @if($campaign->updates->count() > 0)
                    <div class="timeline-list mt-3">
                        @foreach($campaign->updates as $update)
                            <div class="timeline-item">
                                <span class="small text-muted">{{ $update->created_at->format('d/m/Y \à\s H:i') }}</span>
                                <h6 class="fw-bold text-dark mb-1">{{ $update->title }}</h6>
                                <p class="text-secondary small mb-0">{!! nl2br(e($update->content)) !!}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small mb-0">Ainda não existem atualizações publicadas para esta campanha.</p>
                @endif
            </div>

            <!-- Recent Confirmed Donors -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h4 class="font-heading mb-3"><i class="bi bi-heart-fill text-danger me-2"></i> Doadores Solidários ({{ $campaign->donations->count() }})</h4>
                @if($campaign->donations->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($campaign->donations as $donation)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-2">
                                        <i class="bi bi-heart-fill"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $donation->public_donor_name }}</h6>
                                        @if($donation->donor_message)
                                            <p class="mb-0 text-muted small fst-italic">"{{ $donation->donor_message }}"</p>
                                        @endif
                                        <span class="text-muted small" style="font-size: 0.75rem;">{{ $donation->paid_at ? $donation->paid_at->diffForHumans() : 'Recente' }}</span>
                                    </div>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold fs-6">{{ $donation->formatted_amount }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small mb-0">Seja o primeiro a apoiar esta causa médica com a sua doação!</p>
                @endif
            </div>
        </div>

        <!-- Sidebar Column (Donation Box) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg rounded-4 p-4 sticky-top" style="top: 90px; z-index: 10;">
                <div class="card-body p-0">
                    <span class="badge {{ $campaign->status_badge_class }} mb-2 px-3 py-2 fs-6">
                        {{ $campaign->status_label }}
                    </span>

                    <div class="my-3">
                        <span class="display-6 fw-bold font-heading text-success d-block">{{ $campaign->formatted_raised_amount }}</span>
                        <span class="text-muted small">angariados de <strong class="text-dark">{{ $campaign->formatted_target_amount }}</strong> necessários</span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress-fundme">
                        <div class="progress-bar-fundme" role="progressbar" style="width: {{ $campaign->progress_percentage }}%;" aria-valuenow="{{ $campaign->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <div class="d-flex justify-content-between text-muted small fw-bold mt-1 mb-4">
                        <span>{{ $campaign->progress_percentage }}% atingido</span>
                        <span>{{ $campaign->donations->count() }} doações</span>
                    </div>

                    @if($campaign->status === 'published')
                        <!-- Donation Form -->
                        <form action="{{ route('donations.store', $campaign->id) }}" method="POST">
                            @csrf
                            <h6 class="fw-bold mb-2">Selecione o Valor da Doação (Kz)</h6>

                            <!-- Presets -->
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm btn-preset-amount flex-fill" data-value="1000">1.000 Kz</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm btn-preset-amount flex-fill" data-value="2500">2.500 Kz</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm btn-preset-amount flex-fill" data-value="5000">5.000 Kz</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm btn-preset-amount flex-fill" data-value="10000">10.000 Kz</button>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Outro Valor (Kz)</label>
                                <div class="input-group">
                                    <input type="number" name="amount" id="donation_amount_input" class="form-control form-control-lg fw-bold" placeholder="Digite o valor" min="100" value="2500" required>
                                    <span class="input-group-text fw-bold">Kz</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Método de Pagamento</label>
                                <select name="payment_method" class="form-select">
                                    <option value="multicaixa_express">Multicaixa Express (Angola)</option>
                                    <option value="bank_transfer">Transferência Bancária (IBAN)</option>
                                    <option value="kwanza_pay">KwanzaPay</option>
                                    <option value="sandbox">Ambiente Seguro Sandbox (Demonstração)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_anonymous" value="1" id="is_anonymous_check">
                                    <label class="form-check-label small text-muted" for="is_anonymous_check">
                                        Fazer doação em modo anónimo
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted">Mensagem de Apoio (Opcional)</label>
                                <textarea name="donor_message" class="form-control form-control-sm" rows="2" placeholder="Deixe uma palavra de esperança..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-gold-fundme btn-lg w-100 py-3 shadow mb-3">
                                <i class="bi bi-heart-fill me-2"></i> Efetuar Doação Agora
                            </button>
                        </form>
                    @else
                        <div class="alert alert-secondary text-center my-3">
                            <i class="bi bi-info-circle me-1"></i> Esta campanha encontra-se no estado: <strong>{{ $campaign->status_label }}</strong>
                        </div>
                    @endif

                    <div class="p-3 bg-light rounded-3 text-muted small mt-3">
                        <i class="bi bi-shield-check text-success me-1"></i> <strong>Compromisso de Transparência:</strong> Os fundos são retidos e desembolsados diretamente para cobrir as despesas hospitalares indicadas.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Complaint Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('reports.store', $campaign->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0 bg-danger bg-opacity-10">
                    <h5 class="modal-header-title modal-title font-heading text-danger fw-bold" id="reportModalLabel">
                        <i class="bi bi-flag-fill me-2"></i> Denunciar Campanha sob Sigilo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted mb-3">
                        Utilize este formulário caso suspeite de informações falsas, fraude ou uso indevido de imagens. A sua identidade será mantida sob estrito sigilo.
                    </p>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Motivo da Denúncia</label>
                        <select name="reason" class="form-select" required>
                            <option value="">Selecione o motivo...</option>
                            <option value="suspected_fraud">Suspeita de Fraude ou Pedido Falso</option>
                            <option value="false_information">Informações Médicas Incorretas</option>
                            <option value="misused_images">Uso Indevido de Fotos / Imagens de Terceiros</option>
                            <option value="duplicate_campaign">Campanha Duplicada</option>
                            <option value="other">Outro Motivo Grave</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Descrição dos Factos</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Descreva os detalhes que fundamentam a sua suspeita..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Ficheiro Comprovativo (Opcional)</label>
                        <input type="file" name="evidence_file" class="form-control">
                        <span class="form-text text-muted small">Imagens, captura de ecrã ou documento comprovativo (Max: 5MB)</span>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger px-4">Submeter Denúncia Sigilosa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
