import { useEffect, useState } from 'react';
import { motion, useMotionValueEvent, useScroll } from 'motion/react';
import { scrollTo } from '../lib/scroll';

// Shortcut label for the palette: ⌘ on Apple devices, Ctrl elsewhere (Windows fonts also lack a clean ⌘ glyph).
const MOD = typeof navigator !== 'undefined' && /Mac|iPhone|iPad/.test(navigator.platform || navigator.userAgent) ? '⌘' : 'Ctrl';

export const SECTIONS = [
    { id: 'about', label: 'About' },
    { id: 'experience', label: 'Experience' },
    { id: 'work', label: 'Work' },
    { id: 'capabilities', label: 'Stack' },
    { id: 'contact', label: 'Contact' },
];

export default function Nav({ profile, onPalette }) {
    const [hidden, setHidden] = useState(false);
    const [scrolled, setScrolled] = useState(false);
    const [active, setActive] = useState(null);
    const { scrollY, scrollYProgress } = useScroll();

    useMotionValueEvent(scrollY, 'change', (v) => {
        const prev = scrollY.getPrevious() ?? 0;
        const nextHidden = v > prev && v > 400;
        const nextScrolled = v > 40;
        setHidden((h) => (h === nextHidden ? h : nextHidden));
        setScrolled((s) => (s === nextScrolled ? s : nextScrolled));
    });

    useEffect(() => {
        const io = new IntersectionObserver(
            (entries) => entries.forEach((e) => e.isIntersecting && setActive(e.target.id)),
            { rootMargin: '-45% 0px -50% 0px' },
        );
        SECTIONS.forEach((s) => {
            const el = document.getElementById(s.id);
            el && io.observe(el);
        });
        return () => io.disconnect();
    }, []);

    return (
        <motion.header
            className="fixed inset-x-0 top-0 z-50"
            animate={{ y: hidden ? '-110%' : '0%' }}
            transition={{ duration: 0.5, ease: [0.16, 1, 0.3, 1] }}
        >
            {/* Progressive blur: strongest at the top, fading out a little below the bar */}
            <div
                aria-hidden
                className={`progressive-blur pointer-events-none absolute inset-x-0 top-0 h-[170%] transition-opacity duration-500 ${
                    scrolled ? 'opacity-100' : 'opacity-0'
                }`}
            >
                <div />
                <div />
                <div />
                <div />
                <span />
            </div>
            <div className="container-x relative flex items-center justify-between gap-3 py-3 sm:py-4">
                {/* Logo only: the same icon as the favicon */}
                <button onClick={() => scrollTo(0)} className="group rounded-[10px]" data-cursor="Top" aria-label={`${profile.name}, ${profile.role}. Back to top`}>
                    <img
                        src="/icon-192.png"
                        alt=""
                        width="38"
                        height="38"
                        className="h-[38px] w-[38px] rounded-[10px] border hairline transition-[transform,border-color] duration-300 group-hover:scale-105 group-hover:border-acid/50"
                    />
                </button>

                <nav
                    className={`hidden items-center gap-1 rounded-full border p-2 transition-colors duration-500 md:flex ${
                        scrolled ? 'border-line-2 bg-ink/55' : 'border-line bg-ink/50'
                    } glass`}
                >
                    {SECTIONS.map((s) => (
                        <button
                            key={s.id}
                            onClick={() => scrollTo(`#${s.id}`)}
                            className={`relative rounded-full px-3 py-1.5 text-[13px] transition-colors lg:px-4 ${active === s.id ? 'text-ink' : 'text-mute hover:text-fg'}`}
                        >
                            {active === s.id && (
                                <motion.span layoutId="nav-pill" className="absolute inset-0 rounded-full bg-forest" transition={{ type: 'spring', stiffness: 400, damping: 34 }} />
                            )}
                            <span className="relative">{s.label}</span>
                        </button>
                    ))}
                </nav>

                <button
                    onClick={onPalette}
                    className="glass flex items-center gap-2 rounded-full border hairline bg-ink/55 px-3 py-1.5 text-[12px] text-mute transition-colors hover:border-line-2 hover:text-fg"
                    aria-label="Open command menu"
                >
                    <span className="lg:hidden">Menu</span>
                    <span className="hidden lg:inline">Quick actions</span>
                    <kbd className="hidden items-center gap-[3px] rounded-md border hairline px-1.5 py-0.5 font-mono text-[10px] leading-none md:inline-flex">
                        <span>{MOD}</span>
                        <span>K</span>
                    </kbd>
                </button>
            </div>
            <motion.div className="absolute inset-x-0 top-0 h-[2px] origin-left bg-acid/80" style={{ scaleX: scrollYProgress }} />
        </motion.header>
    );
}
