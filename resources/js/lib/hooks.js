import { useEffect, useState } from 'react';

/** Live wall-clock time in a given IANA timezone, e.g. "21:42:07". */
export function useLocalTime(timeZone, withSeconds = false) {
    const format = () =>
        new Intl.DateTimeFormat('en-GB', {
            timeZone,
            hour: '2-digit',
            minute: '2-digit',
            ...(withSeconds && { second: '2-digit' }),
            hour12: false,
        }).format(new Date());

    const [time, setTime] = useState(format);

    useEffect(() => {
        const id = setInterval(() => setTime(format()), 1000);
        return () => clearInterval(id);
    }, [timeZone]);

    return time;
}

/** Pointer handler that feeds --mx / --my to a `.spotlight` card. */
export function spotlight(e) {
    const r = e.currentTarget.getBoundingClientRect();
    e.currentTarget.style.setProperty('--mx', `${e.clientX - r.left}px`);
    e.currentTarget.style.setProperty('--my', `${e.clientY - r.top}px`);
}

export function useIsTouch() {
    const [touch, setTouch] = useState(false);
    useEffect(() => setTouch(window.matchMedia('(hover: none)').matches), []);
    return touch;
}

export async function copyText(text) {
    try {
        await navigator.clipboard.writeText(text);
        return true;
    } catch {
        return false;
    }
}

/** Reactive CSS media query match. */
export function useMediaQuery(query) {
    const get = () => typeof window !== 'undefined' && window.matchMedia(query).matches;
    const [match, setMatch] = useState(get);

    useEffect(() => {
        const mq = window.matchMedia(query);
        const on = () => setMatch(mq.matches);
        on();
        mq.addEventListener('change', on);
        return () => mq.removeEventListener('change', on);
    }, [query]);

    return match;
}
