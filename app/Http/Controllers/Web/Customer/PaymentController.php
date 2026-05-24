<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Web\WebController;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends WebController
{
    public function __construct(private PaymentRepositoryInterface $payments)
    {
    }

    public function create(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfGuest('Vui long dang nhap truoc khi thanh toan.')) {
            return $redirect;
        }

        $bookingId = $request->query('booking_id');

        if (! $bookingId) {
            return $this->missingPaymentRedirect('Khong tim thay thong tin booking can thanh toan.');
        }

        return $this->paymentViewForBooking((string) $bookingId);
    }

    public function show(string $bookingId): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfGuest('Vui long dang nhap truoc khi thanh toan.')) {
            return $redirect;
        }

        return $this->paymentViewForBooking($bookingId);
    }

    public function process(Request $request, string $bookingId): RedirectResponse
    {
        if ($redirect = $this->redirectIfGuest('Vui long dang nhap truoc khi thanh toan.')) {
            return $redirect;
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:cod,wallet,bank'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
        ], [
            'payment_method.required' => 'Vui long chon phuong thuc thanh toan.',
            'payment_method.in' => 'Phuong thuc thanh toan khong hop le.',
            'coupon_code.max' => 'Ma giam gia khong duoc vuot qua 50 ky tu.',
        ]);

        $booking = $this->payments->confirmBookingPaymentForUser(
            Auth::user(),
            $bookingId,
            $validated['payment_method'],
            $validated['coupon_code'] ?? null
        );

        if (! $booking) {
            return $this->missingPaymentRedirect('Don booking khong ton tai hoac khong thuoc tai khoan cua ban.');
        }

        return redirect()
            ->route('booking.show', $booking->booking_id)
            ->with('status', 'Thanh toan thanh cong. Cam on ban da su dung dich vu cua Pet Hotel.');
    }

    public function success(Request $request): RedirectResponse
    {
        $bookingId = $request->query('booking_id');

        if ($bookingId) {
            return redirect()
                ->route('booking.show', $bookingId)
                ->with('status', 'Thanh toan da duoc ghi nhan.');
        }

        return redirect()->route('profile.history-booking.index');
    }

    public function failed(Request $request): RedirectResponse
    {
        $bookingId = $request->query('booking_id');

        if ($bookingId) {
            return redirect()
                ->route('payment.show', $bookingId)
                ->withErrors(['payment' => 'Thanh toan chua thanh cong. Vui long thu lai.']);
        }

        return $this->missingPaymentRedirect('Thanh toan chua thanh cong.');
    }

    public function checkStatus(string $bookingId): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json([
                'exists' => false,
                'message' => 'Ban chua dang nhap.',
            ], 401);
        }

        $orderStatus = $this->payments->orderStatusForUser(Auth::user(), $bookingId);

        if (! $orderStatus) {
            return response()->json([
                'exists' => false,
                'message' => 'Hoa don khong ton tai.',
            ], 404);
        }

        return response()->json([
            'exists' => true,
            ...$orderStatus,
        ]);
    }

    private function paymentViewForBooking(string $bookingId): View|RedirectResponse
    {
        $data = $this->payments->paymentPageDataForUser(Auth::user(), $bookingId);

        if (! $data) {
            return $this->missingPaymentRedirect('Don booking khong ton tai hoac khong thuoc tai khoan cua ban.');
        }

        return view('client.payments.create', $data);
    }

    private function missingPaymentRedirect(string $message): RedirectResponse
    {
        return redirect()
            ->route('profile.history-booking.index')
            ->withErrors(['payment' => $message]);
    }
}
