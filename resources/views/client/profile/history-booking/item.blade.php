@php
    $iconClass = $booking['icon_class'] ?? '';
    $iconName = match ($iconClass) {
        'red' => 'fa-calendar-xmark',
        'blue' => 'fa-calendar-check',
        default => 'fa-calendar',
    };
@endphp

<div class="booking-history-card">
    <div class="booking-history-icon {{ $iconClass }}">
        <i class="fa-regular {{ $iconName }}"></i>
    </div>

    <div class="booking-history-info">
        <h3>
            <a href="{{ $booking['detail_url'] }}" class="booking-history-title-link">
                {{ $booking['title'] }}
            </a>

            @if (($booking['pet_count'] ?? 0) > 0)
                <span>{{ $booking['pet_count'] }} thú cưng</span>
            @endif
        </h3>

        <p>{{ $booking['date_range'] }} · {{ $booking['branch_name'] }}</p>
    </div>

    <div class="booking-history-actions">
        <span class="status-badge {{ $booking['status_class'] }}">{{ $booking['status_label'] }}</span>

        @if ($booking['show_payment'])
            <a href="{{ $booking['payment_url'] }}" class="pay-btn">Thanh toán</a>
        @endif

        <a href="{{ $booking['detail_url'] }}" class="detail-btn">Xem chi tiết</a>
    </div>
</div>
