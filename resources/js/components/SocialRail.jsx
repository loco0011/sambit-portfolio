import { useEffect, useRef, useState } from 'react';
import { motion } from 'motion/react';
import { ease } from '../ui/Reveal';

const ICONS = {
    github: 'M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12',
    linkedin: 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 4.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z',
};

/**
 * Fixed rail on the left edge: name set vertically, ringed social icons, a hairline down to the bottom. xl+ only.
 * Full-bleed bands marked `data-rail-hide` (the stack marquee) would run underneath it, so it fades out while one
 * crosses the lower part of the screen where the rail sits.
 */
export default function SocialRail({ profile, ready }) {
    const links = profile.links.filter((l) => ICONS[l.label.toLowerCase()]);
    const [covered, setCovered] = useState(false);
    const revealed = useRef(false);

    useEffect(() => {
        const bands = document.querySelectorAll('[data-rail-hide]');
        if (!bands.length) return;
        const inside = new Set();
        const io = new IntersectionObserver(
            (entries) => {
                entries.forEach((e) => (e.isIntersecting ? inside.add(e.target) : inside.delete(e.target)));
                setCovered(inside.size > 0);
            },
            { rootMargin: '-50% 0px 0px 0px' },
        );
        bands.forEach((b) => io.observe(b));
        return () => io.disconnect();
    }, []);

    return (
        <motion.aside
            initial={{ opacity: 0, y: 24 }}
            animate={ready ? { opacity: covered ? 0 : 1, y: 0 } : {}}
            // First appearance waits for the hero to settle; later fades are quick.
            transition={covered ? { duration: 0.3 } : { duration: revealed.current ? 0.6 : 1.2, ease, delay: revealed.current ? 0.05 : 1.2 }}
            onAnimationComplete={() => ready && (revealed.current = true)}
            aria-hidden={covered || undefined}
            inert={covered || undefined}
            className="fixed bottom-0 left-5 z-40 hidden flex-col items-center gap-4 xl:flex 2xl:left-8"
            aria-label="Elsewhere"
        >
            <span className="mb-1 rotate-180 font-mono text-[10px] uppercase tracking-[0.2em] text-dim [writing-mode:vertical-rl]">{profile.name}</span>
            {links.map((l) => (
                <a
                    key={l.url}
                    href={l.url}
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label={`${l.label}: ${l.handle}`}
                    className="group relative grid h-[30px] w-[30px] place-items-center text-mute transition-colors duration-300 hover:text-fg focus-visible:text-fg before:absolute before:inset-0 before:rounded-full before:border before:border-line-2 before:transition-[transform,border-color,border-radius] before:duration-500 before:ease-[var(--ease-out-expo)] hover:before:rotate-45 hover:before:scale-110 hover:before:rounded-[9px] hover:before:border-acid focus-visible:before:rotate-45 focus-visible:before:rounded-[9px] focus-visible:before:border-acid"
                >
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor" aria-hidden>
                        <path d={ICONS[l.label.toLowerCase()]} />
                    </svg>
                    <span className="pointer-events-none absolute left-[calc(100%+12px)] top-1/2 -translate-x-1.5 -translate-y-1/2 whitespace-nowrap rounded-full border hairline bg-ink/80 px-2.5 py-1 font-mono text-[11px] text-fg opacity-0 backdrop-blur-md transition-[opacity,transform] duration-500 ease-[var(--ease-out-expo)] group-hover:translate-x-0 group-hover:opacity-100 group-focus-visible:translate-x-0 group-focus-visible:opacity-100">
                        {l.handle}
                    </span>
                </a>
            ))}
            <span className="h-24 w-px bg-gradient-to-b from-line-2 to-transparent" />
        </motion.aside>
    );
}
