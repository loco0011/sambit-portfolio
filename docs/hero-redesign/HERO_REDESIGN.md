# Hero redesign: context and status

Last updated: 2026-09-25. Status: **paused**, Sambit will pick it up later.

## Goal

Replace the current hero (`resources/js/components/Hero.jsx` + `FieldCanvas.jsx`) with something
**unique, professional and "next level"**, using **motion and/or an avatar**.

Current hero, for reference: dark ink + acid green (`#07070a` / `#d4ff4f`), Geist + Instrument Serif,
headline "Engineering complete systems, not just features.", a cursor-reactive dot field, CTAs and a
4-column meta strip. Content comes from `config/portfolio.php` (`profile`, `experience`, `projects_total`).

## What was tried and how Sambit reacted

All three rounds are standalone HTML prototypes in this folder. Open them in a browser.

| Round | File | Idea | Feedback |
|---|---|---|---|
| 1 | `hero-prototypes.html` | A: request-trace waterfall (Nginx → Laravel → Stripe → BullMQ → React). B: engineering blueprint (light, bill of materials, title block). C: giant stacked layer words | "want it unique as well as minimalist elegant" (too busy) |
| 2 | `hero-minimal.html` | Footnotes (verbs with ¹²³⁴ proof notes). The path (one line, a moving dot). The ruler (monthly career timeline) | "dont liking… use motion or avatar, make it next level as well as professional" |
| 3 | `hero-portrait.html` | **Particle portrait**: dots fly in and form a portrait, repel and glow under the cursor, a diagonal scan light sweeps every ~5 s. Rotating headline "I build [products/APIs/infrastructure/AI agents/interfaces] end to end." Floating tags, Kolkata coordinates + live clock | **Current direction.** No verdict yet; paused here |

