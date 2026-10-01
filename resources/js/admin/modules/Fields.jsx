import { useState } from 'react';
import { humanize } from '../lib';
import { MiniToggle } from '../ui/Hardware';

// Keys whose strings are paragraphs, not one-liners.
const LONG = new Set(['headline', 'manifesto', 'summary', 'blurb', 'body', 'availability', 'points', 'highlights']);
// Lists of short tokens edited as chips.
const CHIPS = new Set(['stack', 'tags', 'items']);

const isObject = (v) => v !== null && typeof v === 'object' && !Array.isArray(v);

// An empty value with the same shape, used as the template for "add".
function blank(v) {
    if (Array.isArray(v)) return [];
    if (isObject(v)) return Object.fromEntries(Object.entries(v).map(([k, x]) => [k, blank(x)]));
    if (typeof v === 'number') return 0;
    if (typeof v === 'boolean') return false;
    return '';
}

const titleOf = (item, i) => {
    if (Array.isArray(item)) return item.join(' → ') || `#${i + 1}`;
    if (!isObject(item)) return `#${i + 1}`;
    return item.name || item.role || item.title || item.label || item.group || item.school || item.id || `Item ${i + 1}`;
};

const subtitleOf = (item) => (isObject(item) ? item.company || item.kind || item.period || item.handle || item.year || '' : '');

const move = (list, from, to) => {
    const next = [...list];
    const [x] = next.splice(from, 1);
    next.splice(to, 0, x);
    return next;
};

function Text({ name, value, onChange }) {
    const long = LONG.has(name) || value.length > 70;
    return long ? (
        <textarea className="well" rows={Math.min(8, Math.ceil(value.length / 80) + 1)} value={value} onChange={(e) => onChange(e.target.value)} />
    ) : (
        <input className="well" value={value} onChange={(e) => onChange(e.target.value)} />
    );
}

function Chips({ value, onChange }) {
    const [draft, setDraft] = useState('');
    const add = () => {
        const v = draft.trim();
        if (v) onChange([...value, v]);
        setDraft('');
    };

    return (
        <div className="chips">
            {value.map((chip, i) => (
                <span className="chip" key={i}>
                    <input
                        value={chip}
                        size={Math.max(2, chip.length)}
                        onChange={(e) => onChange(value.map((c, j) => (j === i ? e.target.value : c)))}
                        aria-label={`Item ${i + 1}`}
                    />
                    <button type="button" onClick={() => onChange(value.filter((_, j) => j !== i))} aria-label="Remove">
                        ×
                    </button>
                </span>
            ))}
            <input
                className="chip-add"
                placeholder="+ add, press Enter"
                value={draft}
                onChange={(e) => setDraft(e.target.value)}
                onKeyDown={(e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        add();
                    }
                    if (e.key === 'Backspace' && !draft && value.length) onChange(value.slice(0, -1));
                }}
                onBlur={add}
            />
        </div>
    );
}

function Lines({ name, value, onChange }) {
    return (
        <div className="slots">
            {value.map((line, i) => (
                <div className="line-item" key={i}>
                    <span className="line-num">{String(i + 1).padStart(2, '0')}</span>
                    <Text name={name} value={line} onChange={(v) => onChange(value.map((x, j) => (j === i ? v : x)))} />
                    <ItemActions i={i} list={value} onChange={onChange} />
                </div>
            ))}
            <div>
                <button type="button" className="btn btn--sm" onClick={() => onChange([...value, ''])}>
                    + Add line
                </button>
            </div>
        </div>
    );
}

function ItemActions({ i, list, onChange }) {
    return (
        <span className="row-actions" onClick={(e) => e.stopPropagation()}>
            <button type="button" className="btn btn--sm btn--icon" disabled={i === 0} onClick={() => onChange(move(list, i, i - 1))} aria-label="Move up">
                ↑
            </button>
            <button
                type="button"
                className="btn btn--sm btn--icon"
                disabled={i === list.length - 1}
                onClick={() => onChange(move(list, i, i + 1))}
                aria-label="Move down"
            >
                ↓
            </button>
            <button type="button" className="btn btn--sm btn--icon" onClick={() => onChange(list.filter((_, j) => j !== i))} aria-label="Remove">
                ×
            </button>
        </span>
    );
}

