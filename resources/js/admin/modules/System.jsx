import { useEffect, useState } from 'react';
import { api } from '../api';
import { ago, useTicker } from '../lib';
import { HoldButton, ModLabel } from '../ui/Hardware';

export default function System({ user, site, onPowerOff }) {
    const ticker = useTicker();
    const [form, setForm] = useState({ current_password: '', password: '', password_confirmation: '' });
    const [error, setError] = useState('');
    const [busy, setBusy] = useState(false);
    const [content, setContent] = useState(null);

    useEffect(() => {
        api('content').then(setContent).catch(() => {});
    }, []);

    const set = (k) => (e) => setForm((f) => ({ ...f, [k]: e.target.value }));

    const changePassword = async (e) => {
        e.preventDefault();
        setBusy(true);
        setError('');
        try {
            await api('password', { method: 'PUT', body: form });
            setForm({ current_password: '', password: '', password_confirmation: '' });
            ticker('Access key rotated');
        } catch (err) {
            setError(err.message);
        } finally {
            setBusy(false);
        }
    };

    const factoryReset = async () => {
        try {
            setContent(await api('content', { method: 'DELETE' }));
            ticker('Factory reset · site content restored from config/portfolio.php');
        } catch (e) {
            ticker(e.message, 'warn');
        }
    };

    return (
        <div className="dash">
            <section className="mod span-6">
                <ModLabel index="E1">Access key</ModLabel>
                <form className="fields" onSubmit={changePassword}>
                    <label className="field">
                        <span className="field-label">Current key</span>
                        <input className="well well--lcd" type="password" autoComplete="current-password" value={form.current_password} onChange={set('current_password')} required />
                    </label>
                    <div className="grid-2">
                        <label className="field">
                            <span className="field-label">New key · 10+ chars</span>
                            <input className="well well--lcd" type="password" autoComplete="new-password" value={form.password} onChange={set('password')} required minLength={10} />
                        </label>
                        <label className="field">
                            <span className="field-label">Repeat new key</span>
                            <input
                                className="well well--lcd"
                                type="password"
                                autoComplete="new-password"
                                value={form.password_confirmation}
                                onChange={set('password_confirmation')}
                                required
                            />
                        </label>
                    </div>
                    {error && <div className="err">! {error}</div>}
                    <div>
                        <button className="btn btn--dark" disabled={busy}>
                            {busy ? 'Rotating…' : 'Rotate key'}
                        </button>
                    </div>
                </form>
            </section>

            <div className="span-6 stack">
                <section className="mod">
                    <ModLabel index="E2">Unit info</ModLabel>
                    <div className="lcd lcd-pad">
                        <div className="lcd-rows">
                            <div>
                                <span className="lcd-dim">Operator</span>
                                <span>{user.email}</span>
                            </div>
                            <div>
                                <span className="lcd-dim">Site</span>
                                <a href={site} target="_blank" rel="noreferrer" style={{ textDecoration: 'none' }}>
                                    {site.replace(/^https?:\/\//, '')} ↗
                                </a>
                            </div>
                            <div>
                                <span className="lcd-dim">Content</span>
                                <span>{!content ? '…' : content.customized ? `Edited ${ago(content.updated_at)}` : 'Factory default'}</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section className="mod">
                    <ModLabel index="E3">Factory reset</ModLabel>
                    <p className="note">
                        Throws away every edit made in Content and restores the site from <code>config/portfolio.php</code>. Messages and the résumé are untouched.
                    </p>
                    <HoldButton className="btn--orange" ms={1600} onConfirm={factoryReset} disabled={!content?.customized}>
                        {content?.customized ? 'Hold to reset content' : 'Already factory default'}
                    </HoldButton>
                </section>

                <section className="mod">
                    <ModLabel index="E4">Power</ModLabel>
                    <button className="btn btn--dark btn--block" onClick={onPowerOff}>
                        ⏻ Power off · sign out
                    </button>
                </section>
            </div>
        </div>
    );
}
