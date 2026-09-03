@extends('layouts.app')

@section('title', 'Criar Conta — FundMe Angola')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="logo-icon bg-success text-white rounded-circle d-inline-flex p-3 mb-2 shadow-sm">
                            <i class="bi bi-person-plus-fill fs-2"></i>
                        </div>
                        <h3 class="font-heading fw-bold mb-1">Registe-se na FundMe Angola</h3>
                        <p class="text-muted small">Junte-se à comunidade de solidariedade médica em Angola</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger border-0 small mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nome Completo *</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control bg-light" placeholder="Ex: Teresa Agostinho Neto" required autofocus>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Correio Eletrónico (Email) *</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control bg-light" placeholder="email@dominio.ao" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Telemóvel (WhatsApp) *</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control bg-light" placeholder="+244 923 000 000" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Província</label>
                                <input type="text" name="province" value="{{ old('province') }}" class="form-control bg-light" placeholder="Ex: Luanda, Huambo">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Município</label>
                                <input type="text" name="municipality" value="{{ old('municipality') }}" class="form-control bg-light" placeholder="Ex: Talatona, Lobito">
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Palavra-passe *</label>
                                <input type="password" name="password" class="form-control bg-light" placeholder="Mínimo 8 caracteres" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Confirmar Palavra-passe *</label>
                                <input type="password" name="password_confirmation" class="form-control bg-light" placeholder="Repita a palavra-passe" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-gold-fundme btn-lg w-100 py-3 mb-3 shadow">
                            <i class="bi bi-check-circle-fill me-2"></i> Criar Conta de Utilizador
                        </button>
                    </form>

                    <div class="text-center text-muted small border-top pt-3">
                        Já tem uma conta? <a href="{{ route('login') }}" class="fw-bold text-primary text-decoration-none">Iniciar Sessão</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
