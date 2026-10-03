import { useEffect, useRef, useState } from 'react';
import { AnimatePresence, motion, useInView } from 'motion/react';
import { FadeUp, ease } from '../ui/Reveal';
import { spotlight } from '../lib/hooks';

/** Runs `tick` every `ms` only while the returned ref is on screen. */
function useTicker(ms, tick) {
    const ref = useRef(null);
    const active = useInView(ref, { margin: '-5% 0px' });
    useEffect(() => {
        if (!active) return;
        const id = setInterval(tick, ms);
        return () => clearInterval(id);
    }, [active, ms]);
    return [ref, active];
}

/* ——— Visuals ——————————————————————————————————————————— */

const QA = [
    ['What is our refund policy?', 'Refunds within 14 days, per the policy doc.'],
    ['Which plan suits a 10-person team?', 'The Team plan: 10 seats, shared workspace.'],
    ['When is support available?', 'Mon–Sat, 10:00–19:00 IST, via chat or email.'],
];

function ChatVisual() {
    // One counter drives both: step 0 question, 1 typing, 2 answer; then next question.
    const [n, setN] = useState(0);
    const [ref] = useTicker(1300, () => setN((v) => v + 1));
    const step = n % 3;
    const q = Math.floor(n / 3) % QA.length;
    return (
        <div ref={ref} className="flex h-full flex-col justify-end gap-2 p-4">
            <AnimatePresence mode="popLayout">
                <motion.div
                    key={`q${q}`}
                    layout
                    initial={{ opacity: 0, y: 8 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: -8 }}
                    transition={{ duration: 0.4, ease }}
                    className="max-w-[82%] self-end rounded-2xl rounded-br-md bg-tint/[0.08] px-3 py-2 text-[12px] text-fg/90"
                >
                    {QA[q][0]}
                </motion.div>
                {step === 1 && (
                    <motion.div
                        key="typing"
                        layout
                        initial={{ opacity: 0, y: 6 }}
                        animate={{ opacity: 1, y: 0 }}
                        exit={{ opacity: 0 }}
                        className="flex gap-1 self-start rounded-2xl rounded-bl-md border border-acid/30 bg-acid/[0.06] px-3 py-2.5"
                    >
                        {[0, 1, 2].map((d) => (
                            <span key={d} className="h-1.5 w-1.5 rounded-full bg-acid" style={{ animation: `blink 1s ${d * 0.2}s steps(1) infinite` }} />
                        ))}
                    </motion.div>
                )}
                {step === 2 && (
                    <motion.div
                        key={`a${q}`}
                        layout
                        initial={{ opacity: 0, y: 6 }}
                        animate={{ opacity: 1, y: 0 }}
                        exit={{ opacity: 0 }}
                        transition={{ duration: 0.4, ease }}
                        className="max-w-[88%] self-start rounded-2xl rounded-bl-md border border-acid/30 bg-acid/[0.06] px-3 py-2 text-[12px] text-fg/90"
                    >
                        {QA[q][1]}
                        <span className="mt-1 block font-mono text-[9.5px] text-acid/80">↳ source: company docs</span>
                    </motion.div>
                )}
            </AnimatePresence>
        </div>
    );
}

const SERVERS = ['vps-01 · web', 'vps-02 · api', 'vps-03 · n8n', 'vps-04 · mail', 'vps-05 · apps'];

function InfraVisual() {
    const [deploying, setDeploying] = useState(0);
    const [ref] = useTicker(1100, () => setDeploying((d) => (d + 1) % SERVERS.length));
    return (
        <div ref={ref} className="flex h-full flex-col justify-center gap-1.5 p-4">
            {SERVERS.map((s, i) => {
                const busy = i === deploying;
                return (
                    <div
                        key={s}
                        className={`flex items-center justify-between rounded-md border px-3 py-1.5 font-mono text-[10.5px] transition-colors duration-500 ${
                            busy ? 'border-acid/40 bg-acid/[0.05] text-fg' : 'border-line text-mute'
                        }`}
                    >
                        <span className="flex items-center gap-2">
                            <span className={`h-1.5 w-1.5 rounded-full transition-colors duration-300 ${busy ? 'bg-amber-300 light:bg-amber-500' : 'bg-acid'}`} />
                            {s}
                        </span>
                        <span className={busy ? 'text-amber-200/90 light:text-amber-700' : 'text-dim'}>{busy ? 'deploying…' : 'healthy'}</span>
                    </div>
                );
            })}
        </div>
    );
}

