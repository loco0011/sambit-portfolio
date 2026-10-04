import { useEffect, useMemo, useState } from 'react';
import { api } from '../api';
import { ago, stamp, useToast } from '../lib';
import { ConfirmDialog, Icon, Loading, PageHeader, Segmented } from '../ui/Kit';

const replyLink = (m) => {
    const quoted = m.message
        .split('\n')
        .map((l) => `> ${l}`)
        .join('\n');
    const body = `Hi ${m.name.split(' ')[0]},\n\n\n\n—\nSambit\n\n${quoted}`;
    return `mailto:${m.email}?subject=${encodeURIComponent('Re: your message')}&body=${encodeURIComponent(body)}`;
};

export default function Inbox({ focusId, onUnread }) {
    const toast = useToast();
    const [messages, setMessages] = useState(null);
    const [filter, setFilter] = useState('All');
    const [selected, setSelected] = useState(focusId ?? null);
    const [confirming, setConfirming] = useState(false);
    const [deleting, setDeleting] = useState(false);

    useEffect(() => {
        api('messages')
            .then((list) => {
                setMessages(list);
                setSelected((id) => id ?? list[0]?.id ?? null);
            })
            .catch((e) => toast(e.message, 'warn'));
    }, []);

    const unread = messages?.filter((m) => !m.read_at).length ?? 0;
    useEffect(() => {
        if (messages) onUnread(unread);
    }, [unread]);

    const current = messages?.find((m) => m.id === selected);
    const visible = useMemo(() => (messages ?? []).filter((m) => filter === 'All' || !m.read_at), [messages, filter]);

    const setRead = async (m, read) => {
        setMessages((list) => list.map((x) => (x.id === m.id ? { ...x, read_at: read ? new Date().toISOString() : null } : x)));
        try {
            await api(`messages/${m.id}`, { method: 'PATCH', body: { read } });
        } catch (e) {
            toast(e.message, 'warn');
        }
    };

    // Opening an unread message marks it read after a moment.
    useEffect(() => {
        if (!current || current.read_at) return;
        const t = setTimeout(() => setRead(current, true), 900);
        return () => clearTimeout(t);
    }, [current?.id]);

    const remove = async () => {
        setDeleting(true);
        try {
            await api(`messages/${current.id}`, { method: 'DELETE' });
            const index = visible.findIndex((m) => m.id === current.id);
            const next = visible[index + 1] ?? visible[index - 1];
            setMessages((list) => list.filter((m) => m.id !== current.id));
            setSelected(next?.id ?? null);
            toast('Message deleted');
        } catch (e) {
            toast(e.message, 'warn');
        } finally {
            setDeleting(false);
            setConfirming(false);
        }
    };

    const header = (
        <PageHeader
            title="Inbox"
            description="Messages sent through your contact form."
            actions={
                messages && (
                    <Segmented
                        label="Filter"
                        options={['All', 'Unread']}
                        value={filter}
                        onChange={setFilter}
                        format={(v) => `${v} ${v === 'All' ? messages.length : unread}`}
                    />
                )
            }
        />
    );

    if (!messages) {
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
            <div className="inbox card">
                <ul className="list inbox-list">
                    {visible.map((m) => (
                        <li key={m.id}>
                            <button className={`list-item ${m.id === selected ? 'is-active' : ''}`} onClick={() => setSelected(m.id)}>
                                <span className={`unread-dot ${m.read_at ? 'is-read' : ''}`} />
                                <span className="list-main">
                                    <span className="list-title">{m.name}</span>
                                    <span className="list-snippet">{m.message}</span>
                                </span>
                                <span className="list-meta">{ago(m.created_at)}</span>
                            </button>
                        </li>
                    ))}
                    {!visible.length && <li className="empty">{filter === 'Unread' ? 'You’re all caught up.' : 'No messages yet.'}</li>}
                </ul>

                <article className="message">
                    {current ? (
                        <>
                            <header className="message-head">
                                <div>
                                    <h2>{current.name}</h2>
                                    <a href={`mailto:${current.email}`}>{current.email}</a>
                                </div>
                                <time dateTime={current.created_at}>{stamp(current.created_at)}</time>
                            </header>
                            <dl className="meta">
                                <div>
                                    <dt>Company / role</dt>
                                    <dd>{current.company || '—'}</dd>
                                </div>
                                {Object.entries(current.details ?? {}).map(([label, value]) => (
                                    <div key={label}>
                                        <dt>{label}</dt>
                                        <dd>{value}</dd>
                                    </div>
                                ))}
                            </dl>
                            <div className="message-body">{current.message}</div>
                            <div className="row">
                                <a className="btn btn-primary" href={replyLink(current)}>
                                    <Icon name="reply" />
                                    Reply
                                </a>
                                <button className="btn" onClick={() => setRead(current, !current.read_at)}>
                                    <Icon name="mail" />
                                    Mark as {current.read_at ? 'unread' : 'read'}
                                </button>
                                <button className="btn btn-ghost btn-danger-text" onClick={() => setConfirming(true)}>
                                    <Icon name="trash" />
                                    Delete
                                </button>
                            </div>
                        </>
                    ) : (
                        <div className="empty">Select a message to read it.</div>
                    )}
                </article>
            </div>

            <ConfirmDialog
                open={confirming}
                title="Delete this message?"
                confirmLabel="Delete"
                danger
                busy={deleting}
                onConfirm={remove}
                onCancel={() => setConfirming(false)}
            >
                The message from {current?.name} will be permanently deleted.
            </ConfirmDialog>
        </>
    );
}
