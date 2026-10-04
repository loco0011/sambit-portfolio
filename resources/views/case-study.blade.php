@php($p = $portfolio['profile'])
@php($title = $project['name'].' — '.$project['kind'].' | Case study by '.$p['name'])
@php($description = $project['blurb'])
@php($url = route('work.show', $project['slug']))
@php($image = asset('og-image.png'))
@php($google = config('services.google'))
{{-- Diagram coordinates are 0–100; pull them in from the edges so labels never clip --}}
@php($at = fn ($v, $axis) => $axis === 'x' ? 8 + $v * 0.84 : 10 + $v * 0.8)
<!DOCTYPE html>
<html lang="en" class="bg-ink">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.theme-head')

    @include('partials.google-head')

    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="author" content="{{ $p['name'] }}">
    <meta name="robots" content="{{ app()->isProduction() ? 'index, follow, max-image-preview:large, max-snippet:-1' : 'noindex, nofollow' }}">
    <link rel="canonical" href="{{ $url }}">

    <meta property="og:type" content="article">
    <meta property="og:site_name" content="{{ $p['name'] }}">
    <meta property="og:locale" content="en_IN">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $url }}">
    <meta property="og:image" content="{{ $image }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $image }}">

    <link rel="icon" href="/favicon.ico" sizes="32x32">
    <link rel="icon" type="image/png" href="/icon-192.png" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300..700&family=Geist+Mono:wght@400;500&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">

    <script type="application/ld+json">
        {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
    </script>

    @vite(['resources/css/app.css'])
</head>
<body class="bg-ink text-fg antialiased">
    @include('partials.google-body')

    <header class="container-x flex items-center justify-between gap-4 py-3 sm:py-4">
        <a href="{{ url('/') }}" class="group rounded-[10px]" aria-label="{{ $p['name'] }}, home">
            <img src="/icon-192.png" alt="" width="38" height="38" class="h-[38px] w-[38px] rounded-[10px] border hairline transition-[transform,border-color] duration-300 group-hover:scale-105 group-hover:border-acid/50">
        </a>
        <nav class="flex items-center gap-2 text-[13px]" aria-label="Page">
            <a href="{{ url('/') }}#work" class="rounded-full px-3.5 py-2 text-mute transition-colors hover:text-fg">← All work</a>
            <a href="{{ url('/') }}#contact" class="rounded-full border hairline px-3.5 py-2 text-fg transition-colors hover:border-acid/50">Contact</a>
            <button type="button" id="theme-toggle" aria-label="Toggle light and dark mode" class="glass fixed bottom-5 right-5 z-[70] grid h-11 w-11 place-items-center rounded-full border border-line-2 bg-ink/70 text-mute shadow-[0_8px_30px_-10px_var(--shadow)] backdrop-blur-md transition-[color,border-color,transform] duration-300 hover:scale-105 hover:border-acid/50 hover:text-fg sm:bottom-6 sm:right-6">
                <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <g class="light:hidden"><circle cx="12" cy="12" r="4" /><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" /></g>
                    <path class="hidden light:inline" d="M20.5 14.1A8.5 8.5 0 0 1 9.9 3.5a8.5 8.5 0 1 0 10.6 10.6Z" />
                </svg>
            </button>
            <script>
                document.getElementById('theme-toggle').addEventListener('click', function () {
                    var t = document.documentElement.dataset.theme === 'light' ? 'dark' : 'light';
                    document.documentElement.dataset.theme = t;
                    document.querySelector('meta[name=theme-color]').content = t === 'light' ? '#f1f4ea' : '#07070a';
                    try { localStorage.setItem('sm:theme', t); } catch (e) {}
                });
            </script>
        </nav>
    </header>

    <main>
        {{-- Intro --}}
        <section class="container-x pb-12 pt-12 sm:pt-20">
            <nav aria-label="Breadcrumb" class="eyebrow text-mute">
                <a href="{{ url('/') }}" class="hover:text-fg">{{ $p['name'] }}</a>
                <span class="mx-2 text-dim">/</span>
                <a href="{{ url('/') }}#work" class="hover:text-fg">Work</a>
                <span class="mx-2 text-dim">/</span>
                <span class="text-acid">Case study</span>
            </nav>

            <h1 class="display mt-6 text-[clamp(3rem,9vw,7.5rem)]">{{ $project['name'] }}</h1>
            <p class="mt-4 text-[clamp(1.15rem,2.2vw,1.6rem)] leading-snug text-mute">
                <span class="serif-i text-fg">{{ $project['kind'] }}</span>
                @if (! empty($project['role']))
                    <span class="mx-2 text-dim">·</span>{{ $project['role'] }}
                @endif
            </p>
            <p class="mt-8 max-w-3xl text-[clamp(1.05rem,1.6vw,1.25rem)] leading-relaxed text-fg/85">{{ $project['blurb'] }}</p>

            <div class="mt-8 flex flex-wrap gap-2">
                @foreach ($project['stack'] ?? [] as $tech)
                    <span class="chip">{{ $tech }}</span>
                @endforeach
            </div>

            @if (! empty($project['links']))
                <div class="mt-8 flex flex-wrap gap-3">
                    @foreach ($project['links'] as $i => $link)
                        <a href="{{ $link['url'] }}" target="_blank" rel="noopener"
                           class="{{ $i === 0 ? 'bg-forest text-ink hover:bg-lime hover:text-on-lime' : 'border hairline text-fg hover:border-acid/50' }} inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-[14px] font-medium transition-colors">
                            {{ $link['label'] }} <span aria-hidden="true">↗</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Architecture --}}
        @if (! empty($project['diagram']['nodes']))
            @php($nodes = collect($project['diagram']['nodes'])->keyBy('id'))
            <section class="container-x pb-16">
                <figure>
                    <div class="relative aspect-[4/3] w-full overflow-hidden rounded-[22px] elev border hairline bg-panel sm:aspect-[16/9] sm:rounded-[28px] lg:aspect-[21/9]"
                         role="img" aria-label="Architecture of {{ $project['name'] }}: {{ $nodes->pluck('label')->implode(', ') }}">
                        <svg class="absolute inset-0 h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                            @foreach ($project['diagram']['edges'] ?? [] as [$from, $to])
                                @if (isset($nodes[$from], $nodes[$to]))
                                    <line x1="{{ $at($nodes[$from]['x'], 'x') }}" y1="{{ $at($nodes[$from]['y'], 'y') }}" x2="{{ $at($nodes[$to]['x'], 'x') }}" y2="{{ $at($nodes[$to]['y'], 'y') }}"
                                          class="stroke-tint/16" stroke-width="1" stroke-dasharray="3 3" vector-effect="non-scaling-stroke" />
                                @endif
                            @endforeach
                        </svg>
                        @foreach ($nodes as $node)
                            <span class="{{ ! empty($node['core']) ? 'border-lime bg-lime text-on-lime' : 'hairline bg-ink-2 text-fg/85' }} absolute -translate-x-1/2 -translate-y-1/2 whitespace-nowrap rounded-full border px-2.5 py-1 font-mono text-[10px] sm:px-3.5 sm:py-1.5 sm:text-[12px]"
                                  style="left: {{ $at($node['x'], 'x') }}%; top: {{ $at($node['y'], 'y') }}%">{{ $node['label'] }}</span>
                        @endforeach
                    </div>
                    <figcaption class="mt-3 font-mono text-[12px] text-mute">Architecture of {{ $project['name'] }}</figcaption>
                </figure>
            </section>
        @endif

        {{-- What I built --}}
        <section class="container-x grid gap-8 border-t hairline py-16 md:grid-cols-12">
            <h2 class="eyebrow text-mute md:col-span-3 md:pt-2"><span class="text-acid">01</span> — What I built</h2>
            <ul class="grid gap-5 md:col-span-9 md:grid-cols-2">
                @foreach ($project['highlights'] ?? [] as $point)
                    <li class="flex gap-3 text-[16px] leading-relaxed text-fg/85">
                        <span class="text-acid" aria-hidden="true">↳</span>
                        <span>{{ $point }}</span>
                    </li>
                @endforeach
            </ul>
        </section>

        {{-- Story sections --}}
        @foreach ($project['sections'] ?? [] as $i => $section)
            <section class="container-x grid gap-8 border-t hairline py-16 md:grid-cols-12">
                <h2 class="eyebrow text-mute md:col-span-3 md:pt-2"><span class="text-acid">{{ str_pad($i + 2, 2, '0', STR_PAD_LEFT) }}</span> — {{ $section['title'] }}</h2>
                <p class="max-w-3xl text-[clamp(1.05rem,1.5vw,1.2rem)] leading-relaxed text-fg/85 md:col-span-9">{{ $section['body'] }}</p>
            </section>
        @endforeach

        {{-- Contact --}}
        <section class="container-x border-t hairline py-20">
            <p class="eyebrow text-mute">Next step</p>
            <h2 class="display mt-5 text-[clamp(2.2rem,6vw,4.75rem)]">Building something <span class="serif-i text-acid">similar?</span></h2>
            <p class="mt-5 max-w-xl text-[16px] leading-relaxed text-mute">{{ $p['availability'] }}. I usually reply within a day.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="mailto:{{ $p['email'] }}" class="inline-flex items-center gap-2 rounded-full bg-forest px-5 py-2.5 text-[14px] font-medium text-ink transition-colors hover:bg-lime hover:text-on-lime">Email {{ $p['email'] }}</a>
                <a href="{{ route('resume') }}" class="inline-flex items-center gap-2 rounded-full border hairline px-5 py-2.5 text-[14px] text-fg transition-colors hover:border-acid/50">Download résumé ↓</a>
            </div>
        </section>

        {{-- More work --}}
        @if (count($others))
            <section class="container-x border-t hairline pb-20 pt-16">
                <h2 class="eyebrow text-mute">More case studies</h2>
                <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($others as $other)
                        <a href="{{ route('work.show', $other['slug']) }}" class="elev group rounded-[22px] border hairline bg-ink-2 p-6 transition-colors hover:border-acid/40">
                            <span class="eyebrow text-mute">{{ $other['kind'] }}</span>
                            <span class="mt-3 block text-[1.6rem] font-medium tracking-tight">{{ $other['name'] }}</span>
                            <span class="mt-3 block text-[14px] leading-relaxed text-mute">{{ \Illuminate\Support\Str::limit($other['blurb'], 120) }}</span>
                            <span class="mt-5 inline-block font-mono text-[12px] text-fg/80 transition-colors group-hover:text-acid">Read case study →</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    <footer class="container-x flex flex-wrap items-center justify-between gap-4 border-t hairline py-8 font-mono text-[12px] text-mute">
        <span>© {{ date('Y') }} {{ $p['name'] }} · {{ $p['role'] }}, {{ $p['location'] }}</span>
        <span class="flex gap-5">
            @foreach ($p['links'] as $link)
                <a href="{{ $link['url'] }}" rel="me noopener" target="_blank" class="hover:text-fg">{{ $link['label'] }} ↗</a>
            @endforeach
        </span>
    </footer>
</body>
</html>
