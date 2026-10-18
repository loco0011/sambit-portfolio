import qrcode from 'qrcode-generator';

// App pages (/apps/{slug}): show the download that fits the visitor's device.
// Android gets the APK, iPhone gets "Android only", a computer gets a QR code to open the page on a phone.
const ua = navigator.userAgent;
const device = /Android/i.test(ua)
    ? 'android'
    : /iPhone|iPad|iPod/i.test(ua) || (/Macintosh/.test(ua) && navigator.maxTouchPoints > 1)
      ? 'iphone'
      : 'desktop';

document.querySelectorAll('[data-device]').forEach((el) => (el.hidden = el.dataset.device !== device));

document.querySelectorAll('[data-qr]').forEach((el) => {
    const q = qrcode(0, 'M');
    q.addData(el.dataset.qr);
    q.make();
    el.innerHTML = q.createSvgTag({ cellSize: 4, margin: 0, scalable: true });
});

// Spotlight border on the feature cards, as on the home page.
document.querySelectorAll('.ap-card').forEach((card) =>
    card.addEventListener('pointermove', (e) => {
        const r = card.getBoundingClientRect();
        card.style.setProperty('--mx', `${e.clientX - r.left}px`);
        card.style.setProperty('--my', `${e.clientY - r.top}px`);
    }),
);

document.getElementById('theme-toggle')?.addEventListener('click', () => {
    const t = document.documentElement.dataset.theme === 'light' ? 'dark' : 'light';
    document.documentElement.dataset.theme = t;
    document.querySelector('meta[name=theme-color]').content = t === 'light' ? '#f1f4ea' : '#07070a';
    try {
        localStorage.setItem('sm:theme', t);
    } catch (e) {}
});
