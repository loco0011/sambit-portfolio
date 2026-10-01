import { useEffect, useState } from 'react';
import { AnimatePresence, motion, useMotionValue, useSpring } from 'motion/react';

/**
 * Two-part cursor: a precise dot plus a trailing ring. Any element with
 * data-cursor="Label" grows the ring and shows the label inside it.
 * Only mounts on devices with a fine pointer.
 */
export default function Cursor() {
    const [enabled, setEnabled] = useState(false);
    const [label, setLabel] = useState(null);
    const [hovering, setHovering] = useState(false);
    const [down, setDown] = useState(false);

    const x = useMotionValue(-100);
    const y = useMotionValue(-100);
    const rx = useSpring(x, { stiffness: 380, damping: 32, mass: 0.5 });
    const ry = useSpring(y, { stiffness: 380, damping: 32, mass: 0.5 });

    useEffect(() => {
        if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
        setEnabled(true);

        const move = (e) => {
            x.set(e.clientX);
            y.set(e.clientY);
            const t = e.target.closest?.('[data-cursor], a, button, input, textarea');
            setHovering(!!t);
            setLabel(t?.dataset?.cursor || null);
        };
        const press = () => setDown(true);
        const release = () => setDown(false);

        window.addEventListener('pointermove', move, { passive: true });
        window.addEventListener('pointerdown', press);
        window.addEventListener('pointerup', release);
        return () => {
            window.removeEventListener('pointermove', move);
            window.removeEventListener('pointerdown', press);
            window.removeEventListener('pointerup', release);
        };
    }, []);

    if (!enabled) return null;

    const size = label ? 84 : hovering ? 44 : 28;

    return (
        <>
            <motion.div
                aria-hidden
                className="pointer-events-none fixed left-0 top-0 z-[90] flex items-center justify-center rounded-full border"
                style={{ x: rx, y: ry, translateX: '-50%', translateY: '-50%' }}
                animate={{
                    width: size,
                    height: size,
                    scale: down ? 0.85 : 1,
                    backgroundColor: label ? 'rgba(212,255,79,1)' : 'rgba(212,255,79,0)',
                    borderColor: label ? 'rgba(212,255,79,1)' : hovering ? 'rgba(212,255,79,0.7)' : 'rgba(255,255,255,0.28)',
                }}
                transition={{ type: 'spring', stiffness: 300, damping: 24 }}
            >
                <AnimatePresence>
                    {label && (
                        <motion.span
                            key={label}
                            initial={{ opacity: 0, scale: 0.6 }}
                            animate={{ opacity: 1, scale: 1 }}
                            exit={{ opacity: 0, scale: 0.6 }}
                            className="font-mono text-[10px] font-medium uppercase tracking-widest text-ink"
                        >
                            {label}
                        </motion.span>
                    )}
                </AnimatePresence>
            </motion.div>
            <motion.div
                aria-hidden
                className="pointer-events-none fixed left-0 top-0 z-[91] h-1.5 w-1.5 rounded-full bg-acid"
                style={{ x, y, translateX: '-50%', translateY: '-50%' }}
                animate={{ opacity: label ? 0 : 1 }}
            />
        </>
    );
}
