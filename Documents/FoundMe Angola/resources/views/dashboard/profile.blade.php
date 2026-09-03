@extends('layouts.app')

@section('title', 'Definições do Perfil — FundMe Angola')

@section('content')
<div class="bg-light py-4 border-bottom mb-4">
    <div class="container">
        <h1 class="font-heading h2 mb-1">Definições da Conta & Perfil</h1>
        <p class="text-muted mb-0">Atualize os seus dados de contacto e credenciais de acesso</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <form action="{{ route('dashboard.profile.update') }}" method="POST">
                    @csrf

                    <h5 class="font-heading mb-3"><i class="bi bi-person-lines-fill text-primary me-2"></i> Dados Pessoais</h5>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nome Completo *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Correio Eletrónico (Email)</label>
                        <input type="email" class="form-control bg-light" value="{{ $user->email }}" disabled>
                        <span class="form-text text-muted small">O email de registo não pode ser alterado diretamente por motivos de segurança.</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Telemóvel (WhatsApp) *</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control" required>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Província</label>
                            <select name="province" class="form-select">
                                <option value="">Selecione...</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov }}" {{ $user->province === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Município</label>
                            <input type="text" name="municipality" value="{{ old('municipality', $user->municipality) }}" class="form-control">
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="font-heading mb-3"><i class="bi bi-lock-fill text-danger me-2"></i> Alterar Palavra-passe</h5>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Palavra-passe Atual</label>
                        <input type="password" name="current_password" class="form-control" placeholder="Deixe em branco para não alterar">
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nova Palavra-passe</label>
                            <input type="password" name="password" class="form-control" placeholder="Mínimo 8 caracteres">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Confirmar Nova Palavra-passe</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repita a nova palavra-passe">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-fundme btn-lg w-100 py-3 shadow">
                        <i class="bi bi-check-circle-fill me-2"></i> Guardar Alterações do Perfil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
