import { useEffect, useMemo, useState } from 'react';
import { api } from '../api';
import { ago, humanize, useToast } from '../lib';
import { Loading, PageHeader, Spinner } from '../ui/Kit';
import Field from './Fields';

export default function Content() {
    const toast = useToast();
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
            .catch((e) => toast(e.message, 'warn'));
    }, []);

    const dirty = useMemo(
        () => (draft && saved ? Object.keys(draft).filter((k) => JSON.stringify(draft[k]) !== JSON.stringify(saved.content[k])) : []),
        [draft, saved],
    );

    const save = async () => {
        if (!dirty.length || busy) return;
        setBusy(true);
        try {
            const s = await api('content', { method: 'PUT', body: { content: draft } });
            setSaved(s);
            setDraft(structuredClone(s.content));
            toast(`Saved ${dirty.length} section${dirty.length > 1 ? 's' : ''}. Changes are live.`);
        } catch (e) {
            toast(e.message, 'warn');
        } finally {
            setBusy(false);
        }
    };

    // Ctrl/Cmd+S saves; leaving with unsaved changes asks first.
    useEffect(() => {
        const onKey = (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
                e.preventDefault();
                save();
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

    const header = (
        <PageHeader
            title="Content"
            description={
                !saved ? 'Edit the text shown on your site.' : saved.customized ? `Last saved ${ago(saved.updated_at)}.` : 'Using the defaults from config/portfolio.php.'
            }
        />
    );

    if (!draft) {
        return (
            <>
                {header}
                <Loading />
            </>
        );
    }

    return (
        <>
            {header}
            <div className="editor">
                <nav className="subnav" aria-label="Sections">
                    {Object.keys(draft).map((key) => (
                        <button key={key} type="button" aria-current={key === section ? 'true' : undefined} onClick={() => setSection(key)}>
                            {humanize(key)}
                            {dirty.includes(key) && <span className="unread-dot" aria-label="Unsaved changes" />}
                        </button>
                    ))}
                </nav>

                <section className="card">
                    <div className="card-header">
                        <h2>{humanize(section)}</h2>
                    </div>
                    <div className="card-body">
                        <Field key={section} name={section} value={draft[section]} onChange={(v) => setDraft((d) => ({ ...d, [section]: v }))} bare />
                    </div>
                    <div className="card-footer">
                        <span className="muted">
                            {dirty.length ? `Unsaved changes in ${dirty.map(humanize).join(', ')}` : 'All changes saved'}
                        </span>
                        <button className="btn" disabled={!dirty.length || busy} onClick={() => setDraft(structuredClone(saved.content))}>
                            Discard
                        </button>
                        <button className="btn btn-primary" disabled={!dirty.length || busy} onClick={save}>
                            {busy && <Spinner />}
                            Save changes
                        </button>
                    </div>
                </section>
            </div>
        </>
    );
}
