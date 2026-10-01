function Row({ items, reverse = false, serif = false }) {
    const list = [...items, ...items];
    return (
        <div className="mask-fade-x flex overflow-hidden">
            <div
                className="marquee-track flex shrink-0 items-center gap-8 pr-8 hover:[animation-play-state:paused] sm:gap-10 sm:pr-10"
                style={{ animation: `marquee ${items.length * 3.2}s linear infinite ${reverse ? 'reverse' : ''}` }}
            >
                {list.map((item, i) => (
                    <span key={i} className="flex items-center gap-8 whitespace-nowrap sm:gap-10">
                        <span className={serif ? 'serif-i text-[clamp(1.5rem,2.8vw,2.5rem)] text-dim' : 'text-[clamp(1.5rem,2.8vw,2.5rem)] font-medium tracking-tight text-fg/85'}>
                            {item}
                        </span>
                        <span className="text-acid/70 text-base">✦</span>
                    </span>
                ))}
            </div>
        </div>
    );
}

export default function Marquee({ items }) {
    const half = Math.ceil(items.length / 2);
    return (
        <section aria-label="Technology stack" className="relative space-y-2 border-y hairline py-6 sm:space-y-3 sm:py-8">
            <Row items={items.slice(0, half)} />
            <Row items={items.slice(half)} reverse serif />
        </section>
    );
}
