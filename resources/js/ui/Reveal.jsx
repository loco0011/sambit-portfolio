import { motion } from 'motion/react';

const ease = [0.16, 1, 0.3, 1];

/** Words slide up out of a clipping mask, staggered. */
export function SplitWords({ text, className = '', delay = 0, stagger = 0.05, as: Tag = 'span', inView = true }) {
    const words = text.split(' ');
    const trigger = inView ? { whileInView: 'show', viewport: { once: true, margin: '-10% 0px' } } : { animate: 'show' };

    return (
        <Tag className={className} aria-label={text}>
            <motion.span initial="hide" {...trigger} transition={{ staggerChildren: stagger, delayChildren: delay }} aria-hidden>
                {words.map((w, i) => (
                    <span key={i} className="inline-block overflow-hidden pb-[0.12em] -mb-[0.12em] align-top">
                        <motion.span
                            className="inline-block"
                            variants={{ hide: { y: '110%', rotate: 4 }, show: { y: '0%', rotate: 0, transition: { duration: 1.1, ease } } }}
                        >
                            {w}
                            {i < words.length - 1 && ' '}
                        </motion.span>
                    </span>
                ))}
            </motion.span>
        </Tag>
    );
}

/** Generic fade-and-rise on first view. */
export function FadeUp({ children, delay = 0, y = 24, className = '', as = 'div' }) {
    const M = motion[as];
    return (
        <M
            className={className}
            initial={{ opacity: 0, y }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-8% 0px' }}
            transition={{ duration: 1, ease, delay }}
        >
            {children}
        </M>
    );
}

export { ease };
