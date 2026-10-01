# Sambit Maity — Portfolio: Complete Guide

A recruiter-focused portfolio built with **Laravel 12** (backend, content, contact form, résumé download) and **React 19** (frontend), with custom animations throughout. Dark theme, lime accent (`#d4ff4f`), editorial typography.

- **Local URL:** http://127.0.0.1:8123 (after `php artisan serve --port=8123`)
- **All content lives in one file:** `config/portfolio.php`. Edit it and refresh the page; no rebuild needed.
- **Code changes** (anything in `resources/`) need `npm run build`.

---

## Contents

1. [Tech stack](#1-tech-stack)
2. [Running locally](#2-running-locally)
3. [Page structure, section by section](#3-page-structure-section-by-section)
4. [Editing content (`config/portfolio.php`)](#4-editing-content-configportfoliophp)
5. [Common tasks (recipes)](#5-common-tasks-recipes)
6. [Backend: routes, contact form, résumé](#6-backend-routes-contact-form-résumé)
7. [Design system](#7-design-system)
8. [Performance decisions](#8-performance-decisions)
9. [Responsive behaviour](#9-responsive-behaviour)
10. [Tuning knobs in code](#10-tuning-knobs-in-code)
11. [File structure](#11-file-structure)
12. [Deploying to a VPS](#12-deploying-to-a-vps)
13. [Troubleshooting](#13-troubleshooting)
14. [Open to-dos](#14-open-to-dos)

---

## 1. Tech stack

| Layer | Tools |
|---|---|
| Backend | Laravel 12, PHP 8.2, SQLite (contact messages) |
| Frontend | React 19, Vite 7, Tailwind CSS v4 |
| Animation | Motion (`motion/react`), custom Canvas 2D, CSS keyframes |
| Scrolling | Lenis (smooth wheel scrolling) |
| Fonts | Geist, Geist Mono, Instrument Serif (Google Fonts) |

No other runtime dependencies. The page is a single Blade view (`resources/views/app.blade.php`) that mounts the React app and injects the portfolio content as JSON.

---

## 2. Running locally

```bash
composer install
npm install
cp .env.example .env          # already done
php artisan key:generate      # already done
php artisan migrate           # creates the contact_messages table
npm run build                 # or: npm run dev (hot reload while editing)
php artisan serve --port=8123
```

> **OneDrive note:** Windows marks OneDrive folders read-only and PHP then refuses to write. If you see *"bootstrap/cache directory must be present and writable"*, run:
> ```
> attrib -R bootstrap\cache /S /D
> attrib -R storage /S /D
> ```

> **Windows filename note:** `App.jsx` and `app.jsx` are the same file on Windows. The entry file is therefore `resources/js/main.jsx`.

---

## 3. Page structure, section by section

Top to bottom:

### Intro loader
- "Booting systems" with a counter to 100 and a lime progress line, then the screen lifts away.
- Shown **once per browser session** (stored in `sessionStorage`), skipped for users who prefer reduced motion.

### Global UI
- **Custom cursor** (desktop only): a lime dot and a trailing ring. Over links it grows, and over tagged elements it shows a label (`View`, `PDF`, `Copy`, `Send`, `Trace`, `Open`, `Top`).
- **Film grain:** a static noise overlay for texture.
- **Nav:**
  - The bar holds the SM monogram, name/role, section links (a pill slides to the active section), the live Kolkata clock, and a "Quick actions ⌘K" button.
  - It hides when you scroll down and reappears when you scroll up.
  - Once scrolled, a **progressive blur** sits behind it: four layers of increasing blur fading out below the bar, plus a dark tint.
  - A lime **scroll-progress line** runs along the top edge of the screen.
- **Command palette (⌘K / Ctrl+K, or "Menu" on phones):** download résumé, copy email, send email, call, jump to any section, open GitHub/LinkedIn/Website. It's keyboard-navigable (↑ ↓ ↵ Esc).

### Hero
- An interactive **dot field** (Canvas) that breathes and bends away from the cursor, with lime highlights.
- An availability badge, then the headline *"From idea to **shipped** product, every layer."* with each word rising into place.
- An intro line, **See selected work** (lime, magnetic), and **Résumé ↓** (magnetic).
- An info strip: Currently · Based in (live clock) · Core · Shipping since (uses `projects_total`).
- **Scroll behaviour:**
  - The headline and buttons scroll up more slowly than the page (parallax) and fade out.
  - A moving mask dissolves them before they reach the nav, so nothing lingers in the nav or blur area.
  - At rest nothing is masked.

### Stack band (marquee)
- Two infinite rows of tech names scrolling in opposite directions (sans and italic serif). They pause on hover.

### 01 About
- The manifesto paragraph **lights up word by word** as you scroll, with key words in lime italic.
- The two headline stats underneath (**3+ years**, **25+ projects**) count up, each with a lime accent line.

### 02 Experience
- A timeline with a lime line that **fills as you scroll**. The current role has a pulsing dot and a "Now" badge.
- Each role shows the period, company, title, location, summary (readable sans), bullet points and tech chips.

### 03 Selected Work
Three parts:

1. **Featured projects** (Wonati.ai, Finvena, EhloStack) as **stacking cards**. On desktop screens at least 1024px wide and 640px tall, each card sticks and the earlier ones scale down and dim as the next slides over. Each has a **live architecture diagram**:
   - Nodes spring in and dashed edges fade in.
   - Lime packets travel along the connections.
   - **Hovering a node** isolates its connections.
2. **Also built:** animated cards for five projects:

   | Card | Animation |
   |---|---|
   | Company AI chat agent | question → typing dots → answer with "source: company docs" |
   | Self-hosted infra & automation | 5 servers (web, api, **n8n**, mail, apps) cycling "deploying…" → "healthy" |
   | Earning platform app | wallet balance counting up with floating "+coins" |
   | CRM systems | deal card sliding Lead → Qualified → Won ✓ |
   | Ad-free music player | equalizer, progress bar, "ads skipped" counter |

3. **More projects:** a table (Year · Project · Type · Built with · ↗).
   - Dated rows come first, newest at the top.
   - The first 8 show, with a "Show all" button for the rest.

### 04 Capabilities (Stack)
Seven bento tiles, each with a live mini-visual:

| Tile | Visual |
|---|---|
| Architecture, end to end | 5 layers (Client → Infrastructure) highlighting in turn |
| Servers I provision and run | deploy terminal typing out commands, incl. self-hosted n8n |
| Multi-model AI, orchestrated | requests routed **via n8n or Laravel** to Claude (Anthropic), GPT, DALL·E (OpenAI) or the company chat agent |
| Fast by default | gauge fills **red to 80**, shakes ("needs work"), then climbs through orange/yellow to **lime 95+**; the legend shows "before ~80" crossed out and "▲ +15 pts" |
| Money and messages | live event feed: Stripe, Razorpay, MSG91 OTP, Twilio, Pusher, BullMQ, HMAC |
| Workflows that remove toil | n8n-style flow: Webhook → Filter → Transform → Notify → Log |
| Cross-platform with Flutter | web client + phone sharing one REST API; the phone cycles 2FA code → Dashboard → Messages |

Below the tiles is the **Toolbox**: "45 tools, 7 disciplines."
- Numbered rows with skill **pills**.
- A lime dot marks a daily driver, and quieter detail text sits inside the pill (e.g. `VPS administration · 5+ instances`).
- Pills stagger in, and hovering a row lifts them.

### 05 How I work
- Five principles (hover nudges the title), plus an **Education** card: CGPA 9.08, B.Tech CSE, Pailan College, 2019–2023.

### 06 Contact
- Heading *"Let's build **what's next.**"*
- Copy-email button (magnetic, shows ✓), plus phone, GitHub, LinkedIn and Website links.
- **Form:** Name, Email, Company/role (optional), Message.
  - Placeholders are faded (15% opacity, 10% while focused).
  - When the form scrolls into view, the **Name field invites input**: a blinking lime caret, a lime label, and a lime underline sweep twice. This stops as soon as the visitor clicks or types. It never auto-focuses.
  - Server-side validation errors appear inline. On success the form shows "Message received."

### Footer
- **Brand:** monogram, tagline, availability badge.
- **Link columns:** Navigate · Connect · **Now** (live clock, **online/offline** status for 10:00–21:00 IST, Résumé button).
- **Bottom bar:** © year · Built with Laravel + React · magnetic back-to-top.
- **Wordmark:** a giant outlined **SAMBIT MAITY** whose letters rise in. On desktop a **lime spotlight follows the mouse** and fills the letters.

### No-JavaScript fallback
- If JavaScript is off, a plain HTML version of the name, headline, experience and résumé link is shown (in `app.blade.php`).
- The page also includes Person structured data (JSON-LD) and Open Graph tags for search and link previews.

---

## 4. Editing content (`config/portfolio.php`)

Everything on the page comes from this file. Save it and refresh the page; no rebuild.

### `profile`
| Key | Used for |
|---|---|
| `name`, `role`, `headline`, `location`, `timezone` | hero, nav, footer, SEO |
| `email`, `phone` | contact, palette, footer |
| `available` (bool), `availability` (text) | badges in hero and footer |
| `current.title`, `current.company` | hero info strip, structured data |
| `links[]` | `label`, `handle`, `url` for contact, footer and palette |

### `manifesto`
The About paragraph. Words matching `architecture,` `servers,` `automations` `on` `call` are highlighted (regex in `About.jsx`).

### `stats`
Headline numbers under About. Fields: `value`, `suffix` (e.g. `+`), `label`, optional `from` (count-up start) and `decimals`.

### `stack`
The list of names in the marquee band.

### `skills` (Toolbox)
```php
['group' => 'Backend', 'items' => ['*Laravel|pairs with React & Vue', 'BullMQ|job queues', 'HyperBase']],
```
- `*` prefix makes it a **daily driver** (lime dot, brighter pill).
- `|detail` adds quiet detail text inside the pill.
- The heading's tool and discipline counts update automatically.

### `experience[]`
`role`, `company`, `location`, `period`, `current` (bool), `summary`, `points[]`, `tags[]`.

### `projects[]` (featured stacking cards)
`slug`, `name`, `kind`, `role`, `blurb`, `highlights[]`, `stack[]`, optional `diagram`:
```php
'diagram' => [
    'nodes' => [
        ['id' => 'api', 'label' => 'Laravel API', 'x' => 42, 'y' => 50, 'core' => true], // x/y in % of the map
        ['id' => 'db',  'label' => 'PostgreSQL',  'x' => 76, 'y' => 82],
    ],
    'edges' => [['api', 'db']],
],
```
Without a `diagram`, the card's text spans the full width.

### `projects_total`
The number shown as "25+" in the About stat, hero info strip and Work intro. Update it as you ship more.

### `archive[]` (Also built cards + More projects table)
| Field | Required | Notes |
|---|---|---|
| `name` | ✓ | |
| `kind` | ✓ | short description |
| `year` | | blank shows "—" and sorts after dated rows |
| `org` | | company / client |
| `stack` | | array of tech |
| `url` | | adds a clickable ↗ |
| `card` | | `chat` · `infra` · `music` · `crm` · `earning` makes it an **animated card** instead of a table row |

### `principles[]`
`title` and `body` for "How I work".

### `education`
`school`, `degree`, `period`, `location`, `score`.

---

## 5. Common tasks (recipes)

| I want to… | Do this |
|---|---|
| Add a project to the list | Add a line to `archive` in `config/portfolio.php` |
| Show a project as an animated card | Add `'card' => 'chat'` (or `infra`/`music`/`crm`/`earning`) to its `archive` line |
| Add a link to a project | Add `'url' => 'https://…'` |
| Feature a big project with a diagram | Add an entry to `projects` |
| Mark a skill as a daily driver | Prefix it with `*` in `skills` |
| Change the project count | Edit `projects_total` |
| Update the résumé PDF | Replace `storage/app/private/resume.pdf` |
| Stop showing "Open to roles" | Set `'available' => false` |
| Hide the phone number | Remove the phone entries (profile + contact list in `Contact.jsx` and `CommandPalette.jsx`) |
| Read contact messages | See [section 6](#6-backend-routes-contact-form-résumé) |
| Change the accent colour | Edit `--color-acid` in `resources/css/app.css`, then `npm run build` |

---

## 6. Backend: routes, contact form, résumé

| Route | Controller | Purpose |
|---|---|---|
| `GET /` | `PortfolioController@index` | renders the page with `config('portfolio')` |
| `GET /resume` | `PortfolioController@resume` | downloads `storage/app/private/resume.pdf` as `Sambit_Maity_Resume.pdf` |
| `POST /contact` | `ContactController@store` | validates and saves a message |

**Contact form protection**
- Rate limit: **5 messages per 10 minutes per IP** (`throttle:5,10`).
- Honeypot field `website`: bots that fill it get a fake success.
- CSRF token is sent from the page's `<meta name="csrf-token">`.
- Validation: name ≤120, valid email ≤190, company ≤160 (optional), message 10–5000 chars.

**Reading messages** (table `contact_messages`):
```bash
php artisan tinker --execute="App\Models\ContactMessage::latest()->get(['name','email','company','message','created_at'])->each(fn(\$m)=>dump(\$m->toArray()));"
```
Each message is also logged in `storage/logs/laravel.log`.

---

## 7. Design system

Defined in `resources/css/app.css` (`@theme`):

| Token | Value | Use |
|---|---|---|
| `ink` | `#07070a` | page background |
| `ink-2` / `panel` | `#0c0c10` / `#101015` | cards, surfaces |
| `fg` | `#ededef` | main text |
| `mute` | `#8b8b95` | secondary text |
| `dim` | `#55555e` | tertiary text, meta |
| `line` / `line-2` | white 8% / 14% | hairlines, borders |
| `acid` | `#d4ff4f` | the single accent |

**Type**
- **Geist** for UI and headings.
- **Geist Mono** for labels and meta.
- **Instrument Serif italic** for accent words only.
- Big headings scale with **both width and height**, e.g. `clamp(2.5rem, min(9vw, 13.5vh), 8.5rem)`, so they fit short laptop screens.

**Reusable classes**
- `.container-x` is the page width: 1320px, or 1480px on screens ≥1800px.
- `.eyebrow`, `.display`, `.serif-i`, `.chip` and `.hairline` handle the recurring text and border styles.
- `.spotlight` is a card whose border lights up under the cursor.
- `.progressive-blur` is the nav blur, `.wordmark` is the footer spotlight text, and `.glow` is a cheap radial glow.

**Custom breakpoint:** `stack:` applies only when the screen is at least 1024px wide **and** 640px tall. It gates the sticky stacking project cards.

---

## 8. Performance decisions

The site was tuned after lag reports. What was done and why:

| Change | Why |
|---|---|
| Hero canvas batches dots into ~20 `Path2D` fills per frame, bakes the vignette into dot alpha, skips invisible dots, and pauses off-screen or when the tab is hidden | was ~1,700 draw calls per frame plus a CSS mask |
| No blur filters on scroll-driven text | per-word `filter: blur()` recalculated every scroll frame |
| Diagram packets move with `transform` on full-size wrappers | animating `left`/`top` forced layout every frame |
| No animated SVG stroke-dashoffset | SVG stroke animation can't use the GPU and repaints every frame |
| Every ticking visual (tiles, cards) runs **only while on screen** | intervals were re-rendering React off-screen |
| Clocks are isolated components | a per-second clock was re-rendering the whole hero |
| Large glows use radial gradients instead of `blur(140px)` | huge blur filters are expensive |
| Film grain is static | the old grain animated a 4× screen-size layer |
| Only one `backdrop-filter`: the nav's progressive blur, a ~120px strip shown only after scrolling | full-page backdrop blur re-blurs everything every frame |
| Lenis uses `lerp: 0.1` | feels responsive rather than floaty |
| `prefers-reduced-motion` respected | animations collapse, the loader is skipped, smooth scroll is off |

If scrolling still feels heavy on a weak device, the next levers are:
1. Reduce the nav blur to 2 layers.
2. Switch Lenis off (native scroll): remove `initSmoothScroll()` in `App.jsx`.

---

## 9. Responsive behaviour

Checked with no horizontal overflow at **320, 360, 375, 390, 430, 768, 1024, 1280, 1366, 1536, 1920 and 2560px** widths.

- **Phones:**
  - The headline scales with width (`11.5vw`).
  - Nav links collapse into "Menu" (the command palette).
  - Grids stack into one column, the diagram shows "tap a node", and the tables become stacked rows.
- **Tablets:** Capabilities becomes a 2-column bento, and Contact and Principles stack vertically.
- **Short laptops (≈700px tall):** hero, About and each project card are sized to fit one screen.
- **Stacking cards:** only on wide *and* tall screens. Everywhere else the project cards are a normal list.
- **Custom cursor, magnetic pull and wordmark spotlight:** only on devices with a mouse.

---

## 10. Tuning knobs in code

| What | Where |
|---|---|
| Footer "online" hours (10:00–21:00 IST) | `Footer.jsx`, `hour >= 10 && hour < 21` |
| Hero fade band under the nav | `Hero.jsx`: `HIDE_UNTIL`, `FADE_BAND`, `ENGAGE`, `PARALLAX` |
| Performance gauge numbers / red colour | `Capabilities.jsx`: `BEFORE`, `AFTER`, `RED`, `LIME` |
| AI router requests and targets | `Capabilities.jsx`: `ROUTES`, `HUBS`, `TARGETS` |
| Deploy terminal script | `Capabilities.jsx`: `SCRIPT` |
| Event feed entries | `Capabilities.jsx`: `EVENTS` |
| Chat card Q&As / server names | `ProjectCards.jsx`: `QA`, `SERVERS` |
| Rows shown before "Show all" | `Work.jsx`: `INITIAL = 8` |
| Nav blur strength | `app.css`: `.progressive-blur > div:nth-child(n)` |
| Loader duration / once-per-session key | `Loader.jsx` (`sm:intro-seen`) |
| Hero dot field density and colours | `FieldCanvas.jsx`: `gap`, `NEUTRAL`, `LIT`, `radius` |

---

## 11. File structure

```
portfolio/
├── config/portfolio.php            ← ALL content
├── app/
│   ├── Http/Controllers/
│   │   ├── PortfolioController.php  ← page + résumé download
│   │   └── ContactController.php    ← contact form endpoint
│   └── Models/ContactMessage.php
├── database/migrations/…create_contact_messages_table.php
├── routes/web.php                   ← /, /resume, POST /contact (throttled)
├── storage/app/private/resume.pdf   ← the downloadable résumé
├── resources/
│   ├── views/app.blade.php          ← shell, SEO, JSON-LD, no-JS fallback
│   ├── css/app.css                  ← design tokens, utilities, keyframes
│   └── js/
│       ├── main.jsx                 ← entry
│       ├── App.jsx                  ← page composition, ⌘K shortcut
│       ├── lib/
│       │   ├── scroll.js            ← Lenis smooth scroll, scrollTo, lockScroll
│       │   └── hooks.js             ← clock, spotlight, media query, clipboard
│       ├── ui/                      ← Reveal (SplitWords/FadeUp), Counter, Magnetic, Clock, SectionHead
│       └── components/
│           ├── Loader, Cursor, Nav, CommandPalette
│           ├── Hero, FieldCanvas, Marquee, About, Experience
│           ├── Work, SystemDiagram, ProjectCards
│           ├── Capabilities, Principles, Contact, Footer
├── README.md
└── PORTFOLIO_GUIDE.md               ← this file
```

---

## 12. Deploying to a VPS

1. Point Nginx's web root at `portfolio/public`, and route requests through `index.php` (standard Laravel config).
2. Set in `.env`: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://your-domain`.
3. Run:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build
   php artisan migrate --force
   php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```
4. Make `storage/` and `bootstrap/cache/` writable by the web user.
5. After editing `config/portfolio.php` in production, run `php artisan config:clear` (or re-cache), because config is cached.

---

## 13. Troubleshooting

| Symptom | Fix |
|---|---|
| 500 error, "No application encryption key" | `php artisan key:generate` |
| "bootstrap/cache must be writable" (OneDrive) | `attrib -R bootstrap\cache /S /D` and `attrib -R storage /S /D` |
| Code changes not showing | `npm run build`, then hard refresh (Ctrl+Shift+R) |
| Content change not showing in production | `php artisan config:clear` |
| Loader doesn't appear again | it's once per session; open a new tab/window |
| Animations frozen when testing | a hidden or covered browser window pauses animations; bring it to the front |
| `backdrop-filter` missing in Chrome | don't hand-write `-webkit-backdrop-filter` next to it; the build step keeps only one |

---

## 14. Open to-dos

Content only you can supply:

- [ ] **Replace the résumé PDF** (`storage/app/private/resume.pdf`). It still shows the old title and lacks the new Collabmate points, and its GitHub link says `loco00` instead of `loco0011`.
- [ ] **Years and tech** for: Astrology platform, CRM systems, ID card management system, Business & company websites, Earning platform app, Ad-free music player.
- [ ] **Links** (live site / Play Store / GitHub) for any project, especially the earning app and music player.
- [ ] **Open-source:** name the specific projects contributed to.
- [ ] **Which company** used MSG91, BullMQ, HMAC/2FA and Razorpay, so they can be added to that role's bullet points.
- [ ] **Confirm:** "Role-based access" in Security, the lime daily-driver dots, and the guessed years (2026 for Collabmate items, 2025 for the mentorship platform).
- [ ] **Confirm** "financial marketplace" = Finvena (otherwise add it as its own project).
- [ ] **Footer online hours:** adjust if 10:00–21:00 IST isn't right.
- [ ] Optional: add a social preview image (`og:image`) for nicer link previews on LinkedIn and WhatsApp.
