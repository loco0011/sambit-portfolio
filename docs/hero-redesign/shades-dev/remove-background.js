// Removes the painted background from each final pose (phones-N.png, 2x) → cut-N.png with real transparency.
const sharp = require('sharp');

function boxBlur(src, W, H, r) {
  // separable box blur, 3 passes ≈ gaussian
  let a = Float32Array.from(src), b = new Float32Array(src.length);
  for (let pass = 0; pass < 3; pass++) {
    for (let y = 0; y < H; y++) { let s = 0; for (let x = -r; x <= r; x++) s += a[y * W + Math.min(W - 1, Math.max(0, x))]; for (let x = 0; x < W; x++) { b[y * W + x] = s / (2 * r + 1); s += a[y * W + Math.min(W - 1, x + r + 1)] - a[y * W + Math.max(0, x - r)]; } }
    for (let x = 0; x < W; x++) { let s = 0; for (let y = -r; y <= r; y++) s += b[Math.min(H - 1, Math.max(0, y)) * W + x]; for (let y = 0; y < H; y++) { a[y * W + x] = s / (2 * r + 1); s += b[Math.min(H - 1, y + r + 1) * W + x] - b[Math.max(0, y - r) * W + x]; } }
  }
  return a;
}
const morph = (m, W, H, r, grow) => { const o = new Uint8Array(m.length); for (let y = 0; y < H; y++) for (let x = 0; x < W; x++) { let v = grow ? 0 : 1; for (let dy = -r; dy <= r && (grow ? !v : v); dy++) for (let dx = -r; dx <= r; dx++) { const X = x + dx, Y = y + dy; const on = X >= 0 && Y >= 0 && X < W && Y < H ? m[Y * W + X] : 0; if (grow && on) { v = 1; break; } if (!grow && !on) { v = 0; break; } } o[y * W + x] = v; } return o; };

(async () => {
  const tiles = [];
  for (let n = 1; n <= 6; n++) {
    const { data: d, info } = await sharp(`phones-${n}.png`).removeAlpha().raw().toBuffer({ resolveWithObject: true });
    const W = info.width, H = info.height, N = W * H;
    // 1) background model: smooth field from pixels that look like background (dark, low saturation)
    const cand = new Float32Array(N), ch = [0, 1, 2].map(() => new Float32Array(N));
    for (let i = 0; i < N; i++) {
      const r = d[i * 3], g = d[i * 3 + 1], b = d[i * 3 + 2], mx = Math.max(r, g, b), mn = Math.min(r, g, b);
      // plain background, or the green glow behind his head (green-leaning; the hoodie leans blue)
      const plain = mx >= 8 && mx <= 22 && mx - mn <= 8, glow = mx <= 36 && mx - mn <= 14 && g >= b - 1 && g >= r - 2;
      if (plain || glow) { cand[i] = 1; ch[0][i] = r; ch[1][i] = g; ch[2][i] = b; }
    }
    const wB = boxBlur(cand, W, H, 16), cB = ch.map((c) => boxBlur(c, W, H, 16));
    const bg = (i, c) => (wB[i] > .02 ? cB[c][i] / wB[i] : [12, 12, 16][c]);
    // 2) difference from the background
    const D = new Float32Array(N);
    for (let i = 0; i < N; i++) D[i] = Math.abs(d[i * 3] - bg(i, 0)) + Math.abs(d[i * 3 + 1] - bg(i, 1)) + Math.abs(d[i * 3 + 2] - bg(i, 2));
    // 3) hard mask, closed, holes filled, specks removed
    let M = new Uint8Array(N); for (let i = 0; i < N; i++) M[i] = D[i] > 13 ? 1 : 0;
    M = morph(morph(M, W, H, 3, true), W, H, 3, false);
    const outside = new Uint8Array(N), st = [];
    for (let x = 0; x < W; x++) { st.push(x, (H - 1) * W + x); } for (let y = 0; y < H; y++) { st.push(y * W, y * W + W - 1); }
    while (st.length) { const p = st.pop(); if (outside[p] || M[p]) continue; outside[p] = 1; const x = p % W; if (x > 0) st.push(p - 1); if (x < W - 1) st.push(p + 1); if (p >= W) st.push(p - W); if (p < N - W) st.push(p + W); }
    for (let i = 0; i < N; i++) if (!outside[i]) M[i] = 1; // enclosed gaps belong to him
    const lab = new Int32Array(N); let id = 0; const size = [0];
    for (let s = 0; s < N; s++) { if (!M[s] || lab[s]) continue; id++; let c = 0; const q = [s]; lab[s] = id; while (q.length) { const p = q.pop(); c++; const x = p % W; for (const t of [x > 0 ? p - 1 : -1, x < W - 1 ? p + 1 : -1, p - W, p + W]) if (t >= 0 && t < N && M[t] && !lab[t]) { lab[t] = id; q.push(t); } } size.push(c); }
    for (let i = 0; i < N; i++) if (M[i] && size[lab[i]] < 600) M[i] = 0;
    // 4) soft edge: inside = eroded mask; edge ramp from the colour difference
    const inner = morph(M, W, H, 1, false), ring = morph(M, W, H, 2, true);
    const A = new Float32Array(N);
    for (let i = 0; i < N; i++) A[i] = inner[i] ? 1 : ring[i] ? Math.max(0, Math.min(1, (D[i] - 5) / 14)) : 0;
    const As = boxBlur(A, W, H, 1);
    const rgba = Buffer.alloc(N * 4);
    for (let i = 0; i < N; i++) { rgba[i * 4] = d[i * 3]; rgba[i * 4 + 1] = d[i * 3 + 1]; rgba[i * 4 + 2] = d[i * 3 + 2]; rgba[i * 4 + 3] = Math.round(Math.min(A[i], As[i] * 1.05 + (inner[i] ? 1 : 0)) * 255); }
    await sharp(rgba, { raw: { width: W, height: H, channels: 4 } }).png().toFile(`cut-${n}.png`);
    // preview on a checkerboard so any leftover background stands out
    const chk = Buffer.from(`<svg xmlns="http://www.w3.org/2000/svg" width="${W}" height="${H}"><defs><pattern id="c" width="32" height="32" patternUnits="userSpaceOnUse"><rect width="32" height="32" fill="#e8e8ea"/><rect width="16" height="16" fill="#c4c4c8"/><rect x="16" y="16" width="16" height="16" fill="#c4c4c8"/></pattern></defs><rect width="100%" height="100%" fill="url(#c)"/></svg>`);
    const prev = await sharp(chk).composite([{ input: `cut-${n}.png` }]).png().toBuffer();
    tiles.push({ input: await sharp(prev).resize(460, 462).toBuffer(), left: ((n - 1) % 3) * 460, top: Math.floor((n - 1) / 3) * 462 });
    console.log('pose', n, 'done');
  }
  await sharp({ create: { width: 1380, height: 924, channels: 3, background: '#fff' } }).composite(tiles).png().toFile('cut-check.png');
})();
