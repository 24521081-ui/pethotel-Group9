@extends('layouts.client')

@section('title', 'Chọn chi nhánh đặt phòng')

@section('content')

<section class="booking-branch-select">
    <div class="booking-branch-panel">
        <div class="booking-branch-heading">
            <h1>Chọn chi nhánh</h1>
            <p>Chọn chi nhánh bạn muốn đặt phòng</p>
        </div>

        <div class="booking-branch-list">
            @foreach ($branches as $branch)
                <a href="{{ $branch['bookingUrl'] }}" class="booking-branch-option">
                    <img src="{{ $branch['image'] }}" alt="{{ $branch['name'] }}">

                    <span class="booking-branch-copy">
                        <strong>{{ $branch['name'] }}</strong>
                        <span><i class="fa-solid fa-location-dot"></i> {{ $branch['address'] }}</span>
                        <span class="booking-branch-rating">
                            <i class="fa-solid fa-star"></i>
                            {{ $branch['rating'] }}
                            <small>({{ $branch['reviews'] }} đánh giá)</small>
                        </span>
                    </span>

                    <i class="fa-solid fa-chevron-right booking-branch-arrow"></i>
                </a>
            @endforeach
        </div>
    </div>
</section>

@endsection
