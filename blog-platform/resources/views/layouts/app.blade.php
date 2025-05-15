<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'My Blog')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">

    <header class="p-4 bg-gray-100 dark:bg-gray-800">
        <button onclick="document.documentElement.classList.toggle('dark')" class="float-right">
            Toggle Dark Mode
        </button>
        <h1 class="text-xl">My Blog</h1>
    </header>

    <main class="p-6">
        @yield('content')
    </main>

</body>
</html>
