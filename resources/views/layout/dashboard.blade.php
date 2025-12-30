@include('layout.header')
@include('layout.sidebar')
<main class="ml-64 flex-1 p-8">
@yield('content')
</main>
@include('layout.footer')