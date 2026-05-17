@extends('layouts.client')

@section('title', 'Hệ thống chi nhánh')

@section('content')

<section class="branch-page">
    <div class="branch-layout">

        {{-- Bên trái: bản đồ mô phỏng --}}
        <div class="branch-map">
            <div class="map-marker marker-1">
                <span class="marker-label">Pet Hotel Quận 1</span>
                <span class="marker-dot">2</span>
            </div>

            <div class="map-marker marker-2">
                <span class="marker-label">Pet Hotel Bình Thạnh</span>
                <span class="marker-dot">3</span>
            </div>

            <div class="map-marker marker-3">
                <span class="marker-label">Pet Hotel Thủ Đức</span>
                <span class="marker-dot">4</span>
            </div>

            <div class="map-marker marker-4">
                <span class="marker-label">Pet Hotel Quận 7</span>
                <span class="marker-dot">1</span>
            </div>

            <div class="map-current">
                <i class="fa-solid fa-location-dot"></i>
                <h3>Bạn ở chi nhánh</h3>
                <p>Click vào để xem chi nhánh</p>
            </div>
        </div>

        {{-- Bên phải: danh sách chi nhánh --}}
        <div class="branch-list-panel">
            <div class="branch-top">
                <div>
                    <h1>Hệ thống chi nhánh</h1>
                    <p>Tìm chi nhánh Pet Hotel gần bạn nhất</p>
                </div>
            </div>

            <div class="branch-filter">
                <div class="branch-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Tìm kiếm theo tên hoặc địa chỉ">
                </div>

                <select>
                    <option>Tất cả quận/huyện</option>
                    <option>Quận 1</option>
                    <option>Quận 7</option>
                    <option>Bình Thạnh</option>
                    <option>Thủ Đức</option>
                </select>
            </div>

            <div class="branch-list">

                <div class="branch-card">
                    <img src="{{ asset('assets/client/images/branch-1.jpg') }}" alt="Pet Hotel Quận 7">

                    <div class="branch-info">
                        <h3>Pet Hotel Quận 7</h3>
                        <p><i class="fa-solid fa-location-dot"></i> 123 Đường Nguyễn Văn Linh, Quận 7, TP.HCM</p>
                        <p><i class="fa-solid fa-phone"></i> 1900 1234</p>
                        <p><i class="fa-regular fa-clock"></i> 8.00 - 20.00</p>
                        <p class="rating">
                            <i class="fa-solid fa-star"></i> 4.8
                            <span>(127 đánh giá)</span>
                        </p>
                    </div>

                    <a href="{{ url('/branches/1') }}" class="branch-detail-btn">Xem chi tiết</a>
                </div>

                <div class="branch-card">
                    <img src="{{ asset('assets/client/images/branch-2.jpg') }}" alt="Pet Hotel Quận 1">

                    <div class="branch-info">
                        <h3>Pet Hotel Quận 1</h3>
                        <p><i class="fa-solid fa-location-dot"></i> 45 Đường Lê Lợi, Quận 1, TP.HCM</p>
                        <p><i class="fa-solid fa-phone"></i> 1900 5678</p>
                        <p><i class="fa-regular fa-clock"></i> 7.30 - 21.00</p>
                        <p class="rating">
                            <i class="fa-solid fa-star"></i> 4.6
                            <span>(38 đánh giá)</span>
                        </p>
                    </div>

                    <a href="{{ url('/branches/2') }}" class="branch-detail-btn">Xem chi tiết</a>
                </div>

                <div class="branch-card">
                    <img src="{{ asset('assets/client/images/branch-3.jpg') }}" alt="Pet Hotel Bình Thạnh">

                    <div class="branch-info">
                        <h3>Pet Hotel Bình Thạnh</h3>
                        <p><i class="fa-solid fa-location-dot"></i> 321 Đường Xô Viết Nghệ Tĩnh, Bình Thạnh, TP.HCM</p>
                        <p><i class="fa-solid fa-phone"></i> 1900 3456</p>
                        <p><i class="fa-regular fa-clock"></i> 8.00 - 20.00</p>
                        <p class="rating">
                            <i class="fa-solid fa-star"></i> 4.7
                            <span>(203 đánh giá)</span>
                        </p>
                    </div>

                    <a href="{{ url('/branches/3') }}" class="branch-detail-btn">Xem chi tiết</a>
                </div>

                <div class="branch-card">
                    <img src="{{ asset('assets/client/images/branch-4.jpg') }}" alt="Pet Hotel Thủ Đức">

                    <div class="branch-info">
                        <h3>Pet Hotel Thủ Đức</h3>
                        <p><i class="fa-solid fa-location-dot"></i> 789 Đường Võ Văn Ngân, TP. Thủ Đức, TP.HCM</p>
                        <p><i class="fa-solid fa-phone"></i> 1900 9012</p>
                        <p><i class="fa-regular fa-clock"></i> 8.00 - 19.00</p>
                        <p class="rating">
                            <i class="fa-solid fa-star"></i> 4.5
                            <span>(61 đánh giá)</span>
                        </p>
                    </div>

                    <a href="{{ url('/branches/4') }}" class="branch-detail-btn">Xem chi tiết</a>
                </div>

            </div>
        </div>

    </div>
</section>

@endsection