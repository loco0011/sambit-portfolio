import { useEffect, useRef } from 'react';
import { motion, useMotionTemplate, useMotionValue, useScroll, useTransform } from 'motion/react';
import FieldCanvas from './FieldCanvas';
import HeroCharacter from './HeroCharacter';
import Magnetic from '../ui/Magnetic';
import Clock from '../ui/Clock';
import { ease } from '../ui/Reveal';
import { scrollTo } from '../lib/scroll';

const LINES = [
    [{ t: 'From idea' }],
    [{ t: 'to' }, { t: 'shipped', serif: true }, { t: 'product,' }],
    [{ t: 'every', muted: true }, { t: 'layer.', muted: true }],
];

// Screen-space fade band under the fixed nav. Hero content above the band's
// top edge is fully hidden; it ramps back to visible over FADE_BAND px.
// At rest the band sits off-screen (nothing hidden); within the first
// ENGAGE px of scrolling it slides down to clear the nav *and* its
// progressive-blur zone (~115px), so no hero content lingers up there.
const HIDE_UNTIL = 104; // screen y fully hidden above, once engaged
const FADE_BAND = 56;
const ENGAGE = 120;
const PARALLAX = 0.2;

const bandTop = (s) => {
    const t = Math.min(1, Math.max(0, s / ENGAGE));
    return -FADE_BAND + t * (HIDE_UNTIL + FADE_BAND);
};

