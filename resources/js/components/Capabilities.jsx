import { useEffect, useRef, useState } from 'react';
import { AnimatePresence, animate, motion, useInView } from 'motion/react';
import SectionHead from '../ui/SectionHead';
import { FadeUp, ease } from '../ui/Reveal';
import { spotlight } from '../lib/hooks';

function Tile({ className = '', title, kicker, body, children, delay = 0 }) {
    return (
        <FadeUp delay={delay} className={className}>
            <div onPointerMove={spotlight} className="spotlight flex h-full flex-col overflow-hidden rounded-3xl p-5 sm:p-6 lg:p-7">
                <div className="relative flex-1">{children}</div>
                <div className="relative mt-6">
                    <p className="eyebrow !text-acid/80">{kicker}</p>
                    <h3 className="mt-2 text-[19px] font-medium tracking-tight">{title}</h3>
                    <p className="mt-2 max-w-md text-[14px] leading-relaxed text-mute">{body}</p>
                </div>
            </div>
        </FadeUp>
    );
}

/** Calls `tick` every `ms` only while the returned ref is on screen. */
function useTicker(ms, tick) {
    const ref = useRef(null);
    const active = useInView(ref, { margin: '-5% 0px' });
    useEffect(() => {
        if (!active) return;
        const id = setInterval(tick, ms);
        return () => clearInterval(id);
    }, [active, ms]);
    return ref;
}

/* ——— Visuals ——————————————————————————————————————————— */

const LAYERS = [
    ['Client', 'React · Vue · Flutter'],
    ['Edge', 'Nginx · TLS · caching'],
    ['Application', 'Laravel · Node.js · REST'],
    ['Data', 'PostgreSQL · MySQL · Mongo'],
    ['Infrastructure', 'Docker · Linux VPS · CI/CD'],
];

function LayerStack() {
    const [active, setActive] = useState(0);
    const ref = useTicker(1400, () => setActive((a) => (a + 1) % LAYERS.length));
    return (
        <div ref={ref} className="space-y-2">
            {LAYERS.map(([name, tech], i) => (
                <div
                    key={name}
                    className={`relative flex items-center justify-between gap-3 overflow-hidden rounded-xl border px-3 py-2.5 transition-all duration-500 sm:px-4 sm:py-3 ${
                        i === active ? 'translate-x-1.5 border-acid/50 bg-acid/[0.06]' : 'border-line'
                    }`}
                >
                    <span className="relative flex items-center gap-3 text-[14px]">
                        <span className={`font-mono text-[10px] transition-colors duration-500 ${i === active ? 'text-acid' : 'text-dim'}`}>L{i + 1}</span>
                        {name}
                    </span>
                    <span className="relative hidden truncate font-mono text-[11px] text-mute min-[420px]:inline">{tech}</span>
                </div>
            ))}
        </div>
    );
}

const SCRIPT = [
    ['$', 'ssh deploy@prod-01'],
    ['$', 'php artisan migrate --force'],
    ['✓', 'migrations applied'],
    ['$', 'docker compose up -d --build'],
    ['✓', 'containers healthy (4/4)'],
    ['$', 'docker compose -f n8n.yml up -d'],
    ['✓', 'n8n (self-hosted) online'],
    ['$', 'sudo nginx -t && sudo systemctl reload nginx'],
    ['✓', 'deployed · 0s downtime'],
];

function Terminal() {
    const [lines, setLines] = useState(0);
    const ref = useTicker(650, () => setLines((n) => (n >= SCRIPT.length + 3 ? 0 : n + 1)));
    return (
        <div ref={ref} className="h-[232px] overflow-hidden rounded-xl border hairline bg-ink p-3 font-mono text-[10.5px] leading-[1.9] sm:p-4 sm:text-[11.5px]">
            {SCRIPT.slice(0, lines).map(([p, l], i) => (
                <motion.div key={i} initial={{ opacity: 0, x: -6 }} animate={{ opacity: 1, x: 0 }} className="truncate">
                    <span className={p === '✓' ? 'text-acid' : 'text-dim'}>{p}</span> <span className={p === '✓' ? 'text-acid/80' : 'text-fg/80'}>{l}</span>
                </motion.div>
            ))}
            <span className="inline-block h-3.5 w-1.5 translate-y-0.5 bg-acid" style={{ animation: 'blink 1s steps(1) infinite' }} />
        </div>
    );
}

