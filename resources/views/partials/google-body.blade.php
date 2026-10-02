{{-- GTM no-JS fallback, right after <body>. --}}
@php($google = config('services.google'))
@php($track = app()->isProduction())
@if ($track && $google['gtm'])
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $google['gtm'] }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif
