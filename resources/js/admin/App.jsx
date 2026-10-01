import { useCallback, useEffect, useState } from 'react';
import { api, onSessionLost, setCsrf, subscribeActivity } from './api';
import { Ticker } from './lib';
import Lock from './Lock';
import Content from './modules/Content';
import Dashboard from './modules/Dashboard';
import Files from './modules/Files';
import Inbox from './modules/Inbox';
import System from './modules/System';
import { Led, Screws } from './ui/Hardware';

const MODULES = [
    { id: 'dash', label: 'Dashboard', glyph: '◴' },
    { id: 'inbox', label: 'Inbox', glyph: '✉' },
    { id: 'content', label: 'Content', glyph: '¶' },
    { id: 'files', label: 'Résumé', glyph: '⏏' },
    { id: 'system', label: 'System', glyph: '⚙' },
];

// #/inbox/12 → { id: 'inbox', focus: 12 }
function readHash() {
    const [, id, focus] = window.location.hash.split('/');
    return { id: MODULES.some((m) => m.id === id) ? id : 'dash', focus: focus ? Number(focus) : null };
}

function Clock() {
    const [now, setNow] = useState(new Date());
    useEffect(() => {
        const t = setInterval(() => setNow(new Date()), 1000);
        return () => clearInterval(t);
    }, []);
    const [h, m] = [now.getHours(), now.getMinutes()].map((n) => String(n).padStart(2, '0'));
    return (
        <span className="clock">
            {h}
            <span>:</span>
            {m}
        </span>
    );
}

function Console({ user, site, onPowerOff }) {
    const [route, setRoute] = useState(readHash);
    const [unread, setUnread] = useState(0);
    const [net, setNet] = useState(false);
    const [ticker, setTicker] = useState({ text: `Ready · operator ${user.email}`, tone: 'ok' });

    const say = useCallback((text, tone = 'ok') => setTicker({ text, tone, at: Date.now() }), []);

    useEffect(() => subscribeActivity(setNet), []);

    useEffect(() => {
        const onHash = () => setRoute(readHash());
        window.addEventListener('hashchange', onHash);
        return () => window.removeEventListener('hashchange', onHash);
    }, []);

    const go = (id, focus) => {
        window.location.hash = focus ? `/${id}/${focus}` : `/${id}`;
    };

    // Number keys 1–5 press the matching key, unless you're typing.
    useEffect(() => {
        const onKey = (e) => {
            if (e.metaKey || e.ctrlKey || e.altKey || /input|textarea|select/i.test(e.target.tagName)) return;
            const m = MODULES[Number(e.key) - 1];
            if (m) go(m.id);
        };
        window.addEventListener('keydown', onKey);
        return () => window.removeEventListener('keydown', onKey);
    }, []);

    // Keep the unread LED honest even while you sit on another module.
    useEffect(() => {
        const poll = () => api('dashboard?days=7').then((d) => setUnread(d.messages.unread)).catch(() => {});
        poll();
        const t = setInterval(poll, 60000);
        return () => clearInterval(t);
    }, []);

    const screens = {
        dash: <Dashboard go={go} onUnread={setUnread} />,
        inbox: <Inbox key={route.focus ?? 'inbox'} focusId={route.focus} onUnread={setUnread} />,
        content: <Content />,
        files: <Files />,
        system: <System user={user} site={site} onPowerOff={onPowerOff} />,
    };

    return (
        <Ticker.Provider value={say}>
            <div className="unit">
                <Screws />
                <header className="head">
                    <div className="brand">
                        <span className="brand-model">
                            SM<i>-</i>01
                        </span>
                        <span className="brand-sub">
                            Control unit
                            <br />
                            sambitmaity.com
                        </span>
                    </div>

                    <div className="lcd head-lcd">
                        <span key={ticker.at} className={`ticker lcd-flicker ${ticker.tone === 'warn' ? 'ticker--warn' : ''}`} role="status">
                            {ticker.text}
                        </span>
                        <Clock />
                    </div>

                    <div className="leds">
                        <span className="led-cell">
                            <Led on />
                            PWR
                        </span>
                        <span className="led-cell">
                            <Led on={net} color="yellow" />
                            NET
                        </span>
                        <span className="led-cell">
                            <Led on={unread > 0} color="orange" blink />
                            MSG
                        </span>
                    </div>

                    <button className="power" onClick={onPowerOff} title="Power off (sign out)" aria-label="Sign out">
                        ⏻
                    </button>
                </header>

                <nav className="keys" aria-label="Modules">
                    {MODULES.map((m, i) => (
                        <button
                            key={m.id}
                            className={`key ${route.id === m.id ? 'key--active' : ''}`}
                            onClick={() => go(m.id)}
                            aria-current={route.id === m.id ? 'page' : undefined}
                        >
                            <span className="key-num">{String(i + 1).padStart(2, '0')}</span>
                            <Led on={route.id === m.id} color="orange" />
                            <span className="key-label">
                                {m.label}
                                {m.id === 'inbox' && unread > 0 && <span className="key-count">{unread}</span>}
                            </span>
                            <span className="key-glyph" aria-hidden>
                                {m.glyph}
                            </span>
                        </button>
                    ))}
                </nav>

                <main className="stage">{screens[route.id]}</main>

                <footer className="foot">
                    <span>SM-01 · Assembled in Kolkata</span>
                    <span className="vents" aria-hidden>
                        {Array.from({ length: 8 }, (_, i) => (
                            <i key={i} />
                        ))}
                    </span>
                    <span>Keys 1–5 · ⌘S commits</span>
                </footer>
            </div>
        </Ticker.Provider>
    );
}

export default function App({ boot }) {
    const [user, setUser] = useState(boot.user);

    useEffect(() => onSessionLost(() => setUser(null)), []);

    const powerOff = async () => {
        try {
            const res = await api('logout', { method: 'POST' });
            setCsrf(res.csrf);
        } finally {
            setUser(null);
        }
    };

    return user ? <Console user={user} site={boot.site} onPowerOff={powerOff} /> : <Lock onUnlock={setUser} />;
}
