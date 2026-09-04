@extends('layouts.app')

@section('title', 'Iniciar Sessão — FundMe Angola')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="logo-icon bg-primary text-white rounded-circle d-inline-flex p-3 mb-2 shadow-sm">
                            <i class="bi bi-person-lock fs-2"></i>
                        </div>
                        <h3 class="font-heading fw-bold mb-1">Iniciar Sessão</h3>
                        <p class="text-muted small">Aceda à sua conta da FundMe Angola</p>
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

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Endereço de Correio Eletrónico (Email)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control bg-light" placeholder="exemplo@dominio.ao" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label small fw-bold mb-0">Palavra-passe</label>
                                <a href="{{ route('forgot_password') }}" class="small text-primary text-decoration-none">Esqueceu a palavra-passe?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                                <input type="password" name="password" class="form-control bg-light" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small text-muted" for="remember">Lembrar-me neste dispositivo</label>
                        </div>

                        <button type="submit" class="btn btn-primary-fundme btn-lg w-100 py-3 mb-3 shadow">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Entrar na Conta
                        </button>
                    </form>

                    <div class="text-center text-muted small border-top pt-3">
                        Ainda não tem conta? <a href="{{ route('register') }}" class="fw-bold text-success text-decoration-none">Criar Conta Gratuita</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
