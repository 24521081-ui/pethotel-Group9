<div class="branch-list">
    @forelse ($branches as $branch)
        <article class="branch-card">
            <img src="{{ $branch['image'] }}" alt="{{ $branch['name'] }}">

            <div class="branch-info">
                <div class="branch-card-heading">
                    <h3>{{ $branch['name'] }}</h3>
                    <span>{{ $branch['district'] }}</span>
                </div>

                <p><i class="fa-solid fa-location-dot"></i> {{ $branch['address'] }}</p>
                <p><i class="fa-solid fa-phone"></i> {{ $branch['phone'] }}</p>
                <p><i class="fa-regular fa-clock"></i> {{ $branch['hours'] }}</p>
                <p class="rating">
                    <i class="fa-solid fa-star"></i> {{ $branch['rating'] }}
                    <span>({{ $branch['reviews'] }} đánh giá)</span>
                </p>
            </div>

            <div class="branch-actions">
                <a href="{{ $branch['bookingUrl'] }}" class="branch-booking-btn">Đặt phòng</a>
                <a href="{{ $branch['detailUrl'] }}" class="branch-detail-btn">Xem chi tiết</a>
            </div>
        </article>
    @empty
        <div class="branch-empty">
            <i class="fa-regular fa-map"></i>
            <h3>Không tìm thấy chi nhánh</h3>
            <p>Hãy thử đổi từ khóa hoặc chọn khu vực khác.</p>
            <a href="{{ route('branches.index') }}">Xem tất cả chi nhánh</a>
        </div>
    @endforelse
</div>
