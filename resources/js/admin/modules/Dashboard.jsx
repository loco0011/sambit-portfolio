import { useEffect, useRef, useState } from 'react';
import { api } from '../api';
import { ago, bytes, shortDay, useToast } from '../lib';
import { Card, Icon, Loading, PageHeader, Segmented, Switch } from '../ui/Kit';
import { useResume } from './Files';

function BarChart({ series, hover, onHover }) {
    const max = Math.max(1, ...series.map((d) => d.views));

    return (
        <div className="chart" onMouseLeave={() => onHover(null)} role="img" aria-label="Daily page views">
            <div className="chart-bars">
                {series.map((d, i) => (
                    <div key={d.day} className={`chart-col ${hover === i ? 'is-hover' : ''}`} onMouseEnter={() => onHover(i)}>
                        <div className="chart-bar" style={{ height: d.views ? `${Math.max(3, (d.views / max) * 100)}%` : 0 }} />
                    </div>
                ))}
            </div>
            <div className="chart-axis">
                <span>{shortDay(series[0].day)}</span>
                <span>Peak {max} / day</span>
                <span>Today</span>
            </div>
        </div>
    );
}

function ResumeCard() {
    const { info, busy, upload } = useResume();
    const input = useRef();

    return (
        <Card title="Résumé" description={!info ? 'Checking…' : info.exists ? `${bytes(info.size)} · updated ${ago(info.updated_at)}` : 'No résumé uploaded yet.'}>
            <div className="row">
                <button className="btn" disabled={busy} onClick={() => input.current.click()}>
                    <Icon name="upload" />
                    {busy ? 'Uploading…' : info?.exists ? 'Replace' : 'Upload PDF'}
                </button>
                {info?.exists && (
                    <a className="btn" href="/resume" target="_blank" rel="noreferrer">
                        <Icon name="download" />
                        Download
                    </a>
                )}
            </div>
            <input ref={input} type="file" accept="application/pdf" hidden onChange={(e) => upload(e.target.files[0])} />
        </Card>
    );
}

export default function Dashboard({ go, onUnread }) {
    const toast = useToast();
    const [days, setDays] = useState(30);
    const [data, setData] = useState(null);
    const [hover, setHover] = useState(null);
    const [switching, setSwitching] = useState(false);

    useEffect(() => {
        let live = true;
        api(`dashboard?days=${days}`)
            .then((d) => {
                if (!live) return;
                setData(d);
                onUnread(d.messages.unread);
            })
            .catch((e) => toast(e.message, 'warn'));
        return () => (live = false);
    }, [days]);

    const setAvailable = async (available) => {
        setSwitching(true);
        setData((d) => ({ ...d, available }));
        try {
            await api('content/availability', { method: 'PATCH', body: { available } });
            toast(available ? 'You now show as open to work' : 'You now show as not available');
        } catch (e) {
            setData((d) => ({ ...d, available: !available }));
            toast(e.message, 'warn');
        } finally {
            setSwitching(false);
        }
    };

    const header = (
        <PageHeader
            title="Dashboard"
            description="Traffic and activity on your portfolio."
            actions={<Segmented label="Date range" options={[7, 30, 90]} value={days} onChange={setDays} format={(v) => `${v} days`} />}
        />
    );

    if (!data) {
        return (
            <>
                {header}
                <Loading />
            </>
        );
    }

    const point = hover !== null ? data.series[hover] : null;
    const { totals, devices } = data;
    const deviceTotal = devices.desktop + devices.mobile;
    const pct = (n) => (deviceTotal ? Math.round((n / deviceTotal) * 100) : 0);
    const topRef = Math.max(1, ...data.referrers.map((r) => r.count));

    return (
        <>
            {header}

            <div className="stats">
                {[
                    ['Page views', totals.views],
                    ['Unique visitors', totals.visitors],
                    ['Views today', totals.today],
                    ['Résumé downloads', totals.resume],
                ].map(([label, value]) => (
                    <div className="card stat" key={label}>
                        <span className="stat-label">{label}</span>
                        <span className="stat-value">{value.toLocaleString()}</span>
                    </div>
                ))}
            </div>

            <div className="grid">
                <Card
                    className="span-8"
                    title="Page views"
                    description={point ? `${shortDay(point.day)} · ${point.views} views · ${point.visitors} unique` : `Last ${days} days · ${totals.views} views`}
                >
                    <BarChart series={data.series} hover={hover} onHover={setHover} />
                </Card>

                <div className="span-4 stack">
                    <Card title="Availability" description="Shows the “open to work” badge on your site.">
                        <div className="setting">
                            <span>{data.available ? 'Open to work' : 'Not available'}</span>
                            <Switch label="Open to work" checked={data.available} disabled={switching} onChange={setAvailable} />
                        </div>
                    </Card>
                    <ResumeCard />
                </div>

                <Card className="span-5" title="Top referrers">
                    {data.referrers.length ? (
                        <ul className="bars-list">
                            {data.referrers.map((r) => (
                                <li key={r.host}>
                                    <span className="bars-fill" style={{ width: `${(r.count / topRef) * 100}%` }} />
                                    <span>{r.host}</span>
                                    <span className="num">{r.count}</span>
                                </li>
                            ))}
                        </ul>
                    ) : (
                        <p className="muted">No referrers yet. All traffic is direct.</p>
                    )}
                </Card>

                <Card className="span-3" title="Devices">
                    <div className="split" aria-hidden>
                        <span style={{ flexGrow: devices.desktop || 1 }} />
                        <span style={{ flexGrow: devices.mobile || 1 }} />
                    </div>
                    <dl className="legend">
                        <div>
                            <dt>
                                <i className="dot dot-1" />
                                Desktop
                            </dt>
                            <dd>{pct(devices.desktop)}%</dd>
                        </div>
                        <div>
                            <dt>
                                <i className="dot dot-2" />
                                Mobile
                            </dt>
                            <dd>{pct(devices.mobile)}%</dd>
                        </div>
                    </dl>
                </Card>

                <Card
                    className="span-4"
                    title="Messages"
                    actions={
                        <button className="btn btn-ghost btn-sm" onClick={() => go('inbox')}>
                            Open inbox
                            <Icon name="chevron" size={14} />
                        </button>
                    }
                >
                    <dl className="legend legend-lg">
                        <div>
                            <dt>Unread</dt>
                            <dd>{data.messages.unread}</dd>
                        </div>
                        <div>
                            <dt>Total</dt>
                            <dd>{data.messages.total}</dd>
                        </div>
                    </dl>
                </Card>

                {data.messages.recent.length > 0 && (
                    <Card className="span-12" title="Recent messages" flush>
                        <ul className="list">
                            {data.messages.recent.map((m) => (
                                <li key={m.id}>
                                    <button className="list-item" onClick={() => go('inbox', m.id)}>
                                        <span className={`unread-dot ${m.read_at ? 'is-read' : ''}`} aria-label={m.read_at ? undefined : 'Unread'} />
                                        <span className="list-main">
                                            <span className="list-title">
                                                {m.name}
                                                {m.company && <span className="muted"> · {m.company}</span>}
                                            </span>
                                            <span className="list-snippet">{m.message}</span>
                                        </span>
                                        <span className="list-meta">{ago(m.created_at)}</span>
                                    </button>
                                </li>
                            ))}
                        </ul>
                    </Card>
                )}
            </div>
        </>
    );
}
