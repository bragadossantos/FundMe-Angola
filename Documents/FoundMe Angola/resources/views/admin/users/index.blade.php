@extends('layouts.admin')

@section('title', 'Gestão de Utilizadores — Admin FundMe Angola')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 font-heading fw-bold mb-1">Gestão de Utilizadores & Permissões RBAC</h1>
        <p class="text-muted mb-0">Atribuição de funções (Admin, Verificador, Solicitante, Doador) e suspensão de contas</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <form action="{{ route('admin.users') }}" method="GET" class="row g-3 mb-4">
        <div class="col-md-6">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Pesquisar por nome ou email...">
        </div>
        <div class="col-md-4">
            <select name="role" class="form-select">
                <option value="">Todas as Funções</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                <option value="verifier" {{ request('role') === 'verifier' ? 'selected' : '' }}>Verificador</option>
                <option value="applicant" {{ request('role') === 'applicant' ? 'selected' : '' }}>Solicitante</option>
                <option value="donor" {{ request('role') === 'donor' ? 'selected' : '' }}>Doador</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-light">
                <tr>
                    <th>Utilizador</th>
                    <th>Email</th>
                    <th>Telemóvel</th>
                    <th>Província</th>
                    <th>Função (Role)</th>
                    <th>Estado</th>
                    <th>Ações RBAC</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="fw-bold">{{ $user->name }}</td>
                        <td class="small">{{ $user->email }}</td>
                        <td class="small">{{ $user->phone ?: 'N/A' }}</td>
                        <td class="small">{{ $user->province ?: 'N/A' }}</td>
                        <td>
                            <span class="badge bg-dark">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($user->status) }}</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.users.update_role', $user->id) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <select name="role" class="form-select form-select-sm" style="width: 120px;">
                                    <option value="donor" {{ $user->role === 'donor' ? 'selected' : '' }}>Doador</option>
                                    <option value="applicant" {{ $user->role === 'applicant' ? 'selected' : '' }}>Solicitante</option>
                                    <option value="verifier" {{ $user->role === 'verifier' ? 'selected' : '' }}>Verificador</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <select name="status" class="form-select form-select-sm" style="width: 100px;">
                                    <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Ativo</option>
                                    <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>Suspenso</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-save"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $users->links() }}
    </div>
</div>
@endsection
