import { useEffect, useMemo, useRef, useState } from 'react';
import { AnimatePresence, motion } from 'motion/react';
import { lockScroll, scrollTo } from '../lib/scroll';
import { copyText } from '../lib/hooks';
import { SECTIONS } from './Nav';
import { toggleTheme, useTheme } from '../lib/theme';

export default function CommandPalette({ open, onClose, profile }) {
    const [q, setQ] = useState('');
    const [i, setI] = useState(0);
    const [toast, setToast] = useState(null);
    const input = useRef(null);
    const theme = useTheme();

    const actions = useMemo(
        () => [
            { group: 'Recruiter', label: 'Download résumé (PDF)', hint: '↓', run: () => (window.location.href = '/resume') },
            {
                group: 'Recruiter',
                label: `Copy email — ${profile.email}`,
                hint: '⧉',
                run: async () => {
                    (await copyText(profile.email)) && flash('Email copied');
                    return 'keep';
                },
            },
            { group: 'Recruiter', label: 'Send an email', hint: '↗', run: () => (window.location.href = `mailto:${profile.email}`) },
            { group: 'Recruiter', label: `Call ${profile.phone}`, hint: '☎', run: () => (window.location.href = `tel:${profile.phone.replace(/\s/g, '')}`) },
            ...SECTIONS.map((s) => ({ group: 'Navigate', label: `Go to ${s.label}`, hint: '→', run: () => scrollTo(`#${s.id}`) })),
            {
                group: 'Display',
                label: theme === 'light' ? 'Switch to dark mode' : 'Switch to light mode',
                hint: theme === 'light' ? '☾' : '☀',
                run: () => {
                    toggleTheme();
                    return 'keep';
                },
            },
            ...profile.links.map((l) => ({ group: 'Elsewhere', label: `${l.label} — ${l.handle}`, hint: '↗', run: () => window.open(l.url, '_blank', 'noopener') })),
        ],
        [profile, theme],
    );

    const filtered = actions.filter((a) => a.label.toLowerCase().includes(q.toLowerCase()));

    function flash(msg) {
        setToast(msg);
        setTimeout(() => setToast(null), 1600);
    }

    async function run(a) {
        const keep = await a.run();
        if (keep !== 'keep') onClose();
    }

    useEffect(() => {
        lockScroll(open);
        if (open) {
            setQ('');
            setI(0);
            setTimeout(() => input.current?.focus(), 40);
        }
    }, [open]);

    useEffect(() => setI(0), [q]);

    const onKey = (e) => {
        if (e.key === 'Escape') onClose();
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            setI((v) => Math.min(v + 1, filtered.length - 1));
        }
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            setI((v) => Math.max(v - 1, 0));
        }
        if (e.key === 'Enter' && filtered[i]) run(filtered[i]);
    };

    let lastGroup = null;

    return (
        <AnimatePresence>
            {open && (
                <motion.div
                    className="fixed inset-0 z-[80] flex items-start justify-center bg-ink/80 px-3 pt-[10vh] sm:px-4 sm:pt-[14vh]"
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    exit={{ opacity: 0 }}
                    onClick={onClose}
                >
                    <motion.div
                        role="dialog"
                        aria-label="Command menu"
                        className="w-full max-w-[560px] overflow-hidden rounded-2xl border border-line-2 bg-panel shadow-[0_40px_120px_-20px_var(--shadow),0_0_0_1px_var(--color-acid-dim)]"
                        initial={{ opacity: 0, y: -12, scale: 0.97 }}
                        animate={{ opacity: 1, y: 0, scale: 1 }}
                        exit={{ opacity: 0, y: -8, scale: 0.98 }}
                        transition={{ duration: 0.35, ease: [0.16, 1, 0.3, 1] }}
                        onClick={(e) => e.stopPropagation()}
                        onKeyDown={onKey}
                    >
                        <div className="flex items-center gap-3 border-b hairline px-5">
                            <span className="text-acid">›</span>
                            <input
                                ref={input}
                                value={q}
                                onChange={(e) => setQ(e.target.value)}
                                placeholder="Type a command or search…"
                                className="h-14 flex-1 bg-transparent text-[15px] outline-none placeholder:text-dim"
                            />
                            <kbd className="rounded-md border hairline px-1.5 py-0.5 font-mono text-[10px] text-mute">ESC</kbd>
                        </div>

                        <ul className="max-h-[50vh] overflow-y-auto p-2" data-lenis-prevent>
                            {filtered.length === 0 && <li className="px-3 py-8 text-center text-[13px] text-mute">No matches.</li>}
                            {filtered.map((a, idx) => {
                                const header = a.group !== lastGroup;
                                lastGroup = a.group;
                                return (
                                    <li key={a.label}>
                                        {header && <p className="eyebrow px-3 pb-1.5 pt-3 !text-[10px]">{a.group}</p>}
                                        <button
                                            onMouseEnter={() => setI(idx)}
                                            onClick={() => run(a)}
                                            className={`relative flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-[14px] transition-colors ${
                                                idx === i ? 'text-fg' : 'text-mute'
                                            }`}
                                        >
                                            {idx === i && <motion.span layoutId="cmd-hl" className="absolute inset-0 rounded-lg bg-tint/[0.05]" transition={{ type: 'spring', stiffness: 500, damping: 40 }} />}
                                            <span className="relative">{a.label}</span>
                                            <span className={`relative font-mono text-[12px] ${idx === i ? 'text-acid' : 'text-dim'}`}>{a.hint}</span>
                                        </button>
                                    </li>
                                );
                            })}
                        </ul>

                        <div className="flex items-center justify-between border-t hairline px-5 py-3 font-mono text-[10px] text-dim">
                            <span>↑↓ navigate · ↵ select</span>
                            <AnimatePresence mode="wait">
                                {toast ? (
                                    <motion.span key="t" className="text-acid" initial={{ opacity: 0, y: 4 }} animate={{ opacity: 1, y: 0 }} exit={{ opacity: 0 }}>
                                        ✓ {toast}
                                    </motion.span>
                                ) : (
                                    <motion.span key="n" initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }}>
                                        {profile.name.toLowerCase().replace(' ', '.')}
                                    </motion.span>
                                )}
                            </AnimatePresence>
                        </div>
                    </motion.div>
                </motion.div>
            )}
        </AnimatePresence>
    );
}
