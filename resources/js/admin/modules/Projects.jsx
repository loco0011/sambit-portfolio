import { useEffect, useRef, useState } from 'react';
import { api } from '../api';
import { ago, bytes, stamp, useToast } from '../lib';
import { Card, Icon, Loading, PageHeader, Spinner } from '../ui/Kit';

// One upload slot per APK build listed in the config (side_projects.*.downloads).
function BuildSlot({ app, build, onUploaded }) {
    const toast = useToast();
    const [busy, setBusy] = useState(false);
    const [over, setOver] = useState(false);
    const input = useRef();
    const file = build.upload;

    const upload = async (f) => {
        if (!f || busy) return;
        if (!f.name.toLowerCase().endsWith('.apk')) {
            toast('Please choose an .apk file', 'warn');
            return;
        }
        const form = new FormData();
        form.append('file', f);
        setBusy(true);
        try {
            onUploaded(await api(`apps/${app.slug}/${build.key}`, { method: 'POST', body: form }));
            toast(`${build.label} uploaded (${bytes(f.size)}). It’s live on ${app.page}.`);
        } catch (e) {
            toast(e.message, 'warn');
        } finally {
            setBusy(false);
            input.current.value = '';
        }
    };

    return (
        <div className="grid">
            <div className="span-7">
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
                    <strong>{busy ? 'Uploading…' : over ? 'Drop to upload' : `${file ? 'Replace' : 'Upload'} ${build.label.toLowerCase()}`}</strong>
                    <span className="muted">.apk, up to 200 MB. Uploading replaces the current file.</span>
                    <input ref={input} type="file" accept=".apk,application/vnd.android.package-archive" hidden onChange={(e) => upload(e.target.files[0])} />
                </div>
            </div>
            <dl className="meta span-5">
                <div>
                    <dt>Status</dt>
                    <dd>{file ? <span className="badge badge-success">Live</span> : <span className="badge">Not uploaded</span>}</dd>
                </div>
                <div>
                    <dt>Size</dt>
                    <dd>{file ? bytes(file.size) : '—'}</dd>
                </div>
                <div>
                    <dt>Updated</dt>
                    <dd title={file ? stamp(file.updated_at) : undefined}>{file ? ago(file.updated_at) : '—'}</dd>
                </div>
                <div>
                    <dt>Download URL</dt>
                    <dd>
                        <code className="break">{build.url}</code>
                    </dd>
                </div>
            </dl>
        </div>
    );
}

export default function AppFiles() {
    const toast = useToast();
    const [apps, setApps] = useState(null);

    useEffect(() => {
        api('apps')
            .then((r) => setApps(r.apps))
            .catch((e) => toast(e.message, 'warn'));
    }, []);

    return (
        <>
            <PageHeader title="App files" description="The APKs people download from each app’s page. The page shows the size and checksum of whatever you upload last, never a version number." />
            {!apps ? (
                <Loading />
            ) : (
                <div className="grid">
                    {apps.map((app) => (
                        <Card
                            key={app.slug}
                            className="span-12"
                            title={app.name}
                            description={
                                <>
                                    Public page: <a href={app.page} target="_blank" rel="noreferrer">{app.page}</a>
                                </>
                            }
                        >
                            {app.builds.map((build) => (
                                <BuildSlot key={build.key} app={app} build={build} onUploaded={(r) => setApps(r.apps)} />
                            ))}
                        </Card>
                    ))}
                </div>
            )}
        </>
    );
}