export default function Hero({ profile, ready, handoff = false }) {
    const ref = useRef(null);
    const content = useRef(null);
    const { scrollY, scrollYProgress } = useScroll({ target: ref, offset: ['start start', 'end start'] });
    const opacity = useTransform(scrollYProgress, [0, 0.7], [1, 0]);

    // Parallax in px so we know exactly where the content sits on screen.
    const y = useTransform(scrollY, (s) => Math.max(0, s) * PARALLAX);

    // Where the content block starts, measured without transforms.
    const top0 = useMotionValue(0);
    useEffect(() => {
        const measure = () => content.current && top0.set(content.current.offsetTop);
        measure();
        window.addEventListener('resize', measure);
        return () => window.removeEventListener('resize', measure);
    }, []);

    // Convert that screen band into the content's own coordinates, so the
    // parallax headline and buttons dissolve before reaching the nav instead
    // of lingering behind it.
    const fadeStart = useTransform([scrollY, top0], ([s, t]) => bandTop(s) - (t - s + Math.max(0, s) * PARALLAX));
    const fadeEnd = useTransform(fadeStart, (v) => v + FADE_BAND);
    const mask = useMotionTemplate`linear-gradient(to bottom, transparent ${fadeStart}px, #000 ${fadeEnd}px)`;

    const show = ready ? 'show' : 'hide';

    return (
        <section ref={ref} className="relative flex min-h-[100svh] flex-col overflow-hidden pt-24 sm:pt-28">
            <div className="absolute inset-0">
                <FieldCanvas />
            </div>
            <div className="glow absolute -right-60 -top-60 h-[900px] w-[900px]" />
            <div className="pointer-events-none absolute inset-x-0 bottom-0 h-40 bg-gradient-to-b from-transparent to-ink" />

            <motion.div
                ref={content}
                style={{ y, opacity, maskImage: mask, WebkitMaskImage: mask }}
                className="container-x relative grid flex-1 content-center items-center gap-10 lg:grid-cols-12 lg:gap-8"
            >
                <div className="lg:col-span-7">
                <motion.div
                    initial={{ opacity: 0, y: 12 }}
                    animate={ready ? { opacity: 1, y: 0 } : {}}
                    transition={{ duration: 0.8, ease, delay: 0.1 }}
                    className="mb-5 flex flex-wrap items-center gap-3 sm:mb-7"
                >
                    {profile.available && (
                        <span className="chip !whitespace-normal !text-fg">
                            <span className="relative flex h-1.5 w-1.5">
                                <span className="absolute inset-0 rounded-full bg-acid" style={{ animation: 'pulse-ring 1.8s ease-out infinite' }} />
                                <span className="relative h-1.5 w-1.5 rounded-full bg-acid" />
                            </span>
                            {profile.availability}
                        </span>
                    )}
                </motion.div>

                {/* Sized by width AND height so all three lines + CTAs fit one screen */}
                <h1 className="display text-[clamp(2.5rem,min(9vw,13.5vh),8.5rem)] max-sm:text-[11.5vw] lg:text-[clamp(3rem,min(5.7vw,11.5vh),6.6rem)]">
                    {/* Who this is, for search engines and screen readers; the slogan below is the visual headline */}
                    <span className="sr-only">
                        {profile.name}, {profile.role} in {profile.location}:{' '}
                    </span>
                    <span className="sr-only">From idea to shipped product, every layer.</span>
                    <motion.span initial="hide" animate={show} transition={{ staggerChildren: 0.07, delayChildren: 0.15 }} className="block" aria-hidden>
                        {LINES.map((line, li) => (
                            <span key={li} className="block">
                                {line.map((w) => (
                                    <span key={w.t} className="mr-[0.22em] inline-block overflow-hidden pb-[0.1em] -mb-[0.1em] align-top last:mr-0">
                                        <motion.span
                                            className={`inline-block ${w.serif ? 'serif-i pr-[0.06em] text-acid' : ''} ${w.muted ? 'text-dim' : ''}`}
                                            variants={{
                                                hide: { y: '115%', rotate: 5 },
                                                show: { y: '0%', rotate: 0, transition: { duration: 1.3, ease } },
                                            }}
                                        >
                                            {w.t}
                                        </motion.span>
                                    </span>
                                ))}
                            </span>
                        ))}
                    </motion.span>
                </h1>

                <div className="mt-7 grid gap-7 sm:mt-9 sm:gap-8">
                    <motion.p
                        className="max-w-[520px] text-[clamp(0.95rem,1.2vw,1.1rem)] leading-relaxed text-mute"
                        initial={{ opacity: 0, y: 16 }}
                        animate={ready ? { opacity: 1, y: 0 } : {}}
                        transition={{ duration: 1, ease, delay: 0.75 }}
                    >
                        <span className="text-fg">{profile.name}.</span> {profile.headline}
                    </motion.p>

                    <motion.div
                        className="flex flex-wrap items-center gap-3"
                        initial={{ opacity: 0, y: 16 }}
                        animate={ready ? { opacity: 1, y: 0 } : {}}
                        transition={{ duration: 1, ease, delay: 0.9 }}
                    >
                        <Magnetic>
                            <button
                                onClick={() => scrollTo('#work')}
                                data-cursor="View"
                                className="group flex items-center gap-3 rounded-full bg-lime py-3 pl-6 pr-3 text-[14px] font-medium text-on-lime transition-shadow hover:shadow-[0_0_40px_-4px_color-mix(in_srgb,var(--color-lime)_60%,transparent)] light:shadow-[0_1px_2px_rgb(13_13_17/0.08),0_8px_24px_-10px_color-mix(in_srgb,var(--color-acid)_45%,transparent)]"
                            >
                                See selected work
                                <span className="grid h-7 w-7 place-items-center rounded-full bg-on-lime text-lime transition-transform duration-500 group-hover:rotate-[-45deg]">→</span>
                            </button>
                        </Magnetic>
                        <Magnetic>
                            <a
                                href="/resume"
                                data-cursor="PDF"
                                className="flex items-center gap-2 rounded-full border border-line-2 px-6 py-3 text-[14px] text-fg transition-colors hover:border-fg light:bg-panel light:shadow-[0_1px_2px_rgb(13_13_17/0.05)]"
                            >
                                Résumé <span className="text-mute">↓</span>
                            </a>
                        </Magnetic>
                    </motion.div>
                </div>
                </div>

                <div className="lg:col-span-5">
                    <HeroCharacter ready={ready} handoff={handoff} />
                </div>
            </motion.div>

            {/* Meta strip */}
            <motion.div
                className="container-x relative mt-8 pb-6 sm:mt-10 sm:pb-8"
                initial={{ opacity: 0 }}
                animate={ready ? { opacity: 1 } : {}}
                transition={{ duration: 1.2, delay: 1.1 }}
            >
                <dl className="grid grid-cols-2 gap-x-4 gap-y-5 border-t hairline pt-5 md:grid-cols-4">
                    {[
                        ['Currently', `${profile.current.title} @ ${profile.current.company}`],
                        ['Based in', <>Kolkata, IN · <Clock timeZone={profile.timezone} seconds /></>],
                        ['Core', 'Laravel · Node · React · Infra'],
                        ['Shipping since', `2023 — ${profile.projectsTotal}+ projects, 4 teams`],
                    ].map(([k, v]) => (
                        <div key={k}>
                            <dt className="eyebrow mb-1.5">{k}</dt>
                            <dd className="text-[13px] text-fg">{v}</dd>
                        </div>
                    ))}
                </dl>
            </motion.div>
        </section>
    );
}
