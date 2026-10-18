@php($p = $portfolio['profile'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    {{-- Trial page for recruiters (light, scannable). Kept out of search until it replaces the homepage. --}}
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#f7f8f4">
    <title>{{ $p['name'] }} · Full-Stack Software Engineer</title>
    <meta name="description" content="{{ $p['name'] }}, full-stack software engineer in Kolkata (Laravel, Node.js, React). Immediate joiner.">
    <link rel="icon" href="/favicon.ico" sizes="32x32">
    <link rel="icon" type="image/png" href="/icon-192.png" sizes="192x192">
    @include('partials.google-head')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=Geist+Mono:wght@400;500&family=Instrument+Serif:ital@0;1&display=swap">
<style>
/* Layout: light and calm, scannable in blocks, with three crafted moments built from real assets: the Shades Dev character hero, architecture-drawn project covers, and a phone showcase of real app screens. Brand green + lime, Geist with an Instrument Serif accent. */
:root {
    --bg: #f7f8f4; --card: #ffffff; --ink: #0f1a0a; --text: #39412f; --mute: #6b7362; --faint: #9aa190;
    --line: #e6e9e0; --line-2: #d6dbcd;
    --brand: #2c5a0a; --brand-2: #3d7a0c; --soft: #eef5e1; --lime: #d4f55a; --lime-2: #c2e83e;
    --sans: 'Geist', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;
    --serif: 'Instrument Serif', ui-serif, Georgia, serif;
    --mono: 'Geist Mono', ui-monospace, SFMono-Regular, monospace;
    --shadow: 0 1px 2px rgb(15 26 10 / .04), 0 8px 24px -12px rgb(15 26 10 / .12);
    --shadow-hover: 0 2px 6px rgb(15 26 10 / .05), 0 20px 40px -18px rgb(15 26 10 / .22);
    --ease: cubic-bezier(.2, .7, .2, 1);
    color-scheme: light;
}
* { box-sizing: border-box; }
html { scroll-behavior: smooth; }
body { margin: 0; background: var(--bg); color: var(--text); font-family: var(--sans); font-size: 15.5px; line-height: 1.6; -webkit-font-smoothing: antialiased; overflow-x: hidden; }
a { color: inherit; text-decoration: none; }
a:focus-visible, button:focus-visible, summary:focus-visible { outline: 2px solid var(--brand-2); outline-offset: 3px; border-radius: 8px; }
.wrap { max-width: 1160px; margin: 0 auto; padding-left: max(20px, env(safe-area-inset-left, 0px)); padding-right: max(20px, env(safe-area-inset-right, 0px)); }
.serif { font-family: var(--serif); font-style: italic; font-weight: 400; letter-spacing: -0.01em; }

.proto { background: var(--ink); color: #cfd6c4; font: 11.5px var(--mono); text-align: center; padding: 7px 16px; }

/* Nav */
.nav { position: sticky; top: env(safe-area-inset-top, 0px); z-index: 20; background: rgb(247 248 244 / .82); backdrop-filter: saturate(1.4) blur(14px); border-bottom: 1px solid transparent; transition: border-color .3s, background .3s; }
.nav.scrolled { border-color: var(--line); background: rgb(255 255 255 / .86); }
.nav .wrap { display: flex; align-items: center; justify-content: space-between; gap: 16px; height: 66px; }
.logo { display: flex; align-items: center; gap: 10px; font-weight: 600; color: var(--ink); letter-spacing: -0.01em; }
.logo img { width: 34px; height: 34px; border-radius: 10px; }
.nav nav { display: flex; align-items: center; gap: 2px; }
.nav nav a.l { font-size: 14px; color: var(--mute); padding: 8px 12px; border-radius: 8px; transition: color .2s, background .2s; }
.nav nav a.l:hover { color: var(--ink); background: rgb(15 26 10 / .05); }

.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; font: 500 14.5px var(--sans); border-radius: 999px; padding: 11px 20px; border: 1px solid var(--line-2); background: var(--card); color: var(--ink); cursor: pointer; transition: border-color .2s, box-shadow .25s, background .2s, transform .25s var(--ease); }
.btn:hover { border-color: var(--faint); box-shadow: var(--shadow); transform: translateY(-1px); }
.btn.primary { background: var(--ink); border-color: var(--ink); color: #fff; }
.btn.primary:hover { background: #1d2e14; }
.btn.sm { padding: 8px 15px; font-size: 13.5px; }
.btn svg { width: 16px; height: 16px; }
.btn .arr { transition: transform .3s var(--ease); }
.btn:hover .arr { transform: translateX(3px); }

/* Hero */
.hero { position: relative; padding: 56px 0 28px; display: grid; grid-template-columns: minmax(0, 1.12fr) minmax(0, 1fr); gap: 40px; align-items: center; }
.pill { display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; color: var(--brand); background: var(--card); border: 1px solid var(--line); padding: 6px 14px 6px 10px; border-radius: 999px; box-shadow: var(--shadow); }
.pill i { position: relative; width: 8px; height: 8px; border-radius: 99px; background: #4c9a12; }
.pill i::after { content: ''; position: absolute; inset: -4px; border-radius: inherit; border: 2px solid #4c9a12; opacity: 0; animation: ping 2s ease-out infinite; }
@keyframes ping { 0% { transform: scale(.6); opacity: .7; } 100% { transform: scale(1.6); opacity: 0; } }
.hero h1 { margin: 22px 0 0; font-size: clamp(40px, 5.6vw, 66px); line-height: 1; letter-spacing: -0.045em; color: var(--ink); font-weight: 700; }
.hero h1 .serif { color: var(--brand-2); font-size: 1.08em; letter-spacing: -0.02em; }
.hero .lede { margin: 22px 0 0; font-size: clamp(16px, 1.55vw, 18px); color: var(--text); max-width: 50ch; }
.hero .lede b { color: var(--ink); font-weight: 600; }
.hero .actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 30px; }
.trust { margin-top: 34px; display: flex; flex-wrap: wrap; align-items: center; gap: 10px 26px; }
.trust small { font-size: 12px; color: var(--faint); text-transform: uppercase; letter-spacing: .1em; font-weight: 500; width: 100%; }
.trust span { font-weight: 600; color: #5b6451; font-size: 15.5px; letter-spacing: -0.015em; }
.trust span em { font-style: normal; font-weight: 500; color: var(--brand-2); font-size: 11.5px; margin-left: 6px; background: var(--soft); padding: 2px 7px; border-radius: 99px; vertical-align: 2px; }

.stage { position: relative; aspect-ratio: 1 / 1; max-width: 520px; width: 100%; justify-self: center; }
.blob { position: absolute; inset: 7% 6% 4%; border-radius: 42% 58% 52% 48% / 48% 42% 58% 52%; background: radial-gradient(circle at 30% 25%, #e9fbb0, transparent 60%), linear-gradient(140deg, var(--lime) 0%, #a9d83a 55%, #7fb52a 100%); animation: morph 14s ease-in-out infinite alternate; box-shadow: inset 0 -20px 60px rgb(44 90 10 / .25); }
@keyframes morph { 50% { border-radius: 55% 45% 42% 58% / 52% 58% 42% 48%; } 100% { border-radius: 46% 54% 60% 40% / 40% 50% 50% 60%; } }
.grid-dots { position: absolute; inset: 0; background-image: radial-gradient(rgb(15 26 10 / .12) 1px, transparent 1px); background-size: 20px 20px; -webkit-mask-image: radial-gradient(circle at 50% 50%, #000 30%, transparent 70%); mask-image: radial-gradient(circle at 50% 50%, #000 30%, transparent 70%); }
.poses { position: absolute; inset: 4% 4% 2%; }
.poses img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain; opacity: 0; transform: translateY(8px) scale(.98); transition: opacity .7s ease, transform .9s var(--ease); filter: drop-shadow(0 24px 30px rgb(15 26 10 / .22)); }
.poses img.on { opacity: 1; transform: none; }
.tagchip { position: absolute; z-index: 3; display: flex; align-items: center; gap: 8px; padding: 9px 13px; border-radius: 14px; background: rgb(255 255 255 / .82); backdrop-filter: blur(10px); border: 1px solid rgb(255 255 255 / .9); box-shadow: 0 14px 30px -14px rgb(15 26 10 / .35); font-size: 13px; color: var(--ink); font-weight: 500; white-space: nowrap; animation: bob 6s ease-in-out infinite; }
.tagchip small { display: block; font-size: 11px; color: var(--mute); font-weight: 400; }
.tagchip .ic { width: 28px; height: 28px; border-radius: 9px; display: grid; place-items: center; background: var(--soft); color: var(--brand); font-size: 14px; }
.t1 { top: 9%; left: -4%; }
.t2 { top: 44%; right: -6%; animation-delay: -2s; }
.t3 { bottom: 8%; left: 2%; animation-delay: -4s; }
@keyframes bob { 50% { transform: translateY(-8px); } }
.caption { position: absolute; z-index: 3; bottom: -2%; right: 6%; font: 12px var(--mono); color: var(--mute); background: var(--card); border: 1px solid var(--line); padding: 4px 10px; border-radius: 999px; }

/* Metrics */
.metrics { margin-top: 28px; background: var(--card); border: 1px solid var(--line); border-radius: 22px; box-shadow: var(--shadow); display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); }
.metric { padding: 24px 26px; }
.metric + .metric { border-left: 1px solid var(--line); }
.metric b { display: block; font-size: clamp(30px, 3.4vw, 40px); line-height: 1; color: var(--ink); letter-spacing: -0.04em; font-weight: 700; font-variant-numeric: tabular-nums; }
.metric b span { color: var(--brand-2); }
.metric p { margin: 10px 0 0; font-size: 13.5px; color: var(--mute); line-height: 1.45; }

/* Sections */
section.sec { padding-top: 104px; }
.sec-h { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: end; gap: 14px 24px; margin-bottom: 30px; }
.eyebrow { font: 500 12px var(--mono); letter-spacing: .14em; text-transform: uppercase; color: var(--brand-2); display: flex; align-items: center; gap: 10px; }
.eyebrow::before { content: ''; width: 18px; height: 1px; background: currentColor; }
.sec-h h2 { margin: 10px 0 0; font-size: clamp(30px, 3.6vw, 42px); line-height: 1.05; letter-spacing: -0.035em; color: var(--ink); font-weight: 700; }
.sec-h h2 .serif { color: var(--brand-2); }
.sec-h p { margin: 0; color: var(--mute); font-size: 15px; max-width: 40ch; }

/* Why teams hire me: answers to the questions a recruiter is silently asking */
.why { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
.why-c { background: var(--card); border: 1px solid var(--line); border-radius: 20px; padding: 24px; box-shadow: var(--shadow); position: relative; overflow: hidden; }
.why-c::after { content: ''; position: absolute; right: -40px; top: -40px; width: 120px; height: 120px; border-radius: 50%; background: radial-gradient(closest-side, rgb(212 245 90 / .35), transparent); }
.why-c .ic { width: 40px; height: 40px; border-radius: 12px; display: grid; place-items: center; background: var(--ink); color: var(--lime); }
.why-c .ic svg { width: 19px; height: 19px; }
.why-c h3 { margin: 16px 0 6px; font-size: 17px; color: var(--ink); letter-spacing: -0.015em; }
.why-c p { margin: 0; font-size: 14.5px; color: var(--text); }

/* Recruiter quick sheet */
.sheet { margin-top: 18px; background: var(--card); border: 1px solid var(--line); border-radius: 22px; box-shadow: var(--shadow); overflow: hidden; }
.sheet-h { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; padding: 22px 24px; background: linear-gradient(90deg, var(--soft), #fff 70%); border-bottom: 1px solid var(--line); }
.sheet-h h3 { margin: 6px 0 2px; font-size: 20px; color: var(--ink); letter-spacing: -0.02em; }
.sheet-h p { margin: 0; color: var(--mute); font-size: 14px; }
.sheet-a { display: flex; flex-wrap: wrap; gap: 10px; }
.facts { margin: 0; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); }
.facts div { display: grid; grid-template-columns: 130px minmax(0, 1fr); gap: 12px; padding: 14px 24px; border-bottom: 1px solid var(--line); }
.facts div:nth-child(odd) { border-right: 1px solid var(--line); }
.facts div:nth-last-child(-n+2) { border-bottom: 0; }
.facts dt { font-size: 13px; color: var(--mute); }
.facts dd { margin: 0; font-size: 14.5px; color: var(--ink); font-weight: 500; }
.facts .ok { display: inline-flex; align-items: center; gap: 6px; color: var(--brand); background: var(--soft); border-radius: 999px; padding: 1px 10px; }
.live { display: inline-flex; align-items: center; gap: 7px; margin-top: 6px; color: var(--brand); font-weight: 500; }
.live i { width: 7px; height: 7px; border-radius: 99px; background: #4c9a12; box-shadow: 0 0 0 3px rgb(76 154 18 / .18); }

/* Floating availability dock: the next step is always one click away */
.dock { position: fixed; z-index: 25; left: 50%; bottom: max(18px, env(safe-area-inset-bottom, 0px)); transform: translate(-50%, 140%); display: flex; align-items: center; gap: 8px; padding: 7px 7px 7px 16px; border-radius: 999px; background: rgb(255 255 255 / .9); backdrop-filter: blur(14px); border: 1px solid var(--line); box-shadow: 0 20px 40px -16px rgb(15 26 10 / .35); transition: transform .5s var(--ease); white-space: nowrap; }
.dock.on { transform: translate(-50%, 0); }
.dock-s { display: inline-flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 500; color: var(--ink); margin-right: 6px; }
.dock-s i { width: 8px; height: 8px; border-radius: 99px; background: #4c9a12; box-shadow: 0 0 0 3px rgb(76 154 18 / .2); }
.dock-s em { font-style: normal; color: var(--mute); font-weight: 400; }

/* Experience timeline */
.timeline { position: relative; display: grid; gap: 14px; padding-left: 34px; }
.timeline::before { content: ''; position: absolute; left: 11px; top: 14px; bottom: 14px; width: 2px; background: linear-gradient(var(--lime-2), var(--line) 30%); border-radius: 2px; }
.job { position: relative; background: var(--card); border: 1px solid var(--line); border-radius: 18px; box-shadow: var(--shadow); transition: box-shadow .3s, border-color .3s; }
.job::before { content: ''; position: absolute; left: -29px; top: 26px; width: 12px; height: 12px; border-radius: 99px; background: var(--card); border: 2px solid var(--line-2); }
.job.now::before { background: var(--lime); border-color: var(--brand-2); box-shadow: 0 0 0 4px rgb(212 245 90 / .45); }
.job:hover { box-shadow: var(--shadow-hover); border-color: var(--line-2); }
.job summary { list-style: none; cursor: pointer; display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 4px 20px; padding: 20px 22px; }
.job summary::-webkit-details-marker { display: none; }
.job h3 { margin: 0; font-size: 17px; color: var(--ink); letter-spacing: -0.015em; font-weight: 600; }
.job h3 span { font-weight: 400; color: var(--mute); }
.job .win { margin: 0; font-size: 14.5px; color: var(--text); grid-column: 1; }
.job .win b { color: var(--brand); font-weight: 600; background: linear-gradient(transparent 62%, rgb(212 245 90 / .55) 62%); padding: 0 2px; }
.job .right { grid-row: 1 / span 2; grid-column: 2; display: flex; align-items: center; gap: 12px; }
.job .meta { display: grid; justify-items: end; gap: 4px; }
.job time { font-size: 13px; color: var(--mute); white-space: nowrap; font-variant-numeric: tabular-nums; }
.job .tag { font-size: 11.5px; font-weight: 500; color: var(--mute); background: #f2f4ee; border-radius: 999px; padding: 2px 9px; white-space: nowrap; }
.chev { width: 30px; height: 30px; border-radius: 99px; display: grid; place-items: center; border: 1px solid var(--line); color: var(--faint); transition: transform .35s var(--ease), color .2s, background .2s; }
.chev svg { width: 14px; height: 14px; }
.job[open] .chev { transform: rotate(180deg); color: var(--brand); background: var(--soft); }
.job .body { padding: 0 22px 22px; animation: open .35s var(--ease); }
@keyframes open { from { opacity: 0; transform: translateY(-4px); } }
.job ul { margin: 0; padding: 14px 0 0 18px; border-top: 1px dashed var(--line); display: grid; gap: 7px; font-size: 14.5px; }
.job li::marker { color: var(--brand-2); }
.chips { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 14px; }
.chip { font-size: 12.5px; font-weight: 500; color: #3f4836; background: #f2f4ee; border: 1px solid var(--line); border-radius: 999px; padding: 3px 11px; }

/* Projects: covers drawn from each project's real architecture */
.projects { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px; }
.proj { background: var(--card); border: 1px solid var(--line); border-radius: 22px; box-shadow: var(--shadow); overflow: hidden; display: flex; flex-direction: column; transition: box-shadow .35s, transform .35s var(--ease); }
.proj:hover { box-shadow: var(--shadow-hover); transform: translateY(-4px); }
.cover { position: relative; height: 196px; overflow: hidden; }
.cover .lbl { position: absolute; z-index: 2; left: 16px; top: 14px; font: 500 11px var(--mono); letter-spacing: .06em; text-transform: uppercase; color: rgb(255 255 255 / .8); }
.cover .map { position: absolute; inset: 40px 18px 16px; }
.cover svg { position: absolute; inset: 0; width: 100%; height: 100%; overflow: visible; }
.cover line { stroke: rgb(255 255 255 / .35); stroke-width: 1; stroke-dasharray: 3 3; }
.cover .n { position: absolute; transform: translate(-50%, -50%); white-space: nowrap; font: 500 10.5px var(--mono); color: #fff; background: rgb(255 255 255 / .14); border: 1px solid rgb(255 255 255 / .28); backdrop-filter: blur(6px); padding: 3px 8px; border-radius: 999px; transition: transform .4s var(--ease); }
.cover .n.core { background: #fff; color: var(--ink); border-color: #fff; font-weight: 600; box-shadow: 0 6px 20px -6px rgb(0 0 0 / .4); }
.proj:hover .cover .n { transform: translate(-50%, -50%) scale(1.04); }
.cover .pk { position: absolute; width: 6px; height: 6px; margin: -3px 0 0 -3px; border-radius: 99px; background: var(--lime); box-shadow: 0 0 10px 2px rgb(212 245 90 / .7); opacity: 0; animation: pk 2.6s ease-in-out infinite; animation-delay: var(--d); }
@keyframes pk { 0% { left: var(--x1); top: var(--y1); opacity: 0; } 15%, 80% { opacity: 1; } 100% { left: var(--x2); top: var(--y2); opacity: 0; } }
.proj .in { padding: 20px; display: flex; flex-direction: column; gap: 12px; flex: 1; }
.proj h3 { margin: 0; font-size: 21px; color: var(--ink); letter-spacing: -0.025em; font-weight: 700; }
.proj h3 small { display: block; font-size: 13px; font-weight: 500; color: var(--brand-2); letter-spacing: 0; margin-bottom: 2px; }
.proj p { margin: 0; font-size: 14.5px; }
.proj .chips { margin-top: auto; }
.proj .cta { font-size: 14px; font-weight: 600; color: var(--ink); display: inline-flex; gap: 6px; align-items: center; }
.proj .cta span { transition: transform .25s var(--ease); color: var(--brand-2); }
.proj:hover .cta span { transform: translateX(4px); }
.also { margin-top: 18px; display: flex; flex-wrap: wrap; gap: 8px; align-items: center; font-size: 14px; color: var(--mute); }
.also .chip { background: var(--card); }

/* Products showcase: real app screens */
.show { position: relative; border-radius: 28px; background: radial-gradient(700px 300px at 50% 0%, #fafff0, transparent 70%), linear-gradient(180deg, #eef5e1, #f7f8f4); border: 1px solid var(--line); padding: 40px 24px 0; overflow: hidden; }
.phones { display: flex; justify-content: center; align-items: flex-end; gap: clamp(10px, 2vw, 22px); height: 380px; }
.ph { position: relative; flex: none; width: clamp(130px, 15vw, 182px); aspect-ratio: 480 / 1040; border-radius: 26px; padding: 5px; background: linear-gradient(160deg, #2a3324, #0f1a0a); box-shadow: 0 30px 50px -24px rgb(15 26 10 / .55); transition: transform .5s var(--ease); cursor: pointer; }
.ph img { width: 100%; height: 100%; object-fit: cover; border-radius: 21px; display: block; }
.ph:nth-child(1) { transform: translateY(70px) rotate(-6deg); }
.ph:nth-child(2) { transform: translateY(34px) rotate(-3deg); }
.ph:nth-child(3) { transform: translateY(10px); z-index: 2; }
.ph:nth-child(4) { transform: translateY(34px) rotate(3deg); }
.ph:nth-child(5) { transform: translateY(70px) rotate(6deg); }
.ph:hover { transform: translateY(0) rotate(0) scale(1.03); z-index: 3; }
.ph .name { position: absolute; left: 50%; bottom: calc(100% + 10px); transform: translate(-50%, 6px); opacity: 0; transition: opacity .25s, transform .3s var(--ease); background: var(--ink); color: #fff; font-size: 12px; padding: 4px 10px; border-radius: 999px; white-space: nowrap; pointer-events: none; }
.ph:hover .name { opacity: 1; transform: translate(-50%, 0); }
.applist { margin-top: 18px; display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; }
.app { display: flex; gap: 14px; align-items: center; background: var(--card); border: 1px solid var(--line); border-radius: 16px; padding: 14px 16px; box-shadow: var(--shadow); transition: box-shadow .3s, transform .3s var(--ease); }
.app:hover { box-shadow: var(--shadow-hover); transform: translateY(-2px); }
.app img { width: 44px; height: 44px; border-radius: 12px; flex: none; }
.app div { min-width: 0; flex: 1; }
.app b { display: block; color: var(--ink); font-size: 15px; letter-spacing: -0.01em; }
.app span { display: block; font-size: 13px; color: var(--mute); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.app .go { color: var(--faint); transition: color .2s, transform .25s var(--ease); }
.app:hover .go { color: var(--brand-2); transform: translateX(3px); }

/* Skills */
.skills { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; }
.skill { background: var(--card); border: 1px solid var(--line); border-radius: 18px; padding: 20px; box-shadow: var(--shadow); }
.skill .ic { width: 36px; height: 36px; border-radius: 11px; display: grid; place-items: center; background: var(--soft); color: var(--brand); margin-bottom: 12px; }
.skill .ic svg { width: 18px; height: 18px; }
.skill h3 { margin: 0 0 12px; font-size: 15px; color: var(--ink); font-weight: 600; }
.skill .chips { margin-top: 0; }

/* Contact */
.contact { margin-top: 104px; border-radius: 30px; padding: clamp(30px, 5vw, 56px); background: radial-gradient(500px 260px at 90% 0%, rgb(212 245 90 / .22), transparent 70%), linear-gradient(135deg, #10200a, #24480b 70%, #2f5d0a); color: #fff; display: grid; grid-template-columns: minmax(0, 1.4fr) minmax(0, .8fr); gap: 24px; align-items: center; position: relative; overflow: hidden; }
.contact h2 { margin: 0; font-size: clamp(28px, 3.6vw, 44px); letter-spacing: -0.035em; line-height: 1.05; font-weight: 700; }
.contact h2 .serif { color: var(--lime); }
.contact p { margin: 14px 0 0; color: rgb(255 255 255 / .78); max-width: 46ch; }
.contact .actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 26px; }
.contact .btn.primary { background: var(--lime); color: var(--ink); border-color: var(--lime); }
.contact .btn.primary:hover { background: #e2fb85; }
.contact .btn.ghost { background: transparent; color: #fff; border-color: rgb(255 255 255 / .3); }
.contact .btn.ghost:hover { border-color: #fff; box-shadow: none; }
.contact .me { justify-self: end; width: min(260px, 100%); filter: drop-shadow(0 24px 30px rgb(0 0 0 / .35)); }
footer { padding: 30px 0 44px; display: flex; flex-wrap: wrap; justify-content: space-between; gap: 10px; font-size: 13.5px; color: var(--faint); }
footer a { color: var(--mute); }
footer a:hover { color: var(--brand-2); }

.toast { position: fixed; left: 50%; bottom: max(20px, env(safe-area-inset-bottom, 0px)); transform: translate(-50%, 16px); opacity: 0; transition: opacity .25s, transform .3s; background: var(--ink); color: #fff; font-size: 13.5px; padding: 10px 16px; border-radius: 12px; pointer-events: none; z-index: 30; box-shadow: var(--shadow-hover); }
.toast.show { opacity: 1; transform: translate(-50%, 0); }
.reveal { opacity: 1; transform: none; transition: opacity .7s ease, transform .8s var(--ease); }
.js .reveal:not(.in) { opacity: 0; transform: translateY(18px); }

@media (max-width: 1000px) {
    .hero { grid-template-columns: 1fr; padding-top: 40px; }
    .stage { max-width: 440px; }
    .projects, .applist, .skills { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .metrics { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .metric:nth-child(3) { border-left: 0; }
    .metric:nth-child(n+3) { border-top: 1px solid var(--line); }
    .ph:nth-child(1), .ph:nth-child(5) { display: none; }
}
@media (max-width: 1000px) { .why { grid-template-columns: 1fr; } .facts { grid-template-columns: 1fr; } .facts div:nth-child(odd) { border-right: 0; } .facts div:nth-last-child(2) { border-bottom: 1px solid var(--line); } }
@media (max-width: 640px) {
    .facts div { grid-template-columns: 1fr; gap: 2px; }
    .dock-s em { display: none; }
    .nav nav a.l { display: none; }
    .projects, .applist, .skills { grid-template-columns: 1fr; }
    .t1 { left: 0; } .t2 { right: 0; }
    .job summary { grid-template-columns: 1fr; }
    .job .right { grid-row: auto; grid-column: 1; justify-content: space-between; }
    .job .meta { justify-items: start; grid-auto-flow: column; align-items: center; }
    .phones { height: 300px; }
    .ph:nth-child(2), .ph:nth-child(4) { width: 118px; }
    .contact { grid-template-columns: 1fr; }
    .contact .me { justify-self: center; width: 200px; }
}
@media (prefers-reduced-motion: reduce) { *, *::before, *::after { animation: none !important; transition: none !important; } .js .reveal:not(.in) { opacity: 1; transform: none; } html { scroll-behavior: auto; } }
</style>
</head>
<body>
@include('partials.google-body')

<header class="nav" id="nav">
    <div class="wrap">
        <a class="logo" href="#top"><img src="/icon-192.png" alt="" width="34" height="34">Sambit Maity</a>
        <nav aria-label="Primary">
            <a class="l" href="#experience">Experience</a>
            <a class="l" href="#work">Work</a>
            <a class="l" href="#products">Products</a>
            <a class="l" href="#skills">Skills</a>
            <a class="btn sm" href="{{ route('resume') }}">Résumé</a>
            <a class="btn sm primary" href="#contact">Let's talk</a>
        </nav>
    </div>
</header>

<main class="wrap" id="top">
    <section class="hero">
        <div>
            <span class="pill"><i></i>Available now · immediate joiner</span>
            <h1>Full-stack engineer who ships <span class="serif">end to end.</span></h1>
            <p class="lede">I'm <b>Sambit</b>. I build Laravel, Node.js and React products and take them all the way to production: the database, the APIs, the interface and the servers they run on.</p>
            <div class="actions">
                <a class="btn primary" href="mailto:{{ $p['email'] }}" data-email>Email me <span class="arr">→</span></a>
                <a class="btn" href="{{ route('resume') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4v11m0 0-4-4m4 4 4-4M5 20h14"/></svg>
                    Download résumé
                </a>
            </div>
            <div class="trust">
                <small>Worked with</small>
                <span>Collabmate</span>
                <span>Triophase</span>
                <span>Spiral Compute<em>NZ · remote</em></span>
                <span>FXBS</span>
            </div>
        </div>

        <div class="stage" aria-label="Illustration of Sambit at work">
            <div class="grid-dots"></div>
            <div class="blob"></div>
            <div class="poses" id="poses">
                <img src="/images/hero/dev-1.webp" alt="" class="on"><img src="/images/hero/dev-2.webp" alt=""><img src="/images/hero/dev-3.webp" alt=""><img src="/images/hero/dev-4.webp" alt=""><img src="/images/hero/dev-5.webp" alt=""><img src="/images/hero/dev-6.webp" alt="">
            </div>
            <div class="tagchip t1"><span class="ic">⚡</span><span>Laravel · Node · React<small>core stack</small></span></div>
            <div class="tagchip t2"><span class="ic">✓</span><span>Immediate joiner<small>on-site · hybrid · remote</small></span></div>
            <div class="tagchip t3"><span class="ic">◎</span><span>Kolkata, India<small>3+ years shipping</small></span></div>
            <span class="caption" id="caption">code mode on</span>
        </div>
    </section>

    <div class="metrics reveal">
        <div class="metric"><b data-count="3">3<span>+</span></b><p>years shipping production software</p></div>
        <div class="metric"><b data-count="25">25<span>+</span></b><p>projects across 4 teams, India &amp; New Zealand</p></div>
        <div class="metric"><b>80<span>→</span>95+</b><p>site performance after my rebuild</p></div>
        <div class="metric"><b data-count="5">5<span>+</span></b><p>production servers run single-handedly</p></div>
    </div>

    <section class="sec why-sec" id="why">
        <div class="sec-h reveal">
            <div><div class="eyebrow">Why teams hire me</div><h2>Less risk, <span class="serif">from day one</span></h2></div>
        </div>
        <div class="why">
            <div class="why-c reveal">
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 4 14h7l-1 8 9-12h-7z"/></svg></span>
                <h3>Productive from week one</h3>
                <p>Immediate joiner who covers the whole stack, so one hire moves backend, frontend and deploys forward.</p>
            </div>
            <div class="why-c reveal">
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/></svg></span>
                <h3>Proven on remote teams</h3>
                <p>19 months shipping for a New Zealand team across time zones, including owning a key project end to end.</p>
            </div>
            <div class="why-c reveal">
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4 6v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V6z"/><path d="m9 12 2 2 4-4"/></svg></span>
                <h3>Owns it in production</h3>
                <p>Ran 5+ production servers single-handedly and took a company site from ~80 to 95+. No hand-holding needed.</p>
            </div>
        </div>

        <div class="sheet reveal" id="sheet">
            <div class="sheet-h">
                <div>
                    <div class="eyebrow">For recruiters</div>
                    <h3>Quick sheet</h3>
                    <p>Everything you'll be asked about me, in one place.</p>
                </div>
                <div class="sheet-a">
                    <button class="btn primary" type="button" id="copySummary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="8" y="8" width="13" height="13" rx="2"/><path d="M16 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h3"/></svg>
                        Copy summary for your hiring manager
                    </button>
                    <a class="btn" href="{{ route('resume') }}">Résumé PDF</a>
                </div>
            </div>
            <dl class="facts">
                <div><dt>Roles I fit</dt><dd>Full-Stack Engineer · Backend Engineer (PHP/Laravel, Node.js) · Software Engineer</dd></div>
                <div><dt>Notice period</dt><dd><span class="ok">Immediate</span></dd></div>
                <div><dt>Experience</dt><dd>3+ years · 4 teams · India &amp; New Zealand</dd></div>
                <div><dt>Location</dt><dd>Kolkata, India · open to on-site, hybrid or remote</dd></div>
                <div><dt>Core stack</dt><dd>Laravel, PHP, Node.js, React, TypeScript, MySQL/PostgreSQL, Docker</dd></div>
                <div><dt>Education</dt><dd>B.Tech CSE · CGPA 9.08 / 10</dd></div>
            </dl>
        </div>
    </section>

    <section class="sec" id="experience">
        <div class="sec-h reveal">
            <div><div class="eyebrow">Experience</div><h2>Where I've <span class="serif">made an impact</span></h2></div>
            <p>Tap a role to see what I did there.</p>
        </div>
        <div class="timeline">
            <details class="job now reveal" open>
                <summary>
                    <h3>Software Developer <span>· Collabmate</span></h3>
                    <p class="win">Took the main site from <b>~80 to 95+</b> and ran 5+ production servers alone</p>
                    <span class="right"><span class="meta"><time>Jun – Oct 2026</time><span class="tag">Company closed</span></span><span class="chev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></span></span>
                </summary>
                <div class="body">
                    <ul>
                        <li>Raised performance from ~80 to 95+ with caching, asset and image optimisation, and server tuning.</li>
                        <li>Ran 5+ VPS (Linux, Nginx, Docker) with SSH-based CI/CD that removed manual releases.</li>
                        <li>Built an AI chat agent on company data using Claude and OpenAI through self-hosted n8n.</li>
                    </ul>
                    <div class="chips"><span class="chip">Laravel</span><span class="chip">Flutter</span><span class="chip">Docker</span><span class="chip">Nginx</span><span class="chip">n8n</span><span class="chip">Claude · OpenAI</span></div>
                </div>
            </details>
            <details class="job reveal">
                <summary>
                    <h3>Software Developer <span>· Triophase Global Services</span></h3>
                    <p class="win">Delivered <b>three platforms</b>: college management, a mail server and a mentoring service</p>
                    <span class="right"><span class="meta"><time>Nov 2025 – May 2026</time><span class="tag">Kolkata</span></span><span class="chev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></span></span>
                </summary>
                <div class="body">
                    <ul>
                        <li>Built full-stack applications on Laravel, Node.js, React, Docker and Nginx.</li>
                        <li>Owned API development, delivery, deployment and ongoing maintenance.</li>
                    </ul>
                    <div class="chips"><span class="chip">Laravel</span><span class="chip">Node.js</span><span class="chip">React</span><span class="chip">Docker</span><span class="chip">HyperBase</span></div>
                </div>
            </details>
            <details class="job reveal">
                <summary>
                    <h3>SDE-1 <span>· Spiral Compute</span></h3>
                    <p class="win">Owned a key project <b>end to end</b> for a New Zealand team</p>
                    <span class="right"><span class="meta"><time>Mar 2024 – Oct 2025</time><span class="tag">Remote · NZ</span></span><span class="chev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></span></span>
                </summary>
                <div class="body">
                    <ul>
                        <li>System design, ERD and data modelling, APIs and frontend for a key project.</li>
                        <li>Node.js and Next.js features integrating Strapi CMS and HubSpot CRM; AI and third-party SDKs.</li>
                    </ul>
                    <div class="chips"><span class="chip">Laravel</span><span class="chip">React</span><span class="chip">Node.js</span><span class="chip">Next.js</span><span class="chip">Strapi</span><span class="chip">HubSpot</span></div>
                </div>
            </details>
            <details class="job reveal">
                <summary>
                    <h3>Web Developer <span>· FXBS</span></h3>
                    <p class="win">Responsive client web apps in PHP and JavaScript</p>
                    <span class="right"><span class="meta"><time>Jul 2023 – Feb 2024</time><span class="tag">Kolkata</span></span><span class="chev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></span></span>
                </summary>
                <div class="body">
                    <ul><li>Built and maintained client web applications; debugging, testing and production releases.</li></ul>
                    <div class="chips"><span class="chip">PHP</span><span class="chip">JavaScript</span><span class="chip">HTML/CSS</span></div>
                </div>
            </details>
        </div>
    </section>

    <section class="sec" id="work">
        <div class="sec-h reveal">
            <div><div class="eyebrow">Selected work</div><h2>Products I've built, <span class="serif">from the schema up</span></h2></div>
            <p>Each cover is the real architecture of the product.</p>
        </div>
        <div class="projects" id="projects"></div>
        <div class="also reveal"><span>Also built:</span><span class="chip">AI chat agent on company data</span><span class="chip">Multi-LLM content → HubSpot &amp; Strapi</span><span class="chip">CRMs with Google Calendar</span><span class="chip">+15 more</span></div>
    </section>

    <section class="sec" id="products">
        <div class="sec-h reveal">
            <div><div class="eyebrow">Own products</div><h2>Apps I've <span class="serif">shipped myself</span></h2></div>
            <p>Designed, built and released on my own time. <span class="live"><i></i>Live: you can install them today</span></p>
        </div>
        <div class="show reveal">
            <div class="phones">
                <a class="ph" href="{{ route('apps.show', 'kashew') }}"><span class="name">Kashew</span><img src="/img/apps/kashew/overview.jpg" alt="Kashew app screen"></a>
                <a class="ph" href="{{ route('apps.show', 'xypher') }}"><span class="name">Xypher</span><img src="/img/apps/xypher/vault.jpg" alt="Xypher app screen"></a>
                <a class="ph" href="{{ route('apps.show', 'samgeet') }}"><span class="name">Samgeet</span><img src="/img/apps/samgeet/cover.jpg" alt="Samgeet app screen"></a>
                <a class="ph" href="{{ route('apps.show', 'savr') }}"><span class="name">Savr</span><img src="/img/apps/savr/rides.jpg" alt="Savr app screen"></a>
                <a class="ph" href="{{ route('apps.show', 'klyro') }}"><span class="name">Klyro</span><img src="/img/apps/klyro/home.jpg" alt="Klyro app screen"></a>
            </div>
        </div>
        <div class="applist">
            <a class="app reveal" href="{{ route('apps.show', 'samgeet') }}"><img src="/img/apps/samgeet.png" alt=""><div><b>Samgeet</b><span>Ad-free music player · open source</span></div><span class="go">→</span></a>
            <a class="app reveal" href="{{ route('apps.show', 'xypher') }}"><img src="/img/apps/xypher.png" alt=""><div><b>Xypher</b><span>Encrypted password manager</span></div><span class="go">→</span></a>
            <a class="app reveal" href="{{ route('apps.show', 'kolkata-hiring-map') }}"><img src="/img/apps/kolkata-hiring-map.svg" alt="" style="background:#0b1a14"><div><b>Kolkata Hiring Map</b><span>Who's hiring, on one map</span></div><span class="go">→</span></a>
            <a class="app reveal" href="{{ route('apps.show', 'savr') }}"><img src="/img/apps/savr.png" alt=""><div><b>Savr</b><span>Live price comparison</span></div><span class="go">→</span></a>
            <a class="app reveal" href="{{ route('apps.show', 'kashew') }}"><img src="/img/apps/kashew.svg" alt=""><div><b>Kashew</b><span>Expense tracker that logs itself</span></div><span class="go">→</span></a>
            <a class="app reveal" href="{{ route('apps.show', 'klyro') }}"><img src="/img/apps/klyro.png" alt=""><div><b>Klyro</b><span>AI interview preparation</span></div><span class="go">→</span></a>
        </div>
    </section>

    <section class="sec" id="skills">
        <div class="sec-h reveal">
            <div><div class="eyebrow">Skills</div><h2>What I <span class="serif">work with</span></h2></div>
        </div>
        <div class="skills">
            <div class="skill reveal"><span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="6" rx="2"/><rect x="3" y="14" width="18" height="6" rx="2"/><path d="M7 7h.01M7 17h.01"/></svg></span><h3>Backend</h3><div class="chips"><span class="chip">PHP</span><span class="chip">Laravel</span><span class="chip">Node.js</span><span class="chip">REST APIs</span><span class="chip">Queues</span></div></div>
            <div class="skill reveal"><span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="M3 9h18M8 21h8"/></svg></span><h3>Frontend</h3><div class="chips"><span class="chip">React</span><span class="chip">Next.js</span><span class="chip">TypeScript</span><span class="chip">Tailwind</span><span class="chip">Flutter</span></div></div>
            <div class="skill reveal"><span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="6" rx="8" ry="3"/><path d="M4 6v6c0 1.7 3.6 3 8 3s8-1.3 8-3V6M4 12v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"/></svg></span><h3>Data &amp; cloud</h3><div class="chips"><span class="chip">MySQL</span><span class="chip">PostgreSQL</span><span class="chip">Redis</span><span class="chip">Docker</span><span class="chip">Nginx</span><span class="chip">AWS</span></div></div>
            <div class="skill reveal"><span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9z"/><path d="M19 17l.7 1.8 1.8.7-1.8.7L19 22l-.7-1.8-1.8-.7 1.8-.7z"/></svg></span><h3>AI &amp; integrations</h3><div class="chips"><span class="chip">Claude API</span><span class="chip">OpenAI</span><span class="chip">n8n</span><span class="chip">Stripe</span><span class="chip">HubSpot</span></div></div>
        </div>
    </section>

    <section class="contact reveal" id="contact">
        <div>
            <h2>Need a full-stack engineer <span class="serif">who can start now?</span></h2>
            <p>I'm open to full-stack and backend roles, on-site, hybrid or remote. I usually reply within a day.</p>
            <div class="actions">
                <a class="btn primary" href="mailto:{{ $p['email'] }}" data-email>Email me <span class="arr">→</span></a>
                <a class="btn ghost" href="{{ route('resume') }}">Download résumé</a>
                <a class="btn ghost" href="https://www.linkedin.com/in/sambitmaity/" target="_blank" rel="noopener">LinkedIn</a>
            </div>
        </div>
        <img class="me" src="/images/hero/dev-6.webp" alt="">
    </section>

    <footer>
        <span>© 2026 Sambit Maity · Kolkata · B.Tech CSE, CGPA 9.08</span>
        <a href="{{ url('/') }}">See the interactive portfolio →</a>
    </footer>
</main>
<div class="dock" id="dock" aria-label="Contact">
    <span class="dock-s"><i></i>Available now<em> · immediate joiner</em></span>
    <a class="btn sm primary" href="mailto:{{ $p['email'] }}" data-email>Email me</a>
    <a class="btn sm" href="{{ route('resume') }}">Résumé</a>
</div>
<div class="toast" id="toast" role="status"></div>

<script>
document.documentElement.classList.add('js');
const EMAIL = @json($p['email']);
const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
const toast = (t) => { const el = document.getElementById('toast'); el.textContent = t; el.classList.add('show'); clearTimeout(toast.id); toast.id = setTimeout(() => el.classList.remove('show'), 2600); };
document.querySelectorAll('[data-email]').forEach((a) => a.addEventListener('click', () => {
    navigator.clipboard?.writeText(EMAIL).then(() => toast(`Copied ${EMAIL}`), () => {});
}));

// Character poses, as on the current site
const CAPS = ['code mode on', 'power nap between deploys', 'new mechanical keyboard', 'picking the stack', 'bug fix fuel', 'plan · code · deploy · repeat'];
const poses = [...document.querySelectorAll('#poses img')];
let pi = 0;
if (!reduce) setInterval(() => { poses[pi].classList.remove('on'); pi = (pi + 1) % poses.length; poses[pi].classList.add('on'); document.getElementById('caption').textContent = CAPS[pi]; }, 3200);

// Project covers drawn from each project's real architecture
// Featured projects with their real architecture (config: portfolio.projects)
const STYLE = { wonati: ['AI content platform', 'linear-gradient(135deg,#1e1b4b,#4338ca 60%,#6366f1)', 'Routes content work across OpenAI and Anthropic models, with Stripe billing and live status over Pusher.', ['React', 'Laravel', 'PostgreSQL', 'Stripe']],
    finvena: ['Fintech job marketplace', 'linear-gradient(135deg,#022c22,#047857 60%,#10b981)', 'Two-sided hiring marketplace with role dashboards, real-time chat, payments and SMS.', ['Laravel', 'MySQL', 'Stripe', 'Twilio']],
    ehlostack: ['Mail server platform', 'linear-gradient(135deg,#431407,#c2410c 60%,#f97316)', 'Self-hosted email infrastructure with a control panel for delivery and monitoring.', ['React', 'Node.js', 'ZoneMTA', 'Docker']] };
const PROJECTS = @json($projects)
    .filter((p) => STYLE[p.slug])
    .map((p) => ({ ...p, kind: STYLE[p.slug][0], bg: STYLE[p.slug][1], text: STYLE[p.slug][2], stack: STYLE[p.slug][3] }));
const box = document.getElementById('projects');
PROJECTS.forEach((p) => {
    const by = Object.fromEntries(p.d.nodes.map((n) => [n.id, n]));
    const lines = p.d.edges.map(([a, b]) => `<line x1="${by[a].x}" y1="${by[a].y}" x2="${by[b].x}" y2="${by[b].y}" vector-effect="non-scaling-stroke"/>`).join('');
    const pks = p.d.edges.map(([a, b], i) => `<span class="pk" style="--x1:${by[a].x}%;--y1:${by[a].y}%;--x2:${by[b].x}%;--y2:${by[b].y}%;--d:${(i * 0.5).toFixed(1)}s"></span>`).join('');
    const nodes = p.d.nodes.map((n) => `<span class="n${n.core ? ' core' : ''}" style="left:${n.x}%;top:${n.y}%">${n.label}</span>`).join('');
    const a = document.createElement('a');
    a.className = 'proj reveal';
    a.href = p.url;
    a.innerHTML = `<div class="cover" style="background:${p.bg}"><span class="lbl">system map</span><div class="map"><svg viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">${lines}</svg>${pks}${nodes}</div></div>
        <div class="in"><h3><small>${p.kind}</small>${p.name}</h3><p>${p.text}</p><div class="chips">${p.stack.map((s) => `<span class="chip">${s}</span>`).join('')}</div><span class="cta">Read case study <span>→</span></span></div>`;
    box.appendChild(a);
});

// Reveal on scroll, count-up on the metrics, nav border once scrolled
const io = new IntersectionObserver((es) => es.forEach((e) => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } }), { rootMargin: '0px 0px -8% 0px' });
document.querySelectorAll('.reveal').forEach((el) => io.observe(el));
const nav = document.getElementById('nav');
const dock = document.getElementById('dock');
const hero = document.querySelector('.hero');
const contact = document.getElementById('contact');
const onScroll = () => {
    nav.classList.toggle('scrolled', scrollY > 10);
    // Show the dock once the hero's buttons are out of view, hide it again over the contact band
    const pastHero = hero.getBoundingClientRect().bottom < 80;
    const atContact = contact.getBoundingClientRect().top < innerHeight - 80;
    dock.classList.toggle('on', pastHero && !atContact);
};
addEventListener('scroll', onScroll, { passive: true });
onScroll();

// A ready-to-forward summary for the recruiter's hiring manager
const SUMMARY = `Sambit Maity — Full-Stack Software Engineer (Laravel, PHP, Node.js, React)
Kolkata, India · Immediate joiner · Open to on-site, hybrid or remote
3+ years across 4 teams in India and New Zealand (19 months remote with a NZ team)

Highlights
• Took a company website's performance score from ~80 to 95+
• Ran 5+ production servers single-handedly (Linux, Docker, Nginx, CI/CD)
• Built a multi-model AI content platform (OpenAI + Anthropic) and an AI chat agent on company data
• Ships his own apps: a password manager, an expense tracker, a music player and more

Stack: Laravel, PHP, Node.js, React, Next.js, TypeScript, MySQL/PostgreSQL, Docker, Nginx
Education: B.Tech CSE, CGPA 9.08 / 10
Portfolio: ${location.origin} · Email: ${EMAIL}`;
document.getElementById('copySummary').addEventListener('click', async () => {
    let ok = false;
    try { await navigator.clipboard.writeText(SUMMARY); ok = true; } catch (err) {}
    toast(ok ? 'Summary copied. Paste it into your email or ATS.' : 'Couldn’t copy here. Select the quick sheet instead.');
});
if (!reduce) {
    const cio = new IntersectionObserver((es) => es.forEach((e) => {
        if (!e.isIntersecting) return;
        cio.unobserve(e.target);
        const el = e.target, to = +el.dataset.count, suffix = el.innerHTML.replace(/^\d+/, '');
        const t0 = performance.now();
        const step = (t) => { const k = Math.min(1, (t - t0) / 900); el.innerHTML = Math.round(to * (1 - Math.pow(1 - k, 3))) + suffix; if (k < 1) requestAnimationFrame(step); };
        requestAnimationFrame(step);
    }), { threshold: .6 });
    document.querySelectorAll('[data-count]').forEach((el) => cio.observe(el));
}
</script>
</body>
</html>
