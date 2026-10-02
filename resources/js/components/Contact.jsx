import { useRef, useState } from 'react';
import { AnimatePresence, motion, useInView } from 'motion/react';
import Magnetic from '../ui/Magnetic';
import { SplitWords, FadeUp, ease } from '../ui/Reveal';
import { copyText } from '../lib/hooks';

/**
 * `invite`: once the field scrolls into view, a lime caret blinks at its start
 * and the underline sweeps twice, inviting the visitor to begin. Both stop the
 * moment the field is focused or has text. (No auto-focus: that would jump the
 * page and pop the keyboard on phones.)
 */
function Field({ label, name, error, textarea, invite = false, ...rest }) {
    const Tag = textarea ? 'textarea' : 'input';
    const ref = useRef(null);
    const inView = useInView(ref, { once: true, margin: '-15% 0px' });
    const [engaged, setEngaged] = useState(false);
    const inviting = invite && inView && !engaged;

    return (
        <label ref={ref} className="group block">
            <span className="eyebrow flex justify-between">
                <span className={`transition-colors duration-500 ${inviting ? 'text-acid' : ''}`}>{label}</span>
                {error && <span className="normal-case tracking-normal text-red-400">{error}</span>}
            </span>
            <span className="relative mt-2 block">
                <Tag
                    name={name}
                    {...rest}
                    onFocus={() => setEngaged(true)}
                    onInput={(e) => e.currentTarget.value && setEngaged(true)}
                    className={`w-full resize-none border-b bg-transparent pb-3 text-[16px] outline-none transition-colors placeholder:text-fg/15 focus:border-acid focus:placeholder:text-fg/10 ${
                        error ? 'border-red-400/60' : 'border-line-2'
                    }`}
                />
                {inviting && (
                    <>
                        {/* Blinking caret at the start of the field */}
                        <span
                            aria-hidden
                            className="pointer-events-none absolute left-0 top-[3px] h-[1.15em] w-[2px] rounded-full bg-acid text-[16px]"
                            style={{ animation: 'blink 1s steps(1) infinite' }}
                        />
                        {/* Underline sweep, twice */}
                        <motion.span
                            aria-hidden
                            className="pointer-events-none absolute inset-x-0 bottom-0 h-px origin-left bg-acid"
                            initial={{ scaleX: 0, opacity: 1 }}
                            animate={{ scaleX: [0, 1, 1], opacity: [1, 1, 0] }}
                            transition={{ duration: 1.3, times: [0, 0.6, 1], ease: 'easeInOut', repeat: 1, repeatDelay: 0.4 }}
                        />
                    </>
                )}
            </span>
        </label>
    );
}

export default function Contact({ profile }) {
    const [copied, setCopied] = useState(false);
    const [status, setStatus] = useState('idle'); // idle | sending | sent | error
    const [errors, setErrors] = useState({});

    async function copy() {
        if (await copyText(profile.email)) {
            setCopied(true);
            setTimeout(() => setCopied(false), 1800);
        }
    }

    async function submit(e) {
        e.preventDefault();
        setStatus('sending');
        setErrors({});
        const body = Object.fromEntries(new FormData(e.currentTarget));

        try {
            const res = await fetch('/contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(body),
            });
            if (res.status === 422) {
                const json = await res.json();
                setErrors(Object.fromEntries(Object.entries(json.errors).map(([k, v]) => [k, v[0]])));
                setStatus('idle');
                return;
            }
            if (!res.ok) throw new Error(res.status === 429 ? 'Too many messages. Please try again in a few minutes.' : 'Something went wrong.');
            setStatus('sent');
        } catch (err) {
            setErrors({ form: err.message });
            setStatus('error');
        }
    }

    return (
        <section id="contact" className="relative overflow-hidden py-[clamp(4rem,8vw,7rem)]">
            <div className="glow absolute left-1/2 top-0 h-[700px] w-[1200px] max-w-[200vw] -translate-x-1/2" />

            <div className="container-x relative">
                <FadeUp className="eyebrow flex items-center gap-3">
                    <span className="text-acid">06</span>
                    <span className="h-px w-6 bg-line-2" />
                    Contact
                </FadeUp>

                <h2 className="display mt-6 text-[clamp(2.6rem,min(8vw,14vh),7rem)] sm:mt-8">
                    <SplitWords text="Let's build" className="block" />
                    <SplitWords text="what's next." className="serif-i block text-acid" delay={0.15} />
                </h2>

                <div className="mt-10 grid gap-10 sm:mt-12 lg:grid-cols-12 lg:gap-14">
                    <div className="min-w-0 lg:col-span-5">
                        <FadeUp>
                            <p className="max-w-sm text-[16px] leading-relaxed text-mute">
                                Hiring for a full-stack or backend engineering role? I usually reply within a day.
                            </p>

                            <Magnetic strength={0.2} className="mt-8 w-full max-w-md sm:mt-10">
                                <button
                                    onClick={copy}
                                    data-cursor={copied ? 'Copied' : 'Copy'}
                                    className="group flex w-full items-center justify-between gap-4 rounded-2xl border border-line-2 bg-ink-2 py-4 pl-5 pr-4 text-left transition-colors hover:border-acid/50"
                                >
                                    <span className="min-w-0">
                                        <span className="eyebrow block !text-[10px]">Email</span>
                                        <span className="mt-1 block break-all text-[clamp(0.9rem,1.4vw,1.1rem)]">{profile.email}</span>
                                    </span>
                                    <span className="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white/[0.04] font-mono text-[12px] text-acid">
                                        <AnimatePresence mode="wait">
                                            <motion.span key={copied ? 'y' : 'n'} initial={{ opacity: 0, scale: 0.6 }} animate={{ opacity: 1, scale: 1 }} exit={{ opacity: 0, scale: 0.6 }}>
                                                {copied ? '✓' : '⧉'}
                                            </motion.span>
                                        </AnimatePresence>
                                    </span>
                                </button>
                            </Magnetic>

                            <ul className="mt-10 max-w-md space-y-3 lg:max-w-none">
                                {[{ label: 'Phone', handle: profile.phone, url: `tel:${profile.phone.replace(/\s/g, '')}` }, ...profile.links].map((l) => (
                                    <li key={l.label} className="flex items-baseline justify-between gap-4 border-b hairline pb-3">
                                        <span className="eyebrow">{l.label}</span>
                                        <a href={l.url} target={l.url.startsWith('http') ? '_blank' : undefined} rel="noopener" className="link-u text-[15px] text-fg">
                                            {l.handle} <span className="text-mute">↗</span>
                                        </a>
                                    </li>
                                ))}
                            </ul>
                        </FadeUp>
                    </div>

                    <FadeUp delay={0.15} className="min-w-0 lg:col-span-7">
                        <div className="relative rounded-3xl border hairline bg-ink-2 p-5 sm:p-6 md:p-9">
                            <AnimatePresence mode="wait">
                                {status === 'sent' ? (
                                    <motion.div key="sent" initial={{ opacity: 0, y: 12 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.8, ease }} className="flex min-h-[340px] flex-col items-start justify-center">
                                        <span className="grid h-12 w-12 place-items-center rounded-full bg-acid text-ink">✓</span>
                                        <p className="display mt-8 text-[clamp(2rem,4vw,3rem)]">Message received.</p>
                                        <p className="mt-4 text-mute">Thanks for reaching out. I'll get back to you shortly.</p>
                                    </motion.div>
                                ) : (
                                    <motion.form key="form" onSubmit={submit} exit={{ opacity: 0, y: -12 }} className="space-y-7" noValidate>
                                        <div className="grid gap-7 sm:grid-cols-2">
                                            <Field label="Name" name="name" invite required autoComplete="name" placeholder="Jane Doe" error={errors.name} />
                                            <Field label="Email" name="email" type="email" required autoComplete="email" placeholder="jane@company.com" error={errors.email} />
                                        </div>
                                        <Field label="Company / role (optional)" name="company" autoComplete="organization" placeholder="Acme — Senior Full-Stack Engineer" error={errors.company} />
                                        <Field label="Message" name="message" textarea rows={4} required placeholder="Tell me about the role or the problem…" error={errors.message} />
                                        <input type="text" name="website" tabIndex={-1} autoComplete="off" className="hidden" aria-hidden />

                                        <div className="flex flex-wrap items-center justify-between gap-4">
                                            <p className="text-[13px] text-red-400">{errors.form}</p>
                                            <button
                                                type="submit"
                                                disabled={status === 'sending'}
                                                data-cursor="Send"
                                                className="group flex items-center gap-3 rounded-full bg-fg py-3 pl-6 pr-3 text-[14px] font-medium text-ink transition-colors hover:bg-acid disabled:opacity-60"
                                            >
                                                {status === 'sending' ? 'Sending…' : 'Send message'}
                                                <span className="grid h-7 w-7 place-items-center rounded-full bg-ink text-fg transition-transform duration-500 group-hover:translate-x-0.5 group-hover:rotate-[-45deg]">→</span>
                                            </button>
                                        </div>
                                    </motion.form>
                                )}
                            </AnimatePresence>
                        </div>
                    </FadeUp>
                </div>
            </div>
        </section>
    );
}
