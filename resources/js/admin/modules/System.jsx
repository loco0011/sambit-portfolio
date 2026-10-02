import { useEffect, useState } from 'react';
import { api } from '../api';
import { ago, useToast } from '../lib';
import { Card, ConfirmDialog, Icon, PageHeader, Spinner } from '../ui/Kit';

export default function System({ user, site, onSignOut }) {
    const toast = useToast();
    const [form, setForm] = useState({ current_password: '', password: '', password_confirmation: '' });
    const [error, setError] = useState('');
    const [busy, setBusy] = useState(false);
    const [content, setContent] = useState(null);
    const [confirming, setConfirming] = useState(false);
    const [resetting, setResetting] = useState(false);

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
            toast('Password updated');
        } catch (err) {
            setError(err.message);
        } finally {
            setBusy(false);
        }
    };

    const reset = async () => {
        setResetting(true);
        try {
            setContent(await api('content', { method: 'DELETE' }));
            toast('Content reset to the defaults in config/portfolio.php');
        } catch (e) {
            toast(e.message, 'warn');
        } finally {
            setResetting(false);
            setConfirming(false);
        }
    };

    return (
        <>
            <PageHeader title="Settings" description="Your account and site settings." />
            <div className="grid">
                <Card className="span-7" title="Change password" description="Use at least 10 characters.">
                    <form className="fields" onSubmit={changePassword}>
                        <label className="field">
                            <span className="label">Current password</span>
                            <input className="input" type="password" autoComplete="current-password" value={form.current_password} onChange={set('current_password')} required />
                        </label>
                        <div className="grid-2">
                            <label className="field">
                                <span className="label">New password</span>
                                <input className="input" type="password" autoComplete="new-password" value={form.password} onChange={set('password')} required minLength={10} />
                            </label>
                            <label className="field">
                                <span className="label">Confirm new password</span>
                                <input
                                    className="input"
                                    type="password"
                                    autoComplete="new-password"
                                    value={form.password_confirmation}
                                    onChange={set('password_confirmation')}
                                    required
                                />
                            </label>
                        </div>
                        {error && (
                            <div className="alert alert-danger" role="alert">
                                <Icon name="alert" />
                                {error}
                            </div>
                        )}
                        <div>
                            <button className="btn btn-primary" disabled={busy}>
                                {busy && <Spinner />}
                                Update password
                            </button>
                        </div>
                    </form>
                </Card>

                <div className="span-5 stack">
                    <Card title="Account">
                        <dl className="meta">
                            <div>
                                <dt>Signed in as</dt>
                                <dd className="break">{user.email}</dd>
                            </div>
                            <div>
                                <dt>Site</dt>
                                <dd>
                                    <a href={site} target="_blank" rel="noreferrer">
                                        {site.replace(/^https?:\/\//, '')}
                                    </a>
                                </dd>
                            </div>
                            <div>
                                <dt>Content</dt>
                                <dd>{!content ? '…' : content.customized ? `Edited ${ago(content.updated_at)}` : 'Defaults'}</dd>
                            </div>
                        </dl>
                        <button className="btn" onClick={onSignOut}>
                            <Icon name="logout" />
                            Sign out
                        </button>
                    </Card>

                    <Card className="card-danger" title="Reset content" description="Discards every edit made on the Content page and restores the defaults from config/portfolio.php. Messages and the résumé aren’t affected.">
                        <button className="btn btn-danger" disabled={!content?.customized} onClick={() => setConfirming(true)}>
                            {content?.customized ? 'Reset content' : 'Already using defaults'}
                        </button>
                    </Card>
                </div>
            </div>

            <ConfirmDialog
                open={confirming}
                title="Reset all content?"
                confirmLabel="Reset content"
                danger
                busy={resetting}
                onConfirm={reset}
                onCancel={() => setConfirming(false)}
            >
                Every edit made in the admin will be lost and the site will show the defaults from <code>config/portfolio.php</code>. This can’t be undone.
            </ConfirmDialog>
        </>
    );
}
