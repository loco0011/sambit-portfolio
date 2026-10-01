import { useEffect, useRef } from 'react';

const BUCKETS = 10;
const NEUTRAL = Array.from({ length: BUCKETS }, (_, i) => `rgba(237,237,239,${((i + 1) / BUCKETS) * 0.26})`);
const LIT = Array.from({ length: BUCKETS }, (_, i) => `rgba(212,255,79,${(i + 1) / BUCKETS})`);

/**
 * A living dot-matrix. Points breathe on a slow wave and are displaced
 * and lit by the cursor, like a field reacting to a charge.
 *
 * Performance: dots are batched into a handful of Path2D fills per frame
 * (instead of one fill per dot), the edge vignette is baked into each dot's
 * alpha (no CSS mask), invisible dots are skipped, and the loop pauses
 * when the hero is off-screen or the tab is hidden.
 */
export default function FieldCanvas({ className = '' }) {
    const ref = useRef(null);

    useEffect(() => {
        const canvas = ref.current;
        const ctx = canvas.getContext('2d', { alpha: true });
        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const mouse = { x: -9999, y: -9999, tx: -9999, ty: -9999 };
        let w, h, points, frame, radius;
        let visible = true;

        function resize() {
            w = canvas.clientWidth;
            h = canvas.clientHeight;
            const dpr = Math.min(window.devicePixelRatio || 1, w < 768 ? 1.5 : 2);
            canvas.width = Math.round(w * dpr);
            canvas.height = Math.round(h * dpr);
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

            const gap = w < 640 ? 30 : 28;
            radius = Math.min(200, Math.max(120, w * 0.12));

            // Elliptical vignette centred at 60% / 40%.
            const cx = w * 0.6;
            const cy = h * 0.4;
            const rx = w * 0.75;
            const ry = h * 0.65;

            points = [];
            const cols = Math.ceil(w / gap) + 1;
            const rows = Math.ceil(h / gap) + 1;
            const ox = (w - (cols - 1) * gap) / 2;
            const oy = (h - (rows - 1) * gap) / 2;
            for (let y = 0; y < rows; y++) {
                for (let x = 0; x < cols; x++) {
                    const px = ox + x * gap;
                    const py = oy + y * gap;
                    const d = Math.hypot((px - cx) / rx, (py - cy) / ry);
                    const t = Math.min(1, Math.max(0, (d - 0.3) / 0.5));
                    const fade = 1 - t * t * (3 - 2 * t);
                    if (fade > 0.03) points.push({ x: px, y: py, fade });
                }
            }
        }

        function draw(t) {
            ctx.clearRect(0, 0, w, h);
            mouse.x += (mouse.tx - mouse.x) * 0.12;
            mouse.y += (mouse.ty - mouse.y) * 0.12;
            const time = t * 0.00035;

            const neutral = Array.from({ length: BUCKETS }, () => new Path2D());
            const lit = Array.from({ length: BUCKETS }, () => new Path2D());

            for (let i = 0; i < points.length; i++) {
                const p = points[i];
                const wave = Math.sin(p.x * 0.012 + time * 2) * Math.cos(p.y * 0.014 - time * 1.4);
                const dx = p.x - mouse.x;
                const dy = p.y - mouse.y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                const force = dist < radius ? 1 - dist / radius : 0;
                const push = force * force * 18;
                const px = p.x + (dist ? (dx / dist) * push : 0);
                const py = p.y + (dist ? (dy / dist) * push : 0) + wave * 3;
                const size = 0.8 + force * 1.6 + (wave + 1) * 0.25;

                let path;
                if (force > 0.02) {
                    const a = Math.min(1, 0.2 + force * 0.9) * p.fade;
                    path = lit[Math.min(BUCKETS - 1, Math.floor(a * BUCKETS))];
                } else {
                    const a = ((0.1 + (wave + 1) * 0.07) / 0.26) * p.fade;
                    path = neutral[Math.min(BUCKETS - 1, Math.floor(a * BUCKETS))];
                }
                path.moveTo(px + size, py);
                path.arc(px, py, size, 0, Math.PI * 2);
            }

            for (let b = 0; b < BUCKETS; b++) {
                ctx.fillStyle = NEUTRAL[b];
                ctx.fill(neutral[b]);
                ctx.fillStyle = LIT[b];
                ctx.fill(lit[b]);
            }

            if (!reduce && visible) frame = requestAnimationFrame(draw);
        }

        const start = () => {
            cancelAnimationFrame(frame);
            if (visible && !document.hidden) frame = requestAnimationFrame(draw);
        };

        const onMove = (e) => {
            const r = canvas.getBoundingClientRect();
            mouse.tx = e.clientX - r.left;
            mouse.ty = e.clientY - r.top;
        };
        const onLeave = () => {
            mouse.tx = -9999;
            mouse.ty = -9999;
        };

        let resizeTimer;
        const onResize = () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                resize();
                start();
            }, 120);
        };

        const io = new IntersectionObserver(([e]) => {
            visible = e.isIntersecting;
            start();
        });

        resize();
        start();
        io.observe(canvas);
        window.addEventListener('resize', onResize);
        document.addEventListener('visibilitychange', start);
        if (window.matchMedia('(hover: hover)').matches) {
            window.addEventListener('pointermove', onMove, { passive: true });
            document.addEventListener('pointerleave', onLeave);
        }

        return () => {
            cancelAnimationFrame(frame);
            clearTimeout(resizeTimer);
            io.disconnect();
            window.removeEventListener('resize', onResize);
            document.removeEventListener('visibilitychange', start);
            window.removeEventListener('pointermove', onMove);
            document.removeEventListener('pointerleave', onLeave);
        };
    }, []);

    return <canvas ref={ref} className={`h-full w-full ${className}`} aria-hidden />;
}
