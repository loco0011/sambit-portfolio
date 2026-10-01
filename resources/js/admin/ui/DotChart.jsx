const ROWS = 14;
const CELL = 10;

// Each day is drawn several dot-columns wide on short ranges, so 7 and 90 days both fill the screen.
const widthFor = (days) => (days <= 7 ? 6 : days <= 30 ? 2 : 1);
const gapFor = (days) => (days <= 7 ? 2 : days <= 30 ? 1 : 0);

export default function DotChart({ series, hover, onHover }) {
    const per = widthFor(series.length);
    const gap = gapFor(series.length);
    const max = Math.max(1, ...series.map((d) => d.views));
    const cols = series.length * (per + gap) - gap;

    return (
        <svg viewBox={`0 0 ${cols * CELL} ${ROWS * CELL}`} onMouseLeave={() => onHover(null)} role="img" aria-label="Daily page views">
            {series.map((d, i) => {
                const level = d.views ? Math.max(1, Math.round((d.views / max) * ROWS)) : 0;
                const x0 = i * (per + gap) * CELL;

                return (
                    <g key={d.day} className={`chart-col ${hover === i ? 'chart-col--hover' : ''}`} onMouseEnter={() => onHover(i)}>
                        <rect x={x0} y={0} width={(per + gap) * CELL} height={ROWS * CELL} fill="transparent" />
                        {Array.from({ length: per }, (_, c) =>
                            Array.from({ length: ROWS }, (_, r) => {
                                const on = r < level;
                                return (
                                    <circle
                                        key={`${c}-${r}`}
                                        cx={x0 + c * CELL + CELL / 2}
                                        cy={(ROWS - 1 - r) * CELL + CELL / 2}
                                        r={CELL * 0.34}
                                        className={`dot ${on ? 'dot--on' : ''} ${on && r === level - 1 ? 'dot--peak' : ''}`}
                                        style={on ? { '--d': `${Math.min(900, i * (600 / series.length) + r * 22)}ms` } : undefined}
                                    />
                                );
                            }),
                        )}
                    </g>
                );
            })}
        </svg>
    );
}
