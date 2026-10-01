// Adds a small "anchor" beard (thin moustache + strip under the lip + short beard along the chin) to each pose.
// Usage: node beard.js <outDir>. Needs panel-1..6.png crops (pose 4 uses panel-4-orig.png + fix-pose4-hand via hand3.js).
const sharp = require('sharp');
const { execFileSync } = require('child_process');
// per pose: mouth-line centre on the face midline, head tilt (deg), scale (ear distance / 165)
const P = {
  1: { c: [238, 205], t: 6.8, k: 168 / 165 },
  2: { c: [231, 207], t: 15.3, k: 151 / 165 },
  3: { c: [210, 204], t: -12.2, k: 170 / 165, tongue: true },
  4: { c: [227, 191], t: -11.2, k: 169 / 165 },
  5: { c: [240, 193], t: 9.3, k: 185 / 165 },
  6: { c: [165, 190], t: -10.4, k: 149 / 165 },
};
const HAIR = '#23170f';
function beardSvg(W, H, { c, t, k, tongue }) {
  const base = tongue
    ? `<path d="M-26 13 Q-19 25 -9 27 L-9 21 Q-16 20 -26 13 Z"/><path d="M26 13 Q19 25 9 27 L9 21 Q16 20 26 13 Z"/>`
    : `<path d="M-26 13 Q0 40 26 13 Q14 22 0 22 Q-14 22 -26 13 Z"/>`;
  const strip = tongue ? '' : `<path d="M-3.8 8 L3.8 8 L5 23 L-5 23 Z"/>`;
  return Buffer.from(`<svg xmlns="http://www.w3.org/2000/svg" width="${W}" height="${H}">
  <defs>
    <filter id="f" x="-20%" y="-20%" width="140%" height="140%">
      <feTurbulence type="fractalNoise" baseFrequency="1.4" numOctaves="2" seed="7" result="n"/>
      <feColorMatrix in="n" type="matrix" values="0 0 0 0 0  0 0 0 0 0  0 0 0 0 0  0 0 0 -.35 1.12" result="grain"/>
      <feComposite in="SourceGraphic" in2="grain" operator="in" result="tex"/>
      <feGaussianBlur in="tex" stdDeviation=".45"/>
    </filter>
  </defs>
  <g transform="translate(${c[0]} ${c[1]}) rotate(${t}) scale(${k})" fill="${HAIR}" filter="url(#f)" opacity=".94">
    <path d="M-2.2 -6.5 Q-13 -13.5 -25 -5 Q-22 -1.8 -18 -3 Q-11 -6 -2.2 -2.2 Z"/>
    <path d="M2.2 -6.5 Q13 -13.5 25 -5 Q22 -1.8 18 -3 Q11 -6 2.2 -2.2 Z"/>
    ${strip}${base}
  </g></svg>`);
}
// keep hair off things that sit in front of the chin: noodles, tongue
const inFront = (r, g, b) => (r > 185 && g > 158 && b < 120) || (r > 170 && g < 110 && b > 90);
async function beardLayer(n, file) {
  const { data: d, info } = await sharp(file).removeAlpha().raw().toBuffer({ resolveWithObject: true });
  const W = info.width, H = info.height;
  const layer = await sharp(beardSvg(W, H, P[n])).ensureAlpha().raw().toBuffer();
  for (let i = 0; i < W * H; i++) if (inFront(d[i * 3], d[i * 3 + 1], d[i * 3 + 2])) layer[i * 4 + 3] = 0;
  return sharp(layer, { raw: { width: W, height: H, channels: 4 } }).png().toBuffer();
}
(async () => {
  const out = process.argv[2];
  const bg = [11, 12, 16], pg = [7, 7, 10];
  const ka = bg.map((v, i) => (255 - pg[i]) / (255 - v)), kb = bg.map((v, i) => pg[i] - v * ka[i]);
  const tiles = [];
  for (let n = 1; n <= 6; n++) {
    let img;
    if (n === 4) {
      // beard goes under the repaired hand: rebuild pose 4 with the beard layer injected
      require('fs').writeFileSync('beard-4.png', await beardLayer(4, 'panel-4-orig.png'));
      execFileSync('node', ['hand3.js', '-68', '.8', '232', '222', 'beard-4.png'], { stdio: 'inherit' });
      img = await sharp('panel-4-fixed.png').removeAlpha().png().toBuffer();
    } else {
      img = await sharp(`panel-${n}.png`).removeAlpha().composite([{ input: await beardLayer(n, `panel-${n}.png`) }]).png().toBuffer();
    }
    await sharp(img).toFile(`beard-${n}.png`);
    await sharp(img).linear(ka, kb).webp({ quality: 90 }).toFile(`${out}/pose-${n}.webp`);
    const [cx, cy] = P[n].c;
    tiles.push({ input: await sharp(img).extract({ left: Math.max(0, cx - 110), top: Math.max(0, cy - 150), width: 220, height: 220 }).resize(330, 330).toBuffer(), left: ((n - 1) % 3) * 330, top: Math.floor((n - 1) / 3) * 330 });
  }
  await sharp({ create: { width: 990, height: 660, channels: 3, background: '#000' } }).composite(tiles).png().toFile('beard-check.png');
  console.log('ok');
})();
