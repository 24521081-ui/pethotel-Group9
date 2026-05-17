@extends('layouts.client')

@section('title', 'Khách sạn cho Chó')

@section('content')

{{-- Hero --}}
<section class="dog-hero" style="background-image: url('{{ asset('assets/client/images/dog-hero.jpg') }}')">
    <div class="dog-hero-overlay"></div>

    <div class="dog-hero-content">
        <h1>Khách sạn cho Chó</h1>
        <p>Không gian rộng rãi, hoạt động phong phú cho những người bạn trung thành</p>

        <a href="{{ url('/booking') }}" class="dog-primary-btn">
            Đặt phòng ngay
        </a>
    </div>
</section>

{{-- Care Standards --}}
<section class="dog-section dog-standards">
    <div class="dog-container">
        <h2 class="dog-section-title">Tiêu chuẩn chăm sóc</h2>

        <div class="standard-grid">
            <div class="standard-card">
                <div class="standard-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3>An toàn tuyệt đối</h3>
                <p>Khu vực tách biệt, giám sát 24/7</p>
            </div>

            <div class="standard-card">
                <div class="standard-icon">
                    <i class="fa-regular fa-heart"></i>
                </div>
                <h3>Chăm sóc tận tâm</h3>
                <p>Nhân viên yêu chó, kinh nghiệm lâu năm</p>
            </div>

            <div class="standard-card">
                <div class="standard-icon">
                    <i class="fa-solid fa-wave-square"></i>
                </div>
                <h3>Hoạt động phong phú</h3>
                <p>Chạy nhảy, vui chơi mỗi ngày</p>
            </div>

            <div class="standard-card">
                <div class="standard-icon">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <h3>Dinh dưỡng khoa học</h3>
                <p>Thực đơn cân đối cho từng giống chó</p>
            </div>
        </div>
    </div>
</section>

{{-- Rooms --}}
<section class="dog-section dog-rooms">
    <div class="dog-container">
        <h2 class="dog-section-title">Các loại phòng</h2>

        {{-- Room 1 --}}
        <div class="dog-room-card">
            <div class="dog-room-image">
                <img src="{{ asset('assets/client/images/dog-room-standard.jpg') }}" alt="Phòng Thường">
            </div>

            <div class="dog-room-content">
                <div class="dog-room-header">
                    <div>
                        <h3>Phòng Thường</h3>
                        <p class="room-area">Diện tích: 3m²</p>
                    </div>

                    <div class="room-price">
                        <strong>200.000đ</strong>
                        <span>/ngày</span>
                    </div>
                </div>

                <div class="room-feature-grid">
                    <ul>
                        <li><i class="fa-solid fa-check"></i> Điều hòa</li>
                        <li><i class="fa-solid fa-check"></i> Đồ chơi cơ bản</li>
                        <li><i class="fa-solid fa-check"></i> Bát ăn riêng</li>
                    </ul>

                    <ul>
                        <li><i class="fa-solid fa-check"></i> Giường êm ái</li>
                        <li><i class="fa-solid fa-check"></i> Camera 24/7</li>
                    </ul>
                </div>

                <div class="room-description">
                    <h4>Mô tả chi tiết</h4>
                    <p>
                        Phòng Thường phù hợp với các bé chó cần không gian nghỉ ngơi cơ bản,
                        sạch sẽ và an toàn. Mỗi bé được bố trí khu vực riêng, có giường nằm,
                        bát ăn cá nhân và được nhân viên theo dõi thường xuyên trong ngày.
                    </p>
                </div>

                <div class="room-health-note">
                    <h4>Y tế & chăm sóc</h4>
                    <p>
                        Trước khi nhận phòng, thú cưng cần được kiểm tra tình trạng sức khỏe
                        và xác nhận thông tin tiêm phòng. Nếu bé có dấu hiệu mệt, bỏ ăn hoặc
                        có vấn đề bất thường, nhân viên sẽ thông báo ngay cho chủ nuôi để xử lý kịp thời.
                    </p>
                </div>

                <a href="{{ url('/booking') }}" class="dog-room-btn">Chọn phòng này</a>
            </div>
        </div>

        {{-- Room 2 --}}
        <div class="dog-room-card reverse">
            <div class="dog-room-image">
                <img src="{{ asset('assets/client/images/dog-room-vip.jpg') }}" alt="Phòng VIP">
            </div>

            <div class="dog-room-content">
                <div class="dog-room-header">
                    <div>
                        <h3>Phòng VIP</h3>
                        <p class="room-area">Diện tích: 5m²</p>
                    </div>

                    <div class="room-price">
                        <strong>350.000đ</strong>
                        <span>/ngày</span>
                    </div>
                </div>

                <div class="room-feature-grid">
                    <ul>
                        <li><i class="fa-solid fa-check"></i> Không gian rộng</li>
                        <li><i class="fa-solid fa-check"></i> Bữa ăn đặc biệt</li>
                        <li><i class="fa-solid fa-check"></i> Sân chơi riêng</li>
                    </ul>

                    <ul>
                        <li><i class="fa-solid fa-check"></i> Đồ chơi cao cấp</li>
                        <li><i class="fa-solid fa-check"></i> Tắm mỗi ngày</li>
                    </ul>
                </div>

                <div class="room-description">
                    <h4>Mô tả chi tiết</h4>
                    <p>
                        Phòng VIP dành cho các bé chó cần không gian rộng hơn và chế độ chăm sóc
                        kỹ hơn. Bé được vui chơi trong khu vực riêng, có đồ chơi cao cấp,
                        chế độ ăn linh hoạt và lịch sinh hoạt được ghi chú theo thói quen của chủ nuôi.
                    </p>
                </div>

                <div class="room-health-note">
                    <h4>Y tế & chăm sóc</h4>
                    <p>
                        Nhân viên sẽ theo dõi biểu hiện ăn uống, vận động và tinh thần của bé mỗi ngày.
                        Trường hợp thú cưng có tiền sử bệnh, dị ứng hoặc cần uống thuốc,
                        thông tin sẽ được ghi chú riêng để đảm bảo chăm sóc đúng cách.
                    </p>
                </div>

                <a href="{{ url('/booking') }}" class="dog-room-btn">Chọn phòng này</a>
            </div>
        </div>

        {{-- Room 3 --}}
        <div class="dog-room-card">
            <div class="dog-room-image">
                <img src="{{ asset('assets/client/images/dog-room-luxury.jpg') }}" alt="Phòng Luxury">
            </div>

            <div class="dog-room-content">
                <div class="dog-room-header">
                    <div>
                        <h3>Phòng Luxury</h3>
                        <p class="room-area">Diện tích: 8m²</p>
                    </div>

                    <div class="room-price">
                        <strong>500.000đ</strong>
                        <span>/ngày</span>
                    </div>
                </div>

                <div class="room-feature-grid">
                    <ul>
                        <li><i class="fa-solid fa-check"></i> Suite riêng biệt</li>
                        <li><i class="fa-solid fa-check"></i> Thực đơn VIP</li>
                        <li><i class="fa-solid fa-check"></i> Vườn chơi riêng</li>
                    </ul>

                    <ul>
                        <li><i class="fa-solid fa-check"></i> Spa hằng ngày</li>
                        <li><i class="fa-solid fa-check"></i> Chăm sóc 1-1</li>
                    </ul>
                </div>

                <div class="room-description">
                    <h4>Mô tả chi tiết</h4>
                    <p>
                        Phòng Luxury là lựa chọn cao cấp nhất, phù hợp với các bé cần sự riêng tư,
                        không gian lớn và chăm sóc cá nhân hóa. Bé có khu vui chơi riêng,
                        thực đơn đặc biệt, dịch vụ spa hằng ngày và nhân viên theo dõi sát sao.
                    </p>
                </div>

                <div class="room-health-note">
                    <h4>Y tế & chăm sóc</h4>
                    <p>
                        Với gói Luxury, thú cưng được theo dõi sức khỏe theo từng ca chăm sóc.
                        Các thay đổi về ăn uống, vận động hoặc dấu hiệu bất thường sẽ được ghi nhận
                        và báo lại cho chủ nuôi khi cần thiết.
                    </p>
                </div>

                <a href="{{ url('/booking') }}" class="dog-room-btn">Chọn phòng này</a>
            </div>
        </div>
    </div>
