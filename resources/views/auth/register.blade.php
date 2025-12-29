<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | My App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/auth.css'])
</head>
<body>
    <div class="signup-container">
        <div class="signup-card">
            <h2>Create Account</h2>
            <p>Join our community today</p>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="input-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="john@example.com" required>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="johndoe123" required>
                    @error('username')
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

                <button type="submit" class="signup-btn">Sign Up</button>
            </form>

            <div class="footer-text">
                Already have an account? <a href="/login">Log In</a>
            </div>
        </div>
    </div>
</body>
</html>