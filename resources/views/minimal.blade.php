@php($p = $portfolio['profile'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    {{-- Professional, recruiter-first version of the portfolio. Kept out of search until it becomes the homepage. --}}
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#ffffff">
    <title>{{ $p['name'] }} · Backend-focused full-stack developer</title>
    <meta name="description" content="{{ $p['name'] }}, backend-focused full-stack developer in Kolkata (Laravel, Node.js, React). Immediate joiner.">
    <link rel="icon" href="/favicon.ico" sizes="32x32">
    <link rel="icon" type="image/png" href="/icon-192.png" sizes="192x192">
    @include('partials.google-head')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&display=swap">
<style>
/* Layout: a conventional portfolio. Nav, hero with photo, about, experience, project cards, skills, contact, footer. Neutral palette, one green accent, subtle hover states only. */
:root {
    --bg: #ffffff; --alt: #f8f9fa; --ink: #111827; --text: #374151; --mute: #6b7280; --line: #e5e7eb;
    --accent: #2d6a0f; --accent-2: #24540c; --accent-soft: #f0f7ea;
    --sans: 'Geist', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;
    --radius: 12px;
    color-scheme: light;
}
* { box-sizing: border-box; }
html { scroll-behavior: smooth; }
body { margin: 0; background: var(--bg); color: var(--text); font-family: var(--sans); font-size: 16px; line-height: 1.65; -webkit-font-smoothing: antialiased; }
a { color: inherit; text-decoration: none; }
a:focus-visible, button:focus-visible { outline: 2px solid var(--accent); outline-offset: 3px; border-radius: 6px; }
.container { max-width: 1080px; margin: 0 auto; padding-left: max(24px, env(safe-area-inset-left, 0px)); padding-right: max(24px, env(safe-area-inset-right, 0px)); }

/* Nav */
.nav { position: sticky; top: env(safe-area-inset-top, 0px); z-index: 10; background: rgb(255 255 255 / .95); border-bottom: 1px solid var(--line); }
.nav .container { height: 64px; display: flex; align-items: center; justify-content: space-between; gap: 16px; }
.brand { display: inline-flex; align-items: center; gap: 10px; font-weight: 600; color: var(--ink); font-size: 17px; }
.brand img { width: 34px; height: 34px; border-radius: 9px; display: block; }
.nav ul { list-style: none; margin: 0; padding: 0; display: flex; align-items: center; gap: 24px; font-size: 15px; }
.nav ul li + li:not(.txt) { margin-left: -12px; }
.nav ul a { color: var(--text); transition: color .2s; }
.nav ul a:hover { color: var(--accent); }
/* The link for the section you're reading: green, with an underline that slides between links */
.nav ul .txt a { position: relative; padding: 6px 0; }
.nav ul .txt a.active { color: var(--accent); font-weight: 500; }
.nav-bar { position: absolute; bottom: -1px; height: 2px; border-radius: 2px; background: var(--accent); opacity: 0; transition: left .35s cubic-bezier(.2, .7, .2, 1), width .35s cubic-bezier(.2, .7, .2, 1), opacity .2s; pointer-events: none; }
.nav-bar.on { opacity: 1; }
@media (prefers-reduced-motion: reduce) { .nav-bar { transition: none; } }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; font: 500 15px var(--sans); padding: 11px 20px; border-radius: 8px; border: 1px solid var(--line); background: #fff; color: var(--ink); transition: background .15s, border-color .15s, color .15s; cursor: pointer; }
.btn:hover { border-color: #c7cbd1; background: var(--alt); }
.btn.primary { background: var(--accent); border-color: var(--accent); color: #fff; }
.btn.primary:hover { background: var(--accent-2); border-color: var(--accent-2); }
.btn.sm { padding: 8px 14px; font-size: 14px; }

/* "Interactive site" button: a slowly turning green border */
@property --a { syntax: '<angle>'; initial-value: 0deg; inherits: false; }
.btn.fx { position: relative; border: 1.5px solid transparent; background: linear-gradient(#fff, #fff) padding-box, conic-gradient(from var(--a), #c7ef4a, #2d6a0f, #9bd13a, #2d6a0f, #c7ef4a) border-box; animation: turn 4s linear infinite; color: var(--ink); }
.btn.fx:hover { background: linear-gradient(var(--accent-soft), var(--accent-soft)) padding-box, conic-gradient(from var(--a), #c7ef4a, #2d6a0f, #9bd13a, #2d6a0f, #c7ef4a) border-box; }
.btn.fx .spark { color: var(--accent); font-size: 12px; transition: transform .5s ease; }
.btn.fx:hover .spark { transform: rotate(90deg) scale(1.15); }
.btn.fx .arr { transition: transform .25s ease; }
.btn.fx:hover .arr { transform: translate(2px, -2px); }
@keyframes turn { to { --a: 360deg; } }
.btn.float { position: fixed; z-index: 30; right: max(22px, env(safe-area-inset-right, 0px)); bottom: max(22px, env(safe-area-inset-bottom, 0px)); padding: 12px 20px; font-size: 15px; border-radius: 999px; box-shadow: 0 10px 30px -8px rgb(45 106 15 / .45), 0 2px 6px rgb(17 24 39 / .08); animation: turn 4s linear infinite, bob 3.2s ease-in-out infinite; }
.btn.float:hover { animation-play-state: running, paused; box-shadow: 0 14px 36px -8px rgb(45 106 15 / .55), 0 2px 6px rgb(17 24 39 / .1); }
@keyframes bob { 50% { transform: translateY(-6px); } }
@media (prefers-reduced-motion: reduce) { .btn.fx, .btn.float { animation: none; } .btn.fx .spark, .btn.fx .arr { transition: none; } }

/* Hero */
.hero { padding: 64px 0 72px; }
.hero .container { display: grid; grid-template-columns: minmax(0, 1.3fr) minmax(0, 1fr); gap: 56px; align-items: center; }
.status { display: inline-flex; align-items: center; gap: 8px; font-size: 14px; color: var(--accent); background: var(--accent-soft); padding: 4px 12px; border-radius: 999px; font-weight: 500; }
.status::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: var(--accent); }
.hero h1 { margin: 20px 0 0; font-size: clamp(36px, 4.6vw, 52px); line-height: 1.1; letter-spacing: -0.03em; color: var(--ink); font-weight: 700; }
.hero h1 span { display: block; color: var(--mute); font-weight: 600; font-size: clamp(20px, 2.3vw, 28px); letter-spacing: -0.02em; margin-top: 12px; white-space: nowrap; }
@media (max-width: 480px) { .hero h1 span { white-space: normal; } }
.hero p.lead { margin: 22px 0 0; font-size: 18px; color: var(--text); max-width: 54ch; }
.hero .actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 30px; }
.socials { display: flex; gap: 20px; margin-top: 28px; font-size: 15px; }
.socials a { color: var(--mute); display: inline-flex; align-items: center; gap: 6px; }
.socials a:hover { color: var(--accent); }
.socials svg { width: 18px; height: 18px; }
/* Photo with a thin green outline offset behind it, for depth without effects */
.photo { position: relative; justify-self: end; width: 100%; max-width: 350px; margin: 0 20px 20px 0; }
.photo::before { content: ''; position: absolute; inset: 20px -20px -20px 20px; border: 2px solid var(--accent); border-radius: 16px; }
.photo img { position: relative; width: 100%; height: auto; aspect-ratio: 4 / 5; object-fit: cover; object-position: 50% 20%; border-radius: 16px; display: block; }

/* Proof numbers under the hero */
.proof { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); border: 1px solid var(--line); border-radius: var(--radius); background: #fff; margin-top: 52px; }
.hero .container + .container { display: block; }
.proof div { padding: 20px 22px; border-left: 1px solid var(--line); }
.proof div:first-child { border-left: 0; }
.proof b { display: block; font-size: 28px; line-height: 1.15; letter-spacing: -0.025em; color: var(--ink); font-weight: 700; font-variant-numeric: tabular-nums; }
.proof span { display: block; margin-top: 4px; font-size: 14px; color: var(--mute); line-height: 1.45; }

/* Sections */
section.block { padding: 88px 0; }
section.block.alt { background: var(--alt); border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); }
.head { margin-bottom: 40px; }
.head .kicker { font-size: 14px; font-weight: 600; color: var(--accent); }
.head h2 { margin: 6px 0 0; font-size: clamp(28px, 3vw, 36px); letter-spacing: -0.025em; color: var(--ink); font-weight: 700; line-height: 1.2; }
.head p { margin: 10px 0 0; color: var(--mute); max-width: 60ch; }

/* About */
.about { display: grid; grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr); gap: 56px; }
.about p { margin: 0 0 16px; font-size: 17px; }
.facts { margin: 0; padding: 0; list-style: none; border: 1px solid var(--line); border-radius: var(--radius); background: #fff; }
.facts li { display: flex; justify-content: space-between; gap: 16px; padding: 14px 18px; border-bottom: 1px solid var(--line); font-size: 15px; }
.facts li:last-child { border-bottom: 0; }
.facts span { color: var(--mute); }
.facts b { color: var(--ink); font-weight: 500; text-align: right; }

/* Experience */
.timeline { display: grid; gap: 16px; }
.job { display: grid; grid-template-columns: 200px minmax(0, 1fr); gap: 8px 32px; background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 24px 26px; }
.job .when { font-size: 14.5px; color: var(--mute); font-variant-numeric: tabular-nums; }
.job .when b { display: block; color: var(--ink); font-weight: 600; font-size: 15.5px; margin-bottom: 2px; }
.job h3 { margin: 0; font-size: 17.5px; color: var(--ink); font-weight: 600; }
.job p { margin: 8px 0 0; }
.job .co { font-size: 14.5px; color: var(--mute); margin-top: 2px; }
.job ul { margin: 12px 0 0; padding-left: 18px; }
.job li { margin-top: 6px; }
.job li::marker { color: var(--accent); }
.job li b { color: var(--ink); font-weight: 600; }
.tags { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 14px; }
.tag { font-size: 13px; color: var(--text); background: var(--alt); border: 1px solid var(--line); border-radius: 6px; padding: 2px 9px; }

/* Projects */
.grid3 { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 20px; }
.card { display: flex; flex-direction: column; background: #fff; border: 1px solid var(--line); border-radius: var(--radius); overflow: hidden; transition: border-color .15s, box-shadow .2s; }
.card:hover { border-color: #c7cbd1; box-shadow: 0 8px 24px -12px rgb(17 24 39 / .15); }
.card .body { padding: 22px; display: flex; flex-direction: column; gap: 10px; flex: 1; }
.card .type { font-size: 13px; color: var(--mute); }
.card h3 { margin: 0; font-size: 19px; color: var(--ink); font-weight: 600; letter-spacing: -0.01em; }
.card p { margin: 0; font-size: 15px; }
.card .tags { margin-top: auto; padding-top: 6px; }
.card .more { font-size: 14.5px; font-weight: 500; color: var(--accent); }
.card:hover .more { text-decoration: underline; text-underline-offset: 3px; }
.thumb { aspect-ratio: 16 / 10; background: var(--alt); border-bottom: 1px solid var(--line); overflow: hidden; display: flex; align-items: flex-start; justify-content: center; padding-top: 22px; }
.thumb img { width: 46%; border-radius: 14px 14px 0 0; box-shadow: 0 10px 30px -12px rgb(17 24 39 / .4); display: block; }
.thumb.web { padding: 18px 18px 0; }
.thumb.web img { width: 100%; border-radius: 8px 8px 0 0; }
/* Featured client work: text on the left, how the system fits together on the right */
.featured { display: grid; gap: 16px; }
/* Tabs above the featured project; only one project is shown at a time */
.ftabs { display: inline-flex; justify-self: start; gap: 4px; padding: 4px; background: var(--alt); border: 1px solid var(--line); border-radius: 10px; max-width: 100%; overflow-x: auto; }
.ftabs button { font: 500 14.5px var(--sans); color: var(--mute); background: none; border: 0; border-radius: 7px; padding: 8px 16px; cursor: pointer; white-space: nowrap; transition: color .15s, background .15s, box-shadow .15s; }
.ftabs button:hover { color: var(--ink); }
.ftabs button[aria-selected="true"] { background: #fff; color: var(--ink); box-shadow: 0 1px 3px rgb(17 24 39 / .12); }
.feat[hidden] { display: none; }
.feat.enter { animation: featIn .35s cubic-bezier(.2, .7, .2, 1) both; }
@keyframes featIn { from { opacity: 0; transform: translateY(8px); } }
.feat { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); background: #fff; border: 1px solid var(--line); border-radius: var(--radius); overflow: hidden; transition: border-color .15s, box-shadow .25s; }
.feat:hover { border-color: #c7cbd1; box-shadow: 0 8px 24px -12px rgb(17 24 39 / .15); }
.feat .body { padding: 30px; }
.feat .type { font-size: 13px; color: var(--mute); }
.feat h3 { margin: 4px 0 0; font-size: 24px; color: var(--ink); font-weight: 700; letter-spacing: -0.02em; }
.feat p { margin: 10px 0 0; }
.feat ul { margin: 14px 0 0; padding-left: 18px; font-size: 15px; }
.feat li { margin-top: 5px; }
.feat li::marker { color: var(--accent); }
.feat .more { display: inline-block; margin-top: 18px; font-size: 14.5px; font-weight: 500; color: var(--accent); }
.feat:hover .more { text-decoration: underline; text-underline-offset: 3px; }
.feat .diagram { background: var(--alt); border-left: 1px solid var(--line); display: flex; flex-direction: column; justify-content: center; padding: 22px; }
.feat .diagram small { font-size: 12.5px; color: var(--mute); margin-bottom: 8px; }
.arch { width: 100%; height: auto; display: block; }
.arch line { stroke: #c4cad2; stroke-width: 1.5; }
.arch rect { fill: #fff; stroke: #d1d5db; }
.arch text { font: 500 12px var(--sans); fill: var(--ink); text-anchor: middle; }
.arch .core rect { fill: var(--accent); stroke: var(--accent); }
.arch .core text { fill: #fff; font-weight: 600; }
.subhead { margin: 56px 0 24px; font-size: 20px; color: var(--ink); font-weight: 600; letter-spacing: -0.015em; }

/* Rotating role in the availability badge */
.sr-only { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; }
.roll { position: relative; display: inline-block; vertical-align: bottom; overflow: hidden; height: 1.65em; transition: width .35s ease; font-weight: 600; }
.roll .word { display: inline-block; white-space: nowrap; transition: opacity .25s ease, transform .25s ease; }
.roll.swap .word { opacity: 0; transform: translateY(-5px); }
@media (prefers-reduced-motion: reduce) { .roll, .roll .word { transition: none; } .roll.swap .word { opacity: 1; transform: none; } }

/* Show all / show fewer */
.more-wrap { display: flex; justify-content: center; margin-top: 24px; }
.toggle { gap: 8px; }
.toggle .chev { display: inline-block; transition: transform .3s ease; }
.toggle[aria-expanded="true"] .chev { transform: rotate(180deg); }
.card.is-extra { display: none; }
.card.is-shown { display: flex; animation: cardIn .45s cubic-bezier(.2, .7, .2, 1) both; animation-delay: calc(var(--i, 0) * 60ms); }
@keyframes cardIn { from { opacity: 0; transform: translateY(12px); } }
.card { transition: border-color .15s, box-shadow .25s, transform .25s ease; }
.card:hover { transform: translateY(-2px); }

/* Gentle fade-in as sections come into view (content stays visible without JS) */
.js .reveal { opacity: 0; transform: translateY(14px); transition: opacity .6s ease, transform .6s cubic-bezier(.2, .7, .2, 1); }
.js .reveal.in { opacity: 1; transform: none; }
@media (prefers-reduced-motion: reduce) {
    .js .reveal { opacity: 1; transform: none; transition: none; }
    .card.is-shown, .feat.enter { animation: none; }
    .card, .toggle .chev { transition: none; }
    .card:hover { transform: none; }
    html { scroll-behavior: auto; }
}

/* Skills */
.skills { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 20px; }
.skill { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 22px; }
.skill h3 { margin: 0 0 12px; font-size: 16px; color: var(--ink); font-weight: 600; }
.skill .tags { margin-top: 0; }

/* Contact */
.contact { text-align: center; padding-bottom: 120px !important; }
.contact h2 { margin: 6px 0 0; font-size: clamp(28px, 3.2vw, 38px); color: var(--ink); letter-spacing: -0.025em; }
.contact p { margin: 14px auto 0; max-width: 52ch; color: var(--mute); font-size: 17px; }
.contact .actions { display: flex; justify-content: center; flex-wrap: wrap; gap: 12px; margin-top: 28px; }
.contact .mail { display: block; margin-top: 18px; font-size: 15px; color: var(--mute); }

footer { border-top: 1px solid var(--line); padding: 28px 0; font-size: 14px; color: var(--mute); }
footer .container { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
footer a:hover { color: var(--accent); }

@media (max-width: 900px) {
    .hero .container, .about { grid-template-columns: 1fr; gap: 36px; }
    .photo { justify-self: start; max-width: 280px; }
    .grid3, .skills { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .proof { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .proof div:nth-child(3) { border-left: 0; }
    .proof div:nth-child(n+3) { border-top: 1px solid var(--line); }
    .feat { grid-template-columns: 1fr; }
    .feat .diagram { border-left: 0; border-top: 1px solid var(--line); }
    .job { grid-template-columns: 1fr; gap: 4px; }
}
@media (max-width: 620px) {
    .nav ul li.txt { display: none; }
    .nav ul li + li:not(.txt) { margin-left: 0; }
    .btn.float { left: 50%; right: auto; translate: -50% 0; padding: 11px 18px; font-size: 14px; }
    .nav ul { gap: 8px; }
    .brand span.n { display: none; }
    .grid3, .skills { grid-template-columns: 1fr; }
    .hero { padding: 48px 0; }
    .proof { margin-top: 44px; }
    .proof div { padding: 16px; }
    .proof b { font-size: 21px; }
    .feat .body { padding: 22px; }
    section.block { padding: 64px 0; }
}
</style>
</head>
<body>
@include('partials.google-body')

<header class="nav">
    <div class="container" style="position:relative">
        <span class="nav-bar" id="navBar" aria-hidden="true"></span>
        <a class="brand" href="#top"><img src="/icon-192.png" alt="" width="34" height="34">Sambit Maity</a>
        <ul>
            <li class="txt"><a href="#experience">Experience</a></li>
            <li class="txt"><a href="#projects">Projects</a></li>
            <li class="txt"><a href="#skills">Skills</a></li>
            <li class="txt"><a href="#about">About</a></li>
            <li class="txt"><a href="#contact">Contact</a></li>
            <li><a class="btn sm" href="{{ route('resume') }}">Résumé</a></li>
        </ul>
    </div>
</header>

<main id="top">
    <section class="hero">
        <div class="container">
            <div>
                <span class="status"><span class="sr-only">Immediate joiner for Backend, Full-Stack, Laravel or Software Engineer roles</span><span aria-hidden="true">Immediate joiner · <span class="roll" id="roll"><span class="word">Backend Developer</span></span> roles</span></span>
                <h1>Hi, I'm Sambit.<span>Backend-focused full-stack developer</span></h1>
                <p class="lead">Most of my work is on the backend in Laravel and Node.js: APIs, databases, queues and the servers they run on. I'm comfortable on the React side too, so I can take a feature from the database all the way to the screen.</p>
                <div class="actions">
                    <a class="btn primary" href="#contact">Contact me</a>
                    <a class="btn" href="{{ route('resume') }}">Download résumé</a>
                </div>
                <div class="socials">
                    <a href="https://www.linkedin.com/in/sambitmaity/"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.45 20.45h-3.55v-5.57c0-1.33-.03-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.36V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 1 1 0-4.13 2.06 2.06 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.73v20.54C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.73V1.73C24 .77 23.2 0 22.22 0z"/></svg>LinkedIn</a>
                    <a href="https://github.com/loco0011"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 .3a12 12 0 0 0-3.8 23.38c.6.12.83-.26.83-.57l-.02-2.04c-3.34.72-4.04-1.61-4.04-1.61-.55-1.39-1.33-1.76-1.33-1.76-1.09-.74.08-.73.08-.73 1.2.08 1.84 1.24 1.84 1.24 1.07 1.83 2.81 1.3 3.5 1 .1-.78.42-1.31.76-1.61-2.67-.3-5.47-1.33-5.47-5.93 0-1.31.47-2.38 1.24-3.22-.14-.3-.54-1.52.1-3.18 0 0 1-.32 3.3 1.23a11.5 11.5 0 0 1 6 0c2.28-1.55 3.29-1.23 3.29-1.23.64 1.66.24 2.88.12 3.18.77.84 1.23 1.91 1.23 3.22 0 4.61-2.81 5.62-5.48 5.92.42.36.81 1.1.81 2.22l-.01 3.29c0 .31.21.69.82.57A12 12 0 0 0 12 .3"/></svg>GitHub</a>
                    <a href="mailto:{{ $p['email'] }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>Email</a>
                </div>
            </div>
            <div class="photo"><img src="/images/sambit.jpg" alt="Sambit Maity" width="760" height="950"></div>
        </div>
        <div class="container">
            <div class="proof">
                <div><b>3 years</b><span>building production web apps</span></div>
                <div><b>4 companies</b><span>including 1.5 years for a New Zealand firm</span></div>
                <div><b>15 projects</b><span>9 for clients and employers, 6 of my own</span></div>
                <div><b>80 → 95+</b><span>performance score after my website rebuild</span></div>
            </div>
        </div>
    </section>


    <section class="block alt" id="experience">
        <div class="container">
            <div class="head reveal"><div class="kicker">Experience</div><h2>Where I've worked</h2></div>
            <div class="timeline reveal">
                <article class="job">
                    <div class="when"><b>Collabmate</b>Jun 2026 – Oct 2026</div>
                    <div>
                        <h3>Software Developer</h3>
                        <ul>
                            <li>Set up and ran <b>five production servers</b> on my own, and automated our deploys.</li>
                            <li>Rebuilt the main website and took its performance score from <b>about 80 to 95+</b>.</li>
                            <li>Built an internal assistant that answers staff questions from company documents, using Claude and OpenAI.</li>
                        </ul>
                        <p class="co">A small team, so I covered a lot. The company closed in October 2026.</p>
                        <div class="tags"><span class="tag">Laravel</span><span class="tag">Docker</span><span class="tag">Nginx</span><span class="tag">n8n</span><span class="tag">Flutter</span></div>
                    </div>
                </article>
                <article class="job">
                    <div class="when"><b>Triophase Global Services</b>Nov 2025 – May 2026</div>
                    <div>
                        <h3>Software Developer</h3>
                        <p>Built <b>three products</b> with Laravel, Node.js and React, each from the first commit to deployment:</p>
                        <ul>
                            <li>A college management system</li>
                            <li>A self-hosted mail platform</li>
                            <li>A platform mentors use to run guidance sessions with their clients</li>
                        </ul>
                        <div class="tags"><span class="tag">Laravel</span><span class="tag">Node.js</span><span class="tag">React</span><span class="tag">Docker</span></div>
                    </div>
                </article>
                <article class="job">
                    <div class="when"><b>Spiral Compute</b>Mar 2024 – Oct 2025<br>New Zealand, remote</div>
                    <div>
                        <h3>Software Development Engineer (SDE-1)</h3>
                        <ul>
                            <li>Built client products in Laravel, React, Node.js and Next.js.</li>
                            <li>Integrated Strapi and HubSpot for content and CRM.</li>
                            <li>On one project I did all of it: <b>the database design, the API and the frontend</b>.</li>
                        </ul>
                        <div class="tags"><span class="tag">Laravel</span><span class="tag">React</span><span class="tag">Next.js</span><span class="tag">Strapi</span><span class="tag">HubSpot</span></div>
                    </div>
                </article>
                <article class="job">
                    <div class="when"><b>FXBS</b>Jul 2023 – Feb 2024</div>
                    <div>
                        <h3>Web Developer</h3>
                        <p>My first job. PHP and JavaScript websites for clients.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="block" id="projects">
        <div class="container">
            <div class="head reveal"><div class="kicker">Projects</div><h2>Things I've built</h2><p>Client and company work first, then apps I built on my own.</p></div>
            @php($featured = [
                'wonati' => ['type' => 'AI platform · client work', 'text' => 'An AI writing platform that sends each request to an OpenAI or Anthropic model, with Stripe subscriptions and live progress updates.', 'points' => ['One Laravel API in front of both OpenAI and Anthropic', 'Stripe subscriptions and usage-based billing', 'Generation progress pushed live to the browser with Pusher']],
                'finvena' => ['type' => 'Job marketplace · client work', 'text' => 'A job marketplace for fintech roles, with separate dashboards for candidates and employers, chat, payments and SMS.', 'points' => ['Separate dashboards and permissions for candidates, employers and admins', 'Real-time chat between both sides', 'Stripe payments, and SMS and calls through Twilio']],
                'ehlostack' => ['type' => 'Email infrastructure · client work', 'text' => 'A self-hosted email platform: ZoneMTA does the sending, and a React dashboard manages it and shows how delivery is going.', 'points' => ['Outgoing mail sent through ZoneMTA', 'Dashboard for domains, services and delivery health', 'Runs in Docker behind Nginx']],
            ])
            @php($projects = collect($portfolio['projects'])->keyBy('slug'))
            @php($featured = array_intersect_key($featured, $projects->all()))
            <div class="featured reveal">
                <div class="ftabs" role="tablist" aria-label="Featured projects">
                    @foreach ($featured as $slug => $f)
                        <button type="button" role="tab" id="tab-{{ $slug }}" aria-controls="feat-{{ $slug }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" @unless ($loop->first) tabindex="-1" @endunless>{{ $projects[$slug]['name'] }}</button>
                    @endforeach
                </div>
                @foreach ($featured as $slug => $f)
                    @php($proj = $projects[$slug])
                    <a class="feat" id="feat-{{ $slug }}" role="tabpanel" aria-labelledby="tab-{{ $slug }}" href="{{ route('work.show', $slug) }}" @unless ($loop->first) hidden @endunless>
                        <div class="body">
                            <span class="type">{{ $f['type'] }}</span>
                            <h3>{{ $proj['name'] }}</h3>
                            <p>{{ $f['text'] }}</p>
                            <ul>@foreach ($f['points'] as $pt)<li>{{ $pt }}</li>@endforeach</ul>
                            <div class="tags">@foreach (array_slice($proj['stack'], 0, 5) as $s)<span class="tag">{{ $s }}</span>@endforeach</div>
                            <span class="more">Read case study →</span>
                        </div>
                        @if (! empty($proj['diagram']))
                            <div class="diagram"><small>How it fits together</small>@include('partials.arch-diagram', ['diagram' => $proj['diagram'], 'name' => $proj['name']])</div>
                        @endif
                    </a>
                @endforeach
            </div>
            <h3 class="subhead">More client and company work</h3>
            <div class="grid3" data-limit="3" data-label="projects">
                <div class="card"><div class="body"><span class="type">AI SaaS · client work</span><h3>Multi-LLM content platform</h3><p>Writes content from a prompt using GPT, Claude or Gemini, then publishes it straight into HubSpot or Strapi.</p><div class="tags"><span class="tag">Next.js</span><span class="tag">Node.js</span><span class="tag">OpenAI</span><span class="tag">Claude</span></div></div></div>
                <div class="card"><div class="body"><span class="type">CRM · client work</span><h3>Custom CRM systems</h3><p>CRMs for businesses with drag-and-drop deal pipelines, proposals through to invoices, and meetings booked on Google Calendar with Meet links.</p><div class="tags"><span class="tag">Laravel</span><span class="tag">React</span><span class="tag">Google Calendar API</span></div></div></div>
                <div class="card"><div class="body"><span class="type">AI agent · Collabmate</span><h3>Company chat assistant</h3><p>Answers staff questions from the company's own documents, using Claude and OpenAI wired together in n8n.</p><div class="tags"><span class="tag">n8n</span><span class="tag">Claude</span><span class="tag">OpenAI</span></div></div></div>
                <div class="card"><div class="body"><span class="type">Performance · Collabmate</span><h3>Main website rebuild</h3><p>Rebuilt the company's main website and took its performance score from about 80 to 95+ with caching, image work and server tuning.</p><div class="tags"><span class="tag">Laravel</span><span class="tag">Tailwind</span><span class="tag">Nginx</span></div></div></div>
                <div class="card"><div class="body"><span class="type">Education · Triophase</span><h3>College management system</h3><p>An ERP for colleges, built from the first commit to deployment.</p><div class="tags"><span class="tag">Laravel</span><span class="tag">React</span><span class="tag">MySQL</span></div></div></div>
                <div class="card"><div class="body"><span class="type">Platform · Triophase</span><h3>Mentor guidance platform</h3><p>A platform mentors use to run guidance sessions with their clients.</p><div class="tags"><span class="tag">Laravel</span><span class="tag">Node.js</span><span class="tag">React</span><span class="tag">Docker</span></div></div></div>
            </div>

            <h3 class="subhead">Personal projects</h3>
            <div class="grid3" data-limit="3" data-label="apps">
                <a class="card" href="{{ route('apps.show', 'samgeet') }}"><div class="thumb"><img src="/img/apps/samgeet/cover.jpg" alt="Samgeet app screen"></div><div class="body"><span class="type">Android · open source</span><h3>Samgeet</h3><p>A music player without ads. It learns what you like on the phone.</p><span class="more">View app →</span></div></a>
                <a class="card" href="{{ route('apps.show', 'xypher') }}"><div class="thumb"><img src="/img/apps/xypher/vault.jpg" alt="Xypher app screen"></div><div class="body"><span class="type">Android and web</span><h3>Xypher</h3><p>A password manager that encrypts everything on your phone, so the server never sees your passwords.</p><span class="more">View app →</span></div></a>
                <a class="card" href="{{ route('apps.show', 'kashew') }}"><div class="thumb"><img src="/img/apps/kashew/overview.jpg" alt="Kashew app screen"></div><div class="body"><span class="type">Android</span><h3>Kashew</h3><p>An expense tracker that reads your bank SMS, so payments log themselves.</p><span class="more">View app →</span></div></a>
                <a class="card" href="{{ route('apps.show', 'savr') }}"><div class="thumb"><img src="/img/apps/savr/home.jpg" alt="Savr app screen"></div><div class="body"><span class="type">Android</span><h3>Savr</h3><p>Compares prices on Blinkit, Zepto and Instamart before you order.</p><span class="more">View app →</span></div></a>
                <a class="card" href="{{ route('apps.show', 'klyro') }}"><div class="thumb"><img src="/img/apps/klyro/home.jpg" alt="Klyro app screen"></div><div class="body"><span class="type">Android</span><h3>Klyro</h3><p>Interview practice built from your CV and the job description.</p><span class="more">View app →</span></div></a>
                <a class="card" href="{{ route('apps.show', 'kolkata-hiring-map') }}"><div class="thumb web"><img src="/img/apps/kolkata-hiring-map/map.jpg" alt="Kolkata Hiring Map website"></div><div class="body"><span class="type">Web</span><h3>Kolkata Hiring Map</h3><p>A map of which companies in Kolkata are hiring, with links to the real job posts.</p><span class="more">View project →</span></div></a>
            </div>
        </div>
    </section>

    <section class="block alt" id="skills">
        <div class="container">
            <div class="head reveal"><div class="kicker">Skills</div><h2>What I work with</h2></div>
            <div class="skills reveal">
                <div class="skill"><h3>Backend</h3><div class="tags"><span class="tag">PHP</span><span class="tag">Laravel</span><span class="tag">Node.js</span><span class="tag">REST APIs</span><span class="tag">Queues</span></div></div>
                <div class="skill"><h3>Frontend</h3><div class="tags"><span class="tag">React</span><span class="tag">Next.js</span><span class="tag">TypeScript</span><span class="tag">Vue</span><span class="tag">Tailwind CSS</span></div></div>
                <div class="skill"><h3>Databases</h3><div class="tags"><span class="tag">MySQL</span><span class="tag">PostgreSQL</span><span class="tag">MongoDB</span><span class="tag">Redis</span></div></div>
                <div class="skill"><h3>DevOps</h3><div class="tags"><span class="tag">Linux</span><span class="tag">Docker</span><span class="tag">Nginx</span><span class="tag">CI/CD</span><span class="tag">AWS</span></div></div>
                <div class="skill"><h3>Mobile</h3><div class="tags"><span class="tag">Flutter</span><span class="tag">Dart</span></div></div>
                <div class="skill"><h3>AI and integrations</h3><div class="tags"><span class="tag">Claude API</span><span class="tag">OpenAI API</span><span class="tag">n8n</span><span class="tag">Stripe</span><span class="tag">Twilio</span></div></div>
            </div>
        </div>
    </section>

    <section class="block" id="about">
        <div class="container">
            <div class="head reveal"><div class="kicker">About</div><h2>A bit about me</h2></div>
            <div class="about reveal">
                <div>
                    <p>I've spent the last three years building web products for teams in India and, for a year and a half, for a company in New Zealand. I lean towards the backend, mostly Laravel and Node.js, and handle the React frontend when a project needs it.</p>
                    <p>I like owning a feature from the database to the deploy. Outside work I build my own Android apps in Flutter; they are in the Projects section.</p>
                    <p>Collabmate shut down in October 2026, so I'm looking for my next full-stack or backend role. I can start right away, in Kolkata or remotely.</p>
                </div>
                <ul class="facts">
                    <li><span>Focus</span><b>Backend · Laravel, Node.js</b></li>
                    <li><span>Experience</span><b>3 years</b></li>
                    <li><span>Location</span><b>Kolkata, India</b></li>
                    <li><span>Work mode</span><b>On-site, hybrid or remote</b></li>
                    <li><span>Notice period</span><b>Immediate</b></li>
                    <li><span>Education</span><b>B.Tech CSE, CGPA 9.08</b></li>
                </ul>
            </div>
        </div>
    </section>
    <section class="block alt contact" id="contact">
        <div class="container">
            <div class="head" style="margin-bottom:0"><div class="kicker">Contact</div></div>
            <h2>Get in touch</h2>
            <p>I'm looking for a backend or full-stack role and can start right away. Email is the easiest way to reach me, and I usually reply the same day.</p>
            <div class="actions">
                <a class="btn primary" href="mailto:{{ $p['email'] }}">Email me</a>
                <a class="btn" href="{{ route('resume') }}">Download résumé</a>
                <a class="btn" href="https://www.linkedin.com/in/sambitmaity/">LinkedIn</a>
            </div>
            <span class="mail">{{ $p['email'] }} · {{ $p['phone'] }}</span>
        </div>
    </section>
</main>

<a class="btn fx float" href="{{ url('/') }}" title="The full, animated version of this site"><span class="spark" aria-hidden="true">✦</span>Interactive site<span class="arr" aria-hidden="true">↗</span></a>

<script>
document.documentElement.classList.add('js');

// Highlight the nav link for the section in view
(() => {
    const links = [...document.querySelectorAll('.nav .txt a')];
    const bar = document.getElementById('navBar');
    const box = bar.parentElement;
    const byId = Object.fromEntries(links.map((a) => [a.getAttribute('href').slice(1), a]));
    const sections = Object.keys(byId).map((id) => document.getElementById(id)).filter(Boolean);
    const mark = (id) => {
        links.forEach((a) => {
            const on = a === byId[id];
            a.classList.toggle('active', on);
            on ? a.setAttribute('aria-current', 'true') : a.removeAttribute('aria-current');
        });
        const a = byId[id];
        if (!a || getComputedStyle(a).display === 'none' || !a.offsetParent) { bar.classList.remove('on'); return; }
        const r = a.getBoundingClientRect(), b = box.getBoundingClientRect();
        bar.style.left = r.left - b.left + 'px';
        bar.style.width = r.width + 'px';
        bar.style.top = r.bottom - b.top + 'px';
        bar.classList.add('on');
    };
    // The section crossing the middle band of the screen is the one being read
    let current = null;
    const pick = () => {
        const mid = innerHeight * 0.4;
        let found = null;
        sections.forEach((s) => { const r = s.getBoundingClientRect(); if (r.top <= mid && r.bottom > mid) found = s.id; });
        // At the very bottom of the page, the last section counts as read
        if (innerHeight + scrollY >= document.documentElement.scrollHeight - 4) found = sections[sections.length - 1].id;
        if (found !== current) { current = found; mark(found); }
    };
    // Re-check once scrolling settles too, so instant jumps (nav links, keyboard) are never missed
    let settle;
    addEventListener('scroll', () => { pick(); clearTimeout(settle); settle = setTimeout(pick, 120); }, { passive: true });
    addEventListener('scrollend', pick);
    addEventListener('resize', () => { current = null; pick(); });
    pick();
})();

// Cycle the role in the availability badge
(() => {
    const ROLES = ['Backend Developer', 'Full-Stack Developer', 'Laravel Developer', 'Software Engineer'];
    const roll = document.getElementById('roll');
    const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
    let i = 0;
    const word = roll.firstElementChild;
    setInterval(() => {
        i = (i + 1) % ROLES.length;
        // Fade the current role out, swap the text and width, fade the new one in
        // Width is measured fresh each time (after fonts load) and handed back to auto afterwards, so it never clips
        roll.style.width = Math.ceil(roll.getBoundingClientRect().width) + 'px';
        roll.classList.add('swap');
        setTimeout(() => {
            word.textContent = ROLES[i];
            roll.style.width = Math.ceil(word.getBoundingClientRect().width) + 1 + 'px';
            roll.classList.remove('swap');
            setTimeout(() => (roll.style.width = ''), 400);
        }, reduce ? 0 : 250);
    }, 2400);
})();

// Show the first few cards in each project grid, with a button for the rest
document.querySelectorAll('.grid3[data-limit]').forEach((grid) => {
    const limit = +grid.dataset.limit;
    const cards = [...grid.children];
    if (cards.length <= limit) return;
    const extra = cards.slice(limit);
    extra.forEach((c, i) => { c.classList.add('is-extra'); c.style.setProperty('--i', i); });
    const wrap = document.createElement('div');
    wrap.className = 'more-wrap';
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'btn toggle';
    btn.setAttribute('aria-expanded', 'false');
    const label = () => (btn.getAttribute('aria-expanded') === 'true' ? 'Show fewer' : `Show all ${cards.length} ${grid.dataset.label}`);
    btn.innerHTML = `<span>${label()}</span><span class="chev" aria-hidden="true">↓</span>`;
    btn.addEventListener('click', () => {
        const open = btn.getAttribute('aria-expanded') !== 'true';
        btn.setAttribute('aria-expanded', open);
        extra.forEach((c) => c.classList.toggle('is-shown', open));
        btn.firstChild.textContent = label();
        if (!open) grid.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'start' });
    });
    wrap.appendChild(btn);
    grid.after(wrap);
});

// Featured projects: switch the visible one from the tabs (arrow keys move between tabs)
document.querySelectorAll('.ftabs').forEach((list) => {
    const tabs = [...list.querySelectorAll('[role="tab"]')];
    const show = (tab) => {
        tabs.forEach((t) => {
            const on = t === tab;
            t.setAttribute('aria-selected', on);
            t.tabIndex = on ? 0 : -1;
            const panel = document.getElementById(t.getAttribute('aria-controls'));
            panel.hidden = !on;
            panel.classList.toggle('enter', on);
        });
    };
    tabs.forEach((t, i) => {
        t.addEventListener('click', () => show(t));
        t.addEventListener('keydown', (e) => {
            const step = { ArrowRight: 1, ArrowLeft: -1 }[e.key];
            if (!step) return;
            const next = tabs[(i + step + tabs.length) % tabs.length];
            next.focus();
            show(next);
        });
    });
});
// Fade sections in once as they scroll into view
const io = new IntersectionObserver((entries) => entries.forEach((e) => {
    if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
}), { rootMargin: '0px 0px -8% 0px' });
document.querySelectorAll('.reveal').forEach((el) => io.observe(el));
</script>
</body>
</html>
