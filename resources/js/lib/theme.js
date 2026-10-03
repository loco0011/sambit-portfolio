import { useSyncExternalStore } from 'react';

const KEY = 'sm:theme';
const META = { dark: '#07070a', light: '#f3f3f0' };

/** RGB triplets for colours JS builds itself (canvas, motion-animated values), mirroring app.css. */
export const PALETTE = {
    dark: { fg: [237, 237, 239], acid: [212, 255, 79], lime: [212, 255, 79], tint: [255, 255, 255], bad: [255, 84, 84], bad2: [255, 120, 120], dots: 0.26 },
    light: { fg: [13, 13, 17], acid: [67, 115, 0], lime: [204, 245, 69], tint: [13, 13, 17], bad: [217, 45, 45], bad2: [196, 39, 39], dots: 0.17 },
};

export const rgba = ([r, g, b], a = 1) => `rgba(${r},${g},${b},${a})`;

const listeners = new Set();

export const getTheme = () => (document.documentElement.dataset.theme === 'light' ? 'light' : 'dark');

export function setTheme(theme) {
    document.documentElement.dataset.theme = theme;
    document.querySelector('meta[name=theme-color]')?.setAttribute('content', META[theme]);
    try {
        localStorage.setItem(KEY, theme);
    } catch {}
    listeners.forEach((fn) => fn());
}

export const toggleTheme = () => setTheme(getTheme() === 'light' ? 'dark' : 'light');

function subscribe(fn) {
    listeners.add(fn);
    return () => listeners.delete(fn);
}

/** Current theme ('dark' | 'light'); re-renders on change. */
export const useTheme = () => useSyncExternalStore(subscribe, getTheme);

/** Non-React subscription, for animation loops. */
export const onThemeChange = subscribe;
