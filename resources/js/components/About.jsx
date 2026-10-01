import { useRef } from 'react';
import { motion, useScroll, useTransform } from 'motion/react';
import Counter from '../ui/Counter';
import { FadeUp } from '../ui/Reveal';

function Word({ children, progress, range }) {
    const opacity = useTransform(progress, range, [0.12, 1]);
    return (
        <motion.span style={{ opacity }} className="inline-block">
            {children}&nbsp;
        </motion.span>
    );
}

const HIGHLIGHT = /^(architecture,|servers,|automations|on|call)$/;

export default function About({ manifesto, stats }) {
    const ref = useRef(null);
    const { scrollYProgress } = useScroll({ target: ref, offset: ['start 0.85', 'end 0.45'] });
    const words = manifesto.split(' ');

    return (
        <section id="about" className="container-x pb-[clamp(2rem,4vw,3rem)] pt-[clamp(4rem,8.5vw,7rem)]">
            <div className="grid gap-8 md:grid-cols-12 md:gap-10">
                <FadeUp className="eyebrow flex items-center gap-3 self-start md:col-span-3 md:pt-3">
                    <span className="text-acid">01</span>
                    <span className="h-px w-6 bg-line-2" />
                    About
                </FadeUp>

                <div className="md:col-span-9">
                    <p ref={ref} className="text-[clamp(1.35rem,min(2.7vw,5vh),2.45rem)] font-medium leading-[1.2] tracking-[-0.03em]">
                        {words.map((w, i) => {
                            const start = i / words.length;
                            return (
                                <Word key={i} progress={scrollYProgress} range={[start, start + 1 / words.length]}>
                                    <span className={HIGHLIGHT.test(w) ? 'serif-i text-acid' : ''}>{w}</span>
                                </Word>
                            );
                        })}
                    </p>

                    {/* Headline numbers, part of the statement itself */}
                    <div className="mt-10 flex flex-wrap gap-x-12 gap-y-6 sm:mt-12">
                        {stats.map((s, i) => (
                            <FadeUp key={s.label} delay={i * 0.08} className="flex items-center gap-4 border-l-2 border-acid/70 pl-4">
                                <div className="display text-[clamp(2.4rem,min(4.2vw,8vh),3.6rem)] leading-none">
                                    <Counter value={s.value} from={s.from ?? 0} decimals={s.decimals ?? 0} suffix={s.suffix} />
                                </div>
                                <p className="max-w-[170px] text-[13.5px] leading-snug text-mute">{s.label}</p>
                            </FadeUp>
                        ))}
                    </div>
                </div>
            </div>
        </section>
    );
}
