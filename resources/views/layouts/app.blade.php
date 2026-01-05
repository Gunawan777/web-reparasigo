<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RepairGo') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- App CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<!-- ✅ padding-top di BODY -->
<body style="padding-top: 72px;">
<div id="app">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                {{ config('app.name', 'RepairGo') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <!-- LEFT -->
                <ul class="navbar-nav me-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Dashboard</a>
                        </li>
                    @endauth
                    
                    @if(!Auth::check() || Auth::user()->role === 'teknisi')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('layanan.index') }}">Layanan</a>
                        </li>
                    @endif
                    @if(!Auth::check() || Auth::user()->role === 'pelanggan')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('teknisi.index') }}">Teknisi</a>
                        </li>
                    @endif


                    @auth
                        @if (Auth::user()->role === 'pelanggan')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.bookings.index') }}">
                                    Pesanan Saya
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <!-- RIGHT -->
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <span class="nav-link">{{ Auth::user()->name }}</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                        </li>
                    @endguest
                </ul>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

            </div>
        </div>
    </nav>

    <!-- CONTENT -->
    <main class="py-4">
        <div class="container">
            <div class="row">
                @hasSection('sidebar')
                    <div class="col-md-3">@yield('sidebar')</div>
                    <div class="col-md-9">@yield('content')</div>
                @else
                    <div class="col-md-12">@yield('content')</div>
                @endif
            </div>
        </div>
    </main>

</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Define API_URL globally
    const API_URL = "{{ url('/api') }}";

    // Global fetchApi utility function
    async function fetchApi(url, method = 'GET', data = null) {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        };

        // Add CSRF token for non-GET requests if available
        if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(method.toUpperCase())) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
            }
        }

        const config = {
            method: method,
            url: url,
            headers: headers,
        };

        if (data && (method.toUpperCase() === 'POST' || method.toUpperCase() === 'PUT' || method.toUpperCase() === 'PATCH')) {
            config.data = data;
        }

        try {
            const response = await axios(config);
            if (response.status >= 200 && response.status < 300) {
                return response.data;
            } else {
                // Axios typically throws for non-2xx status, but this is a fallback
                const errorData = response.data || { message: 'An unknown error occurred.' };
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }
        } catch (error) {
            console.error('API call error:', error.response ? error.response.data : error.message);
            const errorMessage = error.response && error.response.data && error.response.data.message
                                 ? error.response.data.message
                                 : error.message;
            // Display alert for user feedback
            alert(`Terjadi kesalahan: ${errorMessage}`);
            throw error; // Re-throw to be caught by the caller
        }
    }
</script>
</body>
</html>
