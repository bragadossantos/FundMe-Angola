@extends('layouts.app')

@section('title', 'Finalizar Doação — FundMe Angola')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-emerald-subtle border-0 p-4 text-center">
                    <div class="rounded-circle bg-success text-white d-inline-flex p-3 mb-2 shadow-sm">
                        <i class="bi bi-credit-card-2-front-fill fs-2"></i>
                    </div>
                    <h3 class="font-heading text-success mb-1">Processamento de Doação</h3>
                    <p class="text-muted small mb-0">Ambiente Seguro de Pagamento Solidário</p>
                </div>

                <div class="card-body p-4">
                    <div class="p-3 bg-light rounded-3 mb-4">
                        <span class="text-muted small d-block">Campanha Apoiada:</span>
                        <h5 class="fw-bold text-dark mb-2">{{ $donation->campaign->title }}</h5>
                        <div class="d-flex justify-content-between align-items-center border-top pt-2 mt-2">
                            <span class="text-muted small">Valor da Contribuição:</span>
                            <span class="fw-bold fs-4 text-success">{{ number_format($donation->amount, 2, ',', '.') }} Kz</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <span class="text-muted small d-block mb-1">Referência da Transação:</span>
                        <div class="p-3 bg-dark text-warning font-monospace rounded-3 d-flex justify-content-between align-items-center fw-bold fs-5">
                            <span>{{ $donation->payment_reference }}</span>
                            <button class="btn btn-sm btn-outline-warning btn-copy-link" data-link="{{ $donation->payment_reference }}">
                                <i class="bi bi-copy"></i>
                            </button>
                        </div>
                    </div>

                    @if($donation->payment_method === 'multicaixa_express')
                        <div class="alert alert-info border-0 mb-4">
                            <h6><i class="bi bi-phone me-1"></i> Instruções Multicaixa Express:</h6>
                            <ol class="small mb-0 ps-3">
                                <li>Abra a aplicação <strong>Multicaixa Express</strong> no seu telemóvel.</li>
                                <li>Selecione a opção <strong>Pagamentos por Referência</strong>.</li>
                                <li>Introduza a Referência acima indicada e confirme o montante de <strong>{{ number_format($donation->amount, 2, ',', '.') }} Kz</strong>.</li>
                            </ol>
                        </div>
                    @elseif($donation->payment_method === 'bank_transfer')
                        <div class="alert alert-info border-0 mb-4">
                            <h6><i class="bi bi-bank me-1"></i> Dados para Transferência Bancária:</h6>
                            <p class="small mb-1"><strong>Banco:</strong> BAI / BPC (Conta Escrow FundMe Angola)</p>
                            <p class="small mb-1"><strong>IBAN:</strong> AO06.0040.0000.1122.3344.5566.7</p>
                            <p class="small mb-0"><strong>Nota:</strong> Inclua a referência <strong>{{ $donation->payment_reference }}</strong> no descritivo da transferência.</p>
                        </div>
                    @else
                        <div class="alert alert-success border-0 mb-4">
                            <h6><i class="bi bi-shield-check me-1"></i> Modo Demonstrativo / Sandbox Activo:</h6>
                            <p class="small mb-0">
                                Para efeitos de teste e demonstração do sistema, pode confirmar o pagamento instantaneamente clicando no botão abaixo.
                            </p>
                        </div>
                    @endif

                    <form action="{{ route('donations.confirm', $donation->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-gold-fundme btn-lg w-100 py-3 shadow">
                            <i class="bi bi-check-circle-fill me-2"></i> Confirmar Pagamento da Doação
                        </button>
                    </form>
                </div>

                <div class="card-footer bg-light border-0 p-3 text-center text-muted small">
                    <i class="bi bi-lock-fill me-1"></i> Transação encriptada e protegida pela FundMe Angola
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
