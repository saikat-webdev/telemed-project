@include('patient.layout.header')
@include('patient.layout.sidebar')

<main class="ml-0 lg:ml-64 flex-1 p-4 lg:p-8">
    @yield('content')
</main>

@include('patient.layout.footer')