@extends('layouts.admin')

@section('title', 'Gestão de Denúncias — Admin FundMe Angola')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 font-heading fw-bold mb-1">Gestão de Denúncias & Fraudes</h1>
        <p class="text-muted mb-0">Análise sigilosa de reclamações enviadas pela comunidade</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-light">
                <tr>
                    <th>Data</th>
                    <th>Campanha Denunciada</th>
                    <th>Motivo</th>
                    <th>Descrição dos Factos</th>
                    <th>Denunciante</th>
                    <th>Estado</th>
                    <th>Ações de Moderação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $rep)
                    <tr>
                        <td class="small">{{ $rep->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($rep->campaign)
                                <a href="{{ route('admin.campaigns.show', $rep->campaign->id) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ Str::limit($rep->campaign->title, 35) }}
                                </a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td><span class="badge bg-warning text-dark">{{ $rep->reason_label }}</span></td>
                        <td class="small text-secondary">{{ Str::limit($rep->description, 60) }}</td>
                        <td class="small">{{ $rep->reporter_name }} ({{ $rep->reporter_email ?: 'Sigiloso' }})</td>
                        <td><span class="badge bg-secondary">{{ $rep->status_label }}</span></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#reportModal{{ $rep->id }}">
                                Moderar
                            </button>
                        </td>
                    </tr>

                    <!-- Report Moderation Modal -->
                    <div class="modal fade" id="reportModal{{ $rep->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <form action="{{ route('admin.reports.update_status', $rep->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header bg-dark text-white">
                                        <h5 class="modal-header-title modal-title font-heading fw-bold">Moderar Denúncia #{{ $rep->id }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <h6 class="fw-bold">Descrição Completa da Denúncia:</h6>
                                        <div class="p-3 bg-light rounded-3 text-secondary small mb-3">
                                            {{ $rep->description }}
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Alterar Estado da Denúncia *</label>
                                            <select name="status" class="form-select" required>
                                                <option value="pending" {{ $rep->status === 'pending' ? 'selected' : '' }}>Pendente</option>
                                                <option value="under_review" {{ $rep->status === 'under_review' ? 'selected' : '' }}>Em Análise</option>
                                                <option value="resolved" {{ $rep->status === 'resolved' ? 'selected' : '' }}>Resolvida (Procedente)</option>
                                                <option value="dismissed" {{ $rep->status === 'dismissed' ? 'selected' : '' }}>Arquivada (Improcedente)</option>
                                            </select>
                                        </div>

                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="suspend_campaign" value="1" id="suspend_check{{ $rep->id }}">
                                            <label class="form-check-label fw-bold text-danger small" for="suspend_check{{ $rep->id }}">
                                                Suspender Imediatamente a Campanha Denunciada
                                            </label>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold small">Notas do Moderador</label>
                                            <textarea name="admin_notes" class="form-control" rows="3" placeholder="Registe os detalhes da decisão...">{{ $rep->admin_notes }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary px-4">Salvar Moderação</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $reports->links() }}
    </div>
</div>
@endsection
