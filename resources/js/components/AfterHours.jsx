import { useEffect, useRef, useState } from 'react';
import { AnimatePresence, motion, useInView, useReducedMotion } from 'motion/react';
import SectionHead from '../ui/SectionHead';
import { FadeUp, ease } from '../ui/Reveal';
import SystemDiagram from './SystemDiagram';
import { VISUALS } from './AfterHoursVisuals';
import { spotlight } from '../lib/hooks';

const DURATION = 8000;

/**
 * Side projects as one showcase: an index of apps on the left (the active one
 * unfolds its details) and a single stage on the right that shows the app
 * running beside its system map. Auto-advances while on screen, pauses on
 * hover, and a click pins the chosen app.
 */
export default function AfterHours({ items }) {
    const ref = useRef(null);
    const inView = useInView(ref, { margin: '-20% 0px' });
    const reduced = useReducedMotion();
    const [active, setActive] = useState(0);
    const [pinned, setPinned] = useState(false);
    const [hovered, setHovered] = useState(false);

    // Time left on the current app survives pauses; a new app starts a fresh countdown.
    const left = useRef(DURATION);
    const shown = useRef(active);
    const running = !pinned && !hovered && inView && !reduced && items.length > 1;

    useEffect(() => {
        if (shown.current !== active) {
            shown.current = active;
            left.current = DURATION;
        }
        if (!running) return;
        const started = Date.now();
        const id = setTimeout(() => setActive((i) => (i + 1) % items.length), left.current);
        return () => {
            clearTimeout(id);
            left.current -= Date.now() - started;
        };
    }, [active, running, items.length]);

    if (!items.length) return null;
    const item = items[active];
    const Visual = VISUALS[item.visual];

    return (
        <section id="after-hours" ref={ref} className="container-x pt-[clamp(3.5rem,6.5vw,6rem)]">
            <SectionHead
                index="04"
                label="After hours"
                title="Things I build"
                accent="on my own time."
                aside="Products I design, build and ship for problems I kept running into. No ads, and none of them asks for more data than it needs. Pick one to see it running next to its system map."
            />

            <div
                className="mt-8 grid gap-6 sm:mt-10 lg:grid-cols-12 lg:gap-10"
                onPointerEnter={(e) => e.pointerType === 'mouse' && setHovered(true)}
                onPointerLeave={() => setHovered(false)}
            >
                <FadeUp as="ol" className="border-t hairline lg:col-span-5">
                    {items.map((p, i) => {
                        const on = i === active;
                        return (
                            <li key={p.slug} className="relative border-b hairline">
                                <button
                                    type="button"
                                    onClick={() => {
                                        setActive(i);
                                        setPinned(true);
                                    }}
                                    aria-expanded={on}
                                    data-cursor={on ? undefined : 'Show'}
                                    className="group grid w-full grid-cols-[2.5rem_minmax(0,1fr)_auto] items-center gap-x-3.5 gap-y-1 py-4 text-left sm:py-5"
                                >
                                    <span className={`font-mono text-[11px] transition-colors ${on ? 'text-acid' : 'text-dim'}`}>{String(i + 1).padStart(2, '0')}</span>
                                    <span
                                        className={`display text-[clamp(1.75rem,3.4vw,3rem)] transition-[color,transform] duration-500 ease-out-expo ${
                                            on ? 'text-fg' : 'text-dim group-hover:translate-x-1.5 group-hover:text-mute'
                                        }`}
                                    >
                                        {p.name}
                                    </span>
                                    <img
                                        src={p.icon}
                                        alt=""
                                        width="40"
                                        height="40"
                                        loading="lazy"
                                        className={`col-start-3 row-span-2 row-start-1 h-10 w-10 rounded-[11px] transition-[filter,opacity,transform] duration-500 ease-out-expo ${
                                            on ? '' : 'rotate-[-8deg] scale-90 opacity-45 grayscale'
                                        }`}
                                    />
                                    <span className={`col-start-2 font-mono text-[11px] transition-colors ${on ? 'text-mute' : 'text-dim'}`}>{p.tag}</span>
                                </button>

                                <AnimatePresence initial={false}>
                                    {on && (
                                        <motion.div
                                            initial={{ height: 0, opacity: 0 }}
                                            animate={{ height: 'auto', opacity: 1 }}
                                            exit={{ height: 0, opacity: 0 }}
                                            transition={{ duration: 0.6, ease }}
                                            className="overflow-hidden"
                                        >
                                            <div className="pb-5 pl-[calc(2.5rem+0.875rem)]">
                                                <p className="max-w-[52ch] text-[14px] leading-relaxed text-mute">{p.kind}</p>
                                                <ul className="mt-3 grid gap-2">
                                                    {p.highlights.map((h) => (
                                                        <li key={h} className="flex gap-3 text-[13.5px] leading-snug text-mute">
                                                            <span className="text-acid">↳</span>
                                                            {h}
                                                        </li>
                                                    ))}
                                                </ul>
                                                <div className="mt-4 flex flex-wrap gap-1.5">
                                                    {p.stack.map((s) => (
                                                        <span key={s} className="chip">{s}</span>
                                                    ))}
                                                </div>
                                            </div>
                                        </motion.div>
                                    )}
                                </AnimatePresence>

                                {on && !pinned && !reduced && (
                                    <span
                                        key={active}
                                        aria-hidden
                                        className="absolute inset-x-0 -bottom-px h-px origin-left bg-acid"
                                        style={{ animation: `progress ${DURATION}ms linear forwards`, animationPlayState: running ? 'running' : 'paused' }}
                                    />
                                )}
                            </li>
                        );
                    })}
                </FadeUp>

                <FadeUp delay={0.1} className="max-lg:order-first lg:col-span-7">
                    <article onPointerMove={spotlight} className="spotlight grid gap-3 rounded-[22px] p-3 sm:rounded-[28px] sm:p-4 md:grid-cols-2 lg:sticky lg:top-24">
                        <div className="flex min-h-[320px] flex-col overflow-hidden rounded-2xl border hairline bg-ink">
                            <div className="flex items-center justify-between gap-3 border-b hairline px-4 py-3">
                                <div className="flex items-center gap-1.5">
                                    <span className="h-2 w-2 rounded-full bg-tint/10" />
                                    <span className="h-2 w-2 rounded-full bg-tint/10" />
                                    <span className="h-2 w-2 rounded-full bg-tint/10" />
                                </div>
                                <span className="truncate font-mono text-[10px] text-dim">{item.slug}.app</span>
                                <span className="flex items-center gap-1.5 font-mono text-[10px] text-acid">
                                    <span className="h-1.5 w-1.5 rounded-full bg-acid" /> live
                                </span>
                            </div>
                            <div className="relative grid flex-1 place-items-center p-5">
                                <AnimatePresence mode="wait" initial={false}>
                                    <motion.div
                                        key={item.slug}
                                        initial={{ opacity: 0, y: 14, filter: 'blur(4px)' }}
                                        animate={{ opacity: 1, y: 0, filter: 'blur(0px)' }}
                                        exit={{ opacity: 0, y: -10, filter: 'blur(4px)' }}
                                        transition={{ duration: 0.45, ease }}
                                        className="w-full max-w-[330px] self-stretch content-center [display:grid]"
                                    >
                                        {Visual && <Visual />}
                                    </motion.div>
                                </AnimatePresence>
                            </div>
                            <div className="flex min-h-[42px] items-center justify-between gap-3 border-t hairline py-1.5 pl-4 pr-1.5 font-mono text-[10px] text-dim">
                                <span className="truncate">{item.meta}</span>
                                <a
                                    href={`/apps/${item.slug}`}
                                    data-cursor="Open"
                                    className="group/get inline-flex shrink-0 items-center gap-2 rounded-full bg-lime py-1 pl-3 pr-1 font-sans text-[12px] font-medium text-on-lime transition-shadow hover:shadow-[0_0_30px_-6px_color-mix(in_srgb,var(--color-lime)_60%,transparent)]"
                                >
                                    {item.platform === 'web' ? 'Visit website' : 'Get the app'}
                                    <span className={`grid h-[22px] w-[22px] place-items-center rounded-full bg-on-lime text-[11px] text-lime transition-transform duration-500 ease-out-expo ${item.platform === 'web' ? 'group-hover/get:translate-x-0.5' : 'group-hover/get:translate-y-0.5'}`}>
                                        {item.platform === 'web' ? '→' : '↓'}
                                    </span>
                                </a>
                            </div>
                        </div>

                        <AnimatePresence mode="wait" initial={false}>
                            <motion.div
                                key={item.slug}
                                initial={{ opacity: 0, y: 14, filter: 'blur(4px)' }}
                                animate={{ opacity: 1, y: 0, filter: 'blur(0px)' }}
                                exit={{ opacity: 0, y: -10, filter: 'blur(4px)' }}
                                transition={{ duration: 0.45, ease, delay: 0.05 }}
                                className="min-w-0"
                            >
                                <SystemDiagram diagram={item.diagram} name={item.name} />
                            </motion.div>
                        </AnimatePresence>
                    </article>
                </FadeUp>
            </div>
        </section>
    );
}
