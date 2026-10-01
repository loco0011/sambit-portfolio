import { useEffect, useRef, useState } from 'react';

export function Screws() {
    return ['tl', 'tr', 'bl', 'br'].map((p) => <span key={p} className={`screw screw--${p}`} aria-hidden />);
}

export function Led({ on, color = 'green', blink, className = '' }) {
    return <span className={`led ${on ? `led--on led--${color}` : ''} ${on && blink ? 'led--blink' : ''} ${className}`} aria-hidden />;
}

export function ModLabel({ index, children, aside }) {
    return (
        <div className="mod-label">
            <span>
                <b>{index}</b>
                {children}
            </span>
            {aside && <span className="aside">{aside}</span>}
        </div>
    );
}

/**
 * Hold-to-confirm button: replaces "are you sure?" dialogs.
 * The cap fills with hazard stripes while held; letting go early cancels.
 */
export function HoldButton({ ms = 1100, onConfirm, className = '', children, disabled }) {
    const [progress, setProgress] = useState(0);
    const frame = useRef();
    const started = useRef(0);

    const stop = () => {
        cancelAnimationFrame(frame.current);
        setProgress(0);
    };

    const tick = (now) => {
        const p = Math.min(1, (now - started.current) / ms);
        setProgress(p);
        if (p >= 1) {
            stop();
            onConfirm();
        } else {
            frame.current = requestAnimationFrame(tick);
        }
    };

    const begin = (e) => {
        if (disabled || (e.type === 'keydown' && ((e.key !== 'Enter' && e.key !== ' ') || e.repeat))) return;
        e.preventDefault();
        started.current = performance.now();
        frame.current = requestAnimationFrame(tick);
    };

    useEffect(() => () => cancelAnimationFrame(frame.current), []);

    return (
        <button
            type="button"
            className={`btn hold ${className}`}
            style={{ '--p': progress }}
            disabled={disabled}
            onPointerDown={begin}
            onPointerUp={stop}
            onPointerLeave={stop}
            onKeyDown={begin}
            onKeyUp={stop}
            onContextMenu={(e) => e.preventDefault()}
        >
            {children}
        </button>
    );
}

/**
 * Rotary knob with detents. Drag it round (or click a label / use arrow keys);
 * it snaps to the nearest option when released.
 */
export function Knob({ options, value, onChange, format = (v) => v }) {
    const sweep = 240;
    const angleOf = (i) => -sweep / 2 + (sweep / (options.length - 1)) * i;
    const index = Math.max(0, options.indexOf(value));
    const [drag, setDrag] = useState(null);
    const ref = useRef();

    const angleFromPointer = (e) => {
        const r = ref.current.getBoundingClientRect();
        const deg = (Math.atan2(e.clientX - (r.left + r.width / 2), -(e.clientY - (r.top + r.height / 2))) * 180) / Math.PI;
        return Math.max(-sweep / 2, Math.min(sweep / 2, deg));
    };

    const nearest = (deg) =>
        options.reduce((best, _, i) => (Math.abs(angleOf(i) - deg) < Math.abs(angleOf(best) - deg) ? i : best), 0);

    const onDown = (e) => {
        e.currentTarget.setPointerCapture(e.pointerId);
        setDrag(angleFromPointer(e));
    };
    const onMove = (e) => drag !== null && setDrag(angleFromPointer(e));
    const onUp = () => {
        if (drag === null) return;
        onChange(options[nearest(drag)]);
        setDrag(null);
    };

    const onKey = (e) => {
        const step = { ArrowRight: 1, ArrowUp: 1, ArrowLeft: -1, ArrowDown: -1 }[e.key];
        if (!step) return;
        e.preventDefault();
        onChange(options[Math.max(0, Math.min(options.length - 1, index + step))]);
    };

    const ticks = Array.from({ length: 25 }, (_, i) => -sweep / 2 + (sweep / 24) * i);

    return (
        <div className="knob-wrap">
            {ticks.map((deg) => (
                <span
                    key={deg}
                    className={`knob-tick ${options.some((_, i) => Math.abs(angleOf(i) - deg) < 0.01) ? 'knob-tick--major' : ''}`}
                    style={{ transform: `rotate(${deg + 180}deg)` }}
                />
            ))}
            {options.map((opt, i) => {
                const rad = ((angleOf(i) - 90) * Math.PI) / 180;
                return (
                    <button
                        key={opt}
                        type="button"
                        className={`knob-label ${opt === value ? 'knob-label--on' : ''}`}
                        style={{ left: `${50 + Math.cos(rad) * 58}%`, top: `${50 + Math.sin(rad) * 58}%` }}
                        onClick={() => onChange(opt)}
                    >
                        {format(opt)}
                    </button>
                );
            })}
            <div
                ref={ref}
                className={`knob ${drag !== null ? 'knob--dragging' : ''}`}
                role="slider"
                tabIndex={0}
                aria-valuenow={value}
                aria-valuetext={format(value)}
                aria-label="Range"
                onPointerDown={onDown}
                onPointerMove={onMove}
                onPointerUp={onUp}
                onPointerCancel={onUp}
                onKeyDown={onKey}
            >
                <div className="knob-cap" style={{ transform: `rotate(${drag ?? angleOf(index)}deg)` }}>
                    <span className="knob-mark" />
                </div>
            </div>
        </div>
    );
}

export function Toggle({ on, onChange, title, sub, busy }) {
    return (
        <button type="button" className={`toggle ${on ? 'toggle--on' : ''}`} aria-pressed={on} onClick={() => !busy && onChange(!on)}>
            <span className="toggle-plate">
                <span className="toggle-word toggle-word--on">ON</span>
                <span className="toggle-lever" />
                <span className="toggle-word toggle-word--off">OFF</span>
            </span>
            <span>
                <span className="toggle-title">
                    <Led on={on} blink={busy} />
                    {title}
                </span>
                {sub && <span className="toggle-sub" style={{ display: 'block' }}>{sub}</span>}
            </span>
        </button>
    );
}

export function MiniToggle({ on, onChange }) {
    return (
        <button type="button" className={`mini-toggle ${on ? 'mini-toggle--on' : ''}`} aria-pressed={on} onClick={() => onChange(!on)}>
            <span className="mini-toggle-track" />
            {on ? 'TRUE' : 'FALSE'}
        </button>
    );
}
