@php($p = $portfolio['profile'])
@php($seo = $portfolio['seo'] ?? [])
@php($title = $seo['title'] ?? $p['name'].' — '.$p['role'])
@php($description = $seo['description'] ?? $p['headline'])
@php($image = asset('og-image.png'))
@php($google = config('services.google'))
@php($track = app()->isProduction())
<!DOCTYPE html>
<html lang="en" class="bg-ink">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#07070a">
    {{-- With JS on, the app replaces the crawlable copy in #root; hide it until then so it never flashes --}}
    <script>document.documentElement.classList.add('js')</script>
    <style>.js #root > .static-copy { display: none }</style>

    @if ($track && $google['gtm'])
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer',@json($google['gtm']));</script>
    @endif
    @if ($track && $google['ga4'])
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $google['ga4'] }}"></script>
        <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config',@json($google['ga4']));</script>
    @endif

    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="author" content="{{ $p['name'] }}">
    <meta name="robots" content="{{ app()->isProduction() ? 'index, follow, max-image-preview:large, max-snippet:-1' : 'noindex, nofollow' }}">
    @if ($google['site_verification'])
        <meta name="google-site-verification" content="{{ $google['site_verification'] }}">
    @endif
    <link rel="canonical" href="{{ url('/') }}">
    <link rel="sitemap" type="application/xml" href="{{ url('sitemap.xml') }}">

    <meta property="og:type" content="profile">
    <meta property="og:site_name" content="{{ $p['name'] }}">
    <meta property="og:locale" content="en_IN">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
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
    <meta name="twitter:description" content="{{ $description }}">
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
    @if ($track && $google['gtm'])
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $google['gtm'] }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    <div id="root">
        {{-- The full portfolio as plain HTML for crawlers, link previews and no-JS visitors. React replaces it on load. --}}
        <main class="static-copy" style="max-width:760px;margin:64px auto;padding:0 20px;font-family:system-ui,sans-serif;line-height:1.6;color:#ededef">
            <header>
                <h1>{{ $p['name'] }} — {{ $p['role'] }}</h1>
                <p>{{ $p['location'] }} · <a href="mailto:{{ $p['email'] }}">{{ $p['email'] }}</a> · {{ $p['availability'] }}</p>
                <p>{{ $p['headline'] }}</p>
            </header>

            <section>
                <h2>About</h2>
                <p>{{ $portfolio['manifesto'] }}</p>
            </section>

            <section>
                <h2>Experience</h2>
                @foreach ($portfolio['experience'] as $job)
                    <article>
                        <h3>{{ $job['role'] }} — {{ $job['company'] }}</h3>
                        <p>{{ $job['period'] }} · {{ $job['location'] ?? '' }}</p>
                        <ul>@foreach ($job['points'] as $pt)<li>{{ $pt }}</li>@endforeach</ul>
                    </article>
                @endforeach
            </section>

            <section>
                <h2>Projects</h2>
                @foreach ($portfolio['projects'] as $project)
                    <article>
                        <h3>{{ $project['name'] }} — {{ $project['kind'] }}</h3>
                        <p>{{ $project['blurb'] }}</p>
                        <p>Built with {{ implode(', ', $project['stack'] ?? []) }}</p>
                    </article>
                @endforeach
            </section>

            <section>
                <h2>Skills</h2>
                <ul>
                    @foreach ($portfolio['skills'] as $group)
                        <li><strong>{{ $group['group'] }}:</strong> {{ collect($group['items'])->map(fn ($i) => ltrim(explode('|', $i)[0], '*'))->implode(', ') }}</li>
                    @endforeach
                </ul>
            </section>

            <section>
                <h2>Education</h2>
                <p>{{ $portfolio['education']['degree'] }}, {{ $portfolio['education']['school'] }} ({{ $portfolio['education']['period'] }})</p>
            </section>

            <section>
                <h2>Contact</h2>
                <ul>
                    <li><a href="mailto:{{ $p['email'] }}">{{ $p['email'] }}</a></li>
                    @foreach ($p['links'] as $link)
                        <li><a href="{{ $link['url'] }}" rel="me">{{ $link['label'] }} — {{ $link['handle'] }}</a></li>
                    @endforeach
                    <li><a href="{{ route('resume') }}">Download résumé (PDF)</a></li>
                </ul>
            </section>
        </main>
    </div>

    <script>window.__PORTFOLIO__ = @json($portfolio);</script>
</body>
</html>
