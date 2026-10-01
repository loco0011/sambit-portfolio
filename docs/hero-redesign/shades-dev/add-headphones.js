// Fits headphones to each pose's real head outline.
// Usage: node phones2.js <outDir> [debug]
const sharp = require('sharp');
const SRC = { 1: 'panel-1.png', 2: 'panel-2.png', 3: 'panel-3.png', 4: 'panel-4-fixed.png', 5: 'panel-5.png', 6: 'panel-6.png' };
// head x-range and the row where the glasses start (measured)
const G = { 1: [175, 322, 152], 2: [180, 311, 170], 3: [117, 265, 162], 4: [140, 288, 151], 5: [179, 337, 150], 6: [83, 217, 150] };
const skin = (r, g, b) => r > 100 && r - g > 20 && g - b > 2 && r < 252;
const OL = '#07070a';

function outline(S, C, maxGap = 7) {
  // boundary radius along a ray: last skin pixel before a long non-skin gap
  return (deg) => {
    const t = deg * Math.PI / 180, dx = Math.cos(t), dy = -Math.sin(t);
    let last = 0, gap = 0, seen = false;
    for (let r = 4; r < 140; r++) {
      const x = Math.round(C[0] + dx * r), y = Math.round(C[1] + dy * r);
      if (S(x, y)) { last = r; gap = 0; seen = true; } else if (seen && ++gap > maxGap) break;
    }
    return last;
  };
}

async function fit(n) {
  const { data: d, info } = await sharp(SRC[n]).removeAlpha().raw().toBuffer({ resolveWithObject: true });
  const W = info.width, H = info.height;
  const S = (x, y) => x >= 0 && y >= 0 && x < W && y < H && skin(d[(y * W + x) * 3], d[(y * W + x) * 3 + 1], d[(y * W + x) * 3 + 2]);
  const [gx0, gx1, gy] = G[n];
  const C = [(gx0 + gx1) / 2, gy - 14];
  const R = outline(S, C);
  const at = (deg) => { const r = R(deg), t = deg * Math.PI / 180; return [C[0] + Math.cos(t) * r, C[1] - Math.sin(t) * r, r]; };
  // ears: the outline bulges out most within these angle windows (below the band, around glasses height)
  const earSearch = (from, to) => {
    let best = null;
    for (let a = from; a <= to; a += 1) { const p = at(a); if (!best || p[2] > best.r) best = { a, r: p[2], p }; }
    // angular extent of the bulge
    let a0 = best.a, a1 = best.a;
    while (a0 > from && at(a0 - 1)[2] > best.r - 7) a0--;
    while (a1 < to && at(a1 + 1)[2] > best.r - 7) a1++;
    return { ...best, a0, a1 };
  };
  const eR = earSearch(-45, 12), eL = earSearch(168, 225);
  const earPt = (e) => { const t = e.a * Math.PI / 180; return [C[0] + Math.cos(t) * (e.r - 8), C[1] - Math.sin(t) * (e.r - 8)]; };
  const EL = earPt(eL), ER = earPt(eR);
  const tilt = Math.atan2(ER[1] - EL[1], ER[0] - EL[0]) * 180 / Math.PI;
  const earH = Math.max(34, Math.min(52, ((eL.a1 - eL.a0) + (eR.a1 - eR.a0)) / 2 * Math.PI / 180 * (eL.r + eR.r) / 2 + 10));
  // band: follow the real outline over the top, from just above each ear
  // head silhouette from scans: top edge per column + outer edge per row (above the ears)
  const sil = [];
  for (let x = gx0 - 12; x <= gx1 + 12; x++) for (let y = 12; y < gy; y++) if (S(x, y) && S(x, y + 1) && S(x, y + 2) && S(x, y + 3)) { sil.push([x, y]); break; }
  const earTopY = Math.min(EL[1], ER[1]) - earH * .4;
  for (let y = 12; y < earTopY; y++) {
    for (let x = gx0 - 30; x < C[0]; x++) if (S(x, y) && S(x + 1, y) && S(x + 2, y)) { sil.push([x, y]); break; }
    for (let x = gx1 + 30; x > C[0]; x--) if (S(x, y) && S(x - 1, y) && S(x - 2, y)) { sil.push([x, y]); break; }
  }
  // outer envelope in 3° bins around the centre
  const bandFrom = eR.a1 + 6, bandTo = eL.a0 - 6, angs = [];
  for (let a = bandFrom; a <= bandTo; a += 3) angs.push(a);
  const raw = angs.map((a) => {
    let r = 0;
    for (const [x, y] of sil) { let ang = Math.atan2(C[1] - y, x - C[0]) * 180 / Math.PI; if (ang < -90) ang += 360; if (Math.abs(ang - a) <= 2) r = Math.max(r, Math.hypot(x - C[0], y - C[1])); }
    return r || R(a);
  });
  const med = raw.map((_, i) => { const w = raw.slice(Math.max(0, i - 3), i + 4).sort((x, y) => x - y); return w[w.length >> 1]; });
  const pts = angs.map((a, i) => { const t = a * Math.PI / 180, r = med[i] + 2.5; return [C[0] + Math.cos(t) * r, C[1] - Math.sin(t) * r]; });
  // light smoothing so painted bumps don't wobble the band
  const sm = pts.map((p, i) => { const q = pts.slice(Math.max(0, i - 2), i + 3); return [q.reduce((s, v) => s + v[0], 0) / q.length, q.reduce((s, v) => s + v[1], 0) / q.length]; });
  return { W, H, C, EL, ER, tilt, earH, band: sm };
}

