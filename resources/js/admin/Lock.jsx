import { useEffect, useState } from 'react';
import { api, setCsrf } from './api';
import { Led, Screws } from './ui/Hardware';

const BOOT = ['SM-01 ROM v26.10', 'Memory check ......... OK', 'Mounting /inbox ....... OK', 'Mounting /content ..... OK', 'Link to site .......... OK', 'Welcome back.'];

function Boot({ onDone }) {
    const [lines, setLines] = useState(0);

    useEffect(() => {
        if (lines >= BOOT.length) {
            const t = setTimeout(onDone, 380);
            return () => clearTimeout(t);
        }
        const t = setTimeout(() => setLines((n) => n + 1), lines === 0 ? 120 : 170);
        return () => clearTimeout(t);
    }, [lines]);

    return (
        <div>
            {BOOT.slice(0, lines).map((l, i) => (
                <div key={i} className={`boot-line ${i === lines - 1 ? 'cursor' : ''}`}>
                    {l}
                </div>
            ))}
        </div>
    );
}

export default function Lock({ onUnlock }) {
    const [form, setForm] = useState({ email: '', password: '' });
    const [state, setState] = useState('locked'); // locked | checking | denied | booting
    const [error, setError] = useState('');
    const [user, setUser] = useState(null);
    const [showKey, setShowKey] = useState(false);

    const submit = async (e) => {
        e.preventDefault();
        setState('checking');
        try {
            const res = await api('login', { method: 'POST', body: form });
            setCsrf(res.csrf);
            setUser(res.user);
            setState('booting');
        } catch (err) {
            setError({ 429: 'Too many attempts · wait 60s', 419: 'Session expired · reload page' }[err.status] ?? 'Access denied');
            setState('denied');
        }
    };

    return (
        <div className="lock">
            <div className={`unit ${state === 'denied' ? 'shake' : ''}`} onAnimationEnd={(e) => e.animationName === 'shake' && setState('locked')}>
                <Screws />
                <div className="brand">
                    <span className="brand-model">
                        SM<i>-</i>01
                    </span>
                    <span className="brand-sub">
                        Control unit
                        <br />
                        Operator access
                    </span>
                    <span style={{ marginLeft: 'auto', display: 'flex', gap: 8 }}>
                        <Led on color={state === 'booting' ? 'green' : error ? 'orange' : 'yellow'} blink={state === 'checking'} />
                    </span>
                </div>

                <div className="lcd lock-screen">
                    {state === 'booting' ? (
                        <Boot onDone={() => onUnlock(user)} />
                    ) : (
                        <>
                            <div className="lcd-line lcd-dim">
                                <span>Status</span>
                                <span>{state === 'checking' ? 'Verifying' : 'Secure'}</span>
                            </div>
                            <div className="lcd-big" style={{ fontSize: 54 }}>
                                {state === 'checking' ? '••••' : 'LOCKED'}
                            </div>
                            <div className={`lcd-line ${error ? "" : "lcd-dim"}`}><span className="cursor">{error || "Insert key to continue"}</span></div>
                        </>
                    )}
                </div>

                <form className="lock-form" onSubmit={submit}>
                    <label className="field">
                        <span className="field-label">Operator</span>
                        <input
                            className="well well--lcd"
                            type="email"
                            autoComplete="username"
                            placeholder="you@domain.com"
                            value={form.email}
                            onChange={(e) => setForm({ ...form, email: e.target.value })}
                            required
                            autoFocus
                        />
                    </label>
                    <label className="field">
                        <span className="field-label">Key</span>
                        <span className="well-reveal">
                            <input
                                className="well well--lcd"
                                type={showKey ? 'text' : 'password'}
                                autoComplete="current-password"
                                placeholder="••••••••••"
                                value={form.password}
                                onChange={(e) => setForm({ ...form, password: e.target.value })}
                                required
                            />
                            <button
                                type="button"
                                className="reveal-btn"
                                onClick={() => setShowKey((v) => !v)}
                                onMouseDown={(e) => e.preventDefault()} // keep focus and caret in the field
                                aria-label={showKey ? 'Hide password' : 'Show password'}
                                aria-pressed={showKey}
                                title={showKey ? 'Hide password' : 'Show password'}
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z" />
                                    <circle cx="12" cy="12" r="3" />
                                    {showKey && <path d="M4 4l16 16" />}
                                </svg>
                            </button>
                        </span>
                    </label>
                    <button className="btn btn--orange btn--block" style={{ height: 50, marginTop: 8 }} disabled={state === 'checking' || state === 'booting'}>
                        Unlock ⏎
                    </button>
                </form>
            </div>
        </div>
    );
}
