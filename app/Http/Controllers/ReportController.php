<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Report;
use App\Models\AuditLog;

class ReportController extends Controller
{
    public function store(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'reason' => 'required|string|in:suspected_fraud,false_information,misused_images,duplicate_campaign,other',
            'description' => 'required|string|min:20',
            'reporter_name' => 'nullable|string|max:255',
            'reporter_email' => 'nullable|email|max:255',
            'evidence_file' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        $evidencePath = null;
        if ($request->hasFile('evidence_file')) {
            $evidencePath = $request->file('evidence_file')->store('private/reports/' . $campaign->id, 'local');
        }

        $user = auth()->user();

        $report = Report::create([
            'campaign_id' => $campaign->id,
            'reporter_id' => $user ? $user->id : null,
            'reporter_name' => $validated['reporter_name'] ?? ($user ? $user->name : 'Denunciante'),
            'reporter_email' => $validated['reporter_email'] ?? ($user ? $user->email : null),
            'reason' => $validated['reason'],
            'description' => $validated['description'],
            'evidence_file_path' => $evidencePath,
            'status' => 'pending',
        ]);

        AuditLog::log(
            action: 'campaign_reported',
            entityType: Campaign::class,
            entityId: $campaign->id,
            newValues: ['reason' => $validated['reason']]
        );

        return redirect()->route('campaigns.show', $campaign->slug)
            ->with('success', 'A sua denúncia foi submetida sob sigilo e será analisada com a máxima prioridade pela nossa equipa.');
    }
}
