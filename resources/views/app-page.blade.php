@php($p = $portfolio['profile'])
@php($web = ($app['platform'] ?? 'android') === 'web')
@php($title = $app['name'].' — '.ucfirst($app['tag']).($web ? '' : ' app for Android').' | by '.$p['name'])
@php($description = $app['headline'].' '.$app['headline_accent'].' '.$app['lede'])
@php($url = route('apps.show', $app['slug']))
@php($image = asset('og-image.png'))
@php($shots = $app['screenshots'] ?? [])
@php($primary = collect($builds)->first())
@php($extra = collect($builds)->slice(1))
@php($mb = fn ($bytes) => $bytes >= 1048576 ? round($bytes / 1048576).' MB' : max(1, round($bytes / 1024)).' KB')
@php($feat = $app['features'] ?? [])
{{-- A build is ready when it's uploaded, or hosted elsewhere (`external`, e.g. the latest GitHub release) --}}
@php($ready = fn ($b) => ! empty($b['external']) || ! empty($b['upload']))
@php($href = fn ($b) => $b['external'] ?? route('apps.download', [$app['slug'], $b['key']]))
@php($size = fn ($b) => ! empty($b['upload']) ? $mb($b['upload']['size']) : 'from '.($b['source'] ?? 'its release page'))
<!DOCTYPE html>
<html lang="en" class="bg-ink">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.theme-head')
    @include('partials.google-head')
    <title>{{ $title }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit($description, 155) }}">
    <meta name="author" content="{{ $p['name'] }}">
    <meta name="robots" content="{{ app()->isProduction() ? 'index, follow, max-image-preview:large, max-snippet:-1' : 'noindex, nofollow' }}">
    <link rel="canonical" href="{{ $url }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $p['name'] }}">
    <meta property="og:locale" content="en_IN">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($description, 200) }}">
    <meta property="og:url" content="{{ $url }}">
    <meta property="og:image" content="{{ $image }}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" href="{{ $app['icon'] }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300..700&family=Geist+Mono:wght@400;500&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <script type="application/ld+json">
        {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
    </script>
    @vite(['resources/css/app.css', 'resources/js/app-page.js'])
</head>
<body class="bg-ink text-fg antialiased" style="--app: {{ $app['color'] ?? '#d4ff4f' }}" data-platform="{{ $web ? 'web' : 'android' }}">
    @include('partials.google-body')

    <header class="container-x flex items-center justify-between gap-4 py-4">
        <a href="{{ url('/') }}#after-hours" class="flex items-center gap-2.5 font-semibold tracking-tight">
            <img src="{{ $app['icon'] }}" alt="" width="34" height="34" class="h-[34px] w-[34px] rounded-[10px]">
            {{ $app['name'] }}
        </a>
        <nav class="flex items-center gap-1.5 text-[13px]" aria-label="Page">
            <a href="{{ url('/') }}#after-hours" class="hidden rounded-full px-3.5 py-2 text-mute transition-colors hover:text-fg sm:inline-flex">← {{ $p['name'] }}'s work</a>
            @unless ($web)
                <a href="#install" class="hidden rounded-full px-3.5 py-2 text-mute transition-colors hover:text-fg md:inline-flex">How to install</a>
            @endunless
            <a href="#get" class="rounded-full border hairline px-3.5 py-2 text-fg transition-colors hover:border-acid/50">{{ $web ? 'Open' : 'Download' }}</a>
            <button type="button" id="theme-toggle" aria-label="Toggle light and dark mode" class="glass fixed bottom-5 right-5 z-[70] grid h-11 w-11 place-items-center rounded-full border border-line-2 bg-ink/70 text-mute shadow-[0_8px_30px_-10px_var(--shadow)] backdrop-blur-md transition-[color,border-color,transform] duration-300 hover:scale-105 hover:border-acid/50 hover:text-fg sm:bottom-6 sm:right-6">
                <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <g class="light:hidden"><circle cx="12" cy="12" r="4" /><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" /></g>
                    <path class="hidden light:inline" d="M20.5 14.1A8.5 8.5 0 0 1 9.9 3.5a8.5 8.5 0 1 0 10.6 10.6Z" />
                </svg>
            </button>
        </nav>
    </header>

    <main>
        {{-- Hero --}}
        <section id="get" class="container-x ap-hero">
            <div>
                <span class="ap-tag"><i></i>Free · {{ $web ? 'Website' : 'Android' }} · {{ strtolower($app['status'] ?? '') }}</span>
                <h1 class="display ap-h1">{{ $app['headline'] }} <span class="serif-i">{{ $app['headline_accent'] }}</span></h1>
                <p class="ap-lede">{{ $app['lede'] }}</p>

                @if ($web)
                    <div class="ap-ctas">
                        @if (! empty($app['website']))
                            <a class="ap-btn" href="{{ $app['website'] }}" target="_blank" rel="noopener">Open {{ $app['name'] }} <span>↗</span></a>
                        @else
                            <span class="ap-btn is-off">Launching soon <span>…</span></span>
                        @endif
                        @if (! empty($app['repo']))
                            <a class="ap-ghost" href="{{ $app['repo'] }}" target="_blank" rel="noopener">Source on GitHub ↗</a>
                        @endif
                    </div>
                    <p class="ap-fine">{{ empty($app['website']) ? 'Built and launch-ready. It goes live as soon as the server is set up.' : 'Opens in a new tab · free' }}</p>
                @elseif (! $primary || ! $ready($primary))
                    <div class="ap-ctas"><span class="ap-btn is-off">Download coming soon <span>…</span></span></div>
                    <p class="ap-fine">The app is ready; the download goes up here shortly.</p>
                @else
                    {{-- Android visitors get the download; iPhone and computer visitors are switched by app-page.js --}}
                    <div data-device="android">
                        <div class="ap-ctas">
                            <a class="ap-btn" href="{{ $href($primary) }}" download>{{ $primary['label'] }} <span>↓</span></a>
                            <a class="ap-ghost" href="#install">How to install</a>
                        </div>
                        <p class="ap-fine">For Android phones · {{ $size($primary) }} · free</p>
                    </div>
                    <div data-device="iphone" hidden>
                        <div class="ap-ctas"><span class="ap-btn is-off">Android only for now <span>✕</span></span></div>
                        <p class="ap-fine">Have an Android phone too? Open {{ preg_replace('#^https?://#', '', $url) }} on it.</p>
                    </div>
                    <div data-device="desktop" hidden>
                        <div class="ap-qr"><div class="ap-qr-code" data-qr="{{ $url }}" role="img" aria-label="QR code for this page"></div><div><b>Scan with your Android phone</b><p>Opens this page on your phone, where you can tap Download.</p></div></div>
                        <p class="ap-fine">Or <a href="{{ $href($primary) }}" download class="underline decoration-line-2 underline-offset-4 hover:text-fg">download the file to this computer</a> ({{ $size($primary) }}).</p>
                    </div>
                    @foreach ($extra as $build)
                        @if ($ready($build))
                            <p class="ap-alt"><a href="{{ $href($build) }}" download>{{ $build['label'] }} ↓</a> <span>{{ $size($build) }}@if (! empty($build['note'])) · {{ $build['note'] }}@endif</span></p>
                        @endif
                    @endforeach
                @endif
            </div>

            <div class="ap-visual {{ $web && count($shots) ? 'is-web' : '' }}" aria-hidden="{{ count($shots) ? 'false' : 'true' }}">
                @if ($web && count($shots) >= 2)
                    <div class="ap-browser is-main"><div class="ap-browser-bar" aria-hidden="true"><i></i><i></i><i></i></div><img src="{{ $shots[0]['src'] }}" alt="{{ $app['name'] }}: {{ $shots[0]['title'] }}" width="1536" height="695"></div>
                    <div class="ap-browser is-side"><div class="ap-browser-bar" aria-hidden="true"><i></i><i></i><i></i></div><img src="{{ $shots[1]['src'] }}" alt="{{ $app['name'] }}: {{ $shots[1]['title'] }}" width="1536" height="695"></div>
                @elseif (count($shots) >= 2)
                    <div class="ap-phone is-back"><img src="{{ $shots[2]['src'] ?? $shots[1]['src'] }}" alt="{{ $app['name'] }}: {{ $shots[2]['title'] ?? $shots[1]['title'] }} screen" width="480" height="1040"></div>
                    <div class="ap-phone is-front"><img src="{{ $shots[0]['src'] }}" alt="{{ $app['name'] }}: {{ $shots[0]['title'] }} screen" width="480" height="1040"></div>
                @else
                    <div class="ap-iconstage"><img src="{{ $app['icon'] }}" alt="" width="160" height="160"></div>
                @endif
                @if (! empty($feat[1]['viz']) && $feat[1]['viz'][0] === 'chat')
                    <div class="ap-float"><small>{{ $feat[1]['title'] }}</small><b>{{ $feat[1]['viz'][1][1] ?? '' }}</b></div>
                @endif
            </div>
        </section>

        {{-- Features --}}
        @if (count($feat) >= 5)
            <section class="container-x ap-block">
                <div class="ap-shead">
                    <h2 class="display">Everything it does, <span class="serif-i">in plain words.</span></h2>
                    <p>{{ $app['kind'] }}</p>
                </div>
                <div class="ap-bento">
                    <div class="ap-card is-big">
                        <span class="eyebrow"><span class="text-acid">01</span> · {{ $feat[0]['eyebrow'] ?? '' }}</span>
                        <div class="ap-bigtitle">{{ $feat[0]['title'] }} <span class="serif-i">{{ $feat[0]['title_accent'] ?? '' }}</span></div>
                        <p>{{ $feat[0]['body'] }}</p>
                        <div class="ap-marquees" aria-hidden="true">
                            @foreach ([['tags', ''], ['tags_alt', 'is-rev']] as [$k, $cls])
                                @if (! empty($feat[0][$k]))
                                    <div class="ap-marq {{ $cls }}">
                                        @for ($i = 0; $i < 2; $i++)
                                            <div>@foreach ($feat[0][$k] as $t)<span>{{ $t }}</span>@endforeach</div>
                                        @endfor
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    @foreach ([1 => 'is-small', 2 => 'is-small', 3 => 'is-wide'] as $i => $span)
                        @php($f = $feat[$i])
                        <div class="ap-card {{ $span }}">
                            @php([$type, $data] = $f['viz'] ?? [null, []])
                            <div class="ap-viz">
                                @if ($type === 'chat')
                                    <div class="ap-chat">@foreach ($data as $n => $line)<div class="{{ $n % 2 ? 'is-reply' : '' }}">{{ $line }}</div>@endforeach</div>
                                @elseif ($type === 'bars')
                                    <div class="ap-bars">@foreach ($data as [$label, $pct, $val])<div>{{ $label }}<i style="--w: {{ $pct }}%"></i><b>{{ $val }}</b></div>@endforeach</div>
                                @elseif ($type === 'rows')
                                    <div class="ap-rows">@foreach ($data as [$label, $note, $on])<div class="{{ $on ? 'is-on' : '' }}">{{ $label }}<span>{{ $note }}</span></div>@endforeach</div>
                                @endif
                            </div>
                            <div><h3>{{ $f['title'] }}</h3><p>{{ $f['body'] }}</p></div>
                        </div>
                    @endforeach

                    <div class="ap-card is-stat">
                        @if (! empty($feat[4]['tags']))<div class="ap-mini">@foreach ($feat[4]['tags'] as $t)<span>{{ $t }}</span>@endforeach</div>@endif
                        <span class="ap-num">{{ $feat[4]['stat'] }}</span>
                        <h3>{{ $feat[4]['title'] }}</h3>
                        <p>{{ $feat[4]['body'] }}</p>
                    </div>
                    <div class="ap-card is-free">
                        <span class="ap-num">₹0</span>
                        <h3>Free, no ads</h3>
                        <p>{{ $app['free'] ?? '' }}</p>
                    </div>
                </div>
            </section>
        @endif

        {{-- Real screens --}}
        @if (count($shots))
            <section class="container-x ap-block">
                <div class="ap-shead"><h2 class="display">See it <span class="serif-i">in action.</span></h2><p>Real screens from the app.</p></div>
                <div class="ap-gallery {{ $web ? 'is-web' : '' }}">
                    @foreach ($shots as $shot)
                        <figure>
                            @if ($web)
                                <div class="ap-browser"><div class="ap-browser-bar" aria-hidden="true"><i></i><i></i><i></i></div><img src="{{ $shot['src'] }}" alt="{{ $app['name'] }}: {{ $shot['title'] }}" width="1536" height="695" loading="lazy"></div>
                            @else
                                <div class="ap-phone"><img src="{{ $shot['src'] }}" alt="{{ $app['name'] }}: {{ $shot['title'] }} screen" width="480" height="1040" loading="lazy"></div>
                            @endif
                            <figcaption><b>{{ $shot['title'] }}</b><small>{{ $shot['caption'] ?? '' }}</small></figcaption>
                        </figure>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Install + questions --}}
        <section id="install" class="container-x ap-block">
            @unless ($web)
                <div class="ap-shead"><h2 class="display">Install it <span class="serif-i">in a minute.</span></h2><p>{{ $app['name'] }} isn't on the Play Store yet, so you install it straight from here.</p></div>
                <div class="ap-steps">
                    <div><span>1</span><b>Tap Download</b><p>Your phone saves the file like any other download{{ $primary && $primary['upload'] ? '. It\'s about '.$mb($primary['upload']['size']) : '' }}.</p></div>
                    <div><span>2</span><b>Allow the install</b><p>Android asks once if your browser may install apps. Tap Settings, turn on "Allow from this source", then go back.</p></div>
                    <div><span>3</span><b>Tap Install</b><p>Open the downloaded file and tap Install. {{ $app['name'] }} appears on your home screen.</p></div>
                </div>
                @if (! empty($app['permissions']))
                    <div class="ap-note"><b>Seeing a warning is normal.</b> Android shows it for any app that isn't from the Play Store. {{ $app['permissions'] }}</div>
                @endif
            @endunless

            <div class="ap-faq">
                <h2 class="display ap-faq-title">Questions</h2>
                @foreach ($app['faq'] ?? [] as $item)
                    <details><summary>{{ $item['q'] }}</summary><p>{{ $item['a'] }}</p></details>
                @endforeach
                <details class="is-dev">
                    <summary>For developers</summary>
                    <div class="ap-dev">
                        <ul>@foreach ($app['highlights'] ?? [] as $h)<li>{{ $h }}</li>@endforeach</ul>
                        <div class="flex flex-wrap gap-1.5">@foreach ($app['stack'] ?? [] as $s)<span class="chip">{{ $s }}</span>@endforeach</div>
                        @if (! empty($app['repo']))<a class="ap-ghost self-start" href="{{ $app['repo'] }}" target="_blank" rel="noopener">Source on GitHub ↗</a>@endif
                        @foreach ($builds as $build)
                            @if (! empty($build['upload']['sha256']))
                                <p class="ap-sha">SHA-256 ({{ $build['label'] }}): <code>{{ $build['upload']['sha256'] }}</code></p>
                            @endif
                        @endforeach
                    </div>
                </details>
            </div>
        </section>

        {{-- Closing band --}}
        <section class="container-x">
            <div class="ap-closing">
                <h2 class="display">{{ $web ? 'Take a look' : 'Get' }} <span class="serif-i">{{ $app['name'] }}.</span></h2>
                <p>Free, and made with care in Kolkata.</p>
                @if ($web)
                    @if (! empty($app['website']))
                        <a class="ap-btn" href="{{ $app['website'] }}" target="_blank" rel="noopener">Open {{ $app['name'] }} <span>↗</span></a>
                    @else
                        <span class="ap-btn is-off">Launching soon <span>…</span></span>
                    @endif
                @elseif ($primary && $ready($primary))
                    <div data-device="android"><a class="ap-btn" href="{{ $href($primary) }}" download>{{ $primary['label'] }} <span>↓</span></a></div>
                    <div data-device="iphone" hidden><span class="ap-btn is-off">Android only for now <span>✕</span></span></div>
                    <div data-device="desktop" hidden><div class="ap-qr mx-auto text-left"><div class="ap-qr-code" data-qr="{{ $url }}" role="img" aria-label="QR code for this page"></div><div><b>Scan to get it on your phone</b><p>{{ $app['name'] }} runs on Android.</p></div></div></div>
                @else
                    <span class="ap-btn is-off">Download coming soon <span>…</span></span>
                @endif
            </div>
        </section>

        {{-- Other apps --}}
        <section class="container-x ap-others">
            <h2 class="eyebrow">More from After hours</h2>
            <div>
                @foreach ($others as $other)
                    <a href="{{ route('apps.show', $other['slug']) }}">
                        <img src="{{ $other['icon'] }}" alt="" width="40" height="40" loading="lazy">
                        <span><b>{{ $other['name'] }}</b><small>{{ $other['tag'] }}</small></span>
                        <span aria-hidden="true">→</span>
                    </a>
                @endforeach
            </div>
        </section>
    </main>

    <footer class="container-x mt-8 flex flex-wrap justify-between gap-3 border-t hairline py-6 pb-24 text-[13px] text-mute">
        <span>Made by <a href="{{ url('/') }}" class="text-fg hover:text-acid">{{ $p['name'] }}</a>, a full-stack engineer in Kolkata.</span>
        <a href="{{ url('/') }}#contact" class="hover:text-fg">Get in touch →</a>
    </footer>

    <style>
        .ap-hero { position: relative; display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 40px; align-items: center; padding-block: clamp(20px, 4vw, 56px) clamp(48px, 7vw, 96px); }
        .ap-hero::before { content: ''; position: absolute; z-index: -1; right: -6%; top: -8%; width: 64%; height: 116%; background: radial-gradient(closest-side, color-mix(in srgb, var(--app) 24%, transparent), transparent); pointer-events: none; }
        .ap-tag { display: inline-flex; gap: 8px; align-items: center; font: 11.5px var(--font-mono); padding: 5px 12px; border-radius: 999px; border: 1px solid color-mix(in srgb, var(--color-acid) 35%, transparent); color: var(--color-acid); background: color-mix(in srgb, var(--color-acid) 8%, transparent); }
        .ap-tag i { width: 6px; height: 6px; border-radius: 99px; background: var(--color-acid); }
        .ap-h1 { margin-top: 22px; font-size: clamp(2.5rem, 4.9vw, 4.4rem); text-wrap: balance; }
        .ap-h1 .serif-i { display: block; color: var(--color-acid); }
        .ap-lede { margin-top: 22px; font-size: clamp(1.02rem, 1.4vw, 1.15rem); line-height: 1.6; color: color-mix(in srgb, var(--color-fg) 80%, transparent); max-width: 48ch; }
        .ap-ctas { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 28px; align-items: center; }
        .ap-btn { display: inline-flex; align-items: center; gap: 10px; border-radius: 999px; padding: 8px 8px 8px 20px; background: var(--color-lime); color: var(--color-on-lime); font-size: 15px; font-weight: 600; transition: box-shadow .4s; }
        .ap-btn:hover { box-shadow: 0 0 44px -6px color-mix(in srgb, var(--color-lime) 60%, transparent); }
        .ap-btn > span { width: 34px; height: 34px; border-radius: 99px; background: var(--color-on-lime); color: var(--color-lime); display: grid; place-items: center; transition: transform .5s var(--ease-out-expo); }
        .ap-btn:hover > span { transform: translateY(2px); }
        .ap-btn.is-off { background: color-mix(in srgb, var(--color-tint) 9%, transparent); color: var(--color-fg); box-shadow: none; cursor: default; }
        .ap-btn.is-off > span { background: color-mix(in srgb, var(--color-tint) 10%, transparent); color: var(--color-mute); transform: none; }
        .ap-ghost { display: inline-flex; align-items: center; gap: 8px; border-radius: 999px; border: 1px solid var(--color-line-2); padding: 12px 18px; font-size: 14px; transition: border-color .3s; }
        .ap-ghost:hover { border-color: color-mix(in srgb, var(--color-acid) 50%, transparent); }
        .ap-fine { margin-top: 12px; font: 11.5px var(--font-mono); color: var(--color-dim); }
        .ap-alt { margin-top: 14px; font-size: 13.5px; color: var(--color-mute); max-width: 52ch; }
        .ap-alt a { color: var(--color-fg); font-weight: 500; text-decoration: underline; text-decoration-color: var(--color-line-2); text-underline-offset: 4px; }
        .ap-alt a:hover { text-decoration-color: var(--color-acid); }
        .ap-qr { display: flex; gap: 14px; align-items: center; border: 1px solid var(--color-line); border-radius: 18px; padding: 12px; background: var(--color-ink-2); max-width: 420px; margin-top: 28px; }
        .ap-qr-code { width: 96px; height: 96px; border-radius: 10px; background: #fff; padding: 7px; flex: none; }
        .ap-qr-code svg { width: 100%; height: 100%; display: block; }
        .ap-qr b { display: block; font-weight: 500; }
        .ap-qr p { margin-top: 3px; font-size: 13px; color: var(--color-mute); }

        .ap-visual { position: relative; height: clamp(420px, 46vw, 560px); }
        .ap-phone { width: 100%; aspect-ratio: 480 / 1040; border-radius: 34px; padding: 7px; background: linear-gradient(160deg, color-mix(in srgb, var(--color-tint) 22%, transparent), color-mix(in srgb, var(--color-tint) 6%, transparent)); box-shadow: 0 40px 80px -30px rgb(0 0 0 / .7), 0 0 0 1px var(--color-line-2); }
        .ap-phone img { width: 100%; height: 100%; object-fit: cover; border-radius: 28px; display: block; }
        .ap-visual .ap-phone { position: absolute; width: clamp(190px, 21vw, 258px); }
        .ap-phone.is-back { left: 6%; top: 8%; transform: rotate(-7deg); }
        .ap-phone.is-front { right: 6%; top: 0; transform: rotate(5deg); z-index: 2; box-shadow: 0 40px 80px -30px rgb(0 0 0 / .8), 0 0 0 1px color-mix(in srgb, var(--app) 60%, transparent), 0 0 60px -10px color-mix(in srgb, var(--app) 50%, transparent); }
        .ap-browser { border-radius: 14px; overflow: hidden; background: var(--color-ink-2); border: 1px solid var(--color-line-2); box-shadow: 0 40px 80px -30px rgb(0 0 0 / .7); }
        .ap-browser-bar { display: flex; gap: 6px; padding: 9px 12px; border-bottom: 1px solid var(--color-line); background: var(--color-panel); }
        .ap-browser-bar i { width: 8px; height: 8px; border-radius: 99px; background: color-mix(in srgb, var(--color-tint) 14%, transparent); }
        .ap-browser img { display: block; width: 100%; height: auto; }
        .ap-visual .ap-browser { position: absolute; }
        .ap-browser.is-main { left: 0; top: 6%; width: 100%; transform: rotate(-1.5deg); box-shadow: 0 40px 80px -30px rgb(0 0 0 / .8), 0 0 0 1px color-mix(in srgb, var(--app) 50%, transparent), 0 0 60px -10px color-mix(in srgb, var(--app) 40%, transparent); }
        .ap-browser.is-side { right: -4%; bottom: 4%; width: 62%; z-index: 2; transform: rotate(2deg); }
        .ap-gallery.is-web { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
        .ap-gallery.is-web figure:first-child { grid-column: 1 / -1; }
        .ap-gallery.is-web figure { text-align: left; }
        .ap-visual.is-web { height: clamp(300px, 30vw, 420px); }
        .ap-iconstage { position: absolute; inset: 8% 10%; border-radius: 40px; display: grid; place-items: center; border: 1px solid var(--color-line); background: radial-gradient(closest-side, color-mix(in srgb, var(--app) 22%, transparent), transparent), radial-gradient(color-mix(in srgb, var(--color-tint) 6%, transparent) 1px, transparent 1px) 0 0 / 18px 18px, var(--color-ink-2); }
        .ap-iconstage img { width: clamp(120px, 14vw, 170px); height: auto; border-radius: 36px; box-shadow: 0 30px 70px -20px color-mix(in srgb, var(--app) 60%, transparent); transform: rotate(-6deg); }
        .ap-float { position: absolute; z-index: 3; left: 0; bottom: 6%; display: grid; padding: 12px 16px; border-radius: 18px; max-width: 260px; background: color-mix(in srgb, var(--color-panel) 88%, transparent); backdrop-filter: blur(12px); border: 1px solid var(--color-line-2); box-shadow: 0 20px 40px -20px rgb(0 0 0 / .6); animation: ap-bob 5s ease-in-out infinite; }
        .ap-float small { font: 10px var(--font-mono); color: var(--color-dim); text-transform: uppercase; letter-spacing: .08em; }
        .ap-float b { font-weight: 500; font-size: 14px; color: var(--color-acid); }
        @keyframes ap-bob { 50% { transform: translateY(-8px); } }

        .ap-block { padding-block: clamp(40px, 6vw, 80px); }
        .ap-shead { display: flex; flex-wrap: wrap; align-items: end; justify-content: space-between; gap: 16px; border-top: 1px solid var(--color-line); padding-top: 22px; }
        .ap-shead h2 { font-size: clamp(2rem, 4.4vw, 3.4rem); max-width: 18ch; text-wrap: balance; }
        .ap-shead h2 .serif-i, .ap-faq-title .serif-i { color: var(--color-mute); }
        .ap-shead p { max-width: 42ch; color: var(--color-mute); }

        .ap-bento { margin-top: 28px; display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 14px; }
        .ap-card { position: relative; overflow: hidden; border-radius: 24px; border: 1px solid var(--color-line); background: var(--color-ink-2); padding: 22px; display: flex; flex-direction: column; gap: 10px; isolation: isolate; box-shadow: var(--elev); }
        .ap-card::before { content: ''; position: absolute; inset: -1px; border-radius: inherit; padding: 1px; pointer-events: none; background: radial-gradient(380px circle at var(--mx, -999px) var(--my, -999px), color-mix(in srgb, var(--color-acid) 55%, transparent), transparent 40%); -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0); -webkit-mask-composite: xor; mask-composite: exclude; }
        .ap-card h3 { font-size: 18px; font-weight: 600; letter-spacing: -0.02em; }
        .ap-card p { color: var(--color-mute); font-size: 14px; line-height: 1.55; margin-top: 4px; }
        .ap-card.is-big { grid-column: span 4; grid-row: span 2; background: radial-gradient(500px 300px at 100% 0%, color-mix(in srgb, var(--app) 20%, transparent), transparent 70%), var(--color-ink-2); }
        .ap-card.is-small { grid-column: span 2; }
        .ap-card.is-wide { grid-column: span 3; display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 20px; align-items: center; }
        .ap-card.is-wide .ap-viz { order: 2; }
        .ap-card.is-stat { grid-column: span 2; }
        .ap-card.is-free { grid-column: span 1; background: var(--color-lime); border-color: transparent; color: var(--color-on-lime); }
        .ap-card.is-free p { color: color-mix(in srgb, var(--color-on-lime) 70%, transparent); }
        .ap-card.is-free h3 { font-size: 16px; }
        .ap-bigtitle { font-size: clamp(2.2rem, 4.4vw, 3.6rem); font-weight: 600; letter-spacing: -0.045em; line-height: .98; margin-top: 6px; }
        .ap-bigtitle .serif-i { color: var(--color-acid); font-weight: 400; }
        .ap-marquees { margin-top: auto; display: grid; gap: 8px; padding-top: 22px; }
        .ap-marq { display: flex; overflow: hidden; -webkit-mask-image: linear-gradient(90deg, transparent, #000 10%, #000 90%, transparent); mask-image: linear-gradient(90deg, transparent, #000 10%, #000 90%, transparent); }
        .ap-marq > div { display: flex; gap: 8px; padding-right: 8px; flex: none; animation: ap-mq 32s linear infinite; }
        .ap-marq.is-rev > div { animation-direction: reverse; }
        .ap-marq span { white-space: nowrap; font-size: 13px; font-weight: 500; padding: 7px 13px; border-radius: 999px; border: 1px solid var(--color-line-2); background: var(--color-panel); }
        .ap-marq.is-rev span { border-color: color-mix(in srgb, var(--app) 40%, transparent); background: color-mix(in srgb, var(--app) 10%, transparent); }
        @keyframes ap-mq { to { transform: translateX(-100%); } }
        .ap-chat { display: grid; gap: 6px; }
        .ap-chat div { font-size: 12.5px; padding: 7px 11px; border-radius: 14px; border-bottom-left-radius: 4px; max-width: 92%; justify-self: start; background: color-mix(in srgb, var(--color-tint) 8%, transparent); }
        .ap-chat div.is-reply { justify-self: end; border-radius: 14px; border-bottom-right-radius: 4px; background: color-mix(in srgb, var(--color-acid) 10%, transparent); border: 1px solid color-mix(in srgb, var(--color-acid) 30%, transparent); }
        .ap-bars { display: grid; gap: 8px; }
        .ap-bars div { display: grid; grid-template-columns: 90px 1fr 28px; gap: 8px; align-items: center; font: 10.5px var(--font-mono); color: var(--color-dim); }
        .ap-bars i { height: 6px; border-radius: 99px; background: color-mix(in srgb, var(--color-tint) 10%, transparent); overflow: hidden; }
        .ap-bars i::after { content: ''; display: block; height: 100%; width: var(--w); background: var(--color-acid); border-radius: inherit; }
        .ap-bars b { font-weight: 400; color: var(--color-fg); text-align: right; }
        .ap-rows { display: grid; gap: 6px; }
        .ap-rows div { display: flex; justify-content: space-between; gap: 10px; padding: 8px 12px; border-radius: 12px; border: 1px solid var(--color-line); font-size: 12.5px; }
        .ap-rows span { font: 10.5px var(--font-mono); color: var(--color-dim); white-space: nowrap; }
        .ap-rows div.is-on { border-color: color-mix(in srgb, var(--color-acid) 35%, transparent); }
        .ap-rows div.is-on span { color: var(--color-acid); }
        .ap-num { font-size: 46px; font-weight: 600; letter-spacing: -0.05em; line-height: 1; }
        .ap-mini { display: flex; flex-wrap: wrap; gap: 5px; }
        .ap-mini span { font: 10.5px var(--font-mono); color: var(--color-mute); padding: 3px 8px; border-radius: 7px; background: color-mix(in srgb, var(--color-tint) 6%, transparent); }

        .ap-gallery { margin-top: 28px; display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
        .ap-gallery figure { display: grid; gap: 12px; text-align: center; }
        .ap-gallery .ap-phone { transition: transform .6s var(--ease-out-expo); }
        .ap-gallery figure:hover .ap-phone { transform: translateY(-6px); }
        .ap-gallery b { font-weight: 600; font-size: 15px; }
        .ap-gallery small { display: block; color: var(--color-mute); font-size: 13px; }

        .ap-steps { margin-top: 28px; display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
        .ap-steps > div { border-radius: 22px; border: 1px solid var(--color-line); background: var(--color-ink-2); padding: 20px; display: grid; gap: 6px; align-content: start; box-shadow: var(--elev); }
        .ap-steps span { width: 30px; height: 30px; border-radius: 99px; display: grid; place-items: center; font: 500 12px var(--font-mono); background: color-mix(in srgb, var(--color-acid) 12%, transparent); color: var(--color-acid); }
        .ap-steps b { font-size: 16px; font-weight: 600; }
        .ap-steps p { color: var(--color-mute); font-size: 14px; line-height: 1.55; }
        .ap-note { margin-top: 14px; border-radius: 18px; padding: 16px 18px; font-size: 14px; line-height: 1.6; background: color-mix(in srgb, var(--color-acid) 7%, transparent); border: 1px solid color-mix(in srgb, var(--color-acid) 25%, transparent); color: color-mix(in srgb, var(--color-fg) 85%, transparent); }
        .ap-note b { color: var(--color-fg); font-weight: 600; }
        .ap-faq { margin-top: 48px; }
        .ap-faq-title { font-size: clamp(1.8rem, 3.6vw, 2.6rem); margin-bottom: 14px; }
        .ap-faq details { border-bottom: 1px solid var(--color-line); }
        .ap-faq details:first-of-type { border-top: 1px solid var(--color-line); }
        .ap-faq summary { list-style: none; cursor: pointer; display: flex; justify-content: space-between; gap: 12px; padding: 18px 2px; font-size: 16px; font-weight: 500; }
        .ap-faq summary::-webkit-details-marker { display: none; }
        .ap-faq summary::after { content: '+'; color: var(--color-dim); font: 18px var(--font-mono); transition: transform .4s var(--ease-out-expo); }
        .ap-faq details[open] summary::after { transform: rotate(45deg); color: var(--color-acid); }
        .ap-faq details > p { margin-bottom: 18px; color: var(--color-mute); max-width: 70ch; line-height: 1.6; }
        .ap-faq details.is-dev summary { font: 12px var(--font-mono); letter-spacing: .14em; text-transform: uppercase; color: var(--color-mute); }
        .ap-dev { display: grid; gap: 14px; padding-bottom: 20px; color: var(--color-mute); }
        .ap-dev ul { display: grid; gap: 8px; }
        .ap-dev li { display: flex; gap: 10px; font-size: 14px; }
        .ap-dev li::before { content: '↳'; color: var(--color-acid); }
        .ap-sha { font: 10.5px var(--font-mono); color: var(--color-dim); overflow-wrap: anywhere; }

        .ap-closing { margin-block: clamp(12px, 3vw, 32px); border-radius: 32px; padding: clamp(36px, 6vw, 72px) 24px; text-align: center; border: 1px solid var(--color-line-2); display: grid; justify-items: center;
            background: radial-gradient(600px 260px at 20% 0%, color-mix(in srgb, var(--app) 28%, transparent), transparent 70%), radial-gradient(600px 260px at 90% 100%, color-mix(in srgb, var(--color-acid) 12%, transparent), transparent 70%), var(--color-ink-2); }
        .ap-closing h2 { font-size: clamp(2.2rem, 5vw, 4rem); }
        .ap-closing h2 .serif-i { color: var(--color-acid); }
        .ap-closing > p { color: var(--color-mute); margin: 14px 0 26px; }
        .ap-closing .ap-qr { margin-top: 0; }
        .ap-others { padding-block: 40px 8px; }
        .ap-others > div { margin-top: 16px; display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; }
        .ap-others a { display: flex; align-items: center; gap: 12px; border-radius: 18px; border: 1px solid var(--color-line); background: var(--color-ink-2); padding: 12px 14px; transition: border-color .3s; }
        .ap-others a:hover { border-color: color-mix(in srgb, var(--color-acid) 40%, transparent); }
        .ap-others img { width: 40px; height: 40px; border-radius: 11px; }
        .ap-others a > span:nth-of-type(1) { display: grid; flex: 1; min-width: 0; }
        .ap-others b { font-weight: 600; font-size: 14.5px; }
        .ap-others small { font: 10.5px var(--font-mono); color: var(--color-dim); }
        .ap-others a > span:last-child { color: var(--color-dim); }

        @media (max-width: 960px) {
            .ap-hero { grid-template-columns: 1fr; }
            .ap-visual { height: 460px; max-width: 520px; width: 100%; margin: 0 auto; }
            .ap-bento { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .ap-card.is-big, .ap-card.is-wide { grid-column: 1 / -1; grid-row: auto; }
            .ap-card.is-small, .ap-card.is-stat, .ap-card.is-free { grid-column: span 1; }
            .ap-gallery, .ap-others > div { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .ap-steps { grid-template-columns: 1fr; }
        }
        @media (max-width: 560px) {
            .ap-bento, .ap-others > div { grid-template-columns: 1fr; }
            .ap-card.is-small, .ap-card.is-stat, .ap-card.is-free { grid-column: 1 / -1; }
            .ap-card.is-wide { grid-template-columns: 1fr; }
            .ap-card.is-wide .ap-viz { order: 0; }
            .ap-visual { height: 400px; }
            .ap-visual .ap-phone { width: 175px; }
            .ap-phone.is-back { left: 2%; } .ap-phone.is-front { right: 2%; }
            .ap-qr { flex-direction: column; text-align: center; }
            .ap-gallery.is-web { grid-template-columns: 1fr; }
            .ap-visual.is-web { height: 260px; }
        }
        @media (prefers-reduced-motion: reduce) { .ap-marq > div, .ap-float { animation: none; } }
    </style>
</body>
</html>
