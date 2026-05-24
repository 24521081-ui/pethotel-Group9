<?php

namespace App\Repositories\Contracts;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;

interface BookingRepositoryInterface
{
    public function customerForUser(?User $user): ?Customer;

    public function bookingFormViewData(string $branchId, bool $isAuthenticated, ?User $user = null): array;

    public function bookingBranches(): array;

    public function bookingHistoryItems(Customer $customer): array;

    public function findCustomerBooking(Customer $customer, string $bookingId): ?Booking;

    public function bookingDetail(Booking $booking): array;

    public function createPendingBookingForUser(?User $user, array $bookingData): Booking;
}
