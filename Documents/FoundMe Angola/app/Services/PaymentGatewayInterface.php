<?php

namespace App\Services;

use App\Models\Donation;

interface PaymentGatewayInterface
{
    /**
     * Create payment transaction for a donation
     */
    public function createPayment(Donation $donation, array $paymentDetails = []): array;

    /**
     * Confirm/Process an existing payment transaction
     */
    public function confirmPayment(string $paymentReference): Donation;

    /**
     * Check payment status with gateway
     */
    public function getPaymentStatus(string $paymentReference): array;

    /**
     * Process refund for a donation
     */
    public function refundPayment(Donation $donation, string $reason = ''): array;
}
