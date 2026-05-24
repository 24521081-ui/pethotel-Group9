@extends('layouts.client')

@section('title', 'Thanh toán')

@php
$money = fn ($amount) => number_format((float) $amount, 0, ',', '.').'đ';
$payment = $payment ?? [];
@endphp

@section('content')

<section class="payment-page">
  <div class="payment-container">

    <h1 class="payment-title">Thanh toán</h1>

    @if (session('status'))
    <div class="alert alert-success">
      {{ session('status') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
      {{ $errors->first() }}
    </div>
    @endif

    <form action="{{ $payment['process_url'] }}" method="POST" class="payment-layout">
      @csrf

      <div class="payment-left">
        <div class="payment-card">
          <h2>Thông tin liên hệ</h2>

          <div class="payment-form-group">
            <label>Họ và tên</label>
            <input type="text" value="{{ $payment['customer_name'] ?? 'Khách hàng' }}" readonly>
          </div>

          <div class="payment-form-group">
            <label>Số điện thoại</label>
            <input type="text" value="{{ $payment['customer_phone'] ?? 'Đang cập nhật' }}" readonly>
          </div>

          <div class="payment-form-group">
            <label>Email</label>
            <input type="email" value="{{ $payment['customer_email'] ?? 'Đang cập nhật' }}" readonly>
          </div>
        </div>

        <div class="payment-card">
          <h2>Phương thức thanh toán</h2>

          <label class="payment-method active">
            <input type="radio" name="payment_method" value="cod" checked>

            <span class="method-dot"></span>

            <span class="method-icon green">
              <i class="fa-solid fa-money-bill-wave"></i>
            </span>

            <span class="method-content">
              <strong>Tiền mặt khi nhận phòng (COD)</strong>
              <small>Thanh toán trực tiếp tại chi nhánh</small>
            </span>
          </label>

          <label class="payment-method">
            <input type="radio" name="payment_method" value="wallet">

            <span class="method-dot"></span>

            <span class="method-icon blue">
              <i class="fa-regular fa-wallet"></i>
            </span>

            <span class="method-content">
              <strong>Ví điện tử (MoMo, ZaloPay)</strong>
              <small>Ghi nhận phương thức, tích hợp cổng thanh toán sau</small>
            </span>
          </label>

          <label class="payment-method">
            <input type="radio" name="payment_method" value="bank">

            <span class="method-dot"></span>

            <span class="method-icon purple">
              <i class="fa-regular fa-credit-card"></i>
            </span>

            <span class="method-content">
              <strong>Chuyển khoản ngân hàng</strong>
              <small>Ghi nhận phương thức, nhân viên sẽ xác nhận sau</small>
            </span>
          </label>
        </div>
      </div>

      <aside class="payment-right">
        <div class="order-card">
          <h2>Chi tiết đơn hàng</h2>

          <div class="order-info">
            <span>Mã booking</span>
            <strong>#{{ $payment['booking_id'] ?? '' }}</strong>
          </div>

          <div class="order-info">
            <span>Chi nhánh</span>
            <strong>{{ $payment['branch_name'] ?? 'Chi nhánh đang cập nhật' }}</strong>
          </div>

          <div class="order-info">
            <span>Loại phòng</span>
            <strong>{{ $payment['room_names'] ?? 'Phòng đang cập nhật' }}</strong>
          </div>

          <div class="order-date-row">
            <div class="order-info">
              <span>Check-in</span>
              <strong>{{ $payment['checkin'] ?? 'Đang cập nhật' }}</strong>
            </div>

            <div class="order-info">
              <span>Check-out</span>
              <strong>{{ $payment['checkout'] ?? 'Đang cập nhật' }}</strong>
            </div>
          </div>

          <div class="order-divider"></div>

          @forelse (($payment['details'] ?? []) as $detail)
          <div class="order-price-row">
            <span>{{ $detail['title'] }} x{{ $detail['quantity'] }}</span>
            <strong>{{ $money($detail['line_total']) }}</strong>
          </div>
          @empty
          <div class="order-info">
            <span>Chi tiết</span>
            <strong>Đang cập nhật</strong>
          </div>
          @endforelse

          <div class="order-divider"></div>

          <div class="order-price-row">
            <span>Tiền phòng</span>
            <strong>{{ $money($payment['room_total'] ?? 0) }}</strong>
          </div>

          <div class="order-price-row">
            <span>Dịch vụ</span>
            <strong>{{ $money($payment['service_total'] ?? 0) }}</strong>
          </div>

          <div class="discount-box">
            <label>
              <i class="fa-solid fa-gift"></i>
              Mã giảm giá
            </label>

            <div class="discount-form">
              <input
                type="text"
                name="coupon_code"
                value="{{ old('coupon_code', $payment['coupon_code'] ?? '') }}"
                placeholder="Nhập mã (VD: WELCOME50)"
              >
            </div>
          </div>

          <div class="order-price-row discount-row">
            <span>Giảm giá</span>
            <strong>-{{ $money($payment['discount_amount'] ?? 0) }}</strong>
          </div>

          <div class="order-divider"></div>

          <div class="order-total">
            <span>Tổng cộng</span>
            <strong>{{ $money($payment['grand_total'] ?? 0) }}</strong>
          </div>

          <button type="submit" class="confirm-order-btn">
            Xác nhận thanh toán
          </button>

          <p class="payment-note">
            Bằng việc đặt hàng, bạn đồng ý với các điều khoản và chính sách của Pet Hotel
          </p>
        </div>
      </aside>
    </form>

  </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const paymentMethods = document.querySelectorAll('.payment-method');

  paymentMethods.forEach(function(method) {
    method.addEventListener('click', function() {
      paymentMethods.forEach(function(item) {
        item.classList.remove('active');
      });

      this.classList.add('active');

      const radio = this.querySelector('input[type="radio"]');
      radio.checked = true;
    });
  });

  const checkStatusUrl = @json(route('payment.check_status', ['bookingId' => $payment['booking_id'] ?? $order->booking_id]));
  const paymentHistoryUrl = @json(route('profile.history-booking.index'));
  const currentStatus = String(@json($order->status) ?? '');
  const currentTotal = Number(@json((float) ($payment['grand_total'] ?? $order->grand_total ?? 0)));
  let pollingStopped = false;

  function stopPolling() {
    pollingStopped = true;
    clearInterval(pollingInterval);
  }

  function lockPaymentForm() {
    document
      .querySelectorAll('.payment-layout button, .payment-layout input, .payment-layout select, .payment-layout textarea')
      .forEach(function(control) {
        control.disabled = true;
      });
  }

  function reloadPaymentPage(message) {
    stopPolling();
    lockPaymentForm();
    alert(message);
    window.location.reload();
  }

  async function fetchOrderStatus() {
    if (pollingStopped) {
      return;
    }

    try {
      const response = await fetch(checkStatusUrl, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
      });

      const data = await response.json().catch(function() {
        return null;
      });

      if (response.status === 401) {
        reloadPaymentPage('Phiên đăng nhập đã hết hạn. Trang sẽ được tải lại.');
        return;
      }

      if (response.status === 404 || (data && data.exists === false)) {
        stopPolling();
        lockPaymentForm();
        alert('Đơn hàng không còn tồn tại trên hệ thống.');
        window.location.href = paymentHistoryUrl;
        return;
      }

      if (!response.ok || !data || data.exists !== true) {
        return;
      }

      if (String(data.status ?? '') !== currentStatus) {
        reloadPaymentPage('Trạng thái đơn hàng đã thay đổi từ hệ thống khác. Trang sẽ tự động tải lại để cập nhật.');
        return;
      }

      const nextTotal = Number(data.grand_total);

      if (!Number.isNaN(nextTotal) && Math.abs(nextTotal - currentTotal) >= 0.01) {
        reloadPaymentPage('Tổng tiền đơn hàng vừa được cập nhật. Vui lòng kiểm tra lại trước khi thanh toán.');
      }
    } catch (error) {
      console.error('Lỗi khi kiểm tra trạng thái đơn hàng:', error);
    }
  }

  const pollingInterval = setInterval(fetchOrderStatus, 5000);

  window.addEventListener('beforeunload', function() {
    stopPolling();
  });
});
</script>
@endpush
