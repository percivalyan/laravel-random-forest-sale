@include('layouts.partials.header')
@include('layouts.partials.sidebar')

<main>
    <div class="container-fluid py-2">
        @yield('content')
    </div>
</main>

@include('layouts.partials.footer')
@include('layouts.partials.script')
