<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\User;
use App\Models\Donation;
use App\Models\Report;
use App\Models\CampaignVerification;
use App\Models\CampaignDocument;
use App\Models\PaymentDestination;
use App\Models\FundDisbursement;
use App\Models\AuditLog;
use App\Models\Hospital;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $pendingCampaignsCount = Campaign::whereIn('status', ['pending_review', 'under_review', 'waiting_documents'])->count();
        $activeCampaignsCount = Campaign::where('status', 'published')->count();
        $completedCampaignsCount = Campaign::whereIn('status', ['goal_reached', 'payment_processing', 'completed'])->count();
        $totalRaised = Donation::where('status', 'paid')->sum('amount');
        $pendingReportsCount = Report::where('status', 'pending')->count();

        $recentCampaigns = Campaign::with(['user', 'beneficiary'])->latest()->take(5)->get();
        $recentDonations = Donation::with('campaign')->where('status', 'paid')->latest()->take(5)->get();

        return view('admin.index', compact(
            'totalUsers',
            'pendingCampaignsCount',
            'activeCampaignsCount',
            'completedCampaignsCount',
            'totalRaised',
            'pendingReportsCount',
            'recentCampaigns',
            'recentDonations'
        ));
    }

    public function campaigns(Request $request)
    {
        $query = Campaign::with(['user', 'beneficiary']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $campaigns = $query->latest()->paginate(15)->withQueryString();

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function showCampaign(Campaign $campaign)
    {
        $campaign->load([
            'user',
            'beneficiary',
            'hospital',
            'fundPlans',
            'documents',
            'verifications.verifier',
            'updates',
            'paymentDestination',
            'disbursements',
            'reports'
        ]);

        $hospitals = Hospital::where('is_verified', true)->get();

        return view('admin.campaigns.show', compact('campaign', 'hospitals'));
    }

    public function updateCampaignStatus(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:approved,published,rejected,waiting_documents,suspended,closed',
            'payment_destination_type' => 'nullable|string|in:hospital_direct,beneficiary_transfer,split_payment',
            'rejection_reason' => 'nullable|string',
            'internal_notes' => 'required|string|min:5',

            // Destination details
            'institution_or_payee_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'mobile_money_number' => 'nullable|string|max:255',
            'invoice_reference' => 'nullable|string|max:255',
            'authorized_amount' => 'nullable|numeric',
        ]);

        $oldStatus = $campaign->status;
        $campaign->status = $validated['status'];

        if ($validated['status'] === 'approved' || $validated['status'] === 'published') {
            $campaign->published_at = $campaign->published_at ?? now();
            $campaign->verification_badge = true;
            if (!empty($validated['payment_destination_type'])) {
                $campaign->payment_destination_type = $validated['payment_destination_type'];
            }
        }

        if ($validated['status'] === 'rejected') {
            $campaign->rejection_reason = $validated['rejection_reason'] ?? null;
        }

        $campaign->save();

        // Log Verification Decision
        CampaignVerification::create([
            'campaign_id' => $campaign->id,
            'verifier_id' => auth()->id(),
            'action' => $validated['status'],
            'internal_notes' => $validated['internal_notes'],
        ]);

        // Save Private Payment Destination Config if provided
        if (!empty($validated['payment_destination_type']) && !empty($validated['institution_or_payee_name'])) {
            PaymentDestination::updateOrCreate(
                ['campaign_id' => $campaign->id],
                [
                    'destination_type' => $validated['payment_destination_type'],
                    'institution_or_payee_name' => $validated['institution_or_payee_name'],
                    'bank_name' => $validated['bank_name'] ?? null,
                    'account_number' => $validated['account_number'] ?? null,
                    'iban' => $validated['iban'] ?? null,
                    'mobile_money_number' => $validated['mobile_money_number'] ?? null,
                    'invoice_reference' => $validated['invoice_reference'] ?? null,
                    'authorized_amount' => $validated['authorized_amount'] ?? $campaign->target_amount,
                    'private_notes' => $validated['internal_notes'],
                ]
            );
        }

        AuditLog::log(
            action: 'campaign_status_updated',
            entityType: Campaign::class,
            entityId: $campaign->id,
            oldValues: ['status' => $oldStatus],
            newValues: ['status' => $campaign->status, 'destination_type' => $campaign->payment_destination_type]
        );

        return back()->with('success', 'Estado da campanha atualizado com sucesso para ' . $campaign->status_label);
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,verifier,applicant,donor',
            'status' => 'required|in:active,suspended',
        ]);

        $old = ['role' => $user->role, 'status' => $user->status];
        $user->update($validated);

        AuditLog::log(
            action: 'user_role_updated',
            entityType: User::class,
            entityId: $user->id,
            oldValues: $old,
            newValues: $validated
        );

        return back()->with('success', 'Dados do utilizador atualizados.');
    }

    public function donations()
    {
        $donations = Donation::with(['campaign', 'user'])->latest()->paginate(15);
        return view('admin.donations.index', compact('donations'));
    }

    public function payments()
    {
        // Campaigns that reached goal or processing payment
        $campaigns = Campaign::whereIn('status', ['goal_reached', 'payment_processing', 'completed'])
            ->with(['paymentDestination', 'disbursements', 'beneficiary'])
            ->latest()
            ->paginate(15);

        return view('admin.payments.index', compact('campaigns'));
    }

    public function disburse(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'transaction_reference' => 'required|string|max:255',
            'public_summary_update' => 'required|string|min:10',
            'proof_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $destination = $campaign->paymentDestination;
        if (!$destination) {
            return back()->with('error', 'Por favor configure primeiro o Método de Destino dos Fundos para esta campanha.');
        }

        $proofPath = null;
        if ($request->hasFile('proof_file')) {
            $proofPath = $request->file('proof_file')->store('private/disbursements/' . $campaign->id, 'local');
        }

        $disbursement = FundDisbursement::create([
            'campaign_id' => $campaign->id,
            'payment_destination_id' => $destination->id,
            'admin_id' => auth()->id(),
            'amount' => $validated['amount'],
            'transaction_reference' => $validated['transaction_reference'],
            'disbursement_date' => now(),
            'proof_document_path' => $proofPath,
            'public_summary_update' => $validated['public_summary_update'],
            'status' => 'completed',
        ]);

        // Update Campaign status to COMPLETED
        $campaign->status = 'completed';
        $campaign->closed_at = now();
        $campaign->save();

        // Create Public Campaign Update for transparency timeline
        $campaign->updates()->create([
            'user_id' => auth()->id(),
            'title' => '✅ Processo de Destinação dos Fundos Concluído',
            'content' => $validated['public_summary_update'] . "\n\nReferência da Operação: " . $validated['transaction_reference'],
            'is_public' => true,
            'approved_by' => auth()->id(),
        ]);

        AuditLog::log(
            action: 'funds_disbursed',
            entityType: Campaign::class,
            entityId: $campaign->id,
            newValues: ['amount' => $disbursement->amount, 'ref' => $disbursement->transaction_reference]
        );

        return back()->with('success', 'Destinação dos fundos registada com sucesso! A campanha foi marcada como Concluída.');
    }

    public function reports()
    {
        $reports = Report::with(['campaign', 'reporter'])->latest()->paginate(15);
        return view('admin.reports.index', compact('reports'));
    }

    public function updateReport(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,under_review,resolved,dismissed',
            'admin_notes' => 'nullable|string',
            'suspend_campaign' => 'nullable|boolean',
        ]);

        $report->status = $validated['status'];
        $report->admin_notes = $validated['admin_notes'] ?? null;
        $report->save();

        if ($request->has('suspend_campaign') && $report->campaign) {
            $report->campaign->update(['status' => 'suspended']);
        }

        AuditLog::log(
            action: 'report_updated',
            entityType: Report::class,
            entityId: $report->id,
            newValues: ['status' => $report->status]
        );

        return back()->with('success', 'Denúncia atualizada.');
    }

    public function documents()
    {
        $documents = CampaignDocument::with(['campaign', 'uploader'])->latest()->paginate(15);
        return view('admin.documents.index', compact('documents'));
    }

    public function logs()
    {
        $logs = AuditLog::with('user')->latest()->paginate(20);
        return view('admin.logs.index', compact('logs'));
    }
}
