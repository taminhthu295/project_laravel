<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Thư viện sách')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="site-header__inner">
            <div class="site-header__title">Thư viện sách</div>
            <nav class="site-header__nav">
                <a href="{{ route('books.index') }}">Sách</a>
                <a href="{{ route('categories.index') }}">Thể loại</a>
            </nav>
        </div>
    </header>

    <main class="container">
        @if(session('success'))
            <div class="alert alert--success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert--error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>