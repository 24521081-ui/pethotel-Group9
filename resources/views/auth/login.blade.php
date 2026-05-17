@extends('layouts.auth')

@section('title', 'Đăng nhập')

@section('content')

<div class="auth-page">
    <div class="auth-container">

        <div class="auth-image">
            <img src="{{ asset('assets/auth/images/login-pet.jpg') }}" alt="Pet Hotel Login">
        </div>

        <div class="auth-form-wrapper">
            <form action="{{ url('/login') }}" method="POST" class="auth-form">
                @csrf

                <h1>Chào mừng trở lại</h1>

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

                <div class="form-group password-group">
                    <label for="password">Mật khẩu</label>

                    <div class="password-input">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                        >

                        <button type="button" class="toggle-password">
                            <i class="fa-regular fa-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember-box">
                        <span>Ghi nhớ đăng nhập</span>
                        <input type="checkbox" name="remember">
                    </label>

                    <a href="{{ url('/forgot-password') }}">Quên mật khẩu</a>
                </div>

                <button type="submit" class="login-submit">
                    Đăng nhập
                </button>

                <a href="#" class="google-btn">
                    <span class="google-icon">G</span>
                    <span>Tiếp tục với Google</span>
                </a>

                <div class="register-row">
                    <span>Chưa có tài khoản?</span>
                    <a href="{{ url('/register') }}">Đăng kí</a>
                </div>

            </form>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    const togglePassword = document.querySelector('.toggle-password');
    const passwordInput = document.querySelector('#password');
    const passwordIcon = document.querySelector('.toggle-password i');

    togglePassword.addEventListener('click', function () {
        const isPassword = passwordInput.type === 'password';

        passwordInput.type = isPassword ? 'text' : 'password';
        passwordIcon.classList.toggle('fa-eye');
        passwordIcon.classList.toggle('fa-eye-slash');
    });
</script>
@endpush