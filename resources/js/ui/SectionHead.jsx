import { SplitWords, FadeUp } from './Reveal';

/** Numbered section header: "02 — Capabilities" + large title + optional aside. */
export default function SectionHead({ index, label, title, accent, aside }) {
    return (
        <div className="grid gap-6 border-t hairline pt-6 sm:gap-8 md:grid-cols-12 md:gap-6">
            <FadeUp className="eyebrow flex items-center gap-3 self-start md:col-span-3 md:pt-3">
                <span className="text-acid">{index}</span>
                <span className="h-px w-6 bg-line-2" />
                {label}
            </FadeUp>
            <div className="md:col-span-9">
                <h2 className="display text-[clamp(2rem,min(5vw,9vh),4.25rem)]">
                    <SplitWords text={title} />
                    {accent && (
                        <>
                            {' '}
                            <SplitWords text={accent} className="serif-i text-mute" delay={0.15} />
                        </>
                    )}
                </h2>
                {aside && <FadeUp delay={0.2} className="mt-5 max-w-xl text-mute text-[15px] leading-relaxed">{aside}</FadeUp>}
            </div>
        </div>
    );
}
