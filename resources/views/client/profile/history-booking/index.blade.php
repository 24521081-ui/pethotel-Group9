@extends('layouts.client')

@section('title', 'Lịch sử đặt phòng')

@section('content')

<section class="account-page">
  <div class="account-container">

    <div class="account-header">
      <div>
        <h1>Lịch sử đặt phòng</h1>
        <p class="account-subtitle">{{ count($bookings ?? []) }} đơn booking trong hồ sơ của bạn</p>
      </div>
    </div>

    @if ($errors->any())
    <div class="pet-page-alert pet-page-alert--error">
      {{ $errors->first() }}
    </div>
    @endif

    @if (session('status'))
    <div class="pet-page-alert pet-page-alert--success">
      {{ session('status') }}
    </div>
    @endif

    <div class="booking-history-list">
      @forelse ($bookings as $booking)
      @include('client.profile.history-booking.item', ['booking' => $booking])
      @empty
      <div class="booking-history-empty">
        <i class="fa-regular fa-calendar"></i>
        <h3>Chưa có đơn booking</h3>
        <p>Các đơn đặt phòng sau khi tạo sẽ được lưu tại đây.</p>
        <a href="{{ route('booking.select') }}" class="detail-btn">Đặt phòng ngay</a>
      </div>
      @endforelse
    </div>

  </div>
</section>

@endsection