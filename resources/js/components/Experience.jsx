import { useRef } from 'react';
import { motion, useScroll, useSpring } from 'motion/react';
import SectionHead from '../ui/SectionHead';
import { ease } from '../ui/Reveal';

function Role({ job }) {
    return (
        <motion.article
            className="relative grid gap-5 pb-11 pl-8 sm:gap-6 sm:pb-13 sm:pl-10 md:grid-cols-12 md:pl-16"
            initial={{ opacity: 0, y: 40 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-15% 0px' }}
            transition={{ duration: 1, ease }}
        >
            {/* Node on the timeline */}
            <span className="absolute left-0 top-2 -translate-x-1/2">
                <span className={`block h-3 w-3 rounded-full border-2 ${job.current ? 'border-acid bg-acid' : 'border-line-2 bg-ink'}`} />
                {job.current && <span className="absolute inset-0 rounded-full bg-acid" style={{ animation: 'pulse-ring 2s ease-out infinite' }} />}
            </span>

            <div className="min-w-0 md:col-span-4">
                <p className="font-mono text-[12px] text-mute">{job.period}</p>
                <h3 className="mt-3 text-[clamp(1.6rem,2.4vw,2.2rem)] font-medium leading-none tracking-[-0.03em]">
                    {job.company}
                    {job.current && <span className="ml-3 align-middle chip !border-acid/40 !text-acid">Now</span>}
                </h3>
                <p className="mt-3 text-[14px] text-fg/80">{job.role}</p>
                <p className="mt-1 font-mono text-[11px] text-dim">{job.location}</p>
            </div>

            <div className="min-w-0 md:col-span-8">
                <p className="text-[clamp(1.05rem,1.35vw,1.2rem)] font-normal leading-relaxed tracking-[-0.01em] text-fg/90">{job.summary}</p>
                <ul className="mt-5 space-y-2.5">
                    {job.points.map((pt, i) => (
                        <motion.li
                            key={i}
                            className="flex gap-4 text-[14.5px] leading-relaxed text-mute"
                            initial={{ opacity: 0, x: -10 }}
                            whileInView={{ opacity: 1, x: 0 }}
                            viewport={{ once: true }}
                            transition={{ duration: 0.7, ease, delay: 0.15 + i * 0.06 }}
                        >
                            <span className="mt-[0.6em] h-px w-4 shrink-0 bg-acid/70" />
                            {pt}
                        </motion.li>
                    ))}
                </ul>
                <div className="mt-5 flex flex-wrap gap-2">
                    {job.tags.map((t) => (
                        <span key={t} className="chip">{t}</span>
                    ))}
                </div>
            </div>
        </motion.article>
    );
}

export default function Experience({ items }) {
    const list = useRef(null);
    const { scrollYProgress } = useScroll({ target: list, offset: ['start 0.7', 'end 0.6'] });
    const fill = useSpring(scrollYProgress, { stiffness: 120, damping: 30 });

    return (
        <section id="experience" className="container-x py-[clamp(3.5rem,6.5vw,6rem)]">
            <SectionHead
                index="02"
                label="Experience"
                title="Three years,"
                accent="four teams, full ownership."
                aside="From client sites in Core PHP to owning the technical foundation end to end. Each role widened the surface area I'm responsible for."
            />

            <div ref={list} className="relative mt-10 sm:mt-12">
                <div className="absolute bottom-16 left-0 top-2 w-px bg-line" />
                <motion.div className="absolute bottom-16 left-0 top-2 w-px origin-top bg-gradient-to-b from-acid via-acid to-acid/0" style={{ scaleY: fill }} />
                {items.map((job) => (
                    <Role key={job.company} job={job} />
                ))}
            </div>
        </section>
    );
}
