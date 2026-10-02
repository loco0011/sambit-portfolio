import { createContext, useContext } from 'react';

// toast(text, tone) — tone is 'ok' or 'warn'.
export const Toast = createContext(() => {});
export const useToast = () => useContext(Toast);

export function ago(date) {
    const s = Math.round((Date.now() - new Date(date).getTime()) / 1000);
    if (s < 60) return 'just now';
    if (s < 3600) return `${Math.floor(s / 60)}m ago`;
    if (s < 86400) return `${Math.floor(s / 3600)}h ago`;
    if (s < 86400 * 30) return `${Math.floor(s / 86400)}d ago`;
    return new Date(date).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
}

export const stamp = (date) =>
    new Date(date).toLocaleString('en-GB', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false });

export const shortDay = (day) => new Date(`${day}T00:00:00`).toLocaleDateString('en-GB', { day: 'numeric', month: 'short' });

export function bytes(n) {
    if (n < 1024) return `${n} B`;
    if (n < 1024 * 1024) return `${(n / 1024).toFixed(1)} KB`;
    return `${(n / 1024 / 1024).toFixed(2)} MB`;
}

export const humanize = (key) => {
    const s = String(key).replace(/[_-]+/g, ' ');
    return s.charAt(0).toUpperCase() + s.slice(1);
};