Published previews (private to Sambit's claude.ai account):
- Round 1: https://claude.ai/artifact/E8TGXj8fPpYAyZpHVxam9x
- Round 2: https://claude.ai/artifact/CisUnDrErq9rMhxkB65pih
- Round 3: https://claude.ai/artifact/7ptNTShzsqHZZH37ytVaBV

## Round 3 details (the one to continue)

- Stays in the existing brand: same colours, fonts, nav, meta strip. The dot portrait continues the
  `FieldCanvas.jsx` dot language (Path2D batching into alpha buckets, pauses off-screen).
- There is **no photo of Sambit in the repo yet**. The prototype defaults to an "SM" monogram and has
  "Use my photo" / drag-and-drop to try a real headshot (stored in browser localStorage only).
- Portrait sampling: cover-fit biased to the top, luminance → dot radius, auto-levels (4th–98th percentile),
  gamma 1.25, oval vignette so the background falls away. Grid gap ~4.2–5.4 px depending on width.
- Physics: spring to home (0.055), damping 0.8, cursor repulsion radius ≈ 20% of canvas width.
- Respects `prefers-reduced-motion` (static portrait, no rotation or scan).

## Next steps when resuming

1. Get a headshot from Sambit (plain background works best) and put it in `public/`.
2. Ask whether round 3 is approved or needs changes.
3. Port it to React: a new `PortraitCanvas.jsx` (modelled on `FieldCanvas.jsx`) and update `Hero.jsx`.
   Keep the existing `ready` prop / Loader timing, the `Magnetic` CTAs, the `Clock` component and the
   nav fade-band logic.
4. Pre-compute the dot data from the photo (at build time or once on load, then cache) so the hero
   doesn't process a full image on every visit.
5. Check mobile (the portrait sits above the text), short laptops (~700px tall) and performance.

## Round 4 (2026-09-27): keep the left side, add a right-side visual

Sambit said the **left side (current headline, lede, CTAs) is okay** and asked for an animated character,
himself, or anything "next level" in the right-side space, with 3 prototypes before implementing.
File: `hero-right-side.html` (switcher at the bottom, deep links `#a` `#b` `#c`).
Preview: https://claude.ai/artifact/Kj4jgwLYxUA43ffV5BGtQk

- **A · Character**: flat SVG illustration of Sambit (glasses, headphones, hoodie) behind a laptop. Head and
  eyes follow the cursor, blinks, types, waves on load and on click, a typewriter speech bubble cycles real
  work ("Routing prompts GPT-4 → Claude", "Lighthouse 80 → 95+"), code tokens float up from the laptop.
- **B · System core**: canvas 3D. A dotted sphere core ("SM") with the 8 stack services on a tilted orbit,
  request packets flying service → core → service, drag to spin, hover a service for what it does.
- **C · Hologram**: a raymarched 3D bust (head, glasses, headphones, shoulders) rendered as ASCII on canvas.
  Turns to face the cursor, glasses/headphones and rim light in acid green, scan-line glitch, decode-in intro.

No verdict yet.

Round 4b (same day): Sambit asked for more options, so three more were added to the same file:
- **D · Live build**: an editor types a JSX component and a project card (Wonati.ai, Finvena, EhloStack) builds
  itself line by line below it, then a "deployed" toast. Both panels tilt in 3D with the cursor.
- **E · Morph**: ~1500 particles re-form into SM → `</>` → API → a gear → AI, each with a caption naming the
  stack. Cursor scatters them, click to morph early.
- **F · The stack**: isometric 3D layers (Infrastructure, Data, Services, Interface) drop in, data packets travel
  between them, hovering a layer lifts it and highlights its label.

Round 4c (same day): Sambit sent a headshot and asked for **C · Hologram built from his real face, with a
loading progress**. Option C now uses the photo instead of the 3D bust.
- Headshot background removed locally with `@imgly/background-removal-node` (medium model), a stray bit near
  the left ear erased. Saved here as `sambit-cutout.png` (full size) and `sambit-cutout.webp` (380 px, the one
  embedded in the prototype as a data URI). Move to `public/` when porting.
- Render: photo → character grid (auto-levels, local contrast), blurred alpha used as fake depth for cursor
  parallax, edges and highlights in acid. Loading sequence ~3.4 s with a % readout: "Loading portrait" →
  "Mapping depth" → "Rendering hologram" (top-to-bottom scan) → "online". Click replays it. Idle: scan line,
  occasional glitch slices, flicker, cursor scrambles nearby characters.
- Follow-up: Sambit asked to "remove the box, just the face should be building" until fully loaded. The progress
  panel, corner frame, caption and scan-line texture were removed from C. Loading is now the face itself: over
  ~5.2 s each character drops into place (top first, scattered), starting as a glitch glyph and sharpening to its
  final character. After that the idle effects continue. Click rebuilds.
- Follow-up: "remove the shirt/shoulder part, just keep the face". The cutout is now masked to the head with a
  feathered egg-shaped oval (cuts just below the goatee) and cropped: `sambit-head.png` (full) and
  `sambit-head.webp` (340 px, embedded). The hologram centres it in the right column at 94% size.

## Round 5 (2026-09-27): pick a character, then its activity

The photo hologram was dropped ("face is not looking good"). A 5-option animated set based on Sambit's own look
(`hero-characters.html`, not published) was interrupted: Sambit asked for a **static list of at least 10
characters** first, generic programmer or funny, **not** drawn to look like him. Activity comes after he picks.
File: `character-picker.html` (12 static SVG characters). Preview: https://claude.ai/artifact/6Af29FJMiMCvkXYXPqaQk3
01 Hooded Hacker · 02 Byte the Robot · 03 Mocha (coffee mug) · 04 Rubber Duck · 05 Senior Cat · 06 Astronaut Dev ·
07 Code Ninja · 08 The Wizard · 09 Octo-stack · 10 Bug Hunter · 11 Terminal Pal · 12 Sloth Dev.
Next: he picks a number → propose activities for it → animate on the real hero layout.
- Follow-up: Sambit kept all 12 and asked for **10 more human characters** ("like you gave at first", i.e. the
  option-A style), plus his own idea: a **one-eyed daku coding, with a missing tooth**. Added 13–22, drawn from a
  shared parts system in the page script: 13 Daku Coder (turban, eye patch, handlebar moustache, tooth gap,
  bandolier of USB sticks, "sudo" laptop) · 14 Chai Coder · 15 Night-shift Dev · 16 The Architect · 17 The Tech
  Lead · 18 DevOps Bear · 19 Startup Founder · 20 Retro Hacker · 21 Beach Freelancer · 22 Gamer Dev.
  He dislikes the mascot ones (duck, ninja); Hooded Hacker is "okay".

## Round 6 (2026-09-27): "Shades Dev", from Sambit's own reference image

Sambit shared a 6-panel reference (a ChatGPT image in his Downloads): a bald guy with black sunglasses and green
lens glints, black hoodie, in 6 moods. Rebuilt as SVG in `hero-dev-character.html`.
Preview: https://claude.ai/artifact/VEEgzJpm6DenjxaeWpAdwV
Hero right side cycles every 4.2 s (bars to jump), the glints follow the cursor, each pose has its own motion:
01 Code mode on (mug + steam, sip) · 02 Power nap (zzz, feet up, breathing) · 03 New mechanical keyboard
(tongue out, pointing, sparks, wiggle) · 04 Picking the stack (React?/Laravel?/Node?/Docker? bobbing, books
Docs/StackOverflow/ChatGPT/YouTube, plant) · 05 Bug fix fuel (noodle cup, chopsticks slurp) · 06 Plan · code ·
deploy · repeat (feet on desk, checklist ticks animate). All six are also shown in a grid below the hero.
- Follow-up: "illustrate well, and no bg / box". Removed the framed box and glow background (the character now
  sits directly on the page and fades out at the bottom); the gallery cells lost their boxes too. Redrew in a
  proper illustration style: dark outlines on every shape, shared gradients (skin, hoodie, glasses, laptop,
  shoes, mug, cup, chair), buzz-cut stubble, ear detail, glossy shades, hood rim, drawstring tips, pocket seam,
  ribbed cuffs, fingers on the hands, sneaker soles with tread.
- Follow-up (2026-09-28): "illustrations are not good quality". Hand-coded SVG can't reach the reference's
  quality, so switched to **using his reference image directly**. `hero-shades.html` + `shades-dev/pose-1..6.webp`:
  each panel cropped inside its border, then colour-levelled so the panel background (#0b0c10) maps to the page
  (#07070a); a radial CSS mask fades the edges, so no box shows. Automatic background removal was tried and
  rejected (it deleted the dark hoodie, sneakers, zzz, question marks, books). Hero: crossfade + blur every
  4.5 s, pauses on hover, 3D tilt toward the cursor, gentle float.
  Preview: https://claude.ai/artifact/Jz9Kke2YvXkxw6LLN1dQVW
  Limitation: each pose is only ~467 px (a 1536×1024 image split 3×2), so slightly soft on retina. For the real
  site, regenerate each pose separately at 1024 px+ on a plain #07070a background.
- Follow-up: pose 4's hand is malformed in the source AI image (fingers merge). Not patchable without looking
  pasted on; Sambit was given a ChatGPT edit prompt and `shades-dev/pose-4-to-fix.png` (original panel) to fix.
- Follow-up: "add a headphone into every photo". Headphones are baked into all six WebPs by
  `shades-dev/add-headphones.js` (sharp + SVG overlay, per-pose ear/top-of-head coordinates, band follows head
  tilt and covers the old hair stripe, green LED on each cup). Run it from a folder containing the original
  `panel-1..6.png` crops (cropped from the reference at cols 18–500/527–1010/1036–1519, rows 14–498/522–1004,
  8 px inset). When a fixed pose 4 arrives, re-crop it, update its coordinates in the script and re-run.
- Follow-up: "headphone not set well" and the hand still isn't fixed. Claude can't generate or repaint raster
  illustration (no image model), and code-drawn overlays don't blend with the painted style. Reverted the six
  WebPs to the no-headphone version (artifact v3). Next step is on Sambit's side: regenerate all six poses in
  ChatGPT at 1024 px on flat #07070a with the headphones drawn in and correct hands, using
  `shades-dev/PROMPTS.md`. Then swap them in (only colour-check/convert needed, no cropping or masking).
- Follow-up: Sambit asked to "fix the hand first". Fixed pose 4 without an image model by reusing a well-drawn
  hand from the same artwork: the pointing hand from pose 3 (`shades-dev/hand-from-pose3.png`), scaled 0.8,
  rotated −68° so the index finger rests on the chin. The broken hand was erased by a traced outline, the
  hoodie and cheek filled by diffusion (kept separate at the chin line), the chin outline redrawn, and the
  laptop re-laid on top. Script: `shades-dev/fix-pose4-hand.js` (run with `-68 .8 232 222` from a folder with
  `panel-3/4.png` crops and `goodhand.png`). Result: `pose-4-hand-fixed.png` → `pose-4.webp`, artifact v4.
- Follow-up: "add a small anchor beard in every". `shades-dev/add-beard.js` paints a thin moustache + strip
  under the lip + short band along the chin onto each pose, centred on the face midline (from ear positions),
  rotated with the head tilt, lightly textured, and kept behind the noodles and tongue by colour gates. Pose 4 is
  rebuilt so the beard sits under the repaired hand (`fix-pose4-hand.js` takes an optional overlay 6th arg).
  Pose 3 skips the strip because of the tongue. Artifact v5. Order when rebuilding: crops → beard (runs the
  pose-4 hand fix itself) → colour levels → WebP.
- Follow-up: beard removed at Sambit's request (artifact v6). Current state: original six poses + the pose 4
  hand fix, no headphones, no beard. `add-beard.js` and `add-headphones.js` are kept but not applied.
- Follow-up: headphones redone to fit each head ("head shape is different on each avatar, should be realistic").
  `shades-dev/add-headphones.js` (replaces the old one) measures each pose from pixels: scalp top edge per
  column + outer edge per row → outer envelope around the head centre → band follows that exact curve; ears
  found by casting rays at glasses height and taking the outline's bulge → cup size from ear height, tilt from
  the two ears. Band has a soft contact shadow, outline, highlight; cups have shell gradient, cushion, slider,
  lime LED. Applied to pose 4 after the hand fix. Artifact v7 (current): six poses + hand fix + fitted headphones.

## Built into the site (2026-09-28)

- Quality pass: each pose upscaled 2× (lanczos3 + light sharpen) with the headphones rendered natively at 2×
  → 932×936 WebP (~40–56 KB each). `add-headphones.js` now does the upscale too.
- Images: `public/images/hero/dev-1..6.webp`.
- New `resources/js/components/HeroCharacter.jsx`: crossfade + blur between poses every 4.5 s (starts after
  the Loader's `ready`), pauses on hover, 3D tilt toward the cursor (springs), gentle float, radial mask so no
  box shows, status label + clickable bars, preloads the other poses, respects reduced motion.
- `Hero.jsx`: content is now a 12-col grid on `lg` (text 7 / character 5); headline gets a smaller `lg` size so
  it fits the column; lede and CTAs stack. Everything else (fade band, parallax, meta strip, FieldCanvas) kept.
  Backup of the previous version: `docs/hero-redesign/Hero.before-character.jsx`.
- Checked with `vite build` (passes) and in Edge at 1440×900, 1280×720 and 400 px wide: no console errors.
- Follow-up: "remove the bg of the avatar completely". `shades-dev/remove-background.js` cuts out each final
  2× pose: builds a smooth per-pixel background model from background-looking pixels (plain dark + the green
  glow, which leans green while the hoodie leans blue), keeps pixels that differ from it, closes/fills holes,
  drops specks, and feathers the edge from the colour difference. Output is real alpha WebP (932×936) in
  `public/images/hero/`. Props that belong to each scene (mug, laptop, keyboard, books, plant, bean bag,
  chair, monitor, zzz, question marks) are kept. `HeroCharacter.jsx` mask changed from an oval to a soft fade
  on the bottom and sides only (where the body is cut by the frame). Rebuilt and checked: no console errors.
  Pipeline order now: crops → hand fix → headphones (+2× upscale) → remove background → WebP.
- Follow-up: smoother pose transitions. Dropped the blur; the outgoing pose now fades out in 0.55 s and drifts up
  6 px, the incoming one starts 0.18 s later and settles over 1.1 s (ease-out, rises 10 px, scale .985 → 1), so
  the two never overlap at full strength. Measured in the browser: crossover at ~0.22/0.37 opacity, no flash.
  Cycling now uses a timeout keyed on the index, so picking a pose from the bars gives it the full 4.5 s.
