import { useEffect, useState } from 'react';
import { AnimatePresence, animate, motion } from 'motion/react';
import { lockScroll } from '../lib/scroll';

const KEY = 'sm:intro-seen';

function seenThisSession() {
    try {
        return sessionStorage.getItem(KEY) === '1';
    } catch {
        return false;
    }
}

/** Short boot sequence: counts to 100, then the curtain lifts. Shown once per session. */
export default function Loader({ name, onDone }) {
    const skip = seenThisSession() || window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const [visible, setVisible] = useState(!skip);
    const [n, setN] = useState(0);

    useEffect(() => {
        if (skip) {
            onDone();
            return;
        }
        lockScroll(true);
        const c = animate(0, 100, {
            duration: 1.6,
            ease: [0.65, 0, 0.35, 1],
            onUpdate: (v) => setN(Math.round(v)),
            onComplete: () => {
                try {
                    sessionStorage.setItem(KEY, '1');
                } catch {}
                setTimeout(() => setVisible(false), 180);
            },
        });
        return () => c.stop();
    }, []);

    return (
        <AnimatePresence
            onExitComplete={() => {
                lockScroll(false);
                onDone();
            }}
        >
            {visible && (
                <motion.div
                    key="loader"
                    className="fixed inset-0 z-[100] flex flex-col justify-between bg-ink p-6 md:p-10"
                    exit={{ clipPath: 'inset(0 0 100% 0)' }}
                    initial={{ clipPath: 'inset(0 0 0% 0)' }}
                    transition={{ duration: 1, ease: [0.76, 0, 0.24, 1] }}
                >
                    <div className="eyebrow flex justify-between gap-4">
                        <span>{name}</span>
                        <span>Portfolio — {new Date().getFullYear()}</span>
                    </div>

                    <div className="overflow-hidden">
                        <motion.p
                            className="display pb-[0.18em] text-[clamp(2rem,5vw,4rem)] text-fg"
                            initial={{ y: '100%' }}
                            animate={{ y: 0 }}
                            transition={{ duration: 0.9, ease: [0.16, 1, 0.3, 1] }}
                        >
                            Booting <span className="serif-i text-mute">systems</span>
                            <span className="text-acid" style={{ animation: 'blink 1s steps(1) infinite' }}>_</span>
                        </motion.p>
                    </div>

                    <div className="flex items-end justify-between gap-6">
                        <div className="h-px flex-1 bg-line">
                            <div className="h-px origin-left bg-acid" style={{ transform: `scaleX(${n / 100})` }} />
                        </div>
                        <span className="font-mono text-[clamp(3rem,min(10vw,16vh),8rem)] leading-none tabular-nums tracking-tighter">
                            {String(n).padStart(3, '0')}
                        </span>
                    </div>
                </motion.div>
            )}
        </AnimatePresence>
    );
}
