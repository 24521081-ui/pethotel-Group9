<?php

namespace App\Repositories\Contracts;

use App\Models\Booking;
use App\Models\User;

interface PaymentRepositoryInterface
{
    public function paymentPageDataForUser(?User $user, string $bookingId): ?array;

    public function confirmBookingPaymentForUser(
        ?User $user,
        string $bookingId,
        string $paymentMethod,
        ?string $couponCode = null
    ): ?Booking;

    public function orderStatusForUser(?User $user, string $bookingId): ?array;
}
