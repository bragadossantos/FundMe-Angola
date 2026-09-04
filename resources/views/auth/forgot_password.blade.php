@extends('layouts.app')

@section('title', 'Recuperar Palavra-passe — FundMe Angola')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="logo-icon bg-warning text-white rounded-circle d-inline-flex p-3 mb-2 shadow-sm">
                            <i class="bi bi-key-fill fs-2"></i>
                        </div>
                        <h3 class="font-heading fw-bold mb-1">Recuperar Acesso</h3>
                        <p class="text-muted small">Insira o seu email registado para receber as instruções</p>
                    </div>

                    <form action="{{ route('forgot_password') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Endereço de Correio Eletrónico (Email)</label>
                            <input type="email" name="email" class="form-control bg-light" placeholder="exemplo@dominio.ao" required autofocus>
                        </div>

                        <button type="submit" class="btn btn-primary-fundme btn-lg w-100 py-3 mb-3 shadow">
                            <i class="bi bi-envelope-fill me-2"></i> Enviar Instruções de Redefinição
                        </button>
                    </form>

                    <div class="text-center text-muted small border-top pt-3">
                        <a href="{{ route('login') }}" class="text-secondary text-decoration-none"><i class="bi bi-arrow-left me-1"></i> Voltar ao Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
