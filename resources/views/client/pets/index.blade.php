@extends('layouts.client')

@section('title', 'Thú cưng của tôi')

@section('content')

<section class="account-page">
    <div class="account-container">

        <div class="account-breadcrumb">
            <a href="{{ url('/') }}">Trang chủ</a>
            <span>/</span>
            <strong>Thú cưng của tôi</strong>
        </div>

        <div class="account-header">
            <h1>Thú cưng của tôi</h1>

            <a href="{{ url('/pets/create') }}" class="orange-btn">
                <i class="fa-solid fa-plus"></i>
                Thêm thú cưng
            </a>
        </div>

        <div class="pet-grid">
            <div class="pet-card">
                <div class="pet-card-top">
                    <div class="pet-icon">
                        <i class="fa-regular fa-face-smile"></i>
                    </div>

                    <div class="pet-info">
                        <h3>566</h3>
                        <p>Mèo · Đực</p>
                    </div>
                </div>

                <span class="pet-status">Chưa có vaccine</span>

                <div class="pet-actions">
                    <a href="{{ url('/pets/1/edit') }}" class="pet-edit-btn">
                        <i class="fa-regular fa-pen-to-square"></i>
                        Xóa
                    </a>

                    <a href="{{ url('/booking') }}" class="pet-book-btn">
                        <i class="fa-regular fa-calendar"></i>
                        Đặt phòng
                    </a>
                </div>
            </div>

            <a href="{{ url('/pets/create') }}" class="add-pet-card">
                <i class="fa-solid fa-plus"></i>
                <span>Thêm thú cưng</span>
            </a>
        </div>

    </div>
</section>

@endsection