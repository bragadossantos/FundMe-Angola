<?php

namespace App\Services;

use App\Models\Donation;
use App\Models\AuditLog;
use Illuminate\Support\Str;

class SandboxPaymentService implements PaymentGatewayInterface
{
    public function createPayment(Donation $donation, array $paymentDetails = []): array
    {
        $reference = 'FMA-' . strtoupper(Str::random(10));
        $donation->update([
            'payment_reference' => $reference,
            'status' => 'pending'
        ]);

        return [
            'success' => true,
            'reference' => $reference,
            'payment_method' => $donation->payment_method,
            'amount' => $donation->amount,
            'currency' => $donation->currency,
            'message' => 'Transação iniciada em ambiente seguro de demonstração/sandbox.',
        ];
    }

    public function confirmPayment(string $paymentReference): Donation
    {
        $donation = Donation::where('payment_reference', $paymentReference)->firstOrFail();

        $donation->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        AuditLog::log(
            action: 'donation_confirmed',
            entityType: Donation::class,
            entityId: $donation->id,
            oldValues: ['status' => 'pending'],
            newValues: ['status' => 'paid', 'amount' => $donation->amount]
        );

        return $donation;
    }

    public function getPaymentStatus(string $paymentReference): array
    {
        $donation = Donation::where('payment_reference', $paymentReference)->first();

        if (!$donation) {
            return ['found' => false, 'status' => 'unknown'];
        }

        return [
            'found' => true,
            'reference' => $donation->payment_reference,
            'status' => $donation->status,
            'amount' => $donation->amount,
            'paid_at' => $donation->paid_at ? $donation->paid_at->toIso8601String() : null,
        ];
    }

    public function refundPayment(Donation $donation, string $reason = ''): array
    {
        $oldStatus = $donation->status;
        $donation->update(['status' => 'refunded']);

        AuditLog::log(
            action: 'donation_refunded',
            entityType: Donation::class,
            entityId: $donation->id,
            oldValues: ['status' => $oldStatus],
            newValues: ['status' => 'refunded', 'reason' => $reason]
        );

        return [
            'success' => true,
            'message' => 'Doação reembolsada com sucesso no ambiente Sandbox.'
        ];
    }
}
