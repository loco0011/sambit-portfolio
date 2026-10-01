import { useEffect, useRef, useState } from 'react';
import { animate, useInView } from 'motion/react';

export default function Counter({ value, from = 0, decimals = 0, suffix = '' }) {
    const ref = useRef(null);
    const inView = useInView(ref, { once: true, margin: '-10% 0px' });
    const [display, setDisplay] = useState(from.toFixed(decimals));

    useEffect(() => {
        if (!inView) return;
        const controls = animate(from, value, {
            duration: 2.2,
            ease: [0.16, 1, 0.3, 1],
            onUpdate: (v) => setDisplay(v.toFixed(decimals)),
        });
        return () => controls.stop();
    }, [inView, value, from, decimals]);

    return (
        <span ref={ref} className="tabular-nums">
            {display}
            <span className="text-acid">{suffix}</span>
        </span>
    );
}
