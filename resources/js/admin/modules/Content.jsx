import { useEffect, useMemo, useState } from 'react';
import { api } from '../api';
import { ago, humanize, useTicker } from '../lib';
import { Led, ModLabel } from '../ui/Hardware';
import Field from './Fields';

const STRIPES = ['var(--orange)', 'var(--blue)', 'var(--yellow)', 'var(--green)', '#f4f1ea'];

const badge = (v) => (Array.isArray(v) ? `LIST·${v.length}` : v && typeof v === 'object' ? 'OBJ' : typeof v === 'number' ? 'NUM' : 'TXT');

export default function Content() {
    const ticker = useTicker();
    const [saved, setSaved] = useState(null);
    const [draft, setDraft] = useState(null);
    const [section, setSection] = useState('profile');
    const [busy, setBusy] = useState(false);

    useEffect(() => {
        api('content')
            .then((s) => {
                setSaved(s);
                setDraft(structuredClone(s.content));
            })
            .catch((e) => ticker(e.message, 'warn'));
    }, []);

    const dirty = useMemo(
        () => (draft && saved ? Object.keys(draft).filter((k) => JSON.stringify(draft[k]) !== JSON.stringify(saved.content[k])) : []),
        [draft, saved],
    );

    const commit = async () => {
        if (!dirty.length || busy) return;
        setBusy(true);
        try {
            const s = await api('content', { method: 'PUT', body: { content: draft } });
            setSaved(s);
            setDraft(structuredClone(s.content));
            ticker(`Committed ${dirty.length} section${dirty.length > 1 ? 's' : ''} · live on site`);
        } catch (e) {
            ticker(e.message, 'warn');
        } finally {
            setBusy(false);
        }
    };

    // Ctrl/Cmd+S commits; leaving with unsaved changes asks first.
    useEffect(() => {
        const onKey = (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
                e.preventDefault();
                commit();
            }
        };
        const onLeave = (e) => {
            if (dirty.length) e.preventDefault();
        };
        window.addEventListener('keydown', onKey);
        window.addEventListener('beforeunload', onLeave);
        return () => {
            window.removeEventListener('keydown', onKey);
            window.removeEventListener('beforeunload', onLeave);
        };
    });

    if (!draft) {
        return <div className="empty">Loading cartridges…</div>;
    }

    const value = draft[section];

    return (
        <div className="editor">
            <section className="mod">
                <ModLabel index="C1">Cartridges</ModLabel>
                <div className="carts">
                    {Object.keys(draft).map((key, i) => (
                        <button
                            key={key}
                            className={`cart ${key === section ? 'cart--active' : ''}`}
                            style={{ '--stripe': STRIPES[i % STRIPES.length] }}
                            onClick={() => setSection(key)}
                        >
                            <span className="cart-stripe" />
                            <span className="cart-sticker">
                                <span>{humanize(key)}</span>
                                <span className="cart-badge">{badge(draft[key])}</span>
                            </span>
                            {dirty.includes(key) ? <Led on color="orange" blink /> : <span className="cart-notch" />}
                        </button>
                    ))}
                </div>
            </section>

            <section className="mod">
                <ModLabel
                    index="C2"
                    aside={saved.customized ? `Edited ${ago(saved.updated_at)}` : 'Factory default · config/portfolio.php'}
                >
                    Editor
                </ModLabel>
                <div className="panel-title">
                    <h2>{humanize(section)}</h2>
                    <span className="cart-badge">{badge(value)}</span>
                </div>

                <Field key={section} name={section} value={value} onChange={(v) => setDraft((d) => ({ ...d, [section]: v }))} bare />

                <div className="commitbar">
                    <div className="lcd">
                        <Led on color={dirty.length ? 'orange' : 'green'} blink={dirty.length > 0} />
                        {busy
                            ? 'Writing…'
                            : dirty.length
                              ? `${dirty.length} unsaved · ${dirty.map(humanize).join(', ')}`
                              : 'In sync with live site'}
                    </div>
                    <button className="btn" disabled={!dirty.length || busy} onClick={() => setDraft(structuredClone(saved.content))}>
                        Revert
                    </button>
                    <button className="btn btn--orange" disabled={!dirty.length || busy} onClick={commit}>
                        Commit ⌘S
                    </button>
                </div>
            </section>
        </div>
    );
}
