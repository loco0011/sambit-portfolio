import { useEffect, useRef, useState } from 'react';
import { api, upload } from '../api';
import { ago, bytes, stamp, useToast } from '../lib';
import { Card, Icon, Loading, PageHeader, Spinner } from '../ui/Kit';

const statusOf = (app) =>
    app.platform === 'web'
        ? { label: app.website ? 'Live site' : 'Website', tone: app.website ? 'badge-success' : '' }
        : app.builds.every((b) => b.upload || b.external)
          ? { label: 'Live', tone: 'badge-success' }
          : { label: 'Needs APK', tone: 'badge-warn' };

// One release slot per APK build listed in the config (side_projects.*.downloads).
function ReleaseSlot({ app, build, onUploaded }) {
    const toast = useToast();
    const [progress, setProgress] = useState(null);
    const [over, setOver] = useState(false);
    const input = useRef();
    const file = build.upload;
    const busy = progress !== null;

    const send = async (f) => {
        if (!f || busy) return;
        if (!f.name.toLowerCase().endsWith('.apk')) {
            toast('Please choose an .apk file', 'warn');
            return;
        }
        const form = new FormData();
        form.append('file', f);
        setProgress(0);
        try {
            onUploaded(await upload(`apps/${app.slug}/${build.key}`, form, setProgress));
            toast(`${app.name}: new release uploaded (${bytes(f.size)}). The download is live on ${app.page}.`);
        } catch (e) {
            toast(e.message, 'warn');
        } finally {
            setProgress(null);
            input.current.value = '';
        }
    };

    return (
        <Card
            className="span-12"
            title={app.builds.length > 1 ? `Release APK · ${build.label}` : 'Release APK'}
            description={build.note || 'Upload the latest release build. It replaces the current download right away; the page shows its size and checksum, never a version number.'}
        >
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
                            send(e.dataTransfer.files[0]);
                        }}
                    >
                        <span className="dropzone-icon">{busy ? <Spinner /> : <Icon name="upload" size={20} />}</span>
                        <strong>{busy ? `Uploading… ${Math.round(progress * 100)}%` : over ? 'Drop to upload' : `${file ? 'Upload a new release' : 'Upload the first release'}`}</strong>
                        {busy ? (
                            <span className="upload-bar" aria-hidden>
                                <span style={{ transform: `scaleX(${progress})` }} />
                            </span>
                        ) : (
                            <span className="muted">Click or drag an .apk here, up to 200 MB</span>
                        )}
                        <input ref={input} type="file" accept=".apk,application/vnd.android.package-archive" hidden onChange={(e) => send(e.target.files[0])} />
                    </div>
                </div>
                <div className="span-5">
                    <dl className="meta">
                        <div>
                            <dt>Status</dt>
                            <dd>{file ? <span className="badge badge-success">Live</span> : <span className="badge">Not uploaded</span>}</dd>
                        </div>
                        <div>
                            <dt>Size</dt>
                            <dd>{file ? bytes(file.size) : '—'}</dd>
                        </div>
                        <div>
                            <dt>Uploaded</dt>
                            <dd title={file ? stamp(file.updated_at) : undefined}>{file ? ago(file.updated_at) : '—'}</dd>
                        </div>
                        <div>
                            <dt>Downloads</dt>
                            <dd>
                                {build.downloads.total} <span className="muted">· {build.downloads.last_30} in 30 days</span>
                            </dd>
                        </div>
                        <div>
                            <dt>SHA-256</dt>
                            <dd>
                                <code className="break" title={file?.sha256 ?? undefined}>{file?.sha256 ? `${file.sha256.slice(0, 16)}…` : '—'}</code>
                            </dd>
                        </div>
                    </dl>
                    {file && (
                        <a className="btn" href={build.url} download>
                            <Icon name="download" />
                            Test download
                        </a>
                    )}
                </div>
            </div>
        </Card>
    );
}

// A build hosted elsewhere (config `external`, e.g. the latest GitHub release): nothing to upload here.
function HostedSlot({ build }) {
    return (
        <Card
            className="span-12"
            title={`Release APK · hosted on ${build.source || 'another site'}`}
            description="The download button links straight to the latest release there, so publishing a new release updates the site automatically."
        >
            <dl className="meta">
                <div>
                    <dt>Status</dt>
                    <dd>
                        <span className="badge badge-success">Live</span>
                    </dd>
                </div>
                <div>
                    <dt>Download link</dt>
                    <dd>
                        <code className="break">{build.external}</code>
                    </dd>
                </div>
            </dl>
            <a className="btn" href={build.external} target="_blank" rel="noreferrer">
                <Icon name="external" />
                Open release
            </a>
        </Card>
    );
}

function Project({ app, onUpdated }) {
    const web = app.platform === 'web';
    const latest = app.builds.map((b) => b.upload?.updated_at).filter(Boolean).sort().pop();
    const hosted = app.builds.length > 0 && app.builds.every((b) => b.external);

    return (
        <article className="project">
            <header className="project-head">
                {app.icon && <img src={app.icon} alt="" width="48" height="48" />}
                <div>
                    <h2>{app.name}</h2>
                    <p className="muted">
                        {app.tag} · {web ? 'Website' : 'Android app'}
                        {app.status && ` · ${app.status}`}
                    </p>
                </div>
                <a className="btn" href={app.page} target="_blank" rel="noreferrer">
                    <Icon name="external" />
                    View page
                </a>
            </header>

            <div className="stats project-stats">
                <div className="card stat">
                    <span className="stat-label">Page views</span>
                    <strong className="stat-value">{app.views.last_30}</strong>
                    <span className="muted">last 30 days · {app.views.total} total</span>
                </div>
                {web ? (
                    <div className="card stat">
                        <span className="stat-label">Website</span>
                        <strong className="stat-value is-text">{app.website ? new URL(app.website).host : 'Not live yet'}</strong>
                        <span className="muted">{app.website ? 'The page’s button opens it' : 'The page shows “Launching soon”'}</span>
                    </div>
                ) : (
                    <>
                        <div className="card stat">
                            <span className="stat-label">Downloads</span>
                            <strong className="stat-value">{app.downloads.last_30}</strong>
                            <span className="muted">last 30 days · {app.downloads.total} total</span>
                        </div>
                        <div className="card stat">
                            <span className="stat-label">Latest release</span>
                            <strong className="stat-value is-text">{hosted ? 'Always latest' : latest ? ago(latest) : 'None yet'}</strong>
                            <span className="muted">{hosted ? `From ${app.builds[0].source}` : latest ? stamp(latest) : 'Upload an APK below'}</span>
                        </div>
                    </>
                )}
            </div>

            <div className="grid">
                {web ? (
                    <Card className="span-12" title="No APK for this one" description="Kolkata Hiring Map is a website, so its page links to the site instead of offering a download.">
                        <p className="muted">
                            When it goes live, add its address as <code>website</code> under Content → Side projects. The page’s “Launching soon” button turns into “Open Kolkata Hiring Map ↗”.
                        </p>
                    </Card>
                ) : (
                    app.builds.map((build) => (build.external ? <HostedSlot key={build.key} build={build} /> : <ReleaseSlot key={build.key} app={app} build={build} onUploaded={onUpdated} />))
                )}
            </div>

            <p className="muted project-foot">Text, features, screenshots and FAQ for this page are edited under Content → Side projects.</p>
        </article>
    );
}

export default function Projects({ focus, go }) {
    const toast = useToast();
    const [apps, setApps] = useState(null);

    useEffect(() => {
        api('apps')
            .then((r) => setApps(r.apps))
            .catch((e) => toast(e.message, 'warn'));
    }, []);

    const header = <PageHeader title="Projects" description="Your After hours apps. Upload each one’s latest release APK here and it goes straight to its download page." />;

    if (!apps) {
        return (
            <>
                {header}
                <Loading />
            </>
        );
    }

    const current = apps.find((a) => a.slug === focus) ?? apps[0];

    return (
        <>
            {header}
            <div className="inbox projects card">
                <ul className="list inbox-list">
                    {apps.map((app) => {
                        const s = statusOf(app);
                        return (
                            <li key={app.slug}>
                                <button className={`list-item ${app.slug === current?.slug ? 'is-active' : ''}`} onClick={() => go('projects', app.slug)}>
                                    {app.icon && <img className="project-icon" src={app.icon} alt="" width="34" height="34" />}
                                    <span className="list-main">
                                        <span className="list-title">{app.name}</span>
                                        <span className="list-snippet">{app.tag}</span>
                                    </span>
                                    <span className={`badge ${s.tone}`}>{s.label}</span>
                                </button>
                            </li>
                        );
                    })}
                </ul>
                {current ? <Project key={current.slug} app={current} onUpdated={(r) => setApps(r.apps)} /> : <div className="empty">No projects yet.</div>}
            </div>
        </>
    );
}
