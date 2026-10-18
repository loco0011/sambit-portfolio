let csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
let onUnauthorized = () => {};
let pending = 0;
const activity = new Set();

export const setCsrf = (token) => (csrf = token);
export const onSessionLost = (fn) => (onUnauthorized = fn);

// The top loading bar subscribes to this to show while requests are in flight.
export function subscribeActivity(fn) {
    activity.add(fn);
    return () => activity.delete(fn);
}

const emit = () => activity.forEach((fn) => fn(pending > 0));

/** POSTs a FormData with upload progress (fetch can't report it), same auth and errors as api(). */
export function upload(path, form, onProgress = () => {}) {
    pending++;
    emit();
    return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', `/admin/api/${path}`);
        xhr.withCredentials = true;
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrf);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.upload.onprogress = (e) => e.lengthComputable && onProgress(e.loaded / e.total);
        xhr.onload = () => {
            let data = {};
            try {
                data = JSON.parse(xhr.responseText);
            } catch {}
            if (xhr.status === 401 || xhr.status === 419) onUnauthorized();
            if (xhr.status >= 200 && xhr.status < 300) return resolve(data);
            const message = xhr.status === 413 ? 'That file is larger than the server accepts.' : data?.errors ? Object.values(data.errors)[0]?.[0] : data?.message;
            const error = new Error(message || `Error ${xhr.status}`);
            error.status = xhr.status;
            reject(error);
        };
        xhr.onerror = () => reject(new Error('Upload failed. Check your connection and try again.'));
        xhr.onloadend = () => {
            pending--;
            emit();
        };
        xhr.send(form);
    });
}

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
