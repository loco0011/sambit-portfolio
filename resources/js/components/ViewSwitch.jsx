import { motion } from 'motion/react';
import { ease } from '../ui/Reveal';

/** Phones only: floating pill beside the theme toggle to the plain, recruiter-first version of the site (/minimal). Wider screens get it in the nav. */
export default function ViewSwitch({ ready }) {
    return (
        <motion.a
            href="/minimal"
            initial={{ opacity: 0, y: 16 }}
            animate={ready ? { opacity: 1, y: 0 } : {}}
            transition={{ duration: 0.8, ease, delay: 1.4 }}
            data-cursor="Open"
            title="A simpler, recruiter-friendly version of this site"
            className="view-switch group fixed bottom-[max(1.25rem,env(safe-area-inset-bottom))] right-[74px] z-[70] flex h-11 items-center gap-2 rounded-full px-4 text-[13px] font-medium text-fg shadow-[0_8px_30px_-10px_var(--shadow)] sm:hidden"
        >
            <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="text-acid" aria-hidden>
                <rect x="3" y="4" width="18" height="16" rx="2" />
                <path d="M7 9h10M7 13h6" />
            </svg>
            <span>Recruiter view</span>
            <span className="text-mute transition-transform duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5" aria-hidden>
                ↗
            </span>
        </motion.a>
    );
}
