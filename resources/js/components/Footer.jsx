import { motion } from 'motion/react';
import { scrollTo } from '../lib/scroll';
import { useLocalTime } from '../lib/hooks';
import Clock from '../ui/Clock';
import Magnetic from '../ui/Magnetic';
import { FadeUp, ease } from '../ui/Reveal';
import { SECTIONS } from './Nav';

/** "Online" during Kolkata working hours, so recruiters know when to expect a reply. */
function Presence({ timeZone }) {
    const hour = Number(useLocalTime(timeZone).slice(0, 2));
    const online = hour >= 10 && hour < 21;
    return (
        <span className="flex items-center gap-2 text-[13px] text-fg/85">
            <span className="relative flex h-1.5 w-1.5">
                {online && <span className="absolute inset-0 rounded-full bg-acid" style={{ animation: 'pulse-ring 2s ease-out infinite' }} />}
                <span className={`relative h-1.5 w-1.5 rounded-full ${online ? 'bg-acid' : 'bg-dim'}`} />
            </span>
            {online ? 'I usually reply within a day' : 'Offline, replies within a day'}
        </span>
    );
}

function Column({ title, children, delay }) {
    return (
        <FadeUp delay={delay} className="min-w-0">
            <p className="eyebrow mb-4">{title}</p>
            <ul className="space-y-2.5">{children}</ul>
        </FadeUp>
    );
}

const link = 'link-u text-[14px] text-fg/80 transition-colors hover:text-fg';

/** Giant outlined name: letters rise in, and a lime spotlight fills them under the cursor. */
function Wordmark({ name }) {
    const letters = name.toUpperCase().split('');
    return (
        <div
            aria-hidden
            onPointerMove={(e) => {
                // Viewport coords: every letter shares one fixed-attachment spotlight.
                e.currentTarget.style.setProperty('--mx', `${e.clientX}px`);
                e.currentTarget.style.setProperty('--my', `${e.clientY}px`);
            }}
            onPointerLeave={(e) => {
                e.currentTarget.style.setProperty('--mx', '-999px');
                e.currentTarget.style.setProperty('--my', '-999px');
            }}
            className="wordmark relative mt-14 select-none overflow-hidden sm:mt-16"
        >
            <motion.p
                className="display flex justify-center whitespace-nowrap text-[clamp(3rem,12.2vw,15rem)] leading-[0.82]"
                initial="hidden"
                whileInView="show"
                viewport={{ once: true, margin: '0px 0px -5% 0px' }}
                transition={{ staggerChildren: 0.045 }}
            >
                {letters.map((ch, i) => (
                    <motion.span
                        key={i}
                        className="inline-block"
                        variants={{ hidden: { y: '100%' }, show: { y: '8%', transition: { duration: 1, ease } } }}
                    >
                        {ch === ' ' ? ' ' : ch}
                    </motion.span>
                ))}
            </motion.p>
        </div>
    );
}

export default function Footer({ profile }) {
    const connect = [...profile.links, { label: 'Email', handle: profile.email, url: `mailto:${profile.email}` }].filter(
        (l, i, arr) => arr.findIndex((x) => x.label === l.label) === i,
    );

    return (
        <footer className="relative overflow-hidden border-t hairline">
            <div className="glow pointer-events-none absolute -bottom-72 left-1/2 h-[600px] w-[1100px] max-w-[200vw] -translate-x-1/2" />

            <div className="container-x relative pt-12 sm:pt-16">
                <div className="grid gap-12 md:grid-cols-12 md:gap-8">
                    {/* Brand */}
                    <FadeUp className="md:col-span-5">
                        <div className="flex items-center gap-3">
                            <span className="grid h-10 w-10 place-items-center rounded-xl border hairline bg-ink-2">
                                <img src="/images/brand/logo-mark.png" alt="" width="30" height="30" className="h-[30px] w-[30px]" />
                            </span>
                            <div className="leading-tight">
                                <p className="text-[15px] font-medium text-fg">{profile.name}</p>
                                <p className="text-[13px] text-mute">{profile.role}</p>
                            </div>
                        </div>
                        <p className="mt-6 max-w-sm text-[15px] leading-relaxed text-mute">
                            Engineering complete systems, from architecture and servers to the <span className="serif-i text-fg">last pixel</span>.
                        </p>
                        {profile.available && (
                            <p className="mt-5 inline-flex items-center gap-2 rounded-full border hairline px-3 py-1.5 font-mono text-[11px] text-fg/85">
                                <span className="h-1.5 w-1.5 rounded-full bg-acid" /> {profile.availability}
                            </p>
                        )}
                    </FadeUp>

                    {/* Link columns */}
                    <div className="grid grid-cols-2 gap-10 sm:grid-cols-3 md:col-span-7">
                        <Column title="Navigate" delay={0.05}>
                            {SECTIONS.map((s) => (
                                <li key={s.id}>
                                    <button onClick={() => scrollTo(`#${s.id}`)} className={link}>
                                        {s.label}
                                    </button>
                                </li>
                            ))}
                        </Column>

                        <Column title="Connect" delay={0.1}>
                            {connect.map((l) => (
                                <li key={l.label}>
                                    <a
                                        href={l.url}
                                        target={l.url.startsWith('http') ? '_blank' : undefined}
                                        rel="noopener"
                                        className={`${link} inline-flex items-center gap-1.5`}
                                    >
                                        {l.label} <span className="text-[11px] text-dim">↗</span>
                                    </a>
                                </li>
                            ))}
                        </Column>

                        <Column title="Now" delay={0.15}>
                            <li>
                                <p className="font-mono text-[22px] leading-none tracking-tight text-fg">
                                    <Clock timeZone={profile.timezone} />
                                </p>
                                <p className="mt-1.5 font-mono text-[11px] text-dim">Kolkata · IST (UTC+5:30)</p>
                            </li>
                            <li className="pt-1">
                                <Presence timeZone={profile.timezone} />
                            </li>
                            <li className="pt-2">
                                <a
                                    href="/resume"
                                    data-cursor="PDF"
                                    className="inline-flex items-center gap-2 rounded-full bg-acid px-4 py-2 text-[13px] font-medium text-ink transition-shadow hover:shadow-[0_0_30px_-4px_rgba(212,255,79,0.6)]"
                                >
                                    Résumé <span>↓</span>
                                </a>
                            </li>
                        </Column>
                    </div>
                </div>

                {/* Bottom bar */}
                <div className="mt-14 flex flex-col-reverse items-start justify-between gap-5 border-t hairline pt-6 sm:mt-16 sm:flex-row sm:items-center">
                    <p className="font-mono text-[11.5px] text-dim">
                        © {new Date().getFullYear()} {profile.name} <span className="mx-2">·</span> Built with Laravel + React
                    </p>
                    <Magnetic strength={0.3}>
                        <button
                            onClick={() => scrollTo(0)}
                            data-cursor="Top"
                            className="group flex items-center gap-3 font-mono text-[11.5px] text-mute transition-colors hover:text-fg"
                        >
                            Back to top
                            <span className="grid h-9 w-9 place-items-center rounded-full border border-line-2 text-fg transition-all duration-500 group-hover:border-acid group-hover:bg-acid group-hover:text-ink">
                                ↑
                            </span>
                        </button>
                    </Magnetic>
                </div>
            </div>

            <Wordmark name={profile.name} />
        </footer>
    );
}
