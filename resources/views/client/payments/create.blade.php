@extends('layouts.client')

@section('title', 'Thanh toán')

@section('content')

<section class="payment-page">
    <div class="payment-container">

        <h1 class="payment-title">Thanh toán</h1>

        <div class="payment-layout">

            {{-- LEFT --}}
            <div class="payment-left">

                <div class="payment-card">
                    <h2>Thông tin liên hệ</h2>

                    <div class="payment-form-group">
                        <label>Họ và tên</label>
                        <input type="text" value="Người dùng" readonly>
                    </div>

                    <div class="payment-form-group">
                        <label>Số điện thoại</label>
                        <input type="text" value="0912345678" readonly>
                    </div>

                    <div class="payment-form-group">
                        <label>Email</label>
                        <input type="email" value="1@gmail.com" readonly>
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
                            <small>Quét mã QR để thanh toán</small>
                        </span>
                    </label>

                    <label class="payment-method">
                        <input type="radio" name="payment_method" value="bank">

                        <span class="method-dot"></span>

                        <span class="method-icon purple">
                            <i class="fa-regular fa-credit-card"></i>
                        </span>

                        <span class="method-content">
                            <strong>Thẻ ngân hàng</strong>
                            <small>Visa, Mastercard, ATM nội địa</small>
                        </span>
                    </label>
                </div>

            </div>

            {{-- RIGHT --}}
            <aside class="payment-right">
                <div class="order-card">
                    <h2>Chi tiết đơn hàng</h2>

                    <div class="order-info">
                        <span>Chi nhánh</span>
                        <strong>Pet Hotel Quận 7</strong>
                    </div>

                    <div class="order-info">
                        <span>Loại phòng</span>
                        <strong>Phòng VIP</strong>
                    </div>

                    <div class="order-date-row">
                        <div class="order-info">
                            <span>Check-in</span>
                            <strong>14/05/2026</strong>
                        </div>

                        <div class="order-info">
                            <span>Check-out</span>
                            <strong>16/05/2026</strong>
                        </div>
                    </div>

                    <div class="order-divider"></div>

                    <div class="order-price-row">
                        <span>Tiền phòng</span>
                        <strong>600.000đ</strong>
                    </div>

                    <div class="discount-box">
                        <label>
                            <i class="fa-solid fa-gift"></i>
                            Mã giảm giá
                        </label>

                        <div class="discount-form">
                            <input type="text" placeholder="Nhập mã (VD: PET100)">
                            <button type="button">Áp dụng</button>
                        </div>
                    </div>

                    <div class="order-divider"></div>

                    <div class="order-total">
                        <span>Tổng cộng</span>
                        <strong>600.000đ</strong>
                    </div>

                    <form action="#" method="POST">
                        @csrf

                        <button type="submit" class="confirm-order-btn">
                            Xác nhận đặt hàng
                        </button>
                    </form>

                    <p class="payment-note">
                        Bằng việc đặt hàng, bạn đồng ý với các điều khoản và chính sách của Pet Hotel
                    </p>
                </div>
            </aside>

        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
    const paymentMethods = document.querySelectorAll('.payment-method');

    paymentMethods.forEach(function (method) {
        method.addEventListener('click', function () {
            paymentMethods.forEach(function (item) {
                item.classList.remove('active');
            });

            this.classList.add('active');

            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
        });
    });
</script>
@endpush