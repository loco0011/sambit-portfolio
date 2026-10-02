import { useRef, useState } from 'react';
import { AnimatePresence, motion, useScroll, useTransform } from 'motion/react';
import SectionHead from '../ui/SectionHead';
import { FadeUp, ease } from '../ui/Reveal';
import SystemDiagram from './SystemDiagram';
import ProjectCards from './ProjectCards';
import { spotlight, useMediaQuery } from '../lib/hooks';

// Must match the `stack` custom variant in app.css.
const STACK_QUERY = '(min-width: 1024px) and (min-height: 640px)';

function ProjectCard({ project, index, total, progress, stacking }) {
    const targetScale = 1 - (total - 1 - index) * 0.04;
    const scale = useTransform(progress, [index / total, 1], [1, targetScale]);
    const dim = useTransform(progress, [index / total, 1], [0, (total - 1 - index) * 0.25]);

    return (
        <div className="stack:sticky stack:top-0 stack:h-screen">
            <div
                className="flex h-full items-start py-3 sm:py-4 stack:py-0 stack:pt-[var(--stack-top)]"
                style={{ '--stack-top': `calc(5rem + ${index * 18}px)` }}
            >
                <motion.article
                    style={stacking ? { scale, transformOrigin: 'top center' } : undefined}
                    onPointerMove={spotlight}
                    className="spotlight relative grid w-full gap-7 overflow-hidden rounded-[22px] p-5 sm:rounded-[28px] sm:p-8 lg:grid-cols-12 lg:gap-10 stack:h-[min(calc(100vh-var(--stack-top)-1.5rem),680px)]"
                >
                    {stacking && <motion.div className="pointer-events-none absolute inset-0 z-10 rounded-[28px] bg-ink" style={{ opacity: dim }} />}

                    <div className={`flex min-h-0 min-w-0 flex-col ${project.diagram ? 'lg:col-span-5' : 'lg:col-span-12'}`}>
                        <span className="eyebrow">
                            <span className="text-acid">{String(index + 1).padStart(2, '0')}</span> / {String(total).padStart(2, '0')} · {project.kind}
                        </span>

                        <h3 className="display mt-4 text-[clamp(2.2rem,min(4.2vw,7.5vh),4rem)] sm:mt-5">{project.name}</h3>
                        <p className="mt-2 font-mono text-[12px] text-mute">{project.role}</p>

                        <p className="mt-4 text-[14.5px] leading-relaxed text-fg/80 sm:mt-5">{project.blurb}</p>

                        <ul className="mt-4 grid gap-2 sm:mt-5 md:grid-cols-2 lg:grid-cols-1">
                            {project.highlights.map((h) => (
                                <li key={h} className="flex gap-3 text-[13.5px] leading-snug text-mute">
                                    <span className="text-acid">↳</span>
                                    {h}
                                </li>
                            ))}
                        </ul>

                        <a
                            href={`/work/${project.slug}`}
                            className="group/cs mt-5 inline-flex items-center gap-2 self-start font-mono text-[12px] text-fg/85 transition-colors hover:text-acid"
                            data-cursor="Read"
                        >
                            Read the case study <span className="transition-transform group-hover/cs:translate-x-1">→</span>
                        </a>

                        <div className="mt-auto flex flex-wrap gap-2 pt-6">
                            {project.stack.map((s) => (
                                <span key={s} className="chip">{s}</span>
                            ))}
                        </div>
                    </div>

                    {project.diagram && (
                        <div className="min-h-0 min-w-0 lg:col-span-7">
                            <SystemDiagram diagram={project.diagram} name={project.name} />
                        </div>
                    )}
                </motion.article>
            </div>
        </div>
    );
}

const INITIAL = 8;

