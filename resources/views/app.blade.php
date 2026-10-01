@php($p = $portfolio['profile'])
<!DOCTYPE html>
<html lang="en" class="bg-ink">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#07070a">

    <title>{{ $p['name'] }} — {{ $p['role'] }}</title>
    <meta name="description" content="{{ $p['headline'] }}">
    <link rel="canonical" href="{{ url('/') }}">

    <meta property="og:type" content="profile">
    <meta property="og:title" content="{{ $p['name'] }} — {{ $p['role'] }}">
    <meta property="og:description" content="{{ $p['headline'] }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='8' fill='%2307070a'/><text x='16' y='22' font-family='monospace' font-size='15' font-weight='700' text-anchor='middle' fill='%23d4ff4f'>SM</text></svg>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300..700&family=Geist+Mono:wght@400;500&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $p['name'],
            'jobTitle' => $p['role'],
            'email' => 'mailto:'.$p['email'],
            'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Kolkata', 'addressCountry' => 'IN'],
            'worksFor' => ['@type' => 'Organization', 'name' => $p['current']['company']],
            'alumniOf' => $portfolio['education']['school'],
            'knowsAbout' => $portfolio['stack'],
            'sameAs' => array_column($p['links'], 'url'),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
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
