let csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
let onUnauthorized = () => {};
let pending = 0;
const activity = new Set();

export const setCsrf = (token) => (csrf = token);
export const onSessionLost = (fn) => (onUnauthorized = fn);

// The NET LED subscribes to this to flicker while requests are in flight.
export function subscribeActivity(fn) {
    activity.add(fn);
    return () => activity.delete(fn);
}

const emit = () => activity.forEach((fn) => fn(pending > 0));

export async function api(path, { method = 'GET', body } = {}) {
    const isForm = body instanceof FormData;
    pending++;
    emit();

    try {
        const res = await fetch(`/admin/api/${path}`, {
            method,
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
                ...(body && !isForm ? { 'Content-Type': 'application/json' } : {}),
            },
            body: body ? (isForm ? body : JSON.stringify(body)) : undefined,
        });

        const data = await res.json().catch(() => ({}));

        // 401 = logged out, 419 = session/CSRF expired. Either way: back to the lock screen.
        if (res.status === 401 || res.status === 419) {
            onUnauthorized();
        }

        if (!res.ok) {
            const message = data?.errors ? Object.values(data.errors)[0]?.[0] : data?.message;
            const error = new Error(message || `Error ${res.status}`);
            error.status = res.status;
            throw error;
        }

        return data;
    } finally {
        pending--;
        emit();
    }
}
