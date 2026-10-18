import { useState } from 'react';
import { AnimatePresence, motion } from 'motion/react';
import { ease } from '../ui/Reveal';
import { useTicker } from '../lib/hooks';
import { MusicVisual } from './ProjectCards';

/* Live mini-UIs for the "After hours" stage, one per app. Same rules as the
   "Also built" visuals: a ticker that pauses off-screen, transform/opacity only. */

const AREAS = [
    { name: 'Salt Lake Sector V', x: 70, y: 42, roles: 6 },
    { name: 'New Town', x: 88, y: 18, roles: 4 },
    { name: 'Park Street', x: 42, y: 60, roles: 3 },
    { name: 'Howrah', x: 12, y: 66, roles: 2 },
    { name: 'Garia', x: 60, y: 82, roles: 1 },
];
const FEED = ['keka → 2 new', 'greenhouse → 1 new', 'careers page → 3 new', 'darwinbox → 1 new'];

function HiringMapVisual() {
    const [n, setN] = useState(0);
    const [ref] = useTicker(1800, () => setN((v) => v + 1));
    const area = AREAS[n % AREAS.length];
    return (
        <div ref={ref} className="relative h-full min-h-[260px]">
            {AREAS.map((a) => {
                const on = a === area;
                return (
                    <span key={a.name} className="absolute -translate-x-1/2 -translate-y-1/2" style={{ left: `${a.x}%`, top: `${a.y}%` }}>
                        {on && <span className="absolute inset-0 rounded-full bg-acid" style={{ animation: 'pulse-ring 1.6s ease-out infinite' }} />}
                        <span
                            className={`relative grid h-7 min-w-7 place-items-center rounded-full border px-1.5 font-mono text-[10.5px] transition-colors duration-500 ${
                                on ? 'border-acid bg-acid text-on-lime' : 'border-line-2 bg-panel text-mute'
                            }`}
                        >
                            {a.roles}
                        </span>
                    </span>
                );
            })}
            <AnimatePresence mode="popLayout" initial={false}>
                <motion.div
                    key={area.name}
                    initial={{ opacity: 0, y: 8 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: -6 }}
                    transition={{ duration: 0.45, ease }}
                    className="absolute left-0 top-0 w-[200px] rounded-xl border hairline bg-panel/95 p-3 shadow-[0_12px_30px_-14px_var(--shadow)] backdrop-blur"
                >
                    <p className="text-[12.5px] text-fg">{area.name}</p>
                    <p className="mt-0.5 font-mono text-[10px] text-dim">{area.roles} live {area.roles === 1 ? 'role' : 'roles'} · checked tonight</p>
                    <p className="mt-2 font-mono text-[10px] text-acid">open posting ↗</p>
                </motion.div>
            </AnimatePresence>
            <p className="absolute bottom-0 left-0 flex items-center gap-2 font-mono text-[10px] text-dim">
                <span className="h-1.5 w-1.5 rounded-full bg-acid" style={{ animation: 'blink 1.2s steps(1) infinite' }} />
                fetcher · {FEED[n % FEED.length]} → pending review
            </p>
        </div>
    );
}

const BASKETS = [
    ['amul taaza toned milk 500 ml', [['Blinkit', 28, '9 min'], ['Zepto', 27, '11 min'], ['Instamart', 29, '14 min']]],
    ['maggi masala noodles ×4', [['Blinkit', 56, '8 min'], ['Zepto', 58, '10 min'], ['Instamart', 54, '18 min']]],
    ['harvest gold bread 400 g', [['Blinkit', 45, '12 min'], ['Zepto', 42, '9 min'], ['Instamart', 45, '15 min']]],
];

function SavrVisual() {
    // 0–3: rows resolve one by one; 4–7: cheapest highlighted; then the next search.
    const [n, setN] = useState(4);
    const [ref] = useTicker(700, () => setN((v) => v + 1));
    const step = n % 8;
    const [query, offers] = BASKETS[Math.floor(n / 8) % BASKETS.length];
    const min = Math.min(...offers.map((o) => o[1]));
    return (
        <div ref={ref} className="flex flex-col gap-1.5">
            <div className="flex items-center gap-2 rounded-full border border-line-2 bg-ink-2 px-3 py-1.5 font-mono text-[11px]">
                <span className="text-dim">search ›</span>
                <span className="truncate text-fg">{query}</span>
                <span className="ml-auto tabular-nums text-dim">{step > 3 ? '4.8 s' : `${(step * 1.4).toFixed(1)} s`}</span>
            </div>
            {offers.map(([platform, price, eta], i) => {
                const done = step > i;
                const best = step > 3 && price === min;
                return (
                    <div
                        key={platform}
                        className={`grid grid-cols-[1fr_auto_74px] items-center gap-2.5 rounded-lg border px-2.5 py-1.5 font-mono text-[11px] transition-colors duration-500 ${
                            best ? 'border-acid/45 bg-acid/[0.06] text-fg' : 'border-line text-mute'
                        }`}
                    >
                        <span>{platform}</span>
                        {done ? (
                            <span className="min-w-[52px] text-right tabular-nums text-fg">
                                ₹{price}
                                <span className="ml-1 text-[9.5px] text-dim">{eta}</span>
                            </span>
                        ) : (
                            <span className="h-2 w-[52px] rounded bg-[linear-gradient(90deg,color-mix(in_srgb,var(--color-tint)_5%,transparent),color-mix(in_srgb,var(--color-tint)_14%,transparent),color-mix(in_srgb,var(--color-tint)_5%,transparent))] bg-[length:200%_100%] [animation:shimmer_1s_linear_infinite]" />
                        )}
                        <span className={`text-right text-[9.5px] ${best ? 'text-acid' : 'text-dim'}`}>{done ? (best ? 'cheapest ✓' : 'live') : 'fetching…'}</span>
                    </div>
                );
            })}
        </div>
    );
}

const HEX = '0123456789abcdef';
const hex = (len) => Array.from({ length: len }, () => HEX[Math.floor(Math.random() * 16)]).join('');
const totp = () => `${Math.floor(100 + Math.random() * 900)} ${Math.floor(100 + Math.random() * 900)}`;
const PERIOD = 10;

function XypherVisual() {
    const [s, setS] = useState(() => ({ left: PERIOD, code: totp(), cipher: hex(32), prefix: hex(5) }));
    const [ref] = useTicker(1000, () =>
        setS((p) => {
            const left = p.left <= 1 ? PERIOD : p.left - 1;
            return { left, code: left === PERIOD ? totp() : p.code, cipher: hex(32), prefix: hex(5) };
        }),
    );
    return (
        <div ref={ref} className="grid grid-cols-[auto_minmax(0,1fr)] items-center gap-4 max-[420px]:grid-cols-1 max-[420px]:justify-items-center">
            <div className="relative h-[92px] w-[92px]">
                <svg viewBox="0 0 92 92" className="h-full w-full -rotate-90" aria-hidden>
                    <circle cx="46" cy="46" r="40" fill="none" strokeWidth="3" className="stroke-tint/10" />
                    <circle
                        cx="46" cy="46" r="40" fill="none" strokeWidth="3" strokeLinecap="round" strokeDasharray="251.3"
                        className="stroke-acid transition-[stroke-dashoffset] duration-1000 ease-linear"
                        style={{ strokeDashoffset: 251.3 * (1 - s.left / PERIOD) }}
                    />
                </svg>
                <div className="absolute inset-0 grid place-content-center text-center font-mono text-[10px] leading-tight text-dim">
                    github
                    <span className="text-[17px] font-medium text-fg">{s.code}</span>
                    {s.left}s
                </div>
            </div>
            <div className="grid min-w-0 gap-1.5 max-[420px]:w-full">
                {[
                    ['on your phone', 'netflix · ••••••••••••', false],
                    ['what the server stores', s.cipher, true],
                    ['breach check sends', `${s.prefix.toUpperCase()} (k-anon)`, false],
                ].map(([k, v, cipher]) => (
                    <div key={k} className={`grid min-w-0 gap-0.5 rounded-lg border px-2.5 py-1.5 ${cipher ? 'border-acid/35' : 'border-line'}`}>
                        <span className="font-mono text-[9.5px] uppercase tracking-[0.06em] text-dim">{k}</span>
                        <code className={`truncate font-mono text-[11.5px] ${cipher ? 'text-acid' : 'text-fg'}`}>{v}</code>
                    </div>
                ))}
            </div>
        </div>
    );
}

const SMS = [
    ['VM-HDFCBK', 'Rs 120.00 debited from A/c XX1234 to SWIGGY via UPI. Ref 402187', 'parsed ✓', ['Swiggy', 'Food', -120]],
    ['AD-AMAZON', '482913 is your OTP for a payment at AMAZON. Do not share it.', 'skipped · OTP'],
    ['VK-SBIINB', 'Rs 2,000 withdrawn at ATM from A/c XX7781', 'parsed ✓', ['ATM', 'Cash wallet', 2000]],
    ['JD-ICICIB', 'Your A/c will be debited Rs 499 on 05-Nov for NETFLIX mandate', 'skipped · not paid yet'],
    ['VM-HDFCBK', 'Rs 40.00 paid to 98XXXXXX21 via UPI. Ref 402911', 'parsed ✓', ['98XXXXXX21', 'People', -40]],
    ['VM-HDFCBK', 'Rs 120.00 debited from A/c XX1234 to SWIGGY via UPI. Ref 402187', 'skipped · duplicate ref'],
];
const money = (v) => `${v < 0 ? '−' : '+'}₹${Math.abs(v).toLocaleString('en-IN')}`;

function KashewVisual() {
    // Each message takes three ticks: arrive, verdict (+ ledger row), leave.
    const [s, setS] = useState({ n: 0, ledger: [{ id: -1, tx: ['Zomato', 'Food', -260] }, { id: -2, tx: ['Rapido', 'Travel', -84] }] });
    const [ref] = useTicker(1100, () =>
        setS((p) => {
            const n = p.n + 1;
            const tx = n % 3 === 1 && SMS[Math.floor(n / 3) % SMS.length][3];
            return { n, ledger: tx ? [{ id: n, tx }, ...p.ledger].slice(0, 3) : p.ledger };
        }),
    );
    const phase = s.n % 3;
    const [from, text, verdict, tx] = SMS[Math.floor(s.n / 3) % SMS.length];
    return (
        <div ref={ref} className="grid gap-2">
            <AnimatePresence mode="popLayout" initial={false}>
                {phase < 2 && (
                    <motion.div
                        key={Math.floor(s.n / 3)}
                        initial={{ opacity: 0, y: 8 }}
                        animate={{ opacity: 1, y: 0 }}
                        exit={{ opacity: 0, y: -6 }}
                        transition={{ duration: 0.4, ease }}
                        className="rounded-2xl rounded-bl-md bg-tint/[0.07] px-3 py-2 text-[11.5px] leading-snug text-fg/90"
                    >
                        <span className="mb-0.5 block font-mono text-[9.5px] text-dim">{from}</span>
                        {text}
                    </motion.div>
                )}
            </AnimatePresence>
            <span
                className={`justify-self-start rounded-full border px-2.5 py-0.5 font-mono text-[10px] ${
                    phase === 0 ? 'border-line-2 text-dim' : tx ? 'border-acid/45 text-acid' : 'border-amber-300/45 text-amber-200/90 light:border-amber-600/45 light:text-amber-700'
                }`}
            >
                {phase === 0 ? 'reading…' : verdict}
            </span>
            <div className="grid min-h-[84px] content-start gap-1">
                <AnimatePresence initial={false}>
                    {s.ledger.map(({ id, tx: [who, cat, amt] }) => (
                        <motion.div
                            key={id}
                            layout
                            initial={{ opacity: 0, y: -8 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0 }}
                            transition={{ duration: 0.5, ease }}
                            className="flex justify-between border-b border-dashed hairline px-0.5 py-1 font-mono text-[11px] text-mute"
                        >
                            <span>
                                <span className="text-fg">{who}</span> · {cat}
                            </span>
                            <span className={`tabular-nums ${amt > 0 ? 'text-acid' : 'text-fg'}`}>{money(amt)}</span>
                        </motion.div>
                    ))}
                </AnimatePresence>
            </div>
        </div>
    );
}

const QUESTIONS = [
    ['How does Laravel retry a failed queued job?', [8.6, 7.2, 9.1, 8.0]],
    ['When would you pick Postgres over MySQL?', [7.8, 6.4, 8.8, 8.5]],
    ['How would you cache an expensive API call?', [9.0, 8.1, 8.7, 7.6]],
];
const AXES = ['accuracy', 'completeness', 'relevance', 'clarity'];

function KlyroVisual() {
    // 0–1: answering, 2–4: scored; then the next question.
    const [n, setN] = useState(0);
    const [ref] = useTicker(1300, () => setN((v) => v + 1));
    const step = n % 5;
    const [q, scores] = QUESTIONS[Math.floor(n / 5) % QUESTIONS.length];
    return (
        <div ref={ref} className="grid gap-2.5">
            <AnimatePresence mode="popLayout" initial={false}>
                <motion.div
                    key={q}
                    initial={{ opacity: 0, y: 8 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: -6 }}
                    transition={{ duration: 0.4, ease }}
                    className="max-w-[92%] justify-self-start rounded-2xl rounded-bl-md border border-acid/30 bg-acid/[0.06] px-3 py-2 text-[11.5px] leading-snug text-fg/90"
                >
                    <span className="block font-mono text-[9.5px] text-acid/80">from your CV · Laravel</span>
                    {q}
                </motion.div>
            </AnimatePresence>
            <div className={`flex gap-1 justify-self-end rounded-2xl rounded-br-md bg-tint/[0.08] px-3 py-2.5 ${step < 2 ? '' : 'invisible'}`}>
                {[0, 1, 2].map((d) => (
                    <span key={d} className="h-1.5 w-1.5 rounded-full bg-mute" style={{ animation: `blink 1s ${d * 0.2}s steps(1) infinite` }} />
                ))}
            </div>
            <div className="grid grid-cols-2 gap-x-3.5 gap-y-1.5">
                {AXES.map((a, i) => (
                    <div key={a} className="grid gap-1 font-mono text-[9.5px] text-dim">
                        <span className="flex justify-between">
                            {a}
                            <span className="tabular-nums text-fg">{step >= 2 ? scores[i].toFixed(1) : '–'}</span>
                        </span>
                        <span className="h-[3px] overflow-hidden rounded-full bg-tint/10">
                            <span
                                className="block h-full origin-left bg-acid transition-transform duration-900 ease-out-expo"
                                style={{ transform: `scaleX(${step >= 2 ? scores[i] / 10 : 0})` }}
                            />
                        </span>
                    </div>
                ))}
            </div>
        </div>
    );
}

export const VISUALS = { samgeet: MusicVisual, hiringmap: HiringMapVisual, savr: SavrVisual, xypher: XypherVisual, kashew: KashewVisual, klyro: KlyroVisual };
