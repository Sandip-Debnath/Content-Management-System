<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My CMS')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    @include('partials.nav') {{-- optional header --}}
    <main>
        @yield('content')
    </main>
    @include('partials.footer') {{-- optional footer --}}
</body>

</html>
