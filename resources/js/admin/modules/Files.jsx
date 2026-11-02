import { useEffect, useRef, useState } from 'react';
import { api } from '../api';
import { ago, bytes, stamp, useToast } from '../lib';
import { Card, Icon, Loading, PageHeader, Spinner } from '../ui/Kit';

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

function TopList({ title, items, empty }) {
    const top = Math.max(1, ...items.map((i) => i.count));

    return (
        <Card className="span-6" title={title}>
            {items.length ? (
                <ul className="bars-list">
                    {items.map((i) => (
                        <li key={i.name}>
                            <span className="bars-fill" style={{ width: `${(i.count / top) * 100}%` }} />
                            <span>{i.name}</span>
                            <span className="num">{i.count}</span>
                        </li>
                    ))}
                </ul>
            ) : (
                <p className="muted">{empty}</p>
            )}
        </Card>
    );
}

function Downloads() {
    const toast = useToast();
    const [page, setPage] = useState(1);
    const [data, setData] = useState(null);
    const [busy, setBusy] = useState(false);

    const load = async () => {
        setBusy(true);
        try {
            setData(await api(`resume/downloads?page=${page}`));
        } catch (e) {
            toast(e.message, 'warn');
        } finally {
            setBusy(false);
        }
    };

    useEffect(() => {
        load();
        // Downloads usually happen in another tab, so catch up when coming back to this one.
        window.addEventListener('focus', load);
        return () => window.removeEventListener('focus', load);
    }, [page]);

    if (!data) return <Loading />;

    const { recent } = data;

    return (
        <>
            <div className="stats">
                {[
                    ['All-time downloads', data.total],
                    ['Last 7 days', data.last_7],
                    ['Last 30 days', data.last_30],
                    ['Countries', data.countries.length],
                ].map(([label, value]) => (
                    <div className="card stat" key={label}>
                        <span className="stat-label">{label}</span>
                        <span className="stat-value">{value.toLocaleString()}</span>
                    </div>
                ))}
            </div>

            <div className="grid downloads-section">
                <Card
                    className="span-12"
                    title="Recent downloads"
                    description="Location and network are estimated from the IP. Home and mobile users show their ISP. IP geolocation by DB-IP."
                    actions={
                        <button className="btn btn-sm" disabled={busy} onClick={load}>
                            {busy ? 'Refreshing…' : 'Refresh'}
                        </button>
                    }
                    flush
                >
                    {recent.data.length ? (
                        <div className="downloads-scroll">
                            <table className="downloads-table">
                                <thead>
                                    <tr>
                                        <th>When</th>
                                        <th>Location</th>
                                        <th>Network</th>
                                        <th>IP address</th>
                                        <th>Browser</th>
                                        <th>Source</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {recent.data.map((d) => {
                                        const x = d.details ?? {};
                                        const place = [x.city, x.region, x.country].filter(Boolean).join(', ');
                                        const browser = [x.browser, x.os].filter(Boolean).join(' on ');

                                        return (
                                            <tr key={d.id}>
                                                <td className="downloads-when" title={stamp(d.created_at)}>
                                                    {ago(d.created_at)}
                                                </td>
                                                <td>
                                                    {place || <span className="muted">Not recorded</span>}
                                                    {x.admin && <span className="badge downloads-you">You</span>}
                                                </td>
                                                <td>{x.org || <span className="muted">—</span>}</td>
                                                <td>{x.ip ? <code>{x.ip}</code> : <span className="muted">—</span>}</td>
                                                <td>
                                                    {browser || <span className="downloads-device">{d.device}</span>}
                                                    {x.language && <span className="muted"> · {x.language}</span>}
                                                </td>
                                                <td>{d.referrer || x.page || <span className="muted">Direct link</span>}</td>
                                            </tr>
                                        );
                                    })}
                                </tbody>
                            </table>
                        </div>
                    ) : (
                        <p className="muted downloads-empty">Nobody has downloaded your résumé yet.</p>
                    )}
                    {recent.last_page > 1 && (
                        <div className="row downloads-pager">
                            <button className="btn btn-sm" disabled={page <= 1} onClick={() => setPage(page - 1)}>
                                Newer
                            </button>
                            <span className="muted">
                                Page {recent.current_page} of {recent.last_page}
                            </span>
                            <button className="btn btn-sm" disabled={page >= recent.last_page} onClick={() => setPage(page + 1)}>
                                Older
                            </button>
                        </div>
                    )}
                </Card>

                <TopList title="Top countries" items={data.countries} empty="No locations recorded yet." />
                <TopList title="Top companies and networks" items={data.orgs} empty="No networks recorded yet." />
            </div>
        </>
    );
}

export default function Files() {
    const { info, busy, upload } = useResume();
    const [over, setOver] = useState(false);
    const input = useRef();

    return (
        <>
            <PageHeader title="Résumé" description="Who is downloading your résumé, and the PDF they get." />
            <Downloads />
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