function Cards({ name, value, onChange }) {
    const [open, setOpen] = useState(null);

    return (
        <div className="slots">
            {value.map((item, i) => (
                <div className="slotcard" key={i}>
                    <div className="slotcard-head" onClick={() => setOpen(open === i ? null : i)}>
                        <span className="slotcard-idx">{String(i + 1).padStart(2, '0')}</span>
                        <span className="slotcard-title">
                            {titleOf(item, i)}
                            {subtitleOf(item) && <small>{subtitleOf(item)}</small>}
                        </span>
                        <ItemActions
                            i={i}
                            list={value}
                            onChange={(next) => {
                                setOpen(null);
                                onChange(next);
                            }}
                        />
                    </div>
                    {open === i && (
                        <div className="slotcard-body">
                            <Field name={name} value={item} onChange={(v) => onChange(value.map((x, j) => (j === i ? v : x)))} bare />
                        </div>
                    )}
                </div>
            ))}
            <div>
                <button
                    type="button"
                    className="btn btn--sm"
                    onClick={() => {
                        onChange([...value, value.length ? blank(value[0]) : '']);
                        setOpen(value.length);
                    }}
                >
                    + Add {humanize(name).replace(/s$/, '')}
                </button>
            </div>
        </div>
    );
}

/**
 * Renders an editor for any value by looking at its shape:
 * text → input/textarea, bool → switch, number → number, short lists → chips,
 * lists of text → numbered lines, lists of objects → collapsible cards, objects → groups.
 */
export default function Field({ name, value, onChange, bare = false }) {
    let control;

    if (typeof value === 'boolean') {
        control = <MiniToggle on={value} onChange={onChange} />;
    } else if (typeof value === 'number') {
        control = <input className="well" type="number" value={value} onChange={(e) => onChange(e.target.value === '' ? 0 : Number(e.target.value))} />;
    } else if (typeof value === 'string' || value === null) {
        control = <Text name={name} value={value ?? ''} onChange={onChange} />;
    } else if (Array.isArray(value)) {
        const allText = value.every((v) => typeof v === 'string');
        const allShort = allText && value.every((v) => v.length <= 40);
        if (value.some(isObject) || (value.length && value.every(Array.isArray))) {
            control = <Cards name={name} value={value} onChange={onChange} />;
        } else if (CHIPS.has(name) || (allShort && !LONG.has(name) && value.length > 0)) {
            control = <Chips value={value} onChange={onChange} />;
        } else {
            control = <Lines name={name} value={value} onChange={onChange} />;
        }
    } else if (isObject(value)) {
        const entries = Object.entries(value);
        const simple = entries.filter(([k, v]) => (typeof v === 'string' && !LONG.has(k) && v.length <= 70) || typeof v === 'number' || typeof v === 'boolean');
        const rich = entries.filter((e) => !simple.includes(e));
        const set = (k) => (v) => onChange({ ...value, [k]: v });

        const body = (
            <div className="fields">
                {simple.length > 0 && (
                    <div className="grid-2">
                        {simple.map(([k, v]) => (
                            <Field key={k} name={k} value={v} onChange={set(k)} />
                        ))}
                    </div>
                )}
                {rich.map(([k, v]) => (
                    <Field key={k} name={k} value={v} onChange={set(k)} />
                ))}
            </div>
        );

        if (bare) return body;
        return (
            <div className="group">
                <span className="field-label">{humanize(name)}</span>
                {body}
            </div>
        );
    }

    if (bare) return control;

    // Lists hold their own inputs and buttons, so they get a plain wrapper instead of a <label>.
    const Wrap = Array.isArray(value) ? 'div' : 'label';

    return (
        <Wrap className="field">
            <span className="field-label">{humanize(name)}</span>
            {control}
        </Wrap>
    );
}
