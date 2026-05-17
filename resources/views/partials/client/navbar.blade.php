<header class="client-header">
    <div class="client-navbar">

        <a href="{{ url('/') }}" class="brand">
            <img src="{{ asset('assets/client/images/logo.png') }}" alt="Pet Hotel Logo" class="brand-logo">
            <span>Pet Hotel</span>
        </a>

        <nav class="nav-menu">
            <a href="{{ url('/') }}" class="nav-link active">Trang chủ</a>

            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle">
                    Dịch vụ
                    <i class="fa-solid fa-chevron-down"></i>
                </a>

                <div class="dropdown-menu">
                    <a href="{{ url('/services/grooming') }}" class="dropdown-link">
                        Dịch vụ Grooming
                    </a>
                </div>
            </div>

            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle">
                    Khách sạn thú cưng
                    <i class="fa-solid fa-chevron-down"></i>
                </a>

                <div class="dropdown-menu">
                    <a href="{{ url('/rooms/dog') }}" class="dropdown-link">
                        Khách sạn cho chó
                    </a>

                    <a href="{{ url('/rooms/cat') }}" class="dropdown-link">
                        Khách sạn cho mèo
                    </a>
                </div>
            </div>

            <a href="{{ url('/branches') }}" class="nav-link">Hệ thống chi nhánh</a>

            <a href="{{ url('/booking') }}" class="nav-link">Đặt phòng</a>
        </nav>

        <div class="user-dropdown">
            <button type="button" class="user-dropdown-toggle">
                <span class="user-avatar">NV</span>
                <span class="user-name">Nguyễn Văn A</span>
                <i class="fa-solid fa-chevron-down"></i>
            </button>

            <div class="user-dropdown-menu">
                <a href="{{ url('/profile') }}" class="user-dropdown-item">
                    <i class="fa-regular fa-user"></i>
                    Hồ sơ cá nhân
                </a>

                <a href="{{ url('/pets') }}" class="user-dropdown-item">
                    <i class="fa-regular fa-circle-dot"></i>
                    Thú cưng của tôi
                </a>

                <a href="{{ url('/bookings') }}" class="user-dropdown-item">
                    <i class="fa-regular fa-calendar"></i>
                    Lịch sử đặt phòng
                </a>

                <div class="dropdown-line"></div>

                <a href="{{ url('/login') }}" class="user-dropdown-item logout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Đăng xuất
                </a>
            </div>
        </div>

    </div>
</header>