@extends('layouts.client')

@section('title', 'Hồ sơ cá nhân')

@section('content')

<section class="account-page">
    <div class="account-container">

        <div class="account-breadcrumb">
            <a href="{{ url('/') }}">Trang chủ</a>
            <span>/</span>
            <strong>Hồ sơ cá nhân</strong>
        </div>

        <div class="account-header">
            <h1>Hồ sơ cá nhân</h1>

            <a href="{{ url('/profile/edit') }}" class="light-btn">
                <i class="fa-regular fa-pen-to-square"></i>
                Chỉnh sửa
            </a>
        </div>

        <div class="profile-card">
            <div class="profile-top">
                <div class="profile-avatar">NV</div>

                <div class="profile-name">
                    <h2>Nguyễn Văn A</h2>
                    <p>nguyenvana@gmail.com</p>
                </div>
            </div>

            <div class="profile-grid">
                <div class="profile-info-item">
                    <span>Số điện thoại</span>
                    <strong>090 1234 567</strong>
                </div>

                <div class="profile-info-item">
                    <span>Ngày sinh</span>
                    <strong>15/03/1995</strong>
                </div>

                <div class="profile-info-item">
                    <span>Địa chỉ</span>
                    <strong>Quận 1, TP.HCM</strong>
                </div>

                <div class="profile-info-item">
                    <span>Thành viên từ</span>
                    <strong>Tháng 1, 2024</strong>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection