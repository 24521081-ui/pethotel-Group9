@extends('layouts.client')

@section('title', 'Lịch sử đặt phòng')

@section('content')

<section class="account-page">
    <div class="account-container">

        <div class="account-breadcrumb">
            <a href="{{ url('/') }}">Trang chủ</a>
            <span>/</span>
            <strong>Lịch sử đặt phòng</strong>
        </div>

        <div class="account-header">
            <h1>Lịch sử đặt phòng</h1>
        </div>

        <div class="booking-history-list">

            <div class="booking-history-card">
                <div class="booking-history-icon">
                    <i class="fa-regular fa-calendar"></i>
                </div>

                <div class="booking-history-info">
                    <h3>Bông, Mochi - Phòng VIP <span style="color:#2563eb;">2 thú cưng</span></h3>
                    <p>20/05/2025 – 23/05/2025 · Chi nhánh Quận 7</p>
                </div>

                <div class="booking-history-actions">
                    <span class="status-badge status-pending">Chờ thanh toán</span>
                    <a href="#" class="pay-btn">Thanh toán</a>
                    <a href="#" class="detail-btn">Xem chi tiết</a>
                </div>
            </div>

            <div class="booking-history-card">
                <div class="booking-history-icon blue">
                    <i class="fa-regular fa-calendar-check"></i>
                </div>

                <div class="booking-history-info">
                    <h3>Mochi - Phòng Tiêu chuẩn</h3>
                    <p>10/05/2025 – 13/05/2025 · Chi nhánh Quận 1</p>
                </div>

                <div class="booking-history-actions">
                    <span class="status-badge status-paid">Hoàn thành</span>
                    <a href="#" class="detail-btn">Xem chi tiết</a>
                </div>
            </div>

            <div class="booking-history-card">
                <div class="booking-history-icon red">
                    <i class="fa-regular fa-calendar-xmark"></i>
                </div>

                <div class="booking-history-info">
                    <h3>Mochi - Phòng Tiêu chuẩn</h3>
                    <p>01/04/2025 – 03/04/2025 · Chi nhánh Quận 1</p>
                </div>

                <div class="booking-history-actions">
                    <span class="status-badge status-cancelled">Đã hủy</span>
                    <a href="#" class="detail-btn">Xem chi tiết</a>
                </div>
            </div>

        </div>

    </div>
</section>

@endsection