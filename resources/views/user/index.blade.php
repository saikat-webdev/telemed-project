<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | My App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/auth.css', 'resources/css/profile.css'])
</head>
<body>
    <div class="signup-container"> <div class="profile-card">
            <div class="profile-header">
                <div class="avatar-circle">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <h2>{{ Auth::user()->name }}</h2>
                <p>@ {{ Auth::user()->username }}</p>
            </div>

            <hr class="divider">

            <div class="profile-body">
                <div class="info-group">
                    <label>Email Address</label>
                    <p>{{ Auth::user()->email }}</p>
                </div>

                <div class="info-group">
                    <label>Account Created</label>
                    <p>{{ Auth::user()->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <div class="profile-actions">
                <a href="#" class="edit-btn">Edit Profile</a>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-link">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>