const sharp = require('sharp');
(async () => {
  const ANG = +process.argv[2], SC = +process.argv[3], TIP = [+process.argv[4], +process.argv[5]];
  const { data: d, info } = await sharp('panel-4.png').removeAlpha().raw().toBuffer({ resolveWithObject: true });
  const W = info.width, H = info.height, img = Float32Array.from(d);
  // exact outline of the broken hand (panel coords, traced from the zoomed view), padded outward a little
  const poly = [[208, 199], [228, 195], [240, 207], [263, 225], [266, 250], [258, 272], [248, 288], [186, 288], [194, 262], [199, 240], [203, 224], [202, 208]];
  const inPoly = (x, y) => { let c = false; for (let i = 0, j = poly.length - 1; i < poly.length; j = i++) { const [xi, yi] = poly[i], [xj, yj] = poly[j]; if ((yi > y) !== (yj > y) && x < (xj - xi) * (y - yi) / (yj - yi) + xi) c = !c; } return c; };
  // real chin line: quadratic through the visible jaw on both sides of the hand
  const Q = [[184, 220], [222, 238], [262, 208]];
  const chinY = (x) => { let best = 1e9, by = 0; for (let t = 0; t <= 1; t += .005) { const bx = (1 - t) ** 2 * Q[0][0] + 2 * (1 - t) * t * Q[1][0] + t * t * Q[2][0]; if (Math.abs(bx - x) < best) { best = Math.abs(bx - x); by = (1 - t) ** 2 * Q[0][1] + 2 * (1 - t) * t * Q[1][1] + t * t * Q[2][1]; } } return by; };
  const CHIN = new Float32Array(W); for (let x = 0; x < W; x++) CHIN[x] = x < Q[0][0] || x > Q[2][0] ? -1 : chinY(x);
  const face = (x, y) => CHIN[x] >= 0 && y < CHIN[x];
  const m = new Uint8Array(W * H);
  for (let y = 190; y < 292; y++) for (let x = 180; x < 272; x++) if (inPoly(x, y)) m[y * W + x] = 1;
  // old fingertip mark just left of the mouth
  for (let y = 189; y < 201; y++) for (let x = 210; x < 225; x++) m[y * W + x] = 1;
  // any stray skin-coloured bits of the old hand left below the chin
  const below = (x, y) => x >= 188 && x <= 258 && y > CHIN[x] + 3;
  for (let y = 205; y < 292; y++) for (let x = 188; x <= 258; x++) { const p = y * W + x; if (below(x, y) && d[p * 3] > 110 && d[p * 3] - d[p * 3 + 2] > 40) m[p] = 1; }
  { const g = Uint8Array.from(m); for (let y = 205; y < 292; y++) for (let x = 188; x <= 258; x++) { const p = y * W + x; if (below(x, y) && !g[p] && (g[p - 1] || g[p + 1] || g[p - W] || g[p + W])) m[p] = 1; } }
  // skin tone sampled from the cheeks either side
  const S = [0, 0, 0]; let n = 0;
  for (let y = 200; y < 214; y++) for (const x of [186, 189, 192, 250, 253, 256]) { const p = y * W + x; for (let c = 0; c < 3; c++) S[c] += d[p * 3 + c]; n++; }
  S.forEach((v, i) => { S[i] = v / n; });
  for (let p = 0; p < W * H; p++) if (m[p]) { const x = p % W, y = (p / W) | 0, v = face(x, y) ? S : [22, 22, 27]; for (let c = 0; c < 3; c++) img[p * 3 + c] = v[c]; }
  // smooth each side separately so skin never bleeds into the hoodie
  for (let it = 0; it < 300; it++) for (let p = W; p < W * H - W; p++) {
    if (!m[p]) continue;
    const f = face(p % W, (p / W) | 0);
    for (let c = 0; c < 3; c++) {
      let s = 0, k = 0;
      for (const q of [p - 1, p + 1, p - W, p + W]) {
        if (face(q % W, (q / W) | 0) !== f) continue;
        if (!m[q] && f && Math.abs(d[q * 3] - S[0]) > 60) continue; // skip outline pixels when sampling skin
        if (!m[q] && !f && d[q * 3] > 70) continue; // hoodie side: never pull in skin-coloured pixels
        s += img[q * 3 + c]; k++;
      }
      if (k) img[p * 3 + c] = s / k;
    }
  }
  const filled = Buffer.from(Uint8Array.from(img, (v) => Math.max(0, Math.min(255, Math.round(v)))));
  const chinSvg = Buffer.from(`<svg xmlns="http://www.w3.org/2000/svg" width="${W}" height="${H}"><path d="M${Q[0]} Q${Q[1]} ${Q[2]}" fill="none" stroke="#4a2a14" stroke-width="2.4" stroke-linecap="round" opacity=".9"/></svg>`);
  const gh = await sharp('goodhand.png').metadata();
  const w = Math.round(gh.width * SC), h = Math.round(gh.height * SC);
  const rot = await sharp(await sharp('goodhand.png').resize(w, h).png().toBuffer()).rotate(ANG, { background: { r: 0, g: 0, b: 0, alpha: 0 } }).png().toBuffer();
  const rm = await sharp(rot).metadata();
  const tip = [132 * SC - w / 2, 31 * SC - h / 2], a = ANG * Math.PI / 180;
  const tr = [tip[0] * Math.cos(a) - tip[1] * Math.sin(a) + rm.width / 2, tip[0] * Math.sin(a) + tip[1] * Math.cos(a) + rm.height / 2];
  const left = Math.round(TIP[0] - tr[0]), top = Math.round(TIP[1] - tr[1]);
  const shadow = await sharp(rot).ensureAlpha().linear([0, 0, 0, .5], [0, 0, 0, 0]).blur(3).png().toBuffer();
  const laptop = await sharp('panel-4.png').removeAlpha().extract({ left: 100, top: 288, width: 220, height: H - 288 }).png().toBuffer();
  const out = await sharp(filled, { raw: { width: W, height: H, channels: 3 } })
    .composite([{ input: chinSvg }, ...(process.argv[6] ? [{ input: process.argv[6] }] : []), { input: shadow, left: left + 2, top: top + 4 }, { input: rot, left, top }, { input: laptop, left: 100, top: 288 }]).png().toBuffer();
  await sharp(out).toFile('panel-4-fixed.png');
  const cut = (b) => sharp(b).extract({ left: 110, top: 60, width: 240, height: 260 }).resize(480, 520).png().toBuffer();
  const orig = await sharp('panel-4.png').removeAlpha().png().toBuffer();
  await sharp({ create: { width: 980, height: 520, channels: 3, background: '#303030' } })
    .composite([{ input: await cut(orig), left: 0, top: 0 }, { input: await cut(out), left: 500, top: 0 }]).png().toFile('hand-fixed-prev.png');
  console.log('ok', left, top);
})();
