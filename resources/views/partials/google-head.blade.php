{{-- GTM + GA4, production only. Placed as high in <head> as possible. --}}
@php($google = config('services.google'))
@php($track = app()->isProduction())
@if ($track && $google['gtm'])
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer',@json($google['gtm']));</script>
@endif
@if ($track && $google['ga4'])
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $google['ga4'] }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config',@json($google['ga4']));</script>
@endif

