@extends('layouts.client')

@section('title', 'Chi tiết chi nhánh')

@section('content')

<section class="branch-detail-hero" style="background-image: url('{{ asset('assets/client/images/branch-banner.jpg') }}')">
    <div class="branch-detail-overlay"></div>

    <div class="branch-detail-hero-content">
        <h1>Pet Hotel Quận 7</h1>

        <div class="branch-stars">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <span>4.8</span>
            <small>(127 đánh giá)</small>
        </div>

        <a href="{{ url('/booking') }}" class="branch-book-btn">
            <i class="fa-regular fa-calendar"></i>
            Đặt phòng ngay
        </a>
    </div>
</section>

<section class="branch-detail-content">
    <div class="branch-detail-container">

        <div class="branch-contact-box">
            <div class="contact-item">
                <i class="fa-solid fa-location-dot"></i>
                <div>
                    <span>Địa chỉ</span>
                    <strong>123 Đường Nguyễn Văn Linh,<br>Quận 7, TP.HCM</strong>
                </div>
            </div>

            <div class="contact-item">
                <i class="fa-solid fa-phone"></i>
                <div>
                    <span>Số điện thoại</span>
                    <strong>1900 1234</strong>
                </div>
            </div>

            <div class="contact-item">
                <i class="fa-regular fa-clock"></i>
                <div>
                    <span>Giờ mở cửa</span>
                    <strong>8.00 - 20.00</strong>
                </div>
            </div>
        </div>

        <div class="branch-section-title">
            <i class="fa-solid fa-briefcase"></i>
            <h2>Loại phòng</h2>
        </div>

        <div class="branch-room-options">
            <div class="branch-room-card">
                <div class="room-icon gray">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <h3>Tiêu chuẩn</h3>
                <p>150.000đ/đêm</p>
            </div>

            <div class="branch-room-card">
                <div class="room-icon yellow">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <h3>VIP</h3>
                <p>300.000đ/đêm</p>
            </div>

            <div class="branch-room-card">
                <div class="room-icon purple">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <h3>Luxury</h3>
                <p>500.000đ/đêm</p>
            </div>
        </div>

        <div class="branch-section-title">
            <i class="fa-regular fa-square-check"></i>
            <h2>Dịch vụ Spa</h2>
        </div>

        <div class="spa-service-list">
            <div class="spa-service-item">
                <span>Tắm cơ bản</span>
                <strong>100.000đ</strong>
            </div>

            <div class="spa-service-item">
                <span>Cắt tỉa lông</span>
                <strong>150.000đ</strong>
            </div>

            <div class="spa-service-item">
                <span>Cắt móng</span>
                <strong>50.000đ</strong>
            </div>

            <div class="spa-service-item">
                <span>Massage thư giãn</span>
                <strong>200.000đ</strong>
            </div>

            <div class="spa-service-item">
                <span>Vệ sinh răng miệng</span>
                <strong>120.000đ</strong>
            </div>

            <div class="spa-service-item">
                <span>Combo Spa cao cấp</span>
                <strong>400.000đ</strong>
            </div>
        </div>

        <a href="{{ url('/branches') }}" class="back-branch-link">
            <i class="fa-solid fa-arrow-left"></i>
            Quay lại danh sách chi nhánh
        </a>

    </div>
</section>

@endsection