function MusicVisual() {
    const [skipped, setSkipped] = useState(128);
    const [ref, active] = useTicker(1600, () => setSkipped((n) => n + 1));
    return (
        <div ref={ref} className="flex h-full items-center gap-4 p-4">
            <div className="grid h-20 w-20 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-acid/40 via-acid/10 to-transparent">
                <span className="text-[26px] text-ink/80 light:text-acid">♪</span>
            </div>
            <div className="min-w-0 flex-1">
                <p className="truncate text-[13px] text-fg">Now playing</p>
                <p className="truncate font-mono text-[10px] text-dim">no interruptions</p>
                <div className="mt-2.5 flex h-6 items-end gap-[3px]">
                    {Array.from({ length: 14 }, (_, k) => (
                        <span
                            key={k}
                            className="w-1 origin-bottom rounded-sm bg-acid/80"
                            style={{
                                height: '100%',
                                animation: `eq ${0.7 + (k % 5) * 0.13}s ${k * 0.05}s ease-in-out infinite alternate`,
                                animationPlayState: active ? 'running' : 'paused',
                            }}
                        />
                    ))}
                </div>
                <div className="mt-2 h-[3px] overflow-hidden rounded-full bg-tint/10">
                    <div className="h-full w-full origin-left bg-fg/70" style={{ animation: 'progress 8s linear infinite', animationPlayState: active ? 'running' : 'paused' }} />
                </div>
                <p className="mt-2 font-mono text-[10px] text-mute">
                    ads skipped{' '}
                    <AnimatePresence mode="popLayout" initial={false}>
                        <motion.span key={skipped} className="inline-block text-acid" initial={{ y: -8, opacity: 0 }} animate={{ y: 0, opacity: 1 }} exit={{ y: 8, opacity: 0 }}>
                            {skipped}
                        </motion.span>
                    </AnimatePresence>
                </p>
            </div>
        </div>
    );
}

const STAGES = ['Lead', 'Qualified', 'Won'];

function CrmVisual() {
    const [stage, setStage] = useState(0);
    const [ref] = useTicker(1300, () => setStage((s) => (s + 1) % STAGES.length));
    return (
        <div ref={ref} className="grid h-full grid-cols-3 gap-2 p-4">
            {STAGES.map((name, i) => (
                <div key={name} className="flex flex-col gap-1.5 rounded-lg border border-line bg-tint/[0.015] p-2">
                    <p className="font-mono text-[9.5px] uppercase tracking-widest text-dim">{name}</p>
                    <div className="h-4 rounded bg-tint/[0.06]" />
                    {i === stage && (
                        <motion.div
                            layoutId="crm-deal"
                            className="rounded border border-acid/50 bg-acid/10 px-1.5 py-1 font-mono text-[9px] text-acid"
                            transition={{ type: 'spring', stiffness: 260, damping: 26 }}
                        >
                            {i === 2 ? 'Deal ✓' : 'Deal'}
                        </motion.div>
                    )}
                    <div className="h-4 rounded bg-tint/[0.04]" />
                </div>
            ))}
        </div>
    );
}

function EarningVisual() {
    const [balance, setBalance] = useState(1240);
    const [gain, setGain] = useState({ id: 0, v: 25 });
    const [ref] = useTicker(1500, () => {
        const v = [10, 25, 50, 15][Math.floor(Math.random() * 4)];
        setGain((g) => ({ id: g.id + 1, v }));
        setBalance((b) => (b > 1900 ? 1240 : b + v));
    });
    const goal = Math.min(1, (balance - 1000) / 1000);
    return (
        <div ref={ref} className="flex h-full items-center justify-center p-4">
            <div className="relative w-[128px] rounded-[18px] border border-line-2 bg-ink px-3 pb-3 pt-4">
                <span className="absolute left-1/2 top-1.5 h-1 w-6 -translate-x-1/2 rounded-full bg-tint/15" />
                <p className="font-mono text-[9px] text-dim">wallet</p>
                <p className="mt-0.5 flex items-baseline gap-1 text-[20px] font-medium tabular-nums tracking-tight text-fg">
                    {balance.toLocaleString('en-IN')}
                    <span className="text-[10px] text-acid">coins</span>
                </p>
                <div className="mt-2 h-1 overflow-hidden rounded-full bg-tint/10">
                    <div className="h-full origin-left rounded-full bg-acid transition-transform duration-700" style={{ transform: `scaleX(${goal})` }} />
                </div>
                <p className="mt-1 font-mono text-[8.5px] text-dim">next payout</p>
                <div className="mt-2 grid grid-cols-2 gap-1">
                    <div className="h-5 rounded bg-tint/[0.06]" />
                    <div className="h-5 rounded bg-acid/15" />
                </div>
                <AnimatePresence>
                    <motion.span
                        key={gain.id}
                        className="pointer-events-none absolute right-2 top-7 font-mono text-[11px] font-medium text-acid"
                        initial={{ opacity: 0, y: 6 }}
                        animate={{ opacity: [0, 1, 1, 0], y: -18 }}
                        transition={{ duration: 1.2, ease: 'easeOut' }}
                    >
                        +{gain.v}
                    </motion.span>
                </AnimatePresence>
            </div>
        </div>
    );
}

const VISUALS = { chat: ChatVisual, infra: InfraVisual, music: MusicVisual, crm: CrmVisual, earning: EarningVisual };

/* ——— Grid ——————————————————————————————————————————————— */

// 6-column grid: first two cards share a row, the next three share the next.
const SPANS = ['md:col-span-3', 'md:col-span-3', 'md:col-span-2', 'md:col-span-2', 'md:col-span-2'];

function Card({ item, index }) {
    const Visual = VISUALS[item.card];
    const Tag = item.url ? 'a' : 'div';
    const external = item.url && !item.url.startsWith('/');
    const linkProps = item.url ? { href: item.url, ...(external && { target: '_blank', rel: 'noopener' }), 'data-cursor': 'Open' } : {};
    return (
        <FadeUp delay={(index % 3) * 0.07} className={SPANS[index % SPANS.length]}>
            <Tag {...linkProps} onPointerMove={spotlight} className="spotlight group flex h-full flex-col overflow-hidden rounded-3xl">
                <div className="relative h-[190px] border-b hairline bg-ink">{Visual && <Visual />}</div>
                <div className="flex flex-1 flex-col p-5 sm:p-6">
                    <div className="flex items-start justify-between gap-3">
                        <h4 className="text-[18px] font-medium tracking-tight text-fg">{item.name}</h4>
                        {item.url && <span className="text-dim transition-colors group-hover:text-acid">{external ? '↗' : '→'}</span>}
                    </div>
                    {(item.org || item.year) && (
                        <p className="mt-1 font-mono text-[11px] text-dim">{[item.org, item.year].filter(Boolean).join(' · ')}</p>
                    )}
                    <p className="mt-2.5 text-[14px] leading-relaxed text-mute">{item.kind}</p>
                    {item.stack?.length > 0 && (
                        <div className="mt-auto flex flex-wrap gap-1.5 pt-5">
                            {item.stack.map((s) => (
                                <span key={s} className="chip">{s}</span>
                            ))}
                        </div>
                    )}
                </div>
            </Tag>
        </FadeUp>
    );
}

export default function ProjectCards({ items }) {
    if (!items.length) return null;
    return (
        <div className="mt-14 sm:mt-16">
            <FadeUp className="flex flex-wrap items-end justify-between gap-4 border-b hairline pb-5">
                <div>
                    <p className="eyebrow">Also built</p>
                    <h3 className="mt-3 text-[clamp(1.5rem,2.6vw,2.2rem)] font-medium leading-tight tracking-[-0.03em]">
                        Products across AI, infra <span className="serif-i text-mute">and mobile.</span>
                    </h3>
                </div>
            </FadeUp>
            <div className="mt-6 grid gap-3 sm:gap-4 md:grid-cols-6">
                {items.map((item, i) => (
                    <Card key={item.name} item={item} index={i} />
                ))}
            </div>
        </div>
    );
}
