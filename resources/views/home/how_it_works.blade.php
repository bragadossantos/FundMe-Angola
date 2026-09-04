@extends('layouts.app')

@section('title', 'Como Funciona — FundMe Angola')

@section('content')
<div class="bg-light text-dark py-5 mb-5 border-bottom">
    <div class="container text-center py-4">
        <h1 class="display-5 font-heading text-dark fw-bold">Como Funciona a FundMe Angola</h1>
        <p class="lead text-dark max-w-700 mx-auto opacity-75">
            Conheça a arquitetura de segurança, privacidade e transparência por trás da nossa plataforma de crowdfunding médico em Angola.
        </p>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-5">
        <!-- For Donors -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                <div class="card-body">
                    <span class="badge bg-success bg-opacity-25 text-success p-2 mb-3 fw-bold"><i class="bi bi-heart-fill me-1"></i> Para Doadores</span>
                    <h3 class="font-heading text-dark mb-4">Como Apoiar uma Causa Médica</h3>

                    <div class="d-flex gap-3 mb-4">
                        <div class="fs-4 fw-bold text-success bg-light rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">1</div>
                        <div>
                            <h5 class="fw-bold text-dark">Escolha uma Campanha Verificada</h5>
                            <p class="text-dark opacity-75 small">Navegue pelas campanhas ativas, filtre por província ou categoria (cirurgia, tratamentos, medicamentos) e leia a história do paciente.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="fs-4 fw-bold text-success bg-light rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">2</div>
                        <div>
                            <h5 class="fw-bold text-dark">Escolha o Valor e Método de Pagamento</h5>
                            <p class="text-dark opacity-75 small">Contribua a partir de qualquer valor em Kz via Multicaixa Express, Transferência Bancária ou KwanzaPay em ambiente 100% seguro.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="fs-4 fw-bold text-success bg-light rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">3</div>
                        <div>
                            <h5 class="fw-bold text-dark">Acompanhe a Transparência</h5>
                            <p class="text-dark opacity-75 small">Receba atualizações sobre a realização do tratamento e o relatório público de liquidação dos fundos ao hospital.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- For Applicants -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
                <div class="card-body">
                    <span class="badge bg-warning bg-opacity-25 text-dark p-2 mb-3 fw-bold"><i class="bi bi-plus-circle-fill me-1"></i> Para Solicitantes</span>
                    <h3 class="font-heading text-dark mb-4">Como Pedir Ajuda para Tratamento</h3>

                    <div class="d-flex gap-3 mb-4">
                        <div class="fs-4 fw-bold text-warning bg-light rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">1</div>
                        <div>
                            <h5 class="fw-bold text-dark">Preencha a Solicitação Gratuita</h5>
                            <p class="text-dark opacity-75 small">Registe-se na plataforma, descreva a necessidade médica do paciente e indique o plano financeiro estimativo.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="fs-4 fw-bold text-warning bg-light rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">2</div>
                        <div>
                            <h5 class="fw-bold text-dark">Envio Seguro de Documentos Privados</h5>
                            <p class="text-dark opacity-75 small">Submeta relatórios médicos, laudos e BI. Estes documentos são estritamente confidenciais e acessíveis apenas pela equipa de verificação.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="fs-4 fw-bold text-warning bg-light rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">3</div>
                        <div>
                            <h5 class="fw-bold text-dark">Verificação & Publicação</h5>
                            <p class="text-dark opacity-75 small">Após a validação, a sua campanha é ativada e pode ser partilhada nas redes sociais para receber angariações.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security & Privacy Sections -->
    <div id="verificacao" class="mt-5 pt-4">
        <div class="p-5 bg-white rounded-4 shadow-sm border">
            <h3 class="font-heading text-dark mb-3"><i class="bi bi-shield-check text-success me-2"></i> Nosso Compromisso de Verificação & Proteção de Dados</h3>
            <p class="text-dark opacity-75">
                A FundMe Angola não é uma rede de divulgação informal. Cada campanha aprovada passa por rigorosa triagem para combater fraudes e proteger a imagem e a privacidade de pacientes vulneráveis.
            </p>
            <div class="row g-4 mt-2">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3">
                        <h6 class="fw-bold text-dark"><i class="bi bi-lock-fill text-primary me-1"></i> Armazenamento Privado de Ficheiros Médicos</h6>
                        <p class="small text-dark opacity-75 mb-0">Relatórios médicos, dados bancários e BI não são disponibilizados publicamente no site. O acesso é exclusivo do administrador e verificadores credenciados.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3">
                        <h6 class="fw-bold text-dark"><i class="bi bi-building-check text-success me-1"></i> Destinação Direta às Instituições</h6>
                        <p class="small text-dark opacity-75 mb-0">Sempre que possível, os pagamentos acumulados são efetuados diretamente às contas oficiais dos hospitais ou clínicas com base nas faturas apresentadas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
