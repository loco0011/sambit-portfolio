import Lenis from 'lenis';

let lenis = null;

export function initSmoothScroll() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return () => {};

    lenis = new Lenis({ lerp: 0.1, smoothWheel: true, wheelMultiplier: 1 });
    if (locked) lenis.stop();

    let frame;
    const raf = (time) => {
        lenis.raf(time);
        frame = requestAnimationFrame(raf);
    };
    frame = requestAnimationFrame(raf);

    return () => {
        cancelAnimationFrame(frame);
        lenis.destroy();
        lenis = null;
    };
}

export function scrollTo(target) {
    const el = typeof target === 'string' ? document.querySelector(target) : target;
    if (!el && target !== 0) return;
    if (lenis) lenis.scrollTo(el ?? 0, { offset: -24, duration: 1.4 });
    else (el ?? document.body).scrollIntoView({ behavior: 'smooth' });
}

// Remembered so a lock requested before Lenis exists (the boot intro mounts first) still applies.
let locked = false;

export function lockScroll(value) {
    locked = value;
    if (!lenis) return;
    locked ? lenis.stop() : lenis.start();
}
