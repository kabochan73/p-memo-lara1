<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Laravel Memo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
    <header class="bg-white border-b border-gray-200 px-6 py-4">
        <span class="text-lg font-bold">Laravel Memo</span>
    </header>

    <main class="max-w-2xl mx-auto px-4 py-8">
        @if (session('status'))
            <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