</section>

{{-- Daily Care Process --}}
<section class="dog-section dog-daily-process">
    <div class="dog-container">
        <h2 class="dog-section-title">Quy trình chăm sóc hằng ngày</h2>

        <div class="daily-process-grid">
            <div class="daily-step">
                <span>1</span>
                <h3>Đón tiếp & Check-in</h3>
                <p>Khám sức khỏe ban đầu</p>
            </div>

            <div class="daily-step">
                <span>2</span>
                <h3>Sắp xếp phòng</h3>
                <p>Bố trí phòng phù hợp</p>
            </div>

            <div class="daily-step">
                <span>3</span>
                <h3>Chế độ ăn uống</h3>
                <p>Theo thời gian biểu cố định</p>
            </div>

            <div class="daily-step">
                <span>4</span>
                <h3>Vui chơi vận động</h3>
                <p>Hoạt động ngoài trời</p>
            </div>

            <div class="daily-step">
                <span>5</span>
                <h3>Chăm sóc y tế</h3>
                <p>Theo dõi sức khỏe hằng ngày</p>
            </div>
        </div>

        {{-- Bổ sung theo ghi chú ảnh cuối --}}
        <div class="care-note-box">
            <h3>Quy trình & Y tế được áp dụng trong suốt thời gian lưu trú</h3>

            <div class="care-note-content">
                <div class="care-note-item">
                    <h4>Quy trình tiếp nhận</h4>
                    <p>
                        Khi thú cưng đến khách sạn, nhân viên sẽ kiểm tra sổ tiêm dại hoặc
                        thông tin vaccine cần thiết, cân nặng, tình trạng sức khỏe ban đầu,
                        sau đó ghi chú thói quen ăn uống, giờ sinh hoạt và các yêu cầu riêng
                        trước khi ký nhận lưu trú.
                    </p>
                </div>

                <div class="care-note-item">
                    <h4>Y tế & xử lý tình huống</h4>
                    <p>
                        Nếu thú cưng có dấu hiệu bất thường như mệt mỏi, bỏ ăn, tiêu hóa kém
                        hoặc gặp sự cố sức khỏe trong thời gian lưu trú, nhân viên sẽ tách bé
                        sang khu vực theo dõi, liên hệ chủ nuôi và hỗ trợ đưa đến cơ sở thú y
                        khi cần thiết.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="dog-cta">
    <div class="dog-container">
        <h2>Để chú cún được nghỉ dưỡng tuyệt vời</h2>
        <p>Đặt phòng ngay để nhận ưu đãi hấp dẫn</p>

        <a href="{{ url('/booking') }}" class="dog-cta-btn">
            Đặt phòng ngay
        </a>
    </div>
</section>

@endsection