@extends('layouts.auth')

@section('title', 'Đăng kí')

@section('content')

<div class="auth-page">
    <div class="auth-container register-container">

        <div class="auth-image register-image">
            <img src="{{ asset('assets/auth/images/login-pet.jpg') }}" alt="Pet Hotel Register">
        </div>

        <div class="auth-form-wrapper">
            <form action="{{ url('/register') }}" method="POST" class="auth-form register-form">
                @csrf

                <h1>Tạo tài khoản mới</h1>

                <div class="form-group">
                    <label for="name">Họ và tên</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>

                    <div class="password-input">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                        >

                        <button type="button" class="toggle-password" data-target="password">
                            <i class="fa-regular fa-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Nhập lại mật khẩu</label>

                    <div class="password-input">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required
                        >

                        <button type="button" class="toggle-password" data-target="password_confirmation">
                            <i class="fa-regular fa-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="login-submit register-submit">
                    Đăng kí
                </button>

                <a href="#" class="google-btn">
                    <span class="google-icon">G</span>
                    <span>Tiếp tục với Google</span>
                </a>

                <div class="register-row login-row">
                    <span>Đã có tài khoản?</span>
                    <a href="{{ url('/login') }}">Đăng nhập</a>
                </div>

            </form>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    const toggleButtons = document.querySelectorAll('.toggle-password');

    toggleButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            const isPassword = input.type === 'password';

            input.type = isPassword ? 'text' : 'password';

            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    });
</script>
@endpush