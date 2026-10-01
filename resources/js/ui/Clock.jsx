import { useLocalTime } from '../lib/hooks';

/** Isolated so its per-second tick re-renders only this text node. */
export default function Clock({ timeZone, seconds = false, className = '' }) {
    const time = useLocalTime(timeZone, seconds);
    return <span className={`tabular-nums ${className}`}>{time}</span>;
}
