<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Campaign;
use App\Models\Donation;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Donor metrics
        $donations = Donation::where('user_id', $user->id)
            ->with('campaign')
            ->latest()
            ->get();
        $totalDonated = $donations->where('status', 'paid')->sum('amount');
        $supportedCampaignsCount = $donations->where('status', 'paid')->pluck('campaign_id')->unique()->count();

        // Applicant metrics
        $myCampaigns = Campaign::where('user_id', $user->id)
            ->with(['beneficiary', 'donations'])
            ->latest()
            ->get();

        return view('dashboard.index', compact(
            'user',
            'donations',
            'totalDonated',
            'supportedCampaignsCount',
            'myCampaigns'
        ));
    }

    public function donations()
    {
        $user = auth()->user();
        $donations = Donation::where('user_id', $user->id)
            ->with('campaign')
            ->latest()
            ->paginate(10);

        return view('dashboard.donations', compact('donations'));
    }

    public function campaigns()
    {
        $user = auth()->user();
        $campaigns = Campaign::where('user_id', $user->id)
            ->with(['beneficiary', 'documents', 'fundPlans'])
            ->latest()
            ->paginate(10);

        return view('dashboard.campaigns', compact('campaigns'));
    }

    public function profile()
    {
        $user = auth()->user();
        $provinces = [
            'Bengo', 'Benguela', 'Bié', 'Cabinda', 'Cuando Cubango', 'Cuanza Norte',
            'Cuanza Sul', 'Cunene', 'Huambo', 'Huíla', 'Luanda', 'Lunda Norte',
            'Lunda Sul', 'Malanje', 'Moxico', 'Namibe', 'Uíge', 'Zaire'
        ];

        return view('dashboard.profile', compact('user', 'provinces'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'province' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'A palavra-passe atual está incorreta.']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        $user->province = $validated['province'] ?? null;
        $user->municipality = $validated['municipality'] ?? null;
        $user->save();

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }
}
