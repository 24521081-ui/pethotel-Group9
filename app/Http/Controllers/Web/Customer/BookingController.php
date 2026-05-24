<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Web\WebController;
use App\Http\Requests\Web\Customer\StoreBookingRequest;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class BookingController extends WebController
{
    public function __construct(private BookingRepositoryInterface $bookings)
    {
        // Dependency Injection của BookingRepositoryInterface để sử dụng trong controller này
    }

    public function index(): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfGuest('Vui lòng đăng nhập để xem lịch sử đặt phòng.')) {
            return $redirect;
        }

        $customer = $this->bookings->customerForUser(Auth::user());

        return view('client.profile.history-booking.index', [
            'bookings' => $customer ? $this->bookings->bookingHistoryItems($customer) : [],
        ]);
    }

    public function create(): View
    {
        return $this->bookingFormView('1');
    }

    public function selectBranch(): View
    {
        return view('client.bookings.select-branch', [
            'branches' => $this->bookings->bookingBranches(),
        ]);
    }

    public function createFromBranch(string $branchId): View
    {
        return $this->bookingFormView($branchId);
    }

    public function show(string $bookingId): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfGuest('Vui lòng đăng nhập để xem chi tiết đặt phòng.')) {
            return $redirect;
        }

        $customer = $this->bookings->customerForUser(Auth::user());
        $booking = $customer ? $this->bookings->findCustomerBooking($customer, $bookingId) : null;

        if (! $booking) {
            return redirect()
                ->route('profile.history-booking.index')
                ->withErrors(['booking' => 'Không tìm thấy đơn booking trong lịch sử của bạn.']);
        }

        return view('client.bookings.show', [
            'booking' => $this->bookings->bookingDetail($booking),
        ]);
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        if ($redirect = $this->redirectIfGuest('Vui lòng đăng nhập trước khi thanh toán.')) {
            return $redirect;
        }

        try {
            $booking = $this->bookings->createPendingBookingForUser(
                Auth::user(),
                $request->bookingPayload()
            );

            if ($request->isHoldOnly()) {
                return redirect()
                    ->route('booking.show', $booking->booking_id)
                    ->with('status', 'Đã giữ chỗ cho bạn. Bạn có thể thanh toán sau trong lịch sử đặt phòng.');
            }

            return redirect()
                ->route('payment.show', $booking->booking_id)
                ->with('status', 'Đã tìm thấy phòng và giữ chỗ cho bạn. Vui lòng thanh toán.');
        } catch (Exception $e) {
            return $this->bookingErrorResponse($e);
        }
    }

    private function bookingFormView(string $branchId): View
    {
        return view('client.bookings.create', $this->bookings->bookingFormViewData(
            $branchId,
            Auth::check(),
            Auth::user()
        ));
    }

    private function bookingErrorResponse(Exception $e): RedirectResponse
    {
        $errorParts = explode('|', $e->getMessage(), 2);
        $field = count($errorParts) === 2 ? $errorParts[0] : 'booking_error';
        $message = count($errorParts) === 2 ? $errorParts[1] : $e->getMessage();

        return back()
            ->withInput()
            ->withErrors([$field => $message]);
    }
}