function ArchiveRow({ item, index }) {
    const Tag = item.url ? 'a' : 'div';
    const external = item.url && !item.url.startsWith('/');
    const linkProps = item.url ? { href: item.url, ...(external && { target: '_blank', rel: 'noopener' }), 'data-cursor': 'Open' } : {};
    return (
        <motion.li
            layout="position"
            initial={{ opacity: 0, y: 14 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-5% 0px' }}
            exit={{ opacity: 0 }}
            transition={{ duration: 0.6, ease, delay: Math.min(index % INITIAL, 7) * 0.04 }}
            className="border-b hairline"
        >
            <Tag
                {...linkProps}
                className="group grid grid-cols-[3rem_1fr_auto] items-baseline gap-x-4 gap-y-1 py-4 transition-colors sm:grid-cols-[3.5rem_minmax(0,1.3fr)_minmax(0,1fr)_minmax(0,1fr)_1.5rem] sm:gap-x-6 sm:py-5"
            >
                <span className="font-mono text-[11.5px] text-dim">{item.year || '—'}</span>
                <span className="min-w-0">
                    <span className="block truncate text-[15.5px] font-medium tracking-tight text-fg transition-transform duration-500 ease-out-expo group-hover:translate-x-1.5 sm:text-[16.5px]">
                        {item.name}
                    </span>
                    {item.org && <span className="mt-0.5 block font-mono text-[10.5px] text-dim">{item.org}</span>}
                </span>
                <span className="col-start-2 truncate text-[13.5px] text-mute sm:col-start-auto">{item.kind}</span>
                <span className="col-start-2 truncate font-mono text-[11px] text-dim sm:col-start-auto">{(item.stack ?? []).join(' · ')}</span>
                <span className="col-start-3 row-start-1 text-right text-dim transition-colors group-hover:text-acid sm:col-start-auto sm:row-start-auto">
                    {item.url ? (external ? '↗' : '→') : ''}
                </span>
            </Tag>
        </motion.li>
    );
}

function Archive({ items, total, featuredCount }) {
    const [open, setOpen] = useState(false);
    if (!items.length) return null;
    const sorted = items
        .map((item, i) => ({ item, i }))
        .sort((a, b) => (Number(b.item.year) || 0) - (Number(a.item.year) || 0) || a.i - b.i)
        .map(({ item }) => item);
    const shown = open ? sorted : sorted.slice(0, INITIAL);

    return (
        <div className="mt-14 sm:mt-16">
            <FadeUp className="flex flex-wrap items-end justify-between gap-4 border-b hairline pb-5">
                <div>
                    <p className="eyebrow">More projects</p>
                    <h3 className="mt-3 text-[clamp(1.5rem,2.6vw,2.2rem)] font-medium leading-tight tracking-[-0.03em]">
                        {total}+ shipped <span className="serif-i text-mute">and counting.</span>
                    </h3>
                </div>
                <p className="max-w-sm text-[13.5px] leading-relaxed text-mute">
                    The rest of the list, beyond the builds above. Full list and walkthroughs on request.
                </p>
            </FadeUp>

            <div className="hidden grid-cols-[3.5rem_minmax(0,1.3fr)_minmax(0,1fr)_minmax(0,1fr)_1.5rem] gap-x-6 border-b hairline py-3 font-mono text-[10.5px] uppercase tracking-[0.14em] text-dim sm:grid">
                <span>Year</span>
                <span>Project</span>
                <span>Type</span>
                <span>Built with</span>
                <span />
            </div>

            <ul>
                <AnimatePresence initial={false}>
                    {shown.map((item, i) => (
                        <ArchiveRow key={item.name} item={item} index={i} />
                    ))}
                </AnimatePresence>
            </ul>

            {items.length > INITIAL && (
                <button
                    onClick={() => setOpen((v) => !v)}
                    className="mt-6 flex items-center gap-2 rounded-full border border-line-2 px-5 py-2.5 text-[13px] text-fg transition-colors hover:border-fg"
                >
                    {open ? 'Show less' : `Show all ${items.length}`}
                    <span className={`transition-transform duration-500 ${open ? 'rotate-180' : ''}`}>↓</span>
                </button>
            )}
        </div>
    );
}

export default function Work({ projects, archive = [], total = 0 }) {
    const ref = useRef(null);
    const stacking = useMediaQuery(STACK_QUERY);
    const { scrollYProgress } = useScroll({ target: ref, offset: ['start start', 'end end'] });

    return (
        <section id="work" className="container-x pt-[clamp(3.5rem,6.5vw,6rem)]">
            <SectionHead
                index="03"
                label="Selected work"
                title="Products I've built"
                accent="from the schema up."
                aside={`${projects.length} featured builds out of ${total}+ shipped. Each is a full system, not just a UI. Explore the architecture maps to see how the pieces fit together.`}
            />

            <div ref={ref} className="relative mt-8 sm:mt-10">
                {projects.map((p, i) => (
                    <ProjectCard key={p.slug} project={p} index={i} total={projects.length} progress={scrollYProgress} stacking={stacking} />
                ))}
            </div>

            <ProjectCards items={archive.filter((a) => a.card)} />
            <Archive items={archive.filter((a) => !a.card)} total={total} featuredCount={projects.length} />
        </section>
    );
}
