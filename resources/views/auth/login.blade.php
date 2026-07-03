@extends('layouts.admin')

@section('content')
    <div class="login-card">
        <div class="login-brand">
            <div class="login-brand-mark">GMS</div>
            <div>
                <strong>Garment Management System</strong>
                <small>Admin sign in</small>
            </div>
        </div>

        <h1>Welcome back</h1>
        <p>Use the admin credentials to access the dashboard.</p>

        <div class="login-note">
            Login: <strong>admin#gms.com</strong> | Password: <strong>password</strong>
        </div>

        <form action="{{ route('login.store') }}" method="POST">
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="text" name="email" value="{{ old('email') }}" placeholder="username@gmail.com" required autofocus>
                @error('email')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Password" required>
                @error('password')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <label style="display:flex;align-items:center;gap:8px;">
                    <input type="checkbox" name="remember" value="1">
                    Remember me
                </label>
                <a href="{{ route('home') }}">Go to frontend</a>
            </div>

            <div style="margin-top:20px;">
                <button class="button primary" type="submit" style="width:100%;">Sign in</button>
            </div>
        </form>

        <div class="social-row" aria-label="Continue with social accounts">
            <div class="social-btn">G</div>
            <div class="social-btn">GH</div>
            <div class="social-btn">f</div>
        </div>

        <div class="login-footer-link">
            Don't have an account? <a href="{{ route('home') }}">Go to frontend</a>
        </div>
    </div>
@endsection
