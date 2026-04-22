<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - HealthHub</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/healthhub-favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">
    @include('admin.layout.sidebar')
    
    <div class="ml-64">
        @include('admin.layout.header')
        
        <main class="p-6 bg-gray-50 min-h-screen">
            @yield('content')
        </main>
        
        @include('admin.layout.footer')
    </div>
</body>
</html>
