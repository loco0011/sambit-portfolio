import SectionHead from '../ui/SectionHead';
import { FadeUp } from '../ui/Reveal';
import { spotlight } from '../lib/hooks';

export default function Principles({ principles, education }) {
    return (
        <section className="container-x py-[clamp(3.5rem,6.5vw,6rem)]">
            <SectionHead index="05" label="How I work" title="Principles" accent="I engineer by." />

            <div className="mt-10 grid gap-10 sm:mt-12 lg:grid-cols-12 lg:gap-12">
                <ol className="lg:col-span-8">
                    {principles.map((p, i) => (
                        <FadeUp as="li" key={p.title} delay={i * 0.06} className="group grid grid-cols-[auto_1fr] gap-4 border-t hairline py-6 last:border-b sm:gap-6 sm:py-7">
                            <span className="font-mono text-[12px] text-dim transition-colors duration-500 group-hover:text-acid">
                                {String(i + 1).padStart(2, '0')}
                            </span>
                            <div>
                                <h3 className="text-[clamp(1.35rem,2.2vw,2rem)] font-medium leading-tight tracking-[-0.03em] transition-transform duration-700 ease-out-expo group-hover:translate-x-2">
                                    {p.title}
                                </h3>
                                <p className="mt-2.5 max-w-xl text-[14.5px] leading-relaxed text-mute">{p.body}</p>
                            </div>
                        </FadeUp>
                    ))}
                </ol>

                <FadeUp delay={0.2} className="lg:col-span-4">
                    <div onPointerMove={spotlight} className="spotlight rounded-3xl p-6 sm:p-7 lg:sticky lg:top-28">
                        <p className="eyebrow">Education</p>
                        <p className="display mt-5 text-[56px] text-acid">9.08</p>
                        <p className="font-mono text-[11px] text-mute">CGPA / 10</p>
                        <div className="my-6 h-px bg-line" />
                        <p className="text-[17px] font-medium leading-snug tracking-tight">{education.degree}</p>
                        <p className="mt-2 text-[14px] text-mute">{education.school}</p>
                        <p className="mt-4 font-mono text-[11px] text-dim">
                            {education.period} · {education.location}
                        </p>
                    </div>
                </FadeUp>
            </div>
        </section>
    );
}
