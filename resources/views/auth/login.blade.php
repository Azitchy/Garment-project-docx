@extends('layouts.admin')

@section('content')
    <div class="login-card">
        <div class="brand" style="border:0;padding:0 0 18px;">
            <div class="brand-mark">GMS</div>
            <div>
                <strong>Garment Management System</strong>
                <small>Admin sign in</small>
            </div>
        </div>

        <h1 style="margin:0 0 10px;font-size:2rem;">Welcome back</h1>
        <p class="subtle" style="margin-bottom:8px;">Use the admin credentials to access the dashboard.</p>

        <div style="padding:12px 14px;border-radius:14px;background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;margin:14px 0 6px;">
            Login: <strong>admin#gms.com</strong> | Password: <strong>password</strong>
        </div>

        <form action="{{ route('login.store') }}" method="POST">
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="text" name="email" value="{{ old('email') }}" placeholder="admin#gms.com" required autofocus>
                @error('email')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="password" required>
                @error('password')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <label style="display:flex;align-items:center;gap:8px;color:#475569;">
                    <input type="checkbox" name="remember" value="1">
                    Remember me
                </label>
                <a href="{{ route('home') }}" style="color:#2563eb;">Go to frontend</a>
            </div>

            <div style="margin-top:18px;">
                <button class="button primary" type="submit" style="width:100%;">Sign in</button>
            </div>
        </form>
    </div>
@endsection