function svgFor(f) {
  const { W, H, EL, ER, tilt, earH, band } = f;
  const path = 'M' + band.map((p) => p.map((v) => v.toFixed(1)).join(' ')).join(' L');
  const cupW = earH * .56, cupH = earH * 1.12;
  // each cup: outer shell + inner cushion ring facing the head + slider arm up to the band
  const cup = (E, side, bandEnd) => {
    const out = side * 4; // shells sit slightly outside the ear
    return `<g transform="translate(${E[0]} ${E[1]}) rotate(${tilt})">
      <path d="M${out} ${-cupH / 2 - 3} L${out} ${-cupH / 2 - 14}" stroke="${OL}" stroke-width="7" stroke-linecap="round"/>
      <path d="M${out} ${-cupH / 2 - 3} L${out} ${-cupH / 2 - 14}" stroke="#3a3a45" stroke-width="3.5" stroke-linecap="round"/>
      <rect x="${out - cupW / 2}" y="${-cupH / 2}" width="${cupW}" height="${cupH}" rx="${cupW * .46}" fill="url(#shell)" stroke="${OL}" stroke-width="2.4"/>
      <rect x="${out - cupW / 2 + (side < 0 ? cupW * .52 : 0)}" y="${-cupH / 2 + 3}" width="${cupW * .48}" height="${cupH - 6}" rx="${cupW * .24}" fill="#1b1b21" stroke="${OL}" stroke-width="1.6"/>
      <path d="M${out - side * cupW * .18} ${-cupH * .36} Q${out - side * cupW * .3} 0 ${out - side * cupW * .18} ${cupH * .36}" stroke="rgba(255,255,255,.22)" stroke-width="2" fill="none" stroke-linecap="round"/>
      <circle cx="${out + side * cupW * .12}" cy="${cupH * .26}" r="2.3" fill="#d4ff4f"/>
    </g>`;
  };
  return Buffer.from(`<svg xmlns="http://www.w3.org/2000/svg" width="${W * 2}" height="${H * 2}"><g transform="scale(2)">
    <defs>
      <linearGradient id="shell" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#34343f"/><stop offset=".55" stop-color="#16161b"/><stop offset="1" stop-color="#0b0b0e"/></linearGradient>
      <filter id="ao" x="-20%" y="-20%" width="140%" height="140%"><feGaussianBlur stdDeviation="2.4"/></filter>
    </defs>
    <path d="${path}" stroke="#000" stroke-width="13" fill="none" stroke-linecap="round" opacity=".45" filter="url(#ao)" transform="translate(0 3)"/>
    ${cup(EL, -1)}${cup(ER, 1)}
    <path d="${path}" stroke="${OL}" stroke-width="11" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
    <path d="${path}" stroke="#1d1d24" stroke-width="7.2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
    <path d="${path}" stroke="#44444f" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" transform="translate(0 -1.8)"/>
  </g></svg>`);
}

(async () => {
  const out = process.argv[2], dbg = process.argv[3];
  const bg = [11, 12, 16], pg = [7, 7, 10];
  const ka = bg.map((v, i) => (255 - pg[i]) / (255 - v)), kb = bg.map((v, i) => pg[i] - v * ka[i]);
  const tiles = [];
  for (let n = 1; n <= 6; n++) {
    const f = await fit(n);
    const up = await sharp(SRC[n]).removeAlpha().resize(f.W * 2, f.H * 2, { kernel: "lanczos3" }).sharpen({ sigma: .8, m1: .6, m2: 1.2 }).png().toBuffer();
    const img = await sharp(up).composite([{ input: svgFor(f) }]).png().toBuffer();
    if (!dbg) await sharp(img).linear(ka, kb).webp({ quality: 92, smartSubsample: true }).toFile(`${out}/pose-${n}.webp`);
    await sharp(img).toFile(`phones-${n}.png`);
    const cx = Math.round(f.C[0]), cy = Math.round(f.C[1]);
    tiles.push({ input: await sharp(img).extract({ left: Math.max(0, cx * 2 - 250), top: Math.max(0, cy * 2 - 220), width: 500, height: 440 }).resize(330, 290).toBuffer(), left: ((n - 1) % 3) * 330, top: Math.floor((n - 1) / 3) * 290 });
    console.log(n, 'tilt', f.tilt.toFixed(1), 'ear h', f.earH.toFixed(0), 'L', f.EL.map(Math.round), 'R', f.ER.map(Math.round));
  }
  await sharp({ create: { width: 990, height: 580, channels: 3, background: '#000' } }).composite(tiles).png().toFile('phones2-check.png');
})();
