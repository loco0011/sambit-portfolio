import { useEffect, useRef, useState } from 'react';
import { api } from '../api';
import { ago, bytes, shortDay, useTicker } from '../lib';
import { Knob, Led, ModLabel, Toggle } from '../ui/Hardware';
import DotChart from '../ui/DotChart';
import { useResume } from './Files';

function ResumeQuick() {
    const { info, busy, upload } = useResume();
    const input = useRef();

    return (
        <section className="mod">
            <ModLabel index="A9" aside={<><Led on={!!info?.exists} blink={busy} color={busy ? 'orange' : 'green'} /> PDF</>}>
                Résumé
            </ModLabel>
            <p className="note">
                {!info ? 'Checking drive…' : info.exists ? `Live · ${bytes(info.size)} · updated ${ago(info.updated_at)}` : 'No résumé uploaded yet.'}
            </p>
            <div style={{ display: 'flex', gap: 10, flexWrap: 'wrap' }}>
                <button className="btn btn--orange" disabled={busy} onClick={() => input.current.click()}>
                    {busy ? 'Writing…' : info?.exists ? 'Replace PDF' : 'Upload PDF'}
                </button>
                {info?.exists && (
                    <a className="btn" href="/resume" target="_blank" rel="noreferrer">
                        View ↗
                    </a>
                )}
            </div>
            <input ref={input} type="file" accept="application/pdf" hidden onChange={(e) => upload(e.target.files[0])} />
        </section>
    );
}

export default function Dashboard({ go, onUnread }) {
    const ticker = useTicker();
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
            .catch((e) => ticker(e.message, 'warn'));
        return () => (live = false);
    }, [days]);

    const setAvailable = async (available) => {
        setSwitching(true);
        setData((d) => ({ ...d, available }));
        try {
            await api('content/availability', { method: 'PATCH', body: { available } });
            ticker(available ? 'Status: open to work · live on site' : 'Status: not available · live on site');
        } catch (e) {
            setData((d) => ({ ...d, available: !available }));
            ticker(e.message, 'warn');
        } finally {
            setSwitching(false);
        }
    };

    if (!data) {
        return <div className="empty">Warming up the display…</div>;
    }

    const point = hover !== null ? data.series[hover] : null;
    const { totals, devices } = data;
    const deviceTotal = devices.desktop + devices.mobile;
    const topRef = Math.max(1, ...data.referrers.map((r) => r.count));

    return (
        <div className="dash">
            <section className="mod span-8">
                <ModLabel index="A1" aside={<><Led on color="orange" blink /> Live</>}>
                    Traffic · page views
                </ModLabel>
                <div className="lcd lcd-pad lcd-flicker" key={days}>
                    <div className="lcd-line">
                        <span>{point ? shortDay(point.day) : `Last ${days} days`}</span>
                        <span className="lcd-dim">{point ? `${point.visitors} unique` : `${totals.visitors} unique visitors`}</span>
                    </div>
                    <div className="lcd-big" style={{ marginTop: 10 }}>
                        {point ? point.views : totals.views}
                    </div>
                    <div className="chart">
                        <DotChart series={data.series} hover={hover} onHover={setHover} />
                    </div>
                    <div className="chart-axis lcd-dim">
                        <span>{shortDay(data.series[0].day)}</span>
                        <span>Peak {Math.max(...data.series.map((d) => d.views))}/day</span>
                        <span>Today</span>
                    </div>
                </div>
            </section>

            <div className="span-4 stack">
                <section className="mod">
                    <ModLabel index="A2">Range · days</ModLabel>
                    <Knob options={[7, 30, 90]} value={days} onChange={setDays} format={(v) => `${v}D`} />
                </section>
                <section className="mod">
                    <ModLabel index="A3">Broadcast</ModLabel>
                    <Toggle
                        on={data.available}
                        busy={switching}
                        onChange={setAvailable}
                        title={data.available ? 'Open to work' : 'Not available'}
                        sub="Flips the availability badge on your site instantly."
                    />
                </section>
                <ResumeQuick />
            </div>

            <section className="mod span-12">
                <ModLabel index="A4">Readouts · last {days} days</ModLabel>
                <div className="readouts">
                    {[
                        ['Page views', totals.views],
                        ['Unique visitors', totals.visitors],
                        ['Views today', totals.today],
                        ['Résumé downloads', totals.resume],
                    ].map(([label, value]) => (
                        <div className="lcd readout" key={label}>
                            <div className="lcd-line lcd-dim">{label}</div>
                            <div className="readout-val">{String(value).padStart(3, '0')}</div>
                        </div>
                    ))}
                </div>
            </section>

            <section className="mod span-5">
                <ModLabel index="A5">Referrers</ModLabel>
                <div className="lcd lcd-pad" style={{ minHeight: 190 }}>
                    {data.referrers.length ? (
                        <div className="reflist">
                            {data.referrers.map((r) => (
                                <div className="ref" key={r.host}>
                                    <span>{r.host}</span>
                                    <span>{r.count}</span>
                                    <div className="ref-track">
                                        <div className="ref-bar" style={{ width: `${(r.count / topRef) * 100}%` }} />
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="lcd-line lcd-dim">No referrers yet — direct traffic only</div>
                    )}
                </div>
            </section>

            <section className="mod span-3">
                <ModLabel index="A6">Devices</ModLabel>
                <div className="devbar">
                    <div style={{ flexGrow: devices.desktop || 1, background: 'var(--ink)' }} />
                    <div style={{ flexGrow: devices.mobile || 1, background: 'var(--orange)' }} />
                </div>
                <div className="devbar-legend">
                    <span>
                        <i className="swatch" style={{ background: 'var(--ink)' }} />
                        Desk {deviceTotal ? Math.round((devices.desktop / deviceTotal) * 100) : 0}%
                    </span>
                    <span>
                        <i className="swatch" style={{ background: 'var(--orange)' }} />
                        Mob {deviceTotal ? Math.round((devices.mobile / deviceTotal) * 100) : 0}%
                    </span>
                </div>
            </section>

            <section className="mod span-4">
                <ModLabel index="A7" aside={<button className="btn btn--sm" onClick={() => go('inbox')}>Open inbox →</button>}>
                    Messages
                </ModLabel>
                <div className="lcd lcd-pad">
                    <div className="lcd-line lcd-dim">
                        <span>Unread</span>
                        <span>Total</span>
                    </div>
                    <div className="lcd-line" style={{ alignItems: 'baseline' }}>
                        <span className="lcd-big" style={{ fontSize: 52 }}>{data.messages.unread}</span>
                        <span className="readout-val" style={{ fontSize: 28 }}>{data.messages.total}</span>
                    </div>
                </div>
            </section>

            {data.messages.recent.length > 0 && (
                <section className="mod span-12">
                    <ModLabel index="A8">Latest transmissions</ModLabel>
                    <div className="minis">
                        {data.messages.recent.map((m) => (
                            <button key={m.id} className="mini" onClick={() => go('inbox', m.id)}>
                                <span className="mini-name">
                                    <Led on={!m.read_at} color="orange" />
                                    {m.name}
                                </span>
                                <span>{m.company || 'No company'} · {ago(m.created_at)}</span>
                                <p>{m.message}</p>
                            </button>
                        ))}
                    </div>
                </section>
            )}
        </div>
    );
}
