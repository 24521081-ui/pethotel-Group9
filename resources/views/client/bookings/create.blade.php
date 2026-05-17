@extends('layouts.client')

@section('title', 'Đặt phòng')

@section('content')

<section class="booking-page-v2">
    <div class="booking-wrapper">

        {{-- LEFT --}}
        <div class="booking-main">

            <div class="booking-breadcrumb">
                <a href="{{ url('/') }}">Trang chủ</a>
                <span>/</span>
                <a href="{{ url('/branches') }}">Chi nhánh</a>
                <span>/</span>
                <a href="{{ url('/branches/1') }}">Chi tiết</a>
                <span>/</span>
                <strong>Đặt phòng</strong>
            </div>

            <div class="booking-branch-card-v2">
                <img src="{{ asset('assets/client/images/branch-1.jpg') }}" alt="Pet Hotel Quận 7">

                <div class="booking-branch-info">
                    <h2>Pet Hotel Quận 7</h2>
                    <p><i class="fa-solid fa-location-dot"></i> 123 Đường Nguyễn Văn Linh, Quận 7, TP.HCM</p>
                    <p><i class="fa-solid fa-phone"></i> 1900 1234</p>
                    <p><i class="fa-regular fa-clock"></i> 8:00 - 20:00</p>
                </div>
            </div>

            <form action="#" method="POST" id="bookingForm">
                @csrf

                {{-- Thông tin liên hệ --}}
                <div class="booking-box-v2">
                    <h3><i class="fa-regular fa-user"></i> Thông tin liên hệ</h3>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Họ & tên <span>*</span></label>
                            <input type="text" id="customerName" value="Nguyễn Văn A">
                        </div>

                        <div class="form-group">
                            <label>Số điện thoại <span>*</span></label>
                            <input type="text" id="customerPhone" value="0901 234 567">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="customerEmail" value="example@email.com">
                    </div>
                </div>

                {{-- Chọn ngày --}}
                <div class="booking-box-v2">
                    <h3><i class="fa-regular fa-calendar"></i> Chọn ngày</h3>

                    <div class="booking-date-layout">
                        <div class="calendar-card">
                            <div class="calendar-header">
                                <button type="button" id="prevMonth" class="calendar-nav-btn">
                                    <i class="fa-solid fa-chevron-left"></i>
                                </button>

                                <h4 id="calendarMonthYear">Tháng 5 Năm 2026</h4>

                                <button type="button" id="nextMonth" class="calendar-nav-btn">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>
                            </div>

                            <div class="calendar-weekdays">
                                <span>CN</span>
                                <span>T2</span>
                                <span>T3</span>
                                <span>T4</span>
                                <span>T5</span>
                                <span>T6</span>
                                <span>T7</span>
                            </div>

                            <div id="calendarDays" class="calendar-days"></div>
                        </div>

                        <div class="date-selected-box">
                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label>Ngày nhận phòng <span>*</span></label>
                                    <input type="text" id="checkinDisplay" readonly placeholder="Chưa chọn">
                                </div>

                                <div class="form-group">
                                    <label>Ngày trả phòng <span>*</span></label>
                                    <input type="text" id="checkoutDisplay" readonly placeholder="Chưa chọn">
                                </div>
                            </div>

                            <div class="nights-preview" id="nightsPreview">
                                Chưa chọn đủ ngày nhận và ngày trả
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Loại phòng --}}
                <div class="booking-box-v2">
                    <h3><i class="fa-solid fa-briefcase"></i> Loại phòng</h3>

                    <div class="booking-room-list-v2">
                        <label class="booking-room-card-v2 active" data-room-name="Tiêu chuẩn" data-room-price="150000">
                            <input type="radio" name="room_type" checked>
                            <div class="room-icon gray">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>
                            <h4>Tiêu chuẩn</h4>
                            <p>150.000đ/đêm</p>
                            <span class="checked-dot"><i class="fa-solid fa-check"></i></span>
                        </label>

                        <label class="booking-room-card-v2" data-room-name="VIP" data-room-price="300000">
                            <input type="radio" name="room_type">
                            <div class="room-icon yellow">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>
                            <h4>VIP</h4>
                            <p>300.000đ/đêm</p>
                        </label>

                        <label class="booking-room-card-v2" data-room-name="Luxury" data-room-price="500000">
                            <input type="radio" name="room_type">
                            <div class="room-icon purple">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>
                            <h4>Luxury</h4>
                            <p>500.000đ/đêm</p>
                        </label>
                    </div>
                </div>

                {{-- Chọn thú cưng --}}
                <div class="booking-box-v2">
                    <h3><i class="fa-regular fa-circle-dot"></i> Chọn thú cưng <small>(chọn ít nhất 1 bé)</small></h3>

                    <div class="pet-select-list">
                        <label class="pet-item">
                            <div class="pet-item-left">
                                <div class="pet-avatar">
                                    <i class="fa-regular fa-face-smile"></i>
                                </div>

                                <div class="pet-item-info">
                                    <strong>566</strong>
                                    <span>Mèo · Đực</span>
                                </div>
                            </div>

                            <input type="checkbox" class="pet-checkbox" checked>
                        </label>

                        <button type="button" class="add-pet-btn-v2">
                            <span>+</span>
                            Thêm thú cưng mới
                        </button>
                    </div>
                </div>

                {{-- Dịch vụ spa --}}
                <div class="booking-box-v2">
                    <h3><i class="fa-regular fa-square-check"></i> Dịch vụ Spa</h3>

                    <div class="spa-check-list">
                        <label class="spa-check-item">
                            <div>
                                <strong>Tắm cơ bản</strong>
                                <span>100.000đ</span>
                            </div>
                            <input type="checkbox" class="spa-service" data-name="Tắm cơ bản" data-price="100000">
                        </label>

                        <label class="spa-check-item">
                            <div>
                                <strong>Cắt móng</strong>
                                <span>70.000đ</span>
                            </div>
                            <input type="checkbox" class="spa-service" data-name="Cắt móng" data-price="70000">
                        </label>

                        <label class="spa-check-item">
                            <div>
                                <strong>Cắt tỉa lông</strong>
                                <span>150.000đ</span>
                            </div>
                            <input type="checkbox" class="spa-service" data-name="Cắt tỉa lông" data-price="150000">
                        </label>

                        <label class="spa-check-item">
                            <div>
                                <strong>Massage thư giãn</strong>
                                <span>200.000đ</span>
                            </div>
                            <input type="checkbox" class="spa-service" data-name="Massage thư giãn" data-price="200000">
                        </label>
                    </div>
                </div>

            </form>
        </div>

        {{-- RIGHT --}}
        <aside class="booking-summary">
            <div class="summary-card">
                <h3>Tóm tắt đặt phòng</h3>

                <div class="summary-group">
                    <span>Chi nhánh</span>
                    <strong>Pet Hotel Quận 7</strong>
                </div>

                <div class="summary-group">
                    <span>Loại phòng</span>
                    <strong id="summaryRoomName">Tiêu chuẩn</strong>
                </div>

                <div class="summary-group">
                    <span>Check-in</span>
                    <strong id="summaryCheckin">Chưa chọn</strong>
                </div>

                <div class="summary-group">
                    <span>Check-out</span>
                    <strong id="summaryCheckout">Chưa chọn</strong>
                </div>

                <div class="summary-group">
                    <span>Số đêm</span>
                    <strong id="summaryNights">0 đêm</strong>
                </div>

                <div class="summary-group">
                    <span>Đơn giá phòng</span>
                    <strong id="summaryRoomPrice">150.000đ</strong>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-spa">
                    <h4>Dịch vụ Spa</h4>
                    <div id="summarySpaList">
                        <p class="summary-empty">Chưa chọn dịch vụ</p>
                    </div>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-total-row">
                    <span>Tạm tính phòng</span>
                    <strong id="summaryRoomTotal">0đ</strong>
                </div>

                <div class="summary-total-row">
                    <span>Tổng dịch vụ</span>
                    <strong id="summarySpaTotal">0đ</strong>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-grand-total">
                    <span>Tổng cộng</span>
                    <strong id="summaryGrandTotal">0đ</strong>
                </div>

                <button type="submit" form="bookingForm" class="summary-pay-btn">
                    Thanh toán
                </button>
            </div>
        </aside>

    </div>
