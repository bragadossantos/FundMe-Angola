@extends('layouts.app')

@section('title', 'Redefinir Palavra-passe — FundMe Angola')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="logo-icon bg-warning text-white rounded-circle d-inline-flex p-3 mb-2 shadow-sm">
                            <i class="bi bi-shield-lock-fill fs-2"></i>
                        </div>
                        <h3 class="font-heading fw-bold mb-1">Definir Nova Palavra-passe</h3>
                        <p class="text-muted small">Escolha uma nova palavra-passe para a sua conta</p>
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

                    <form action="{{ route('reset_password') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="reset_email" class="form-label small fw-bold">Endereço de Correio Eletrónico (Email)</label>
                            <input type="email" id="reset_email" name="email" value="{{ old('email', $email) }}" class="form-control bg-light" placeholder="exemplo@dominio.ao" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="reset_password" class="form-label small fw-bold">Nova Palavra-passe</label>
                            <input type="password" id="reset_password" name="password" class="form-control bg-light" placeholder="Mínimo 8 caracteres" required minlength="8">
                        </div>

                        <div class="mb-4">
                            <label for="reset_password_confirmation" class="form-label small fw-bold">Confirmar Nova Palavra-passe</label>
                            <input type="password" id="reset_password_confirmation" name="password_confirmation" class="form-control bg-light" placeholder="Repita a palavra-passe" required minlength="8">
                        </div>

                        <button type="submit" class="btn btn-primary-fundme btn-lg w-100 py-3 mb-3 shadow">
                            <i class="bi bi-check-circle-fill me-2"></i> Redefinir Palavra-passe
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
