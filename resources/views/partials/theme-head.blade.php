{{-- Applies the saved theme before first paint so the page never flashes the wrong one. Dark is the default. --}}
<meta name="theme-color" content="#07070a">
<script>
    (function () {
        var t = 'dark';
        try { t = localStorage.getItem('sm:theme') === 'light' ? 'light' : 'dark'; } catch (e) {}
        document.documentElement.dataset.theme = t;
        document.querySelector('meta[name=theme-color]').content = t === 'light' ? '#f1f4ea' : '#07070a';
    })();
</script>
