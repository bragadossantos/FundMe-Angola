<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCampaigns = Campaign::published()
            ->where('is_featured', true)
            ->with(['beneficiary'])
            ->latest()
            ->take(3)
            ->get();

        $urgentCampaigns = Campaign::published()
            ->where('status', 'published')
            ->orderBy('created_at', 'asc')
            ->take(3)
            ->get();

        $recentCampaigns = Campaign::published()
            ->with(['beneficiary'])
            ->latest('published_at')
            ->take(6)
            ->get();

        // Metrics
        $totalRaised = Donation::where('status', 'paid')->sum('amount');
        $totalDonationsCount = Donation::where('status', 'paid')->count();
        $completedCampaignsCount = Campaign::whereIn('status', ['goal_reached', 'payment_processing', 'completed'])->count();
        $totalCampaignsCount = Campaign::published()->count();

        return view('home.index', compact(
            'featuredCampaigns',
            'urgentCampaigns',
            'recentCampaigns',
            'totalRaised',
            'totalDonationsCount',
            'completedCampaignsCount',
            'totalCampaignsCount'
        ));
    }

    public function howItWorks()
    {
        return view('home.how_it_works');
    }
}
