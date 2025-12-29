<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In | My App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/auth.css'])
</head>
<body>
    <div class="signup-container">
        <div class="signup-card">
            <h2>Welcome Back</h2>
            <p>Please enter your details</p>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="john@example.com" required autofocus>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="remember-me">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me</label>
                </div>

                <button type="submit" class="signup-btn">Log In</button>
            </form>

            <div class="footer-text">
                Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
            </div>
        </div>
    </div>
</body>
</html>