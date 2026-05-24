@extends('layouts.client')

@section('title', $typeRoom['name'])

@section('content')

<section class="type-room-page">
  <div class="type-room-container">
    <div class="type-room-breadcrumb">
      <a href="{{ url('/') }}">Trang chủ</a>
      <span>/</span>
      <a href="{{ url('/pet-hotel/dogs') }}">Khách sạn thú cưng</a>
      <span>/</span>
      <strong>{{ $typeRoom['name'] }}</strong>
    </div>

    <div class="type-room-hero">
      <div class="type-room-copy">
        <span class="type-room-badge">{{ $typeRoom['label'] }}</span>
        <h1>{{ $typeRoom['name'] }}</h1>
        <p>{{ $typeRoom['description'] }}</p>

        <div class="type-room-stats">
          <div>
            <span>Giá</span>
            <strong>{{ $typeRoom['price'] }}</strong>
          </div>
          <div>
            <span>Diện tích</span>
            <strong>{{ $typeRoom['area'] }}</strong>
          </div>
          <div>
            <span>Sức chứa</span>
            <strong>{{ $typeRoom['capacity'] }}</strong>
          </div>
          <div>
            <span>Cân nặng</span>
            <strong>{{ $typeRoom['weight'] }}</strong>
          </div>
        </div>

        <a href="{{ url('/booking') }}" class="type-room-primary-btn">Đặt phòng</a>
      </div>

      @if (! empty($typeRoom['images']))
        <div class="type-room-gallery">
          <img src="{{ $typeRoom['images'][0] }}" alt="{{ $typeRoom['name'] }}">

          <div class="type-room-thumbs">
            @foreach (array_slice($typeRoom['images'], 1, 3) as $image)
              <img src="{{ $image }}" alt="{{ $typeRoom['name'] }}">
            @endforeach
          </div>
        </div>
      @endif
    </div>

    <div class="type-room-detail-grid">
      <div class="type-room-panel">
        <h2>Tiện ích phòng</h2>
        <ul>
          @foreach ($typeRoom['features'] as $feature)
            <li><i class="fa-solid fa-check"></i> {{ $feature }}</li>
          @endforeach
        </ul>
      </div>

      <div class="type-room-panel">
        <h2>Y tế & chăm sóc</h2>
        <p>{{ $typeRoom['care'] }}</p>
      </div>
    </div>
  </div>
</section>

@endsection
