import { createContext, useContext } from 'react';

export const Ticker = createContext(() => {});
export const useTicker = () => useContext(Ticker);

export function ago(date) {
    const s = Math.round((Date.now() - new Date(date).getTime()) / 1000);
    if (s < 60) return 'just now';
    if (s < 3600) return `${Math.floor(s / 60)}m ago`;
    if (s < 86400) return `${Math.floor(s / 3600)}h ago`;
    if (s < 86400 * 30) return `${Math.floor(s / 86400)}d ago`;
    return new Date(date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}

export const stamp = (date) =>
    new Date(date)
        .toLocaleString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false })
        .toUpperCase();

export const shortDay = (day) => new Date(`${day}T00:00:00`).toLocaleDateString('en-GB', { day: '2-digit', month: 'short' }).toUpperCase();

export function bytes(n) {
    if (n < 1024) return `${n} B`;
    if (n < 1024 * 1024) return `${(n / 1024).toFixed(1)} KB`;
    return `${(n / 1024 / 1024).toFixed(2)} MB`;
}

export const pad = (n, size = 4) => String(n).padStart(size, '0');

export const humanize = (key) => String(key).replace(/[_-]+/g, ' ');

// A barcode that is unique per message: bar widths derived from the id.
export function barcode(seed) {
    let x = Number(seed) * 9301 + 49297;
    const stops = [];
    let pos = 0;
    while (pos < 100) {
        x = (x * 9301 + 49297) % 233280;
        const w = 0.6 + (x / 233280) * 2.4;
        const dark = stops.length % 2 === 0;
        stops.push(`${dark ? '#2a2824' : 'transparent'} ${pos.toFixed(2)}% ${(pos + w).toFixed(2)}%`);
        pos += w;
    }
    return `linear-gradient(90deg, ${stops.join(', ')})`;
}
