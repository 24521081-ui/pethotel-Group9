<footer class="client-footer">
    <div class="footer-container">

        <div class="footer-column">
            <h3>Liên hệ</h3>

            <ul class="footer-contact">
                <li>
                    <i class="fa-solid fa-location-dot"></i>
                    <span>123 đường Lê Lợi, Quận 1,<br>TP.HCM</span>
                </li>

                <li>
                    <i class="fa-solid fa-phone"></i>
                    <span>Hotline: 1900 1234</span>
                </li>

                <li>
                    <i class="fa-solid fa-envelope"></i>
                    <span>contact@pethotel.com</span>
                </li>
            </ul>

            <div class="footer-socials">
                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
            </div>
        </div>

        <div class="footer-column">
            <h3>Đối tác</h3>

            <div class="partner-list">
                <div class="partner-card">Pedigree</div>
                <div class="partner-card">Royal Canin</div>
                <div class="partner-card">Whiskas</div>
            </div>
        </div>

        <div class="footer-column">
    <h3>Chính sách</h3>

    <ul class="footer-links">
        <li>
            <a href="{{ url('/policies#quy-dinh-chung') }}">
                Chính sách đặt phòng
            </a>
        </li>

        <li>
            <a href="{{ url('/policies#hoan-tien') }}">
                Chính sách hủy phòng
            </a>
        </li>

        <li>
            <a href="{{ url('/policies#quy-dinh-chung') }}">
                Yêu cầu sức khỏe
            </a>
        </li>

        <li>
            <a href="{{ url('/policies#bao-mat') }}">
                Chính sách bảo mật
            </a>
        </li>

        <li>
            <a href="{{ url('/policies') }}">
                Điều khoản sử dụng
            </a>
        </li>
    </ul>
</div>

        <div class="footer-column">
            <h3>Nhận ưu đãi</h3>

            <p class="footer-desc">
                Đăng ký email để nhận thông tin về ưu đãi và chương trình khuyến mãi
            </p>

            <form action="#" method="POST" class="subscribe-form">
                @csrf
                <input type="email" name="email" placeholder="Nhập email của bạn">
                <button type="submit">Đăng ký</button>
            </form>
        </div>

    </div>

    <div class="footer-bottom">
        <p>© 2026 Pet Hotel. All rights reserved.</p>
    </div>
</footer>