<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Campaign;
use App\Models\Beneficiary;
use App\Models\Hospital;
use App\Models\CampaignFundPlan;
use App\Models\CampaignDocument;
use App\Models\AuditLog;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $query = Campaign::published()->with(['beneficiary']);

        // Search Keyword
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('location_province', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Province Filter
        if ($request->filled('province')) {
            $query->where('location_province', $request->province);
        }

        // Status Filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('status', 'published');
                    break;
                case 'goal_reached':
                    $query->whereIn('status', ['goal_reached', 'payment_processing', 'completed']);
                    break;
                case 'featured':
                    $query->where('is_featured', true);
                    break;
            }
        }

        // Sorting
        switch ($request->sort) {
            case 'urgent':
                $query->orderBy('created_at', 'asc');
                break;
            case 'most_raised':
                $query->orderBy('raised_amount', 'desc');
                break;
            default: // recent
                $query->latest('published_at');
                break;
        }

        $campaigns = $query->paginate(9)->withQueryString();

        $provinces = [
            'Bengo', 'Benguela', 'Bié', 'Cabinda', 'Cuando Cubango', 'Cuanza Norte',
            'Cuanza Sul', 'Cunene', 'Huambo', 'Huíla', 'Luanda', 'Lunda Norte',
            'Lunda Sul', 'Malanje', 'Moxico', 'Namibe', 'Uíge', 'Zaire'
        ];

        return view('campaigns.index', compact('campaigns', 'provinces'));
    }

    public function show($slug)
    {
        $campaign = Campaign::where('slug', $slug)
            ->with([
                'beneficiary',
                'hospital',
                'fundPlans',
                'updates' => fn($q) => $q->where('is_public', true)->latest(),
                'donations' => fn($q) => $q->where('status', 'paid')->latest()->take(20),
                'user'
            ])
            ->firstOrFail();

        // If not published, check if user is author/admin/verifier
        if (!in_array($campaign->status, ['published', 'goal_reached', 'payment_processing', 'completed'])) {
            $user = auth()->user();
            if (!$user || ($user->id !== $campaign->user_id && !$user->isVerifier() && !$user->isAdmin())) {
                abort(403, 'Esta campanha está sob análise e ainda não está visível publicamente.');
            }
        }

        return view('campaigns.show', compact('campaign'));
    }

    public function create()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('info', 'Por favor, inicie sessão ou crie uma conta para solicitar uma campanha.');
        }

        $hospitals = Hospital::where('is_verified', true)->get();
        $provinces = [
            'Bengo', 'Benguela', 'Bié', 'Cabinda', 'Cuando Cubango', 'Cuanza Norte',
            'Cuanza Sul', 'Cunene', 'Huambo', 'Huíla', 'Luanda', 'Lunda Norte',
            'Lunda Sul', 'Malanje', 'Moxico', 'Namibe', 'Uíge', 'Zaire'
        ];

        return view('campaigns.create', compact('hospitals', 'provinces'));
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Validate Form Inputs
        $validated = $request->validate([
            // Applicant & Patient
            'beneficiary_name' => 'required|string|max:255',
            'age_range' => 'required|string',
            'relation_to_applicant' => 'required|string',
            'location_province' => 'required|string',
            'location_municipality' => 'nullable|string|max:255',
            'is_identity_hidden' => 'nullable|boolean',

            // Medical
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'hospital_id' => 'nullable|exists:hospitals,id',
            'hospital_name' => 'nullable|string|max:255',
            'treatment_location' => 'required|in:angola,estrangeiro',
            'expected_treatment_date' => 'nullable|date',
            'short_description' => 'required|string|max:500',
            'story' => 'required|string|min:50',

            // Financial
            'target_amount' => 'required|numeric|min:1000',
            'fund_item_name' => 'nullable|array',
            'fund_item_amount' => 'nullable|array',

            // Files (Max 10MB each)
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'medical_documents.*' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:10240',
            'identity_document' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:10240',
        ]);

        // 1. Create Beneficiary
        $beneficiary = Beneficiary::create([
            'user_id' => $user->id,
            'full_name' => $validated['beneficiary_name'],
            'age_range' => $validated['age_range'],
            'relation_to_applicant' => $validated['relation_to_applicant'],
            'location_province' => $validated['location_province'],
            'location_municipality' => $validated['location_municipality'] ?? null,
            'is_identity_hidden' => $request->has('is_identity_hidden'),
        ]);

        // 2. Slug generation
        $slugBase = Str::slug($validated['title']);
        $slug = $slugBase;
        $counter = 1;
        while (Campaign::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $counter++;
        }

        // 3. Featured Image Upload (Public image authorized for campaign)
        $featuredImagePath = null;
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $fileName = 'featured_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $featuredImagePath = $file->storeAs('campaigns/featured', $fileName, 'public');
        }

        // 4. Create Campaign
        $campaign = Campaign::create([
            'user_id' => $user->id,
            'beneficiary_id' => $beneficiary->id,
            'hospital_id' => $validated['hospital_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $slug,
            'short_description' => $validated['short_description'],
            'story' => $validated['story'],
            'category' => $validated['category'],
            'target_amount' => $validated['target_amount'],
            'raised_amount' => 0.00,
            'currency' => 'Kz',
            'status' => 'pending_review', // Starts in PENDING_REVIEW as per specs
            'location_province' => $validated['location_province'],
            'location_municipality' => $validated['location_municipality'] ?? null,
            'hospital_name' => $validated['hospital_name'] ?? null,
            'treatment_location' => $validated['treatment_location'],
            'expected_treatment_date' => $validated['expected_treatment_date'] ?? null,
            'featured_image' => $featuredImagePath,
        ]);

        // 5. Store Itemized Financial Plan
        if (!empty($request->fund_item_name) && is_array($request->fund_item_name)) {
            foreach ($request->fund_item_name as $index => $name) {
                if (!empty($name) && isset($request->fund_item_amount[$index])) {
                    CampaignFundPlan::create([
                        'campaign_id' => $campaign->id,
                        'item_name' => $name,
                        'estimated_amount' => (float)$request->fund_item_amount[$index],
                    ]);
                }
            }
        }

        // 6. Secure Upload of Private Confidential Documents
        // Strict privacy requirement: stored in non-public private location
        if ($request->hasFile('identity_document')) {
            $idFile = $request->file('identity_document');
            $randomName = 'id_' . Str::random(20) . '.' . $idFile->getClientOriginalExtension();
            $path = $idFile->storeAs('private/documents/' . $campaign->id, $randomName, 'local');

            CampaignDocument::create([
                'campaign_id' => $campaign->id,
                'document_type' => 'identity_card',
                'original_name' => $idFile->getClientOriginalName(),
                'file_path' => $path,
                'file_mime' => $idFile->getClientMimeType(),
                'file_size' => $idFile->getSize(),
                'is_private' => true,
                'uploaded_by' => $user->id,
            ]);
        }

        if ($request->hasFile('medical_documents')) {
            foreach ($request->file('medical_documents') as $medFile) {
                $randomName = 'med_' . Str::random(20) . '.' . $medFile->getClientOriginalExtension();
                $path = $medFile->storeAs('private/documents/' . $campaign->id, $randomName, 'local');

                CampaignDocument::create([
                    'campaign_id' => $campaign->id,
                    'document_type' => 'medical_report',
                    'original_name' => $medFile->getClientOriginalName(),
                    'file_path' => $path,
                    'file_mime' => $medFile->getClientMimeType(),
                    'file_size' => $medFile->getSize(),
                    'is_private' => true,
                    'uploaded_by' => $user->id,
                ]);
            }
        }

        // Update User Role to 'applicant' if was donor
        if ($user->role === 'donor') {
            $user->update(['role' => 'applicant']);
        }

        AuditLog::log(
            action: 'campaign_submitted',
            entityType: Campaign::class,
            entityId: $campaign->id,
            newValues: ['title' => $campaign->title, 'target_amount' => $campaign->target_amount]
        );

        return redirect()->route('dashboard.campaigns')->with('success', 'Solicitação de campanha enviada com sucesso! A equipa da FundMe Angola fará a verificação dos documentos brevemente.');
    }
}
