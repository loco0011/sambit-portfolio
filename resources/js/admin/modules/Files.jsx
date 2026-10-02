import { useEffect, useRef, useState } from 'react';
import { api } from '../api';
import { ago, bytes, stamp, useToast } from '../lib';
import { Card, Icon, PageHeader, Spinner } from '../ui/Kit';

// Shared by the Résumé page and the dashboard card.
export function useResume() {
    const toast = useToast();
    const [info, setInfo] = useState(null);
    const [busy, setBusy] = useState(false);

    useEffect(() => {
        api('resume').then(setInfo).catch((e) => toast(e.message, 'warn'));
    }, []);

    const upload = async (file) => {
        if (!file) return;
        if (file.type !== 'application/pdf') {
            toast('Please choose a PDF file', 'warn');
            return;
        }
        const form = new FormData();
        form.append('file', file);
        setBusy(true);
        try {
            setInfo(await api('resume', { method: 'POST', body: form }));
            toast(`Résumé uploaded (${bytes(file.size)}). It’s live at /resume.`);
        } catch (e) {
            toast(e.message, 'warn');
        } finally {
            setBusy(false);
        }
    };

    return { info, busy, upload };
}

export default function Files() {
    const { info, busy, upload } = useResume();
    const [over, setOver] = useState(false);
    const input = useRef();

    return (
        <>
            <PageHeader title="Résumé" description="The PDF visitors download from your site." />
            <div className="grid">
                <Card className="span-7" title="Upload" description="Uploading replaces the current file immediately.">
                    <div
                        className={`dropzone ${over ? 'is-over' : ''}`}
                        role="button"
                        tabIndex={0}
                        aria-disabled={busy}
                        onClick={() => !busy && input.current.click()}
                        onKeyDown={(e) => !busy && (e.key === 'Enter' || e.key === ' ') && input.current.click()}
                        onDragOver={(e) => {
                            e.preventDefault();
                            setOver(true);
                        }}
                        onDragLeave={() => setOver(false)}
                        onDrop={(e) => {
                            e.preventDefault();
                            setOver(false);
                            upload(e.dataTransfer.files[0]);
                        }}
                    >
                        <span className="dropzone-icon">{busy ? <Spinner /> : <Icon name="upload" size={20} />}</span>
                        <strong>{busy ? 'Uploading…' : over ? 'Drop to upload' : 'Click to upload or drag and drop'}</strong>
                        <span className="muted">PDF, up to 10 MB</span>
                        <input ref={input} type="file" accept="application/pdf" hidden onChange={(e) => upload(e.target.files[0])} />
                    </div>
                </Card>

                <Card className="span-5" title="Current file">
                    <dl className="meta">
                        <div>
                            <dt>Status</dt>
                            <dd>{!info ? 'Checking…' : info.exists ? <span className="badge badge-success">Live</span> : <span className="badge">Not uploaded</span>}</dd>
                        </div>
                        <div>
                            <dt>Size</dt>
                            <dd>{info?.exists ? bytes(info.size) : '—'}</dd>
                        </div>
                        <div>
                            <dt>Updated</dt>
                            <dd title={info?.exists ? stamp(info.updated_at) : undefined}>{info?.exists ? ago(info.updated_at) : '—'}</dd>
                        </div>
                        <div>
                            <dt>Public URL</dt>
                            <dd>
                                <code>/resume</code>
                            </dd>
                        </div>
                        <div>
                            <dt>Downloads as</dt>
                            <dd className="break">Sambit_Maity_Full_Stack_Software_Engineer_Resume.pdf</dd>
                        </div>
                    </dl>
                    {info?.exists && (
                        <a className="btn" href="/resume" target="_blank" rel="noreferrer">
                            <Icon name="download" />
                            Test download
                        </a>
                    )}
                </Card>
            </div>
        </>
    );
}
