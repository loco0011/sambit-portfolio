import { AnimatePresence, motion } from 'motion/react';
import { toggleTheme, useTheme } from '../lib/theme';

const SUN = (
    <>
        <circle cx="12" cy="12" r="4" />
        <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
    </>
);
const MOON = <path d="M20.5 14.1A8.5 8.5 0 0 1 9.9 3.5a8.5 8.5 0 1 0 10.6 10.6Z" />;

/** Floating sun / moon switch between the dark and light themes, pinned bottom-right. */
export default function ThemeToggle({ className = '' }) {
    const light = useTheme() === 'light';

    return (
        <button
            type="button"
            onClick={toggleTheme}
            aria-label={light ? 'Switch to dark mode' : 'Switch to light mode'}
            title={light ? 'Dark mode' : 'Light mode'}
            data-cursor={light ? 'Dark' : 'Light'}
            className={`glass fixed bottom-[max(1.25rem,env(safe-area-inset-bottom))] right-5 z-[70] grid h-11 w-11 place-items-center overflow-hidden rounded-full border border-line-2 bg-ink/70 text-mute shadow-[0_8px_30px_-10px_var(--shadow)] backdrop-blur-md transition-[color,border-color,transform] duration-300 hover:scale-105 hover:border-acid/50 hover:text-fg sm:bottom-6 sm:right-6 ${className}`}
        >
            <AnimatePresence mode="wait" initial={false}>
                <motion.svg
                    key={light ? 'moon' : 'sun'}
                    viewBox="0 0 24 24"
                    width="17"
                    height="17"
                    fill="none"
                    stroke="currentColor"
                    strokeWidth="1.8"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    initial={{ y: 12, rotate: -60, opacity: 0 }}
                    animate={{ y: 0, rotate: 0, opacity: 1 }}
                    exit={{ y: -12, rotate: 60, opacity: 0 }}
                    transition={{ duration: 0.3, ease: [0.16, 1, 0.3, 1] }}
                    aria-hidden
                >
                    {light ? MOON : SUN}
                </motion.svg>
            </AnimatePresence>
        </button>
    );
}
