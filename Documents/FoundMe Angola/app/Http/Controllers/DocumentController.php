<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CampaignDocument;
use App\Models\AuditLog;

class DocumentController extends Controller
{
    public function download(CampaignDocument $document)
    {
        $user = auth()->user();

        // Security check
        if (!$user) {
            abort(401, 'Autenticação necessária para visualizar este documento privado.');
        }

        $isOwner = ($user->id === $document->uploaded_by || $user->id === $document->campaign->user_id);
        $isStaff = ($user->isVerifier() || $user->isAdmin());

        if (!$isOwner && !$isStaff) {
            AuditLog::log(
                action: 'unauthorized_document_access_attempt',
                entityType: CampaignDocument::class,
                entityId: $document->id
            );
            abort(403, 'Acesso não autorizado a documentos confidenciais.');
        }

        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'Ficheiro não encontrado.');
        }

        AuditLog::log(
            action: 'private_document_accessed',
            entityType: CampaignDocument::class,
            entityId: $document->id,
            newValues: ['document_type' => $document->document_type, 'original_name' => $document->original_name]
        );

        return Storage::disk('local')->download($document->file_path, $document->original_name);
    }
}