// Orchestration layers a request can go through, and the models/agents it can reach.
const HUBS = ['n8n', 'Laravel'];
const TARGETS = [
    { name: 'Claude', by: 'Anthropic' },
    { name: 'GPT', by: 'OpenAI' },
    { name: 'OpenAI image generation', by: 'OpenAI' },
    { name: 'Chat agent', by: 'company data' },
];
// [request, hub index, target index]
const ROUTES = [
    ['summarise a client brief', 0, 0],
    ['answer a support question', 0, 3],
    ['draft long-form copy', 1, 1],
    ['generate cover art', 1, 2],
    ['triage an inbound email', 0, 0],
    ['qualify a new lead', 0, 3],
    ['review content for policy', 1, 0],
];

function Router() {
    const [i, setI] = useState(0);
    const ref = useTicker(1900, () => setI((v) => (v + 1) % ROUTES.length));
    const [prompt, hub, target] = ROUTES[i];

    return (
        <div ref={ref} className="flex h-[150px] flex-col justify-between">
            <div className="flex items-center justify-between gap-3 rounded-xl border hairline bg-ink px-3 py-2">
                <div className="min-w-0">
                    <p className="font-mono text-[9px] uppercase tracking-widest text-dim">request</p>
                    <AnimatePresence mode="wait">
                        <motion.p
                            key={i}
                            initial={{ opacity: 0, y: 5 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, y: -5 }}
                            transition={{ duration: 0.25 }}
                            className="mt-0.5 truncate font-mono text-[11.5px] text-fg"
                        >
                            “{prompt}”
                        </motion.p>
                    </AnimatePresence>
                </div>
                <span className="shrink-0 font-mono text-[9.5px] text-dim">
                    via <span className="text-acid">{HUBS[hub]}</span>
                </span>
            </div>

            <div className="flex items-center gap-2.5">
                <div className="space-y-1.5">
                    {HUBS.map((h, hi) => (
                        <div
                            key={h}
                            className={`rounded-md border px-2 py-1 text-center font-mono text-[10px] transition-colors duration-500 ${
                                hub === hi ? 'border-acid/60 bg-acid/10 text-acid' : 'border-line text-dim'
                            }`}
                        >
                            {h}
                        </div>
                    ))}
                </div>

                <div className="relative h-px min-w-4 flex-1 bg-line">
                    <motion.span key={i} className="absolute inset-0" initial={{ x: '0%' }} animate={{ x: '100%' }} transition={{ duration: 0.7, ease: 'easeInOut', delay: 0.15 }}>
                        <span className="absolute left-0 top-1/2 h-1.5 w-1.5 -translate-y-1/2 rounded-full bg-acid" />
                    </motion.span>
                </div>

                <div className="grid grid-cols-2 gap-1.5">
                    {TARGETS.map((t, ti) => (
                        <div
                            key={t.name}
                            className={`rounded-md border px-2 py-1 transition-colors duration-500 ${
                                target === ti ? 'border-acid/60 bg-acid/10' : 'border-line'
                            }`}
                        >
                            <p className={`whitespace-nowrap font-mono text-[10.5px] leading-tight ${target === ti ? 'text-acid' : 'text-mute'}`}>{t.name}</p>
                            <p className="whitespace-nowrap font-mono text-[8.5px] leading-tight text-dim">{t.by}</p>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}

const BEFORE = 80;
const AFTER = 95;
const RED = [255, 84, 84];
const LIME = [212, 255, 79];

/** Ring colour for a score: red up to the old score, then blends to lime at the new one. */
function scoreColor(v) {
    const t = Math.min(1, Math.max(0, (v - BEFORE) / (AFTER - BEFORE)));
    const c = RED.map((r, k) => Math.round(r + (LIME[k] - r) * t));
    return `rgb(${c[0]},${c[1]},${c[2]})`;
}

function Gauge() {
    const ref = useRef(null);
    const inView = useInView(ref, { once: true });
    const [v, setV] = useState(0);
    const [phase, setPhase] = useState('idle'); // idle | before | hold | after | done

    // Three beats: the old site fills to ~80 in red, holds with a warning
    // pulse, then the rebuild climbs to 95+ as the ring turns lime.
    useEffect(() => {
        if (!inView) return;
        let current;
        let stopped = false;
        const run = (from, to, opts) =>
            new Promise((resolve) => {
                current = animate(from, to, { ...opts, onUpdate: (x) => setV(Math.round(x)), onComplete: resolve });
            });
        (async () => {
            setPhase('before');
            await run(0, BEFORE, { duration: 1.1, ease: [0.33, 1, 0.68, 1] });
            if (stopped) return;
            setPhase('hold');
            await new Promise((r) => setTimeout(r, 900));
            if (stopped) return;
            setPhase('after');
            await run(BEFORE, AFTER, { duration: 1.5, ease: [0.16, 1, 0.3, 1] });
            if (!stopped) setPhase('done');
        })();
        return () => {
            stopped = true;
            current?.stop();
        };
    }, [inView]);

    const R = 52;
    const C = 2 * Math.PI * R;
    const color = scoreColor(v);
    const improved = phase === 'after' || phase === 'done';
    const done = phase === 'done';
    // Tick marking where the old site scored.
    const angle = (BEFORE / 100) * 2 * Math.PI;
    const tick = (r) => [70 + r * Math.cos(angle), 70 + r * Math.sin(angle)];
    const [x1, y1] = tick(R - 8);
    const [x2, y2] = tick(R + 8);

    return (
        <div ref={ref} className="flex h-[150px] items-center justify-center gap-5">
            <div className="relative">
                {/* Soft red warning glow while stuck at the old score */}
                <motion.div
                    aria-hidden
                    className="absolute inset-3 rounded-full"
                    style={{ background: 'radial-gradient(closest-side, rgba(255,84,84,0.28), transparent)' }}
                    animate={{ opacity: phase === 'hold' ? [0, 1, 0.3, 1, 0] : 0 }}
                    transition={{ duration: 0.9, ease: 'easeInOut' }}
                />
                <motion.div
                    aria-hidden
                    className="absolute inset-3 rounded-full"
                    style={{ background: 'radial-gradient(closest-side, rgba(212,255,79,0.22), transparent)' }}
                    animate={{ opacity: done ? 1 : 0, scale: done ? [0.85, 1.05, 1] : 0.85 }}
                    transition={{ duration: 0.6, ease: [0.16, 1, 0.3, 1] }}
                />

                <svg width="140" height="140" viewBox="0 0 140 140" className="relative -rotate-90">
                    <circle cx="70" cy="70" r={R} fill="none" stroke="rgba(255,255,255,0.07)" strokeWidth="6" />
                    <circle
                        cx="70"
                        cy="70"
                        r={R}
                        fill="none"
                        stroke={color}
                        strokeWidth="6"
                        strokeLinecap="round"
                        strokeDasharray={`${(C * v) / 100} ${C}`}
                    />
                    <line x1={x1} y1={y1} x2={x2} y2={y2} stroke="rgba(255,255,255,0.5)" strokeWidth="1.5" />
                </svg>

                <div className="absolute inset-0 flex flex-col items-center justify-center">
                    <motion.span
                        className="text-[34px] font-medium tabular-nums leading-none tracking-tight"
                        style={{ color }}
                        animate={phase === 'hold' ? { x: [0, -3, 3, -2, 2, 0] } : done ? { scale: [1, 1.12, 1] } : {}}
                        transition={{ duration: phase === 'hold' ? 0.45 : 0.5, ease: 'easeOut' }}
                    >
                        {v}
                        {done && '+'}
                    </motion.span>
                    <span className="mt-1.5 font-mono text-[9px] uppercase tracking-widest" style={{ color: improved ? 'rgba(212,255,79,0.85)' : 'rgba(255,120,120,0.85)' }}>
                        {improved ? 'after rebuild' : phase === 'hold' ? 'needs work' : 'before'}
                    </span>
                </div>
            </div>

            <div className="space-y-2 font-mono text-[11px]">
                <p className={`flex items-center gap-2 transition-colors duration-500 ${improved ? 'text-mute line-through decoration-[#ff5454]/70' : 'text-[#ff7878]'}`}>
                    <span className="h-1.5 w-3 rounded-full bg-[#ff5454]" /> before · ~{BEFORE}
                </p>
                <p className={`flex items-center gap-2 transition-colors duration-500 ${improved ? 'text-acid' : 'text-dim'}`}>
                    <span className="h-1.5 w-3 rounded-full bg-acid" /> after · {AFTER}+
                </p>
                <motion.p
                    className="pt-1 text-[11px] text-acid"
                    initial={{ opacity: 0, y: 6 }}
                    animate={done ? { opacity: 1, y: 0 } : { opacity: 0, y: 6 }}
                    transition={{ duration: 0.5, ease: [0.16, 1, 0.3, 1] }}
                >
                    ▲ +{AFTER - BEFORE} pts
                </motion.p>
            </div>
        </div>
    );
}

const EVENTS = [
    ['stripe', 'payment_intent.succeeded'],
    ['razorpay', 'payment.captured'],
    ['pusher', 'generation.completed'],
    ['msg91', 'otp.delivered'],
    ['twilio', 'message.delivered'],
    ['stripe', 'invoice.paid'],
    ['pusher', 'chat.message.sent'],
    ['msg91', 'otp.verified'],
    ['bullmq', 'job.completed'],
    ['laravel', 'hmac.verified'],
];

function EventStream() {
    const [n, setN] = useState(3);
    const ref = useTicker(1300, () => setN((v) => v + 1));
    const visible = Array.from({ length: 4 }, (_, k) => ({ id: n - k, e: EVENTS[(n - k) % EVENTS.length] }));
    return (
        <div ref={ref} className="h-[150px] space-y-1.5 overflow-hidden">
            <AnimatePresence initial={false}>
                {visible.map(({ id, e }, k) => (
                    <motion.div
                        key={id}
                        layout
                        initial={{ opacity: 0, y: -20 }}
                        animate={{ opacity: 1 - k * 0.22, y: 0 }}
                        exit={{ opacity: 0 }}
                        transition={{ duration: 0.5, ease }}
                        className="flex items-center justify-between gap-3 rounded-lg border hairline bg-ink px-3 py-2 font-mono text-[10.5px] sm:text-[11px]"
                    >
                        <span className="truncate text-fg/85">{e[1]}</span>
                        <span className={k === 0 ? 'text-acid' : 'text-dim'}>{e[0]}</span>
                    </motion.div>
                ))}
            </AnimatePresence>
        </div>
    );
}

const FLOW = ['Webhook', 'Filter', 'Transform', 'Notify', 'Log'];

function Flow() {
    const [step, setStep] = useState(0);
    const ref = useTicker(700, () => setStep((s) => (s + 1) % (FLOW.length + 2)));
    return (
        <div ref={ref} className="grid grid-cols-3 gap-2 py-4 sm:flex sm:h-[120px] sm:items-center sm:gap-0 sm:py-0">
            {FLOW.map((f, i) => (
                <div key={f} className="flex items-center sm:flex-1 sm:last:flex-none">
                    <div
                        className={`w-full rounded-xl border px-2 py-2.5 text-center font-mono text-[10.5px] transition-all duration-500 sm:w-auto sm:px-3 sm:text-[11px] ${
                            step > i ? 'border-acid/60 bg-acid/10 text-acid' : 'border-line text-dim'
                        }`}
                    >
                        {f}
                    </div>
                    {i < FLOW.length - 1 && (
                        <div className="relative mx-1.5 hidden h-px flex-1 bg-line sm:block">
                            <div className="absolute inset-0 origin-left bg-acid transition-transform duration-700" style={{ transform: `scaleX(${step > i + 1 ? 1 : 0})` }} />
                        </div>
                    )}
                </div>
            ))}
        </div>
    );
}

/* One API, two clients: the phone cycles through real app screens while the
   web client mirrors the same state, with data pulsing out from a shared API. */

const SCREENS = ['2FA code', 'Dashboard', 'Messages'];
const pop = { hidden: { opacity: 0, scale: 0.6 }, show: { opacity: 1, scale: 1 } };

function OtpScreen() {
    return (
        <div>
            <div className="h-1.5 w-2/3 rounded bg-white/15" />
            <div className="mt-1 h-1 w-1/2 rounded bg-white/[0.07]" />
            <motion.div className="mt-3 grid grid-cols-4 gap-1" initial="hidden" animate="show" transition={{ staggerChildren: 0.18, delayChildren: 0.15 }}>
                {['4', '8', '1', '7'].map((d) => (
                    <div key={d} className="grid h-5 place-items-center rounded border border-line-2">
                        <motion.span variants={pop} className="font-mono text-[9px] text-fg">
                            {d}
                        </motion.span>
                    </div>
                ))}
            </motion.div>
            <motion.div
                className="mt-3 h-4 rounded bg-acid text-center font-mono text-[7px] leading-4 text-ink"
                initial={{ opacity: 0.25 }}
                animate={{ opacity: 1 }}
                transition={{ delay: 0.95, duration: 0.3 }}
            >
                verified ✓
            </motion.div>
        </div>
    );
}

function DashboardScreen() {
    return (
        <div>
            <div className="grid grid-cols-2 gap-1">
                <div className="h-5 rounded bg-acid/15" />
                <div className="h-5 rounded bg-white/[0.06]" />
            </div>
            <motion.div className="mt-2 flex h-[42px] items-end gap-1" initial="hidden" animate="show" transition={{ staggerChildren: 0.08 }}>
                {[0.45, 0.7, 0.55, 0.9, 0.75].map((h, k) => (
                    <motion.div
                        key={k}
                        className={`flex-1 origin-bottom rounded-sm ${k === 3 ? 'bg-acid' : 'bg-white/20'}`}
                        style={{ height: `${h * 100}%` }}
                        variants={{ hidden: { scaleY: 0 }, show: { scaleY: 1, transition: { duration: 0.6, ease } } }}
                    />
                ))}
            </motion.div>
        </div>
    );
}

function ChatScreen() {
    const bubbles = [
        ['self-start bg-white/10', 'w-3/4'],
        ['self-end bg-acid/80', 'w-2/3'],
        ['self-start bg-white/10', 'w-1/2'],
    ];
    return (
        <motion.div className="flex flex-col gap-1.5" initial="hidden" animate="show" transition={{ staggerChildren: 0.3, delayChildren: 0.1 }}>
            {bubbles.map(([side, w], k) => (
                <motion.div
                    key={k}
                    className={`h-3.5 rounded-md ${side} ${w}`}
                    variants={{ hidden: { opacity: 0, y: 6, scale: 0.9 }, show: { opacity: 1, y: 0, scale: 1, transition: { duration: 0.35, ease } } }}
                />
            ))}
        </motion.div>
    );
}

const SCREEN_VIEWS = [OtpScreen, DashboardScreen, ChatScreen];

function Mobile() {
    const [s, setS] = useState(0);
    const ref = useTicker(2400, () => setS((v) => (v + 1) % SCREENS.length));
    const View = SCREEN_VIEWS[s];

    return (
        <div ref={ref} className="flex h-[168px] items-center gap-2 sm:gap-3">
            {/* Web client */}
            <div className="min-w-0 flex-1 overflow-hidden rounded-lg border border-line-2 bg-ink">
                <div className="flex items-center gap-1 border-b hairline px-2 py-1.5">
                    <span className="h-1.5 w-1.5 rounded-full bg-white/10" />
                    <span className="h-1.5 w-1.5 rounded-full bg-white/10" />
                    <span className="h-1.5 w-1.5 rounded-full bg-white/10" />
                    <span className="ml-1.5 h-2 flex-1 rounded-sm bg-white/[0.05]" />
                </div>
                <div className="flex gap-2 p-2">
                    <div className="w-1/4 space-y-1">
                        {SCREENS.map((name, k) => (
                            <div key={name} className={`h-1.5 rounded-sm transition-colors duration-500 ${k === s ? 'bg-acid/80' : 'bg-white/10'}`} />
                        ))}
                    </div>
                    <div className="min-w-0 flex-1">
                        <AnimatePresence mode="wait">
                            <motion.p
                                key={s}
                                initial={{ opacity: 0, y: 4 }}
                                animate={{ opacity: 1, y: 0 }}
                                exit={{ opacity: 0, y: -4 }}
                                transition={{ duration: 0.25 }}
                                className="truncate font-mono text-[9px] text-fg/80"
                            >
                                {SCREENS[s]}
                            </motion.p>
                        </AnimatePresence>
                        <div className="mt-1.5 h-1.5 w-full rounded bg-white/[0.07]" />
                        <div className="mt-1 h-1.5 w-2/3 rounded bg-white/[0.07]" />
                        <div className="mt-1 h-1.5 w-4/5 rounded bg-white/[0.07]" />
                    </div>
                </div>
                <p className="px-2 pb-1.5 font-mono text-[8px] text-dim">web · React</p>
            </div>

            {/* Shared API with data pulsing out to both clients */}
            <div className="relative flex w-[58px] shrink-0 flex-col items-center sm:w-[72px]">
                <div className="relative h-px w-full bg-line">
                    <motion.span key={`l${s}`} className="absolute inset-0" initial={{ x: '50%', opacity: 1 }} animate={{ x: '0%', opacity: [1, 1, 0] }} transition={{ duration: 0.6, ease: 'easeOut' }}>
                        <span className="absolute left-0 top-1/2 h-1.5 w-1.5 -translate-y-1/2 rounded-full bg-acid" />
                    </motion.span>
                    <motion.span key={`r${s}`} className="absolute inset-0" initial={{ x: '50%', opacity: 1 }} animate={{ x: '100%', opacity: [1, 1, 0] }} transition={{ duration: 0.6, ease: 'easeOut' }}>
                        <span className="absolute left-0 top-1/2 h-1.5 w-1.5 -translate-y-1/2 rounded-full bg-acid" />
                    </motion.span>
                </div>
                <motion.div
                    key={`api${s}`}
                    className="absolute top-1/2 -translate-y-1/2 rounded-md border border-acid/50 bg-[#141a08] px-1.5 py-0.5 font-mono text-[8.5px] text-acid"
                    initial={{ scale: 1.15 }}
                    animate={{ scale: 1 }}
                    transition={{ duration: 0.4, ease }}
                >
                    REST API
                </motion.div>
            </div>

            {/* Flutter app */}
            <div className="flex shrink-0 flex-col items-center">
                <div className="relative h-[146px] w-[74px] overflow-hidden rounded-[16px] border border-line-2 bg-ink px-2 pb-2 pt-4">
                    <span className="absolute left-1/2 top-1.5 h-1 w-5 -translate-x-1/2 rounded-full bg-white/15" />
                    <AnimatePresence mode="wait">
                        <motion.p
                            key={`t${s}`}
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            transition={{ duration: 0.2 }}
                            className="mb-2 truncate font-mono text-[7.5px] text-mute"
                        >
                            {SCREENS[s]}
                        </motion.p>
                    </AnimatePresence>
                    <AnimatePresence mode="wait">
                        <motion.div
                            key={s}
                            initial={{ opacity: 0, x: 16 }}
                            animate={{ opacity: 1, x: 0 }}
                            exit={{ opacity: 0, x: -16 }}
                            transition={{ duration: 0.35, ease }}
                        >
                            <View />
                        </motion.div>
                    </AnimatePresence>
                    <div className="absolute inset-x-2 bottom-2 flex justify-around">
                        {SCREENS.map((name, k) => (
                            <span key={name} className={`h-1 w-1 rounded-full transition-colors duration-500 ${k === s ? 'bg-acid' : 'bg-white/15'}`} />
                        ))}
                    </div>
                </div>
                <p className="mt-1.5 font-mono text-[8px] text-dim">Flutter · iOS · Android</p>
            </div>
        </div>
    );
}

/* ——— Section ——————————————————————————————————————————— */

export default function Capabilities({ skills }) {
    return (
        <section id="capabilities" className="container-x py-[clamp(3.5rem,6.5vw,6rem)]">
            <SectionHead
                index="04"
                label="Capabilities"
                title="One engineer,"
                accent="every layer."
                aside="I'm comfortable anywhere in the stack, and responsible for all of it: from the interface a customer touches to the server it runs on."
            />

            <div className="mt-10 grid gap-3 sm:mt-12 sm:gap-4 md:grid-cols-12">
                <Tile className="md:col-span-12 lg:col-span-7" kicker="Ownership" title="Architecture, end to end" body="I make the calls on structure, data models and trade-offs, then carry them through every layer to production.">
                    <LayerStack />
                </Tile>
                <Tile className="md:col-span-6 lg:col-span-5" delay={0.08} kicker="Infrastructure" title="Servers I provision and run" body="5+ production VPS instances run single-handedly, including a self-hosted n8n: Linux, Nginx, Docker, SSH deploys and monitoring.">
                    <Terminal />
                </Tile>

                <Tile className="md:col-span-6 lg:col-span-4" kicker="AI integration" title="Multi-model AI, orchestrated" body="Anthropic Claude and OpenAI models wired through self-hosted n8n and Laravel, including a company chat agent grounded in real data.">
                    <Router />
                </Tile>
                <Tile className="md:col-span-6 lg:col-span-4" delay={0.08} kicker="Performance" title="Fast by default" body="Rebuilt a company's primary site and took its performance score from ~80 to 95+.">
                    <Gauge />
                </Tile>
                <Tile className="md:col-span-6 lg:col-span-4" delay={0.16} kicker="Payments & realtime" title="Money and messages" body="Stripe and Razorpay billing, MSG91 OTP, Twilio and Pusher events, queued with BullMQ and secured with HMAC-verified webhooks and 2FA.">
                    <EventStream />
                </Tile>

                <Tile className="md:col-span-12 lg:col-span-7" kicker="Automation" title="Workflows that remove toil" body="n8n pipelines that connect services and turn repetitive operational work into something that just runs.">
                    <Flow />
                </Tile>
                <Tile className="md:col-span-12 lg:col-span-5" delay={0.08} kicker="Mobile" title="Cross-platform with Flutter" body="Building and optimising Flutter apps alongside the web products they share an API with.">
                    <Mobile />
                </Tile>
            </div>

            <Toolbox skills={skills} />
        </section>
    );
}

/* ——— Toolbox (skills table) ——————————————————————————————— */

/** '*Laravel' → core skill, 'VPS administration|5+ instances' → name + detail. */
function parseSkill(raw) {
    const core = raw.startsWith('*');
    const [name, note] = (core ? raw.slice(1) : raw).split('|');
    return { name: name.trim(), note: note?.trim(), core };
}

function SkillRow({ group, items, index }) {
    const skills = items.map(parseSkill);
    return (
        <motion.div
            className="group grid grid-cols-1 gap-3 border-b hairline py-4 transition-colors duration-500 hover:bg-white/[0.015] md:grid-cols-12 md:gap-6 md:py-5"
            initial="hidden"
            whileInView="show"
            viewport={{ once: true, margin: '-8% 0px' }}
        >
            <motion.div
                className="flex items-baseline gap-4 md:col-span-3 md:flex-col md:gap-1.5"
                variants={{ hidden: { opacity: 0, y: 12 }, show: { opacity: 1, y: 0, transition: { duration: 0.7, ease } } }}
            >
                <span className="font-mono text-[11px] text-acid">{String(index + 1).padStart(2, '0')}</span>
                <h4 className="text-[17px] font-medium tracking-tight text-fg md:text-[19px]">{group}</h4>
                <span className="ml-auto font-mono text-[10.5px] text-dim md:ml-0">{skills.length} tools</span>
            </motion.div>

            <motion.ul
                className="flex min-w-0 flex-wrap content-start items-start gap-2 self-start md:col-span-9 md:pt-1"
                variants={{ show: { transition: { staggerChildren: 0.035, delayChildren: 0.1 } } }}
            >
                {skills.map((s) => (
                    <motion.li
                        key={s.name}
                        variants={{ hidden: { opacity: 0, y: 8, scale: 0.96 }, show: { opacity: 1, y: 0, scale: 1, transition: { duration: 0.5, ease } } }}
                        className={`inline-flex items-center gap-2 rounded-full border px-3.5 py-1.5 text-[13.5px] transition-[border-color,background-color,transform] duration-300 group-hover:-translate-y-px hover:border-acid/50 ${
                            s.core ? 'border-line-2 bg-white/[0.035] text-fg' : 'border-line bg-transparent text-fg/75'
                        }`}
                    >
                        {s.core && <span className="h-1.5 w-1.5 shrink-0 rounded-full bg-acid" />}
                        <span className="whitespace-nowrap">{s.name}</span>
                        {s.note && <span className="whitespace-nowrap font-mono text-[10.5px] text-dim">· {s.note}</span>}
                    </motion.li>
                ))}
            </motion.ul>
        </motion.div>
    );
}

function Toolbox({ skills }) {
    const total = skills.reduce((n, g) => n + g.items.length, 0);
    return (
        <div className="mt-12 sm:mt-16">
            <FadeUp className="flex flex-wrap items-end justify-between gap-4 border-b hairline pb-5">
                <div>
                    <p className="eyebrow">Toolbox</p>
                    <h3 className="mt-3 text-[clamp(1.5rem,2.6vw,2.2rem)] font-medium leading-tight tracking-[-0.03em]">
                        {total} tools, <span className="serif-i text-mute">{skills.length} disciplines.</span>
                    </h3>
                </div>
                <p className="flex items-center gap-2 font-mono text-[11px] text-mute">
                    <span className="h-1.5 w-1.5 rounded-full bg-acid" /> daily drivers
                    <span className="ml-3 h-3 w-5 rounded-full border border-line" /> worked with
                </p>
            </FadeUp>

            {skills.map((g, i) => (
                <SkillRow key={g.group} group={g.group} items={g.items} index={i} />
            ))}
        </div>
    );
}
