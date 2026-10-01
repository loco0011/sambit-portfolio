import { useEffect, useRef, useState } from 'react';
import { AnimatePresence, motion, useReducedMotion, useSpring } from 'motion/react';
import { ease } from '../ui/Reveal';

const POSES = [
    { src: '/images/hero/dev-1.webp', label: 'Code mode on' },
    { src: '/images/hero/dev-2.webp', label: 'Power nap between deploys' },
    { src: '/images/hero/dev-3.webp', label: 'New mechanical keyboard' },
    { src: '/images/hero/dev-4.webp', label: 'Picking the stack' },
    { src: '/images/hero/dev-5.webp', label: 'Bug fix fuel' },
    { src: '/images/hero/dev-6.webp', label: 'Plan · code · deploy · repeat' },
];
const INTERVAL = 4500;

// The poses have transparent backgrounds; only soften where the body is cut by the frame (bottom and sides).
const MASK = 'linear-gradient(to bottom, #000 82%, transparent 99%), linear-gradient(to right, transparent 0%, #000 5%, #000 95%, transparent 100%)';
const MASK_STYLE = { maskImage: MASK, WebkitMaskImage: MASK, maskComposite: 'intersect', WebkitMaskComposite: 'source-in' };

export default function HeroCharacter({ ready }) {
    const reduced = useReducedMotion();
    const ref = useRef(null);
    const [index, setIndex] = useState(0);
    const [hovered, setHovered] = useState(false);
    const rotateX = useSpring(0, { stiffness: 120, damping: 18 });
    const rotateY = useSpring(0, { stiffness: 120, damping: 18 });

    // Cycle through the poses; hold still while the cursor is on him. Keyed on the index so a
    // pose picked from the bars also gets its full time on screen.
    useEffect(() => {
        if (!ready || reduced || hovered) return;
        const t = setTimeout(() => setIndex((i) => (i + 1) % POSES.length), INTERVAL);
        return () => clearTimeout(t);
    }, [ready, reduced, hovered, index]);

    // Warm the cache so the first cycle doesn't flash.
    useEffect(() => {
        POSES.slice(1).forEach(({ src }) => {
            const img = new Image();
            img.src = src;
        });
    }, []);

    // Tilt toward the cursor.
    useEffect(() => {
        if (reduced) return;
        const onMove = (e) => {
            const r = ref.current?.getBoundingClientRect();
            if (!r) return;
            const x = Math.max(-1, Math.min(1, (e.clientX - r.left - r.width / 2) / r.width));
            const y = Math.max(-1, Math.min(1, (e.clientY - r.top - r.height / 2) / r.height));
            rotateY.set(x * 8);
            rotateX.set(-y * 6);
        };
        window.addEventListener('pointermove', onMove);
        return () => window.removeEventListener('pointermove', onMove);
    }, [reduced]);

    const pose = POSES[index];

    return (
        <motion.div
            ref={ref}
            className="relative mx-auto w-full max-w-[340px] sm:max-w-[420px] lg:mx-0 lg:ml-auto lg:max-w-[520px]"
            initial={{ opacity: 0, y: 20 }}
            animate={ready ? { opacity: 1, y: 0 } : {}}
            transition={{ duration: 1.2, ease, delay: 0.35 }}
            onPointerEnter={() => setHovered(true)}
            onPointerLeave={() => {
                setHovered(false);
                rotateX.set(0);
                rotateY.set(0);
            }}
        >
            <div style={{ perspective: 1200 }}>
                <motion.div className="relative aspect-square" style={{ rotateX, rotateY }}>
                    <motion.div
                        className="absolute inset-0"
                        animate={reduced ? {} : { y: [0, -6, 0] }}
                        transition={{ duration: 5.5, ease: 'easeInOut', repeat: Infinity }}
                    >
                        <AnimatePresence initial={false}>
                            <motion.img
                                key={pose.src}
                                src={pose.src}
                                alt={`Illustrated developer in sunglasses and headphones: ${pose.label}`}
                                width={932}
                                height={936}
                                draggable={false}
                                decoding="async"
                                className="absolute inset-0 h-full w-full select-none object-contain will-change-[opacity,transform]"
                                style={MASK_STYLE}
                                // Outgoing pose clears quickly; the next one settles in a beat later, so the
                                // two never sit on top of each other at full strength.
                                initial={{ opacity: 0, scale: 0.985, y: 10 }}
                                animate={{ opacity: 1, scale: 1, y: 0, transition: { duration: 1.1, ease: [0.22, 1, 0.36, 1], delay: 0.18 } }}
                                exit={{ opacity: 0, scale: 1.01, y: -6, transition: { duration: 0.55, ease: [0.4, 0, 0.2, 1] } }}
                            />
                        </AnimatePresence>
                    </motion.div>
                </motion.div>
            </div>

            <div className="mt-1 flex items-center justify-between gap-3">
                <p className="font-mono text-[11.5px] tracking-[0.06em] text-mute" aria-live="polite">
                    <span className="mr-2 text-acid">
                        {String(index + 1).padStart(2, '0')} / {String(POSES.length).padStart(2, '0')}
                    </span>
                    {pose.label}
                </p>
                <div className="flex gap-1.5" role="group" aria-label="Choose a pose">
                    {POSES.map((p, i) => (
                        <button
                            key={p.src}
                            type="button"
                            aria-label={p.label}
                            aria-current={i === index}
                            onClick={() => setIndex(i)}
                            className={`h-1.5 rounded-full transition-all duration-300 ${i === index ? 'w-8 bg-acid' : 'w-5 bg-line-2 hover:bg-mute'}`}
                        />
                    ))}
                </div>
            </div>
        </motion.div>
    );
}
