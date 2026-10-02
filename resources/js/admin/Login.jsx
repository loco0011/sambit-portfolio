import { useState } from 'react';
import { api, setCsrf } from './api';
import { Icon, Spinner } from './ui/Kit';

export default function Login({ onSignIn }) {
    const [form, setForm] = useState({ email: '', password: '' });
    const [busy, setBusy] = useState(false);
    const [error, setError] = useState('');
    const [showPassword, setShowPassword] = useState(false);

    const submit = async (e) => {
        e.preventDefault();
        setBusy(true);
        setError('');
        try {
            const res = await api('login', { method: 'POST', body: form });
            setCsrf(res.csrf);
            onSignIn(res.user);
        } catch (err) {
            setError(
                { 429: 'Too many attempts. Please wait a minute and try again.', 419: 'Your session expired. Reload the page and try again.' }[err.status] ??
                    'Incorrect email or password.',
            );
            setBusy(false);
        }
    };

    return (
        <div className="login">
            <form className="login-card" onSubmit={submit}>
                <span className="brand-mark"><img src="/images/brand/logo-mark.png" alt="" /></span>
                <h1>Sign in</h1>
                <p className="muted">Admin for sambitmaity.com</p>

                {error && (
                    <div className="alert alert-danger" role="alert">
                        <Icon name="alert" />
                        {error}
                    </div>
                )}

                <label className="field">
                    <span className="label">Email</span>
                    <input
                        className="input"
                        type="email"
                        autoComplete="username"
                        value={form.email}
                        onChange={(e) => setForm({ ...form, email: e.target.value })}
                        required
                        autoFocus
                    />
                </label>
                <label className="field">
                    <span className="label">Password</span>
                    <span className="input-group">
                        <input
                            className="input"
                            type={showPassword ? 'text' : 'password'}
                            autoComplete="current-password"
                            value={form.password}
                            onChange={(e) => setForm({ ...form, password: e.target.value })}
                            required
                        />
                        <button
                            type="button"
                            className="icon-btn"
                            onClick={() => setShowPassword((v) => !v)}
                            onMouseDown={(e) => e.preventDefault()} // keep focus and caret in the field
                            aria-label={showPassword ? 'Hide password' : 'Show password'}
                            aria-pressed={showPassword}
                            title={showPassword ? 'Hide password' : 'Show password'}
                        >
                            <Icon name={showPassword ? 'eyeOff' : 'eye'} />
                        </button>
                    </span>
                </label>
                <button className="btn btn-primary btn-block" disabled={busy}>
                    {busy && <Spinner />}
                    {busy ? 'Signing in…' : 'Sign in'}
                </button>
            </form>
        </div>
    );
}
