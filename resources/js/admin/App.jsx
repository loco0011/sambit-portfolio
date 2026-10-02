import { useCallback, useEffect, useState } from 'react';
import { api, onSessionLost, setCsrf, subscribeActivity } from './api';
import { Toast } from './lib';
import Login from './Login';
import Content from './modules/Content';
import Dashboard from './modules/Dashboard';
import Files from './modules/Files';
import Inbox from './modules/Inbox';
import System from './modules/System';
import { Icon } from './ui/Kit';

const MODULES = [
    { id: 'dash', label: 'Dashboard', icon: 'dashboard' },
    { id: 'inbox', label: 'Inbox', icon: 'inbox' },
    { id: 'content', label: 'Content', icon: 'edit' },
    { id: 'files', label: 'Résumé', icon: 'file' },
    { id: 'system', label: 'Settings', icon: 'settings' },
];

// #/inbox/12 → { id: 'inbox', focus: 12 }
function readHash() {
    const [, id, focus] = window.location.hash.split('/');
    return { id: MODULES.some((m) => m.id === id) ? id : 'dash', focus: focus ? Number(focus) : null };
}

function Toaster({ toasts, onDismiss }) {
    return (
        <div className="toaster" role="status" aria-live="polite">
            {toasts.map((t) => (
                <div key={t.id} className={`toast ${t.tone === 'warn' ? 'toast-warn' : ''}`}>
                    <Icon name={t.tone === 'warn' ? 'alert' : 'check'} />
                    <span>{t.text}</span>
                    <button type="button" className="icon-btn" onClick={() => onDismiss(t.id)} aria-label="Dismiss">
                        <Icon name="x" size={14} />
                    </button>
                </div>
            ))}
        </div>
    );
}

function Shell({ user, site, onSignOut }) {
    const [route, setRoute] = useState(readHash);
    const [unread, setUnread] = useState(0);
    const [loading, setLoading] = useState(false);
    const [toasts, setToasts] = useState([]);

    const dismiss = useCallback((id) => setToasts((list) => list.filter((t) => t.id !== id)), []);
    const toast = useCallback(
        (text, tone = 'ok') => {
            const id = Date.now() + Math.random();
            setToasts((list) => [...list.slice(-2), { id, text, tone }]);
            setTimeout(() => dismiss(id), 4000);
        },
        [dismiss],
    );

    useEffect(() => subscribeActivity(setLoading), []);

    useEffect(() => {
        const onHash = () => setRoute(readHash());
        window.addEventListener('hashchange', onHash);
        return () => window.removeEventListener('hashchange', onHash);
    }, []);

    const go = (id, focus) => {
        window.location.hash = focus ? `/${id}/${focus}` : `/${id}`;
    };

    // Number keys 1–5 switch pages, unless you're typing.
    useEffect(() => {
        const onKey = (e) => {
            if (e.metaKey || e.ctrlKey || e.altKey || /input|textarea|select/i.test(e.target.tagName)) return;
            const m = MODULES[Number(e.key) - 1];
            if (m) go(m.id);
        };
        window.addEventListener('keydown', onKey);
        return () => window.removeEventListener('keydown', onKey);
    }, []);

    // Keep the unread badge current while you're on another page.
    useEffect(() => {
        const poll = () => api('dashboard?days=7').then((d) => setUnread(d.messages.unread)).catch(() => {});
        poll();
        const t = setInterval(poll, 60000);
        return () => clearInterval(t);
    }, []);

    const pages = {
        dash: <Dashboard go={go} onUnread={setUnread} />,
        inbox: <Inbox key={route.focus ?? 'inbox'} focusId={route.focus} onUnread={setUnread} />,
        content: <Content />,
        files: <Files />,
        system: <System user={user} site={site} onSignOut={onSignOut} />,
    };

    return (
        <Toast.Provider value={toast}>
            <div className={`progress ${loading ? 'is-active' : ''}`} aria-hidden />
            <div className="shell">
                <aside className="sidebar">
                    <div className="brand">
                        <span className="brand-mark"><img src="/images/brand/logo-mark.png" alt="" /></span>
                        <span>
                            <strong>Sambit Maity</strong>
                            <small>Admin</small>
                        </span>
                    </div>

                    <nav className="nav" aria-label="Main">
                        {MODULES.map((m) => (
                            <a key={m.id} href={`#/${m.id}`} className="nav-item" aria-current={route.id === m.id ? 'page' : undefined}>
                                <Icon name={m.icon} />
                                <span>{m.label}</span>
                                {m.id === 'inbox' && unread > 0 && <span className="count">{unread}</span>}
                            </a>
                        ))}
                    </nav>

                    <div className="sidebar-foot">
                        <a className="nav-item" href={site} target="_blank" rel="noreferrer">
                            <Icon name="external" />
                            <span>View site</span>
                        </a>
                        <div className="account">
                            <span className="avatar" aria-hidden>
                                {user.email[0].toUpperCase()}
                            </span>
                            <span className="account-email" title={user.email}>
                                {user.email}
                            </span>
                            <button type="button" className="icon-btn" onClick={onSignOut} title="Sign out" aria-label="Sign out">
                                <Icon name="logout" />
                            </button>
                        </div>
                    </div>
                </aside>

                <main className="main">{pages[route.id]}</main>
            </div>
            <Toaster toasts={toasts} onDismiss={dismiss} />
        </Toast.Provider>
    );
}

export default function App({ boot }) {
    const [user, setUser] = useState(boot.user);

    useEffect(() => onSessionLost(() => setUser(null)), []);

    const signOut = async () => {
        try {
            const res = await api('logout', { method: 'POST' });
            setCsrf(res.csrf);
        } finally {
            setUser(null);
        }
    };

    return user ? <Shell user={user} site={boot.site} onSignOut={signOut} /> : <Login onSignIn={setUser} />;
}
