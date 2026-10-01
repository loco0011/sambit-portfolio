<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0b121b">
    <title>SM-01 Control Unit</title>

    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%231e2a38'/><circle cx='16' cy='16' r='7' fill='%235fe3ff'/></svg>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Doto:wght@500;800;900&family=Inter+Tight:wght@400;500;600;700&family=Martian+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    @viteReactRefresh
    @vite(['resources/css/admin.css', 'resources/js/admin/main.jsx'])
</head>
<body>
    <div id="admin"></div>
    <script>
        window.__ADMIN__ = @json(['user' => $user, 'site' => url('/')]);
    </script>
</body>
</html>
