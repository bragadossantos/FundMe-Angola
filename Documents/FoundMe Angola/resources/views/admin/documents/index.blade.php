@extends('layouts.admin')

@section('title', 'Cofre de Documentos Privados — Admin FundMe Angola')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2 font-heading fw-bold mb-1">Cofre de Documentos Privados</h1>
        <p class="text-muted mb-0">Auditoria centralizada dos ficheiros confidenciais submetidos para verificação</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-light">
                <tr>
                    <th>Data Envio</th>
                    <th>Campanha</th>
                    <th>Tipo de Documento</th>
                    <th>Nome do Ficheiro</th>
                    <th>Enviado Por</th>
                    <th>Sigilo</th>
                    <th>Ações de Download</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                    <tr>
                        <td class="small">{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($doc->campaign)
                                <a href="{{ route('admin.campaigns.show', $doc->campaign->id) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ Str::limit($doc->campaign->title, 35) }}
                                </a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td><span class="badge bg-info text-dark">{{ $doc->document_type_name }}</span></td>
                        <td class="small font-monospace">{{ $doc->original_name }}</td>
                        <td class="small">{{ $doc->uploader ? $doc->uploader->name : 'Solicitante' }}</td>
                        <td><span class="badge bg-warning text-dark"><i class="bi bi-lock-fill me-1"></i> Privado</span></td>
                        <td>
                            <a href="{{ route('documents.download', $doc->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="bi bi-download me-1"></i> Descarregar
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $documents->links() }}
    </div>
</div>
@endsection
