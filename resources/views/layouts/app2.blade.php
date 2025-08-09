<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'CMS') }}</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    {{-- Custom Styles --}}
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        nav.navbar {
            background: linear-gradient(90deg, #0d6efd, #0b5ed7);
        }

        .navbar-brand,
        .nav-link,
        .dropdown-item {
            color: white !important;
        }

        .feed-card {
            border-radius: 12px;
            border: none;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .feed-card img {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            max-height: 350px;
            object-fit: cover;
        }

        .comment-box textarea {
            resize: none;
            border-radius: 8px;
        }

        footer {
            background: #fff;
            padding: 15px;
            font-size: 14px;
            color: #6c757d;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
    </style>
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('frontend.articles.index') }}">
                <i class="fa-solid fa-newspaper"></i> {{ config('app.name', 'CMS') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fa-solid fa-user"></i>
                                {{ auth()->user()->name }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                            </a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i>
                                Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="container my-4">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer>
        &copy; {{ date('Y') }} {{ config('app.name', 'CMS') }}. All rights reserved.
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
