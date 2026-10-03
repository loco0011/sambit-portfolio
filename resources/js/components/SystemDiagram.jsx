import { useRef, useState } from 'react';
import { motion, useInView } from 'motion/react';

/**
 * Architecture map of a project. Edges fade in on view, packets travel
 * along them, and hovering a node isolates its connections.
 *
 * Performance: packets ride on full-size wrappers translated by a percentage
 * of their own box (= the canvas), so their motion is pure GPU transform.
 * Edges are static dashed lines (animating SVG strokes forces a repaint every
 * frame), and everything animated pauses while the diagram is off-screen.
 */
const mix = (token, pct) => `color-mix(in srgb, var(--color-${token}) ${pct}%, transparent)`;

export default function SystemDiagram({ diagram, name }) {
    const ref = useRef(null);
    const active = useInView(ref, { margin: '0px 0px -10% 0px' });
    const [focus, setFocus] = useState(null);
    const byId = Object.fromEntries(diagram.nodes.map((n) => [n.id, n]));
    const touches = (e) => !focus || e[0] === focus || e[1] === focus;

    return (
        <div ref={ref} className="relative flex h-full min-h-[280px] flex-col overflow-hidden rounded-2xl border hairline bg-ink sm:min-h-[320px]">
            <div className="flex items-center justify-between gap-3 border-b hairline px-4 py-3">
                <div className="flex items-center gap-1.5">
                    <span className="h-2 w-2 rounded-full bg-tint/10" />
                    <span className="h-2 w-2 rounded-full bg-tint/10" />
                    <span className="h-2 w-2 rounded-full bg-tint/10" />
                </div>
                <span className="truncate font-mono text-[10px] text-dim">system.map — {name.toLowerCase()}</span>
                <span className="flex items-center gap-1.5 font-mono text-[10px] text-acid">
                    <span className="h-1.5 w-1.5 rounded-full bg-acid" /> live
                </span>
            </div>

            <div
                className="relative flex-1"
                style={{
                    backgroundImage: 'radial-gradient(color-mix(in srgb, var(--color-tint) 6%, transparent) 1px, transparent 1px)',
                    backgroundSize: '18px 18px',
                }}
            >
                <div className="absolute inset-[12%_9%] md:inset-[10%_12%]">
                    <svg className="absolute inset-0 h-full w-full overflow-visible" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden>
                        {diagram.edges.map((e, i) => {
                            const a = byId[e[0]];
                            const b = byId[e[1]];
                            return (
                                <line
                                    key={i}
                                    x1={a.x} y1={a.y} x2={b.x} y2={b.y}
                                    vectorEffect="non-scaling-stroke"
                                    strokeWidth={1}
                                    strokeDasharray="3 3"
                                    style={{
                                        stroke: touches(e) ? (focus ? mix('acid', 80) : mix('tint', 30)) : mix('tint', 5),
                                        opacity: active ? 1 : 0,
                                        transition: `stroke .35s, opacity 1s ${0.3 + i * 0.1}s`,
                                    }}
                                />
                            );
                        })}
                    </svg>

                    {active &&
                        diagram.edges.map((e, i) => {
                            const a = byId[e[0]];
                            const b = byId[e[1]];
                            return (
                                <motion.div
                                    key={`p${i}`}
                                    aria-hidden
                                    className="pointer-events-none absolute inset-0"
                                    initial={{ x: `${a.x}%`, y: `${a.y}%`, opacity: 0 }}
                                    animate={{
                                        x: [`${a.x}%`, `${b.x}%`],
                                        y: [`${a.y}%`, `${b.y}%`],
                                        opacity: touches(e) ? [0, 1, 1, 0] : 0,
                                    }}
                                    transition={{ duration: 2.2, repeat: Infinity, delay: 0.8 + i * 0.45, ease: 'easeInOut', repeatDelay: 0.8 }}
                                >
                                    <span className="absolute left-0 top-0 h-1.5 w-1.5 -translate-x-1/2 -translate-y-1/2 rounded-full bg-acid shadow-[0_0_10px_2px_color-mix(in_srgb,var(--color-acid)_60%,transparent)]" />
                                </motion.div>
                            );
                        })}

                    {diagram.nodes.map((n, i) => (
                        <button
                            key={n.id}
                            type="button"
                            onMouseEnter={() => setFocus(n.id)}
                            onMouseLeave={() => setFocus(null)}
                            onFocus={() => setFocus(n.id)}
                            onBlur={() => setFocus(null)}
                            className={`absolute whitespace-nowrap rounded-md border px-1.5 py-1 font-mono text-[9px] sm:text-[10px] md:rounded-lg md:px-2.5 md:py-1.5 md:text-[11px] ${
                                n.core
                                    ? 'border-acid/60 bg-acid-bg text-acid shadow-[0_0_30px_-6px_color-mix(in_srgb,var(--color-acid)_50%,transparent)]'
                                    : focus === n.id
                                      ? 'border-fg/60 bg-panel text-fg'
                                      : 'border-line-2 bg-panel text-mute'
                            }`}
                            style={{
                                left: `${n.x}%`,
                                top: `${n.y}%`,
                                opacity: active ? 1 : 0,
                                transform: `translate(-50%, -50%) scale(${active ? 1 : 0.7})`,
                                transition: `opacity .6s ${0.1 + i * 0.06}s, transform .7s cubic-bezier(.34,1.56,.64,1) ${0.1 + i * 0.06}s, color .3s, border-color .3s, background-color .3s`,
                            }}
                            data-cursor="Trace"
                        >
                            {n.label}
                        </button>
                    ))}
                </div>
            </div>

            <div className="border-t hairline px-4 py-2.5 font-mono text-[10px] text-dim">
                <span className="text-mute">
                    <span className="hidden sm:inline">hover</span>
                    <span className="sm:hidden">tap</span> a node
                </span>{' '}
                to trace its connections
            </div>
        </div>
    );
}
