<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Donation;
use App\Services\PaymentGatewayInterface;

class DonationController extends Controller
{
    protected PaymentGatewayInterface $paymentGateway;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request, Campaign $campaign)
    {
        // 1. Check if campaign can receive donations
        if ($campaign->status !== 'published') {
            return redirect()->route('campaigns.show', $campaign->slug)
                ->with('error', 'Esta campanha não está disponível para receber doações no momento.');
        }

        // 2. Validate request
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'payment_method' => 'required|string|in:multicaixa_express,bank_transfer,kwanza_pay,sandbox',
            'donor_name' => 'nullable|string|max:255',
            'donor_email' => 'nullable|email|max:255',
            'donor_phone' => 'nullable|string|max:50',
            'is_anonymous' => 'nullable|boolean',
            'donor_message' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();

        // 3. Create Donation record with status PENDING
        $donation = Donation::create([
            'campaign_id' => $campaign->id,
            'user_id' => $user ? $user->id : null,
            'donor_name' => $validated['donor_name'] ?? ($user ? $user->name : null),
            'donor_email' => $validated['donor_email'] ?? ($user ? $user->email : null),
            'donor_phone' => $validated['donor_phone'] ?? ($user ? $user->phone : null),
            'amount' => $validated['amount'],
            'currency' => 'Kz',
            'status' => 'pending',
            'payment_method' => $validated['payment_method'],
            'is_anonymous' => $request->has('is_anonymous'),
            'donor_message' => $validated['donor_message'] ?? null,
        ]);

        // 4. Initiate with Payment Service Gateway
        $gatewayResponse = $this->paymentGateway->createPayment($donation, $validated);

        // 5. In Sandbox development mode, redirect to confirmation step
        return redirect()->route('donations.checkout', ['donation' => $donation->id])
            ->with('info', $gatewayResponse['message']);
    }

    public function checkout(Donation $donation)
    {
        if ($donation->status === 'paid') {
            return redirect()->route('campaigns.show', $donation->campaign->slug)
                ->with('success', 'Esta doação já se encontra confirmada. Muito obrigado!');
        }

        return view('donations.checkout', compact('donation'));
    }

    public function confirm(Request $request, Donation $donation)
    {
        if ($donation->status === 'paid') {
            return redirect()->route('campaigns.show', $donation->campaign->slug);
        }

        // Confirm payment via Gateway service
        $confirmedDonation = $this->paymentGateway->confirmPayment($donation->payment_reference);

        $campaign = $confirmedDonation->campaign;

        // Message based on goal
        $msg = 'Sua doação de ' . $donation->formatted_amount . ' foi confirmada com sucesso! Muito obrigado pela solidariedade.';
        if ($campaign->status === 'goal_reached') {
            $msg .= ' 🎯 A meta da campanha foi atingida graças à sua contribuição!';
        }

        return redirect()->route('campaigns.show', $campaign->slug)->with('success', $msg);
    }
}