</section>

@endsection

@push('scripts')
<script>
    // =========================
    // Utils
    // =========================
    function formatCurrency(number) {
        return new Intl.NumberFormat('vi-VN').format(number) + 'đ';
    }

    function formatDateVN(date) {
        const d = String(date.getDate()).padStart(2, '0');
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const y = date.getFullYear();
        return `${d}/${m}/${y}`;
    }

    function sameDate(a, b) {
        return a && b &&
            a.getDate() === b.getDate() &&
            a.getMonth() === b.getMonth() &&
            a.getFullYear() === b.getFullYear();
    }

    function dateDiffInDays(start, end) {
        const oneDay = 1000 * 60 * 60 * 24;
        const diff = Math.round((end - start) / oneDay);
        return diff > 0 ? diff : 0;
    }

    // =========================
    // Calendar
    // =========================
    const calendarDays = document.getElementById('calendarDays');
    const calendarMonthYear = document.getElementById('calendarMonthYear');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');

    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    let checkinDate = null;
    let checkoutDate = null;

    const monthNames = [
        'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
        'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
    ];

    function renderCalendar(month, year) {
        calendarDays.innerHTML = '';
        calendarMonthYear.textContent = `${monthNames[month]} Năm ${year}`;

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        for (let i = 0; i < firstDay; i++) {
            const empty = document.createElement('div');
            empty.className = 'calendar-day empty';
            calendarDays.appendChild(empty);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dayEl = document.createElement('button');
            dayEl.type = 'button';
            dayEl.className = 'calendar-day';
            dayEl.textContent = day;

            if (sameDate(date, checkinDate)) {
                dayEl.classList.add('selected-checkin');
            }

            if (sameDate(date, checkoutDate)) {
                dayEl.classList.add('selected-checkout');
            }

            if (checkinDate && checkoutDate && date > checkinDate && date < checkoutDate) {
                dayEl.classList.add('in-range');
            }

            dayEl.addEventListener('click', function () {
                if (!checkinDate || (checkinDate && checkoutDate)) {
                    checkinDate = date;
                    checkoutDate = null;
                } else if (date > checkinDate) {
                    checkoutDate = date;
                } else {
                    checkinDate = date;
                    checkoutDate = null;
                }

                updateDateDisplays();
                renderCalendar(currentMonth, currentYear);
                updateSummary();
            });

            calendarDays.appendChild(dayEl);
        }
    }

    prevMonthBtn.addEventListener('click', function () {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar(currentMonth, currentYear);
    });

    nextMonthBtn.addEventListener('click', function () {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar(currentMonth, currentYear);
    });

    function updateDateDisplays() {
        const checkinDisplay = document.getElementById('checkinDisplay');
        const checkoutDisplay = document.getElementById('checkoutDisplay');
        const nightsPreview = document.getElementById('nightsPreview');

        checkinDisplay.value = checkinDate ? formatDateVN(checkinDate) : '';
        checkoutDisplay.value = checkoutDate ? formatDateVN(checkoutDate) : '';

        if (checkinDate && checkoutDate) {
            const nights = dateDiffInDays(checkinDate, checkoutDate);
            nightsPreview.textContent = `${nights} đêm · ${formatDateVN(checkinDate)} → ${formatDateVN(checkoutDate)}`;
        } else {
            nightsPreview.textContent = 'Chưa chọn đủ ngày nhận và ngày trả';
        }
    }

    // =========================
    // Room selection
    // =========================
    const roomCards = document.querySelectorAll('.booking-room-card-v2');

    roomCards.forEach(card => {
        card.addEventListener('click', function () {
            roomCards.forEach(item => item.classList.remove('active'));
            this.classList.add('active');

            const radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;

            updateSummary();
        });
    });

    // =========================
    // Summary
    // =========================
    function updateSummary() {
        const activeRoom = document.querySelector('.booking-room-card-v2.active');
        const roomName = activeRoom.dataset.roomName;
        const roomPrice = parseInt(activeRoom.dataset.roomPrice || 0);

        const nights = (checkinDate && checkoutDate) ? dateDiffInDays(checkinDate, checkoutDate) : 0;
        const roomTotal = roomPrice * nights;

        document.getElementById('summaryRoomName').textContent = roomName;
        document.getElementById('summaryRoomPrice').textContent = formatCurrency(roomPrice);
        document.getElementById('summaryCheckin').textContent = checkinDate ? formatDateVN(checkinDate) : 'Chưa chọn';
        document.getElementById('summaryCheckout').textContent = checkoutDate ? formatDateVN(checkoutDate) : 'Chưa chọn';
        document.getElementById('summaryNights').textContent = `${nights} đêm`;
        document.getElementById('summaryRoomTotal').textContent = formatCurrency(roomTotal);

        const spaChecked = document.querySelectorAll('.spa-service:checked');
        const summarySpaList = document.getElementById('summarySpaList');

        let spaHtml = '';
        let spaTotal = 0;

        if (spaChecked.length === 0) {
            spaHtml = `<p class="summary-empty">Chưa chọn dịch vụ</p>`;
        } else {
            spaChecked.forEach(item => {
                const name = item.dataset.name;
                const price = parseInt(item.dataset.price || 0);
                spaTotal += price;

                spaHtml += `
                    <div class="summary-spa-row">
                        <span>${name}</span>
                        <strong>${formatCurrency(price)}</strong>
                    </div>
                `;
            });
        }

        summarySpaList.innerHTML = spaHtml;
        document.getElementById('summarySpaTotal').textContent = formatCurrency(spaTotal);
        document.getElementById('summaryGrandTotal').textContent = formatCurrency(roomTotal + spaTotal);
    }

    document.querySelectorAll('.spa-service').forEach(item => {
        item.addEventListener('change', updateSummary);
    });

    renderCalendar(currentMonth, currentYear);
    updateDateDisplays();
    updateSummary();
</script>
@endpush