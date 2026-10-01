import { useEffect, useMemo, useState } from 'react';
import { api } from '../api';
import { ago, pad, useTicker } from '../lib';
import { HoldButton, Led, ModLabel } from '../ui/Hardware';
import Receipt from '../ui/Receipt';

const replyLink = (m) => {
    const quoted = m.message
        .split('\n')
        .map((l) => `> ${l}`)
        .join('\n');
    const body = `Hi ${m.name.split(' ')[0]},\n\n\n\n—\nSambit\n\n${quoted}`;
    return `mailto:${m.email}?subject=${encodeURIComponent('Re: your message')}&body=${encodeURIComponent(body)}`;
};

export default function Inbox({ focusId, onUnread }) {
    const ticker = useTicker();
    const [messages, setMessages] = useState(null);
    const [filter, setFilter] = useState('all');
    const [selected, setSelected] = useState(focusId ?? null);
    const [shredding, setShredding] = useState(false);

    useEffect(() => {
        api('messages')
            .then((list) => {
                setMessages(list);
                setSelected((id) => id ?? list[0]?.id ?? null);
            })
            .catch((e) => ticker(e.message, 'warn'));
    }, []);

    const unread = messages?.filter((m) => !m.read_at).length ?? 0;
    useEffect(() => {
        if (messages) onUnread(unread);
    }, [unread]);

    const current = messages?.find((m) => m.id === selected);
    const visible = useMemo(() => (messages ?? []).filter((m) => filter === 'all' || !m.read_at), [messages, filter]);

    const setRead = async (m, read) => {
        setMessages((list) => list.map((x) => (x.id === m.id ? { ...x, read_at: read ? new Date().toISOString() : null } : x)));
        try {
            await api(`messages/${m.id}`, { method: 'PATCH', body: { read } });
        } catch (e) {
            ticker(e.message, 'warn');
        }
    };

    // Opening an unread message marks it read after a beat, like actually reading it.
    useEffect(() => {
        if (!current || current.read_at) return;
        const t = setTimeout(() => setRead(current, true), 900);
        return () => clearTimeout(t);
    }, [current?.id]);

    const shred = async () => {
        try {
            await api(`messages/${current.id}`, { method: 'DELETE' });
            setShredding(true);
        } catch (e) {
            ticker(e.message, 'warn');
        }
    };

    const afterShred = () => {
        const index = visible.findIndex((m) => m.id === current.id);
        const next = visible[index + 1] ?? visible[index - 1];
        ticker(`Transmission ${pad(current.id)} shredded`);
        setMessages((list) => list.filter((m) => m.id !== current.id));
        setSelected(next?.id ?? null);
        setShredding(false);
    };

    if (!messages) {
        return <div className="empty">Reading the queue…</div>;
    }

    return (
        <div className="inbox">
            <section className="mod">
                <ModLabel
                    index="B1"
                    aside={
                        <span className="filters">
                            <button className={`btn btn--sm ${filter === 'all' ? 'btn--dark' : ''}`} onClick={() => setFilter('all')}>
                                All {messages.length}
                            </button>
                            <button className={`btn btn--sm ${filter === 'unread' ? 'btn--dark' : ''}`} onClick={() => setFilter('unread')}>
                                New {unread}
                            </button>
                        </span>
                    }
                >
                    Queue
                </ModLabel>
                <div className="queue-list">
                    {visible.map((m) => (
                        <button
                            key={m.id}
                            className={`ticket ${m.id === selected ? 'ticket--active' : ''} ${m.read_at ? 'ticket--read' : ''}`}
                            onClick={() => setSelected(m.id)}
                        >
                            <Led on={!m.read_at} color="orange" />
                            <span className="ticket-name">{m.name}</span>
                            <span className="ticket-time">{ago(m.created_at)}</span>
                            <span className="ticket-snip">{m.message}</span>
                        </button>
                    ))}
                    {!visible.length && <div className="empty">{filter === 'unread' ? 'All caught up' : 'No transmissions yet'}</div>}
                </div>
            </section>

            <section className="mod printer">
                <div className="printer-body">
                    <div className="printer-top">
                        <span>SM-01 THERMAL</span>
                        <span style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
                            {current ? 'PRINTING' : 'IDLE'} <Led on color={current ? 'green' : 'yellow'} blink={shredding} />
                        </span>
                    </div>
                    <div className="slot" />
                </div>
                <div className="paper-out">
                    {current ? (
                        <Receipt key={current.id} message={current} shredding={shredding} onShredded={afterShred} />
                    ) : (
                        <div className="empty">Paper tray empty</div>
                    )}
                </div>
                {current && !shredding && (
                    <div className="printer-actions">
                        <a className="btn btn--blue" href={replyLink(current)}>
                            ↩ Reply
                        </a>
                        <button className="btn" onClick={() => setRead(current, !current.read_at)}>
                            Mark {current.read_at ? 'unread' : 'read'}
                        </button>
                        <HoldButton className="btn--orange" onConfirm={shred}>
                            Hold to shred
                        </HoldButton>
                    </div>
                )}
            </section>
        </div>
    );
}
