@php($p = $portfolio['profile'])
<!DOCTYPE html>
<html lang="en" class="bg-ink">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#07070a">

    @php($title = $p['name'].' — '.$p['role'])
    @php($image = asset('og-image.png'))
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $p['headline'] }}">
    <meta name="author" content="{{ $p['name'] }}">
    <meta name="keywords" content="{{ $p['name'] }}, {{ $p['role'] }}, {{ implode(', ', array_slice($portfolio['stack'], 0, 12)) }}, {{ $p['location'] }}">
    <meta name="robots" content="{{ app()->isProduction() ? 'index, follow, max-image-preview:large' : 'noindex, nofollow' }}">
    <link rel="canonical" href="{{ url('/') }}">
    <link rel="sitemap" type="application/xml" href="{{ url('sitemap.xml') }}">

    <meta property="og:type" content="profile">
    <meta property="og:site_name" content="{{ $p['name'] }}">
    <meta property="og:locale" content="en_IN">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $p['headline'] }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ $image }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $p['name'] }}, {{ $p['role'] }}">
    <meta property="profile:first_name" content="{{ strtok($p['name'], ' ') }}">
    <meta property="profile:last_name" content="{{ trim(strstr($p['name'], ' ') ?: '') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $p['headline'] }}">
    <meta name="twitter:image" content="{{ $image }}">
    <meta name="twitter:image:alt" content="{{ $p['name'] }}, {{ $p['role'] }}">

    <link rel="icon" href="/favicon.ico" sizes="32x32">
    <link rel="icon" type="image/png" href="/icon-192.png" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300..700&family=Geist+Mono:wght@400;500&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">

    <script type="application/ld+json">
        {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
    </script>

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/main.jsx'])
</head>
<body class="bg-ink text-fg antialiased">
    <div id="root"></div>

    {{-- Recruiter / no-JS fallback: the full résumé as plain semantic HTML --}}
    <noscript>
        <main style="max-width:720px;margin:64px auto;padding:0 20px;font-family:system-ui;color:#ededef">
            <h1>{{ $p['name'] }}</h1>
            <p>{{ $p['role'] }} · {{ $p['location'] }} · <a href="mailto:{{ $p['email'] }}">{{ $p['email'] }}</a></p>
            <p>{{ $p['headline'] }}</p>
            <h2>Experience</h2>
            @foreach ($portfolio['experience'] as $job)
                <h3>{{ $job['role'] }} — {{ $job['company'] }} <small>({{ $job['period'] }})</small></h3>
                <ul>@foreach ($job['points'] as $pt)<li>{{ $pt }}</li>@endforeach</ul>
            @endforeach
            <p><a href="{{ route('resume') }}">Download résumé (PDF)</a></p>
        </main>
    </noscript>

    <script>window.__PORTFOLIO__ = @json($portfolio);</script>
</body>
</html>
