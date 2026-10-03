import { useEffect, useRef, useState } from 'react';
import { lockScroll } from '../lib/scroll';
import { HERO_IMAGE_MASK } from './HeroCharacter';

const KEY = 'sm:intro-seen';
const IMG = '/images/hero/dev-1.webp';
const EASE = 'cubic-bezier(.16,1,.3,1)';
const CURTAIN = 'cubic-bezier(.76,0,.24,1)';
const GLYPHS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789</>_#*';

// Where the headphone LEDs and lens glints sit on dev-1.webp, as % of the square frame.
const LEDS = [[31.3, 36.1], [68.4, 44.4]];
const GLINTS = [[47.2, 29.4, 4.4], [58.4, 32.1, 5.6]];

const sleep = (ms) => new Promise((r) => setTimeout(r, ms));
const fade = (els, duration = 300) => els.forEach((el) => el?.animate([{ opacity: 1 }, { opacity: 0 }], { duration, fill: 'forwards' }));

/** Once per session, and never for reduced motion. */
export function shouldPlayIntro() {
    try {
        if (sessionStorage.getItem(KEY) === '1') return false;
    } catch {}
    return !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

function decode(el, text, duration, alive) {
    const start = performance.now();
    return new Promise((done) => {
        const frame = (now) => {
            if (!alive()) return done();
            const p = Math.min(1, (now - start) / duration);
            el.innerHTML = [...text]
                .map((c, i) => {
                    if (c === ' ' || p >= 0.25 + (i / text.length) * 0.75) return c;
                    return `<span class="text-acid">${GLYPHS[(Math.random() * GLYPHS.length) | 0]}</span>`;
                })
                .join('');
            p < 1 ? requestAnimationFrame(frame) : done();
        };
        requestAnimationFrame(frame);
    });
}

async function type(el, text, cps, alive) {
    for (let i = 1; i <= text.length; i++) {
        if (!alive()) return;
        el.textContent = text.slice(0, i);
        await sleep(1000 / cps);
    }
}

/**
 * "Lens" boot: the Shades Dev powers on (headphone LEDs, lens glints, a scan line), the name
 * decodes, then he glides into his spot in the hero while the page builds around him.
 */
export default function Loader({ play, name, role, onReveal }) {
    const [mounted, setMounted] = useState(play);
    const root = useRef(null);
    const skipRef = useRef(() => {});

    useEffect(() => {
        if (!play) {
            onReveal();
            return;
        }

        const html = document.documentElement;
        const r = root.current;
        const q = (s) => r.querySelector(s);
        const qa = (s) => [...r.querySelectorAll(s)];
        let dead = false;
        let revealed = false;
        const alive = () => !dead;

        html.dataset.boot = 'on'; // hides the hero figure until he lands in it
        lockScroll(true);

        const reveal = () => {
            if (revealed) return;
            revealed = true;
            onReveal();
        };
        const finish = () => {
            delete html.dataset.boot;
            lockScroll(false);
            try {
                sessionStorage.setItem(KEY, '1');
            } catch {}
            setMounted(false);
        };
        const skip = async () => {
            if (dead) return;
            dead = true;
            reveal();
            delete html.dataset.boot;
            await r.animate([{ opacity: getComputedStyle(r).opacity }, { opacity: 0 }], { duration: 260, fill: 'forwards' }).finished.catch(() => {});
            finish();
        };
        skipRef.current = skip;
        const onKey = (e) => {
            if (e.metaKey || e.ctrlKey || e.altKey || e.key === 'Tab') return;
            skip();
        };
        window.addEventListener('keydown', onKey);

        (async () => {
            const fig = q('[data-fig]');
            const img = q('[data-img]');
            const st = q('[data-status]');
            const leds = qa('[data-led]');
            const glints = qa('[data-glint]');
            await img.decode().catch(() => {});

            await sleep(150);
            if (!alive()) return;
            st.textContent = 'power';
            leds.forEach((l, i) => l.animate({ opacity: [0, 1, 0, 1, 0.2, 1] }, { duration: 700, delay: i * 120, easing: 'steps(1)', fill: 'forwards' }));

            await sleep(550);
            if (!alive()) return;
            st.textContent = 'optics';
            glints.forEach((g, i) => g.animate({ opacity: [0, 1, 0.1, 0.9, 0.3, 1] }, { duration: 650, delay: i * 90, easing: 'steps(1)', fill: 'forwards' }));

            await sleep(650);
            if (!alive()) return;
            st.textContent = 'scanning';
            const scanEase = 'cubic-bezier(.65,0,.35,1)';
            img.animate([{ clipPath: 'inset(0 0 100% 0)' }, { clipPath: 'inset(0 0 0% 0)' }], { duration: 950, easing: scanEase, fill: 'forwards' });
            q('[data-scan]').animate(
                [{ top: '0%', opacity: 1 }, { top: '96%', opacity: 1, offset: 0.92 }, { top: '100%', opacity: 0 }],
                { duration: 950, easing: scanEase, fill: 'forwards' },
            );
            leds.concat(glints).forEach((g) => g.animate([{ opacity: 1 }, { opacity: 0.35 }], { duration: 600, delay: 600, fill: 'forwards' }));

            await sleep(1000);
            if (!alive()) return;
            q('[data-shine] i').animate([{ left: '-40%' }, { left: '130%' }], { duration: 750, easing: 'cubic-bezier(.6,0,.3,1)' });
            st.innerHTML = '<span class="text-acid">code mode on</span>';
            type(q('[data-role]'), role, 40, alive);
            await decode(q('[data-name]'), name.toUpperCase(), 850, alive);

            await sleep(650);
            if (!alive()) return;

            // Hand-off: fly into the hero figure's exact box. If it's off screen (small phones), just fade.
            fade([q('[data-txt]'), q('[data-hud]'), q('[data-skip]')]);
            fade(leds.concat(glints));
            const target = document.querySelector('[data-hero-figure]')?.getBoundingClientRect();
            const from = fig.getBoundingClientRect();
            const landable = target && target.width > 0 && target.top < window.innerHeight * 0.8 && target.bottom > 0;

            await sleep(160);
            if (!alive()) return;
            reveal();
            const bg = getComputedStyle(r).backgroundColor;
            r.animate([{ backgroundColor: bg }, { backgroundColor: 'transparent' }], { duration: 900, delay: 120, easing: 'ease-out', fill: 'forwards' });

            if (landable) {
                fig.style.transformOrigin = '0 0';
                await fig
                    .animate(
                        [{ transform: 'none' }, { transform: `translate(${target.left - from.left}px, ${target.top - from.top}px) scale(${target.width / from.width})` }],
                        { duration: 1050, easing: CURTAIN, fill: 'forwards' },
                    )
                    .finished.catch(() => {});
            } else {
                await fig
                    .animate([{ opacity: 1, transform: 'none' }, { opacity: 0, transform: 'scale(1.04)' }], { duration: 700, easing: EASE, fill: 'forwards' })
                    .finished.catch(() => {});
            }
            if (alive()) finish();
        })();

        return () => {
            dead = true;
            window.removeEventListener('keydown', onKey);
        };
    }, []);

    if (!mounted) return null;

    return (
        <div ref={root} className="fixed inset-0 z-[100] grid place-items-center overflow-hidden bg-ink" role="status" aria-label={`Loading ${name}'s portfolio`}>
            <div data-hud className="eyebrow pointer-events-none absolute inset-5 grid grid-cols-2 content-between gap-3 md:inset-10">
                <span>Operator</span>
                <span className="text-right">{window.location.host}</span>
                <span>22.57°N 88.36°E · Kolkata</span>
                <span className="text-right">
                    Status <b data-status className="font-normal text-fg">standby</b>
                </span>
            </div>

            <div className="flex flex-col items-center" aria-hidden>
                <div data-fig className="boot-fig relative will-change-transform">
                    <img
                        data-img
                        src={IMG}
                        alt=""
                        width={932}
                        height={936}
                        className="absolute inset-0 h-full w-full object-contain"
                        style={{ ...HERO_IMAGE_MASK, clipPath: 'inset(0 0 100% 0)' }}
                    />
                    {LEDS.map(([x, y]) => (
                        <i key={x} data-led className="boot-led" style={{ left: `${x}%`, top: `${y}%` }} />
                    ))}
                    {GLINTS.map(([x, y, w]) => (
                        <i key={x} data-glint className="boot-glint" style={{ left: `${x}%`, top: `${y}%`, width: `${w}%` }} />
                    ))}
                    <div data-scan className="boot-scan" />
                    <div data-shine className="boot-shine">
                        <i />
                    </div>
                </div>
                <div data-txt className="relative -mt-[clamp(16px,3vh,40px)] text-center">
                    <div data-name className="display min-h-[1em] whitespace-pre text-[clamp(2rem,min(7vw,9vh),5rem)]" />
                    <div data-role className="boot-role eyebrow mt-3.5 min-h-[1.4em]" />
                </div>
            </div>

            <button
                type="button"
                data-skip
                onClick={() => skipRef.current()}
                className="eyebrow absolute bottom-16 left-1/2 -translate-x-1/2 transition-colors hover:text-fg md:bottom-10"
            >
                Skip intro
            </button>
        </div>
    );
}
