@extends('layouts.app')

@section('title', 'Solicitar Campanha Médica — FundMe Angola')

@php
    // Work out which step should be shown first: if the server rejected the
    // submission, jump straight to the earliest step that has an invalid
    // field instead of always restarting at Step 1, since old() below will
    // already have restored everything the user typed.
    $stepFields = [
        1 => ['beneficiary_name', 'age_range', 'relation_to_applicant', 'location_province', 'location_municipality', 'is_identity_hidden'],
        2 => ['title', 'category', 'hospital_id', 'hospital_name', 'treatment_location', 'expected_treatment_date', 'short_description', 'story', 'featured_image'],
        3 => ['target_amount', 'fund_item_name', 'fund_item_amount', 'fund_item_name.*', 'fund_item_amount.*'],
        4 => ['identity_document', 'medical_documents', 'medical_documents.*', 'terms_accepted'],
    ];
    $startStep = 1;
    foreach ($stepFields as $step => $fields) {
        if ($errors->hasAny($fields)) {
            $startStep = $step;
            break;
        }
    }
@endphp

@section('content')
<div class="bg-light py-4 border-bottom mb-4">
    <div class="container">
        <h1 class="font-heading h2 mb-1">Solicitar Apoio para Tratamento Médico</h1>
        <p class="text-muted mb-0">Preencha os dados da campanha. Todas as informações médicas serão analisadas pela nossa equipa de verificação.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-1"></i> Corrija os seguintes campos:</h6>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Step Indicators Header -->
            <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
                <div class="row text-center wizard-indicators" role="list" aria-label="Progresso da solicitação">
                    <div class="col-3 step-indicator-item" id="ind-step-1" role="listitem">
                        <div class="fw-bold fs-5 mb-1"><i class="bi bi-person-fill"></i> 1</div>
                        <span class="small">Solicitante & Paciente</span>
                    </div>
                    <div class="col-3 step-indicator-item" id="ind-step-2" role="listitem">
                        <div class="fw-bold fs-5 mb-1"><i class="bi bi-journal-medical"></i> 2</div>
                        <span class="small">Dados Médicos</span>
                    </div>
                    <div class="col-3 step-indicator-item" id="ind-step-3" role="listitem">
                        <div class="fw-bold fs-5 mb-1"><i class="bi bi-calculator"></i> 3</div>
                        <span class="small">Plano Orçamental</span>
                    </div>
                    <div class="col-3 step-indicator-item" id="ind-step-4" role="listitem">
                        <div class="fw-bold fs-5 mb-1"><i class="bi bi-file-earmark-lock-fill"></i> 4</div>
                        <span class="small">Documentos Privados</span>
                    </div>
                </div>
                <p class="text-center text-muted small mb-0 mt-2" id="wizard-step-caption" aria-live="polite">Passo {{ $startStep }} de 4</p>
            </div>

            <!-- Form -->
            <div class="card border-0 shadow-lg rounded-4 p-4 bg-white">
                <form action="{{ route('campaigns.store') }}" method="POST" enctype="multipart/form-data" id="multiStepCampaignForm" data-start-step="{{ $startStep - 1 }}">
                    @csrf

                    <!-- STEP 1: Solicitante & Paciente -->
                    <div class="wizard-step" id="wizard-step-1">
                        <h4 class="font-heading text-primary mb-3"><i class="bi bi-person-circle me-2"></i> Passo 1: Identificação do Paciente / Beneficiário</h4>
                        <p class="text-muted small mb-4">Informe quem receberá o apoio financeiro e a sua relação familiar com o paciente.</p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="beneficiary_name" class="form-label fw-bold">Nome Completo do Paciente (Beneficiário) *</label>
                                <input type="text" id="beneficiary_name" name="beneficiary_name" value="{{ old('beneficiary_name') }}" class="form-control" placeholder="Nome do paciente" required>
                            </div>

                            <div class="col-md-3">
                                <label for="age_range" class="form-label fw-bold">Faixa Etária *</label>
                                <select id="age_range" name="age_range" class="form-select" required>
                                    <option value="0-5 anos" {{ old('age_range') === '0-5 anos' ? 'selected' : '' }}>0 - 5 anos (Bebé / Criança)</option>
                                    <option value="6-12 anos" {{ old('age_range') === '6-12 anos' ? 'selected' : '' }}>6 - 12 anos (Criança)</option>
                                    <option value="13-17 anos" {{ old('age_range') === '13-17 anos' ? 'selected' : '' }}>13 - 17 anos (Adolescente)</option>
                                    <option value="18-35 anos" {{ old('age_range') === '18-35 anos' ? 'selected' : '' }}>18 - 35 anos (Jovem Adulto)</option>
                                    <option value="36-59 anos" {{ old('age_range') === '36-59 anos' ? 'selected' : '' }}>36 - 59 anos (Adulto)</option>
                                    <option value="60+ anos" {{ old('age_range') === '60+ anos' ? 'selected' : '' }}>60+ anos (Sénior)</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="relation_to_applicant" class="form-label fw-bold">Relação com o Solicitante *</label>
                                <select id="relation_to_applicant" name="relation_to_applicant" class="form-select" required>
                                    <option value="O próprio" {{ old('relation_to_applicant') === 'O próprio' ? 'selected' : '' }}>O próprio paciente</option>
                                    <option value="Pai/Mãe" {{ old('relation_to_applicant') === 'Pai/Mãe' ? 'selected' : '' }}>Pai / Mãe</option>
                                    <option value="Filho(a)" {{ old('relation_to_applicant') === 'Filho(a)' ? 'selected' : '' }}>Filho(a)</option>
                                    <option value="Cônjuge" {{ old('relation_to_applicant') === 'Cônjuge' ? 'selected' : '' }}>Cônjuge</option>
                                    <option value="Irmão/Irmã" {{ old('relation_to_applicant') === 'Irmão/Irmã' ? 'selected' : '' }}>Irmão / Irmã</option>
                                    <option value="Tutor Legal" {{ old('relation_to_applicant') === 'Tutor Legal' ? 'selected' : '' }}>Tutor Legal / Responsável</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="location_province" class="form-label fw-bold">Província de Residência *</label>
                                <select id="location_province" name="location_province" class="form-select" required>
                                    <option value="">Selecione a província...</option>
                                    @foreach($provinces as $prov)
                                        <option value="{{ $prov }}" {{ old('location_province') === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="location_municipality" class="form-label fw-bold">Município</label>
                                <input type="text" id="location_municipality" name="location_municipality" value="{{ old('location_municipality') }}" class="form-control" placeholder="Ex: Talatona, Cazenga, Lobito">
                            </div>

                            <div class="col-md-12 mt-3">
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_identity_hidden" value="1" id="is_identity_hidden_check" {{ old('is_identity_hidden') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold text-dark" for="is_identity_hidden_check">
                                            Proteção de Identidade do Paciente no Site Público
                                        </label>
                                    </div>
                                    <span class="text-muted small d-block mt-1">
                                        Se ativado, o nome público do paciente será ocultado como "Paciente (Identidade Protegida)" para salvaguardar a sua privacidade médica.
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-primary-fundme px-4 btn-next-step">Próximo Passo <i class="bi bi-arrow-right ms-1"></i></button>
                        </div>
                    </div>

                    <!-- STEP 2: Dados Médicos & História -->
                    <div class="wizard-step" id="wizard-step-2" style="display: none;">
                        <h4 class="font-heading text-primary mb-3"><i class="bi bi-hospital me-2"></i> Passo 2: Informações Médicas & História</h4>
                        <p class="text-muted small mb-4">Descreva o diagnóstico, o tratamento necessário e onde será realizado.</p>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="title" class="form-label fw-bold">Título Claro da Campanha *</label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" class="form-control" placeholder="Ex: Cirurgia Cardíaca Urgente para a Pequena Maria" required>
                            </div>

                            <div class="col-md-4">
                                <label for="category" class="form-label fw-bold">Categoria Médica *</label>
                                <select id="category" name="category" class="form-select" required>
                                    <option value="cirurgia" {{ old('category') === 'cirurgia' ? 'selected' : '' }}>Cirurgia / Operação</option>
                                    <option value="tratamento" {{ old('category') === 'tratamento' ? 'selected' : '' }}>Tratamento Continuado</option>
                                    <option value="medicamentos" {{ old('category') === 'medicamentos' ? 'selected' : '' }}>Medicamentos de Alto Custo</option>
                                    <option value="exames" {{ old('category') === 'exames' ? 'selected' : '' }}>Exames e Diagnóstico</option>
                                    <option value="outro" {{ old('category') === 'outro' ? 'selected' : '' }}>Outro Apoio Médico</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="hospital_id" class="form-label fw-bold">Hospital / Unidade de Saúde em Angola (Se aplicável)</label>
                                <select id="hospital_id" name="hospital_id" class="form-select">
                                    <option value="">Selecione da lista de hospitais credenciados...</option>
                                    @foreach($hospitals as $hosp)
                                        <option value="{{ $hosp->id }}" {{ (string) old('hospital_id') === (string) $hosp->id ? 'selected' : '' }}>{{ $hosp->name }} ({{ $hosp->province }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="hospital_name" class="form-label fw-bold">Ou Nome do Hospital / Clínica Não Listada</label>
                                <input type="text" id="hospital_name" name="hospital_name" value="{{ old('hospital_name') }}" class="form-control" placeholder="Ex: Clínica Girassol, Hospital Central de Benguela">
                            </div>

                            <div class="col-md-6">
                                <label for="treatment_location" class="form-label fw-bold">Local Onde Será Efetuado o Tratamento *</label>
                                <select id="treatment_location" name="treatment_location" class="form-select" required>
                                    <option value="angola" {{ old('treatment_location', 'angola') === 'angola' ? 'selected' : '' }}>Em Angola</option>
                                    <option value="estrangeiro" {{ old('treatment_location') === 'estrangeiro' ? 'selected' : '' }}>No Estrangeiro (Junta Médica / Específica)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="expected_treatment_date" class="form-label fw-bold">Data Prevista para o Tratamento (Opcional)</label>
                                <input type="date" id="expected_treatment_date" name="expected_treatment_date" value="{{ old('expected_treatment_date') }}" class="form-control">
                            </div>

                            <div class="col-md-12">
                                <label for="short_description" class="form-label fw-bold">Resumo Curto da Causa (Para o Cartão da Campanha) *</label>
                                <textarea id="short_description" name="short_description" class="form-control" rows="2" placeholder="Explique resumidamente em 2 ou 3 frases qual é a urgência médica..." required>{{ old('short_description') }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <label for="story" class="form-label fw-bold">História Completa e Situação Clínica *</label>
                                <textarea id="story" name="story" class="form-control" rows="5" placeholder="Conte em detalhe a história do paciente, como surgiu a doença e porque a família precisa de ajuda financeira..." required>{{ old('story') }}</textarea>
                            </div>

                            <div class="col-md-12">
                                <label for="featured_image" class="form-label fw-bold">Fotografia de Capa Autorizada (Pública)</label>
                                <input type="file" id="featured_image" name="featured_image" class="form-control image-preview-input" data-preview-target="featured-preview" accept="image/*">
                                <span class="form-text text-muted small">Carregue uma imagem digna do paciente ou da situação clínica autorizada para divulgação. A imagem não pode ser recuperada automaticamente se a submissão falhar — terá de a selecionar novamente.</span>
                                <div class="mt-2">
                                    <img id="featured-preview" alt="Pré-visualização" style="display:none; max-height: 180px;" class="rounded-3 border">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary px-4 btn-prev-step"><i class="bi bi-arrow-left me-1"></i> Anterior</button>
                            <button type="button" class="btn btn-primary-fundme px-4 btn-next-step">Próximo Passo <i class="bi bi-arrow-right ms-1"></i></button>
                        </div>
                    </div>

                    <!-- STEP 3: Objetivo Financeiro e Discriminação -->
                    <div class="wizard-step" id="wizard-step-3" style="display: none;">
                        <h4 class="font-heading text-primary mb-3"><i class="bi bi-calculator-fill me-2"></i> Passo 3: Objetivo Financeiro & Discriminação Orçamental</h4>
                        <p class="text-muted small mb-4">Indique a meta total em Kwanzas (Kz) e adicione a estimativa de custos para cada despesa médica.</p>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="target_amount_input" class="form-label fw-bold fs-5 text-success">Meta Total Necessária (Kz) *</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" name="target_amount" id="target_amount_input" value="{{ old('target_amount') }}" class="form-control fw-bold text-success fs-3" placeholder="0,00" min="1000" step="500" required>
                                    <span class="input-group-text fw-bold text-success">Kz</span>
                                </div>
                            </div>

                            <div class="col-md-12 mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0">Discriminação Detalhada das Despesas Médicas</h6>
                                    <button type="button" class="btn btn-outline-success btn-sm rounded-pill" id="btnAddFundItem">
                                        <i class="bi bi-plus-circle me-1"></i> Adicionar Item Orçamental
                                    </button>
                                </div>

                                <div id="fundPlanItemsContainer">
                                    @php
                                        $oldFundNames = old('fund_item_name', ['']);
                                        $oldFundAmounts = old('fund_item_amount', []);
                                    @endphp
                                    @foreach($oldFundNames as $idx => $oldFundName)
                                        <div class="row g-2 mb-2 fund-plan-row">
                                            <div class="col-md-7">
                                                <label for="fund_item_name_{{ $idx }}" class="visually-hidden">Nome do item orçamental</label>
                                                <input type="text" id="fund_item_name_{{ $idx }}" name="fund_item_name[]" value="{{ $oldFundName }}" class="form-control" placeholder="Ex: Intervenção Cirúrgica ou Bloco" required>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label for="fund_item_amount_{{ $idx }}" class="visually-hidden">Valor estimado em Kz</label>
                                                    <input type="number" id="fund_item_amount_{{ $idx }}" name="fund_item_amount[]" value="{{ $oldFundAmounts[$idx] ?? '' }}" class="form-control fund-amount-input" placeholder="Valor em Kz" step="500" min="0" required>
                                                    <span class="input-group-text">Kz</span>
                                                </div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-center">
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-fund-item w-100" aria-label="Remover item orçamental"><i class="bi bi-trash"></i>✕</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary px-4 btn-prev-step"><i class="bi bi-arrow-left me-1"></i> Anterior</button>
                            <button type="button" class="btn btn-primary-fundme px-4 btn-next-step">Próximo Passo <i class="bi bi-arrow-right ms-1"></i></button>
                        </div>
                    </div>

                    <!-- STEP 4: Documentos Comprovativos Privados -->
                    <div class="wizard-step" id="wizard-step-4" style="display: none;">
                        <h4 class="font-heading text-primary mb-3"><i class="bi bi-shield-lock-fill me-2"></i> Passo 4: Envio Seguro de Documentos Confidenciais</h4>

                        <div class="alert alert-warning border-0 shadow-sm mb-4">
                            <div class="d-flex align-items-center gap-2 font-heading fw-bold">
                                <i class="bi bi-lock-fill fs-4 text-warning"></i> Garantia de Sigilo & Proteção de Dados
                            </div>
                            <p class="small mb-0 mt-1">
                                Os documentos submetidos nesta etapa são <strong>estritamente confidenciais</strong>. ELES NÃO SERÃO EXIBIDOS PUBLICAMENTE NO SITE. O acesso é exclusivo do administrador e verificadores credenciados para validação da veracidade da campanha.
                            </p>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="identity_document" class="form-label fw-bold">Documento de Identidade do Solicitante (BI / Passaporte) *</label>
                                <input type="file" id="identity_document" name="identity_document" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                                <span class="form-text text-muted small">Carregue foto ou PDF nítido do Bilhete de Identidade. Máximo 10MB.</span>
                            </div>

                            <div class="col-md-6">
                                <label for="medical_documents" class="form-label fw-bold">Relatórios Médicos / Receitas / Orçamentos Hospitalares *</label>
                                <input type="file" id="medical_documents" name="medical_documents[]" class="form-control" accept=".jpg,.jpeg,.png,.pdf" multiple required>
                                <span class="form-text text-muted small">Pode selecionar múltiplos ficheiros (Laudos médicos, orçamentos, exames). Máximo 10MB cada.</span>
                            </div>
                        </div>

                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" name="terms_accepted" value="1" id="terms_check" {{ old('terms_accepted') ? 'checked' : '' }} required>
                            <label class="form-check-label small text-secondary" for="terms_check">
                                Declaro sob compromisso de honra que todas as informações prestadas e documentos anexados são autênticos e verdadeiros, estando ciente de que a falsificação constitui crime punível por lei.
                            </label>
                        </div>

                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary px-4 btn-prev-step"><i class="bi bi-arrow-left me-1"></i> Anterior</button>
                            <button type="submit" class="btn btn-gold-fundme btn-lg px-5 py-3 shadow">
                                <i class="bi bi-send-fill me-2"></i> Submeter Solicitação para Verificação
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
