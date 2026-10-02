# SEO plan — sambitmaity.com

Research date: 3 Oct 2026. No paid keyword tool was used, so there are no search-volume numbers yet.
Rankings below come from live Google results. Once Search Console has 4–6 weeks of data,
replace the guesses here with the real queries from **Performance → Queries**.

## What the search results show

| Query | Who ranks today | Can a one-page site win it? |
|---|---|---|
| `Sambit Maity` | GitHub (samgeet repo), LinkedIn, other Maitys (Leiden researcher, politicians) | **Yes, fast.** Nobody else owns the exact name. Needs indexing plus profiles linking back. |
| `Sambit Maity developer / portfolio / resume` | Nothing relevant | **Yes.** Comes with the name. |
| `full stack developer Kolkata` | Upwork, Indeed, job boards, agencies | Hard with one page. Possible with a dedicated page and backlinks. |
| `freelance Laravel developer Kolkata` | Upwork, Truelancer, Twine, plus one solo dev (sajjanjha.com) with a page per service | **Yes, with a dedicated page.** The solo dev proves it's doable. |
| `Node.js developer Kolkata` | Upwork, job boards | Medium, needs a dedicated page. |
| `n8n automation developer India` | Agencies, Upwork, n8n community forum | Medium. A case-study page plus a community post linking back. |

## Keyword map

**Tier 1, brand (homepage):** target these now.
- Sambit Maity
- Sambit Maity developer, Sambit Maity portfolio, Sambit Maity resume
- loco0011 (GitHub handle)

**Tier 2, role + place (homepage title, H1 and copy):** already worked into the title, description and H1.
- full-stack developer Kolkata
- Laravel developer Kolkata / India
- Node.js developer Kolkata
- full-stack software engineer India

**Tier 3, long-tail (each needs its own page to rank):** future pages.
- hire freelance Laravel developer Kolkata → `/laravel-developer-kolkata`
- n8n automation developer India → `/n8n-automation`
- Laravel + React developer for hire → `/hire`
- Claude / OpenAI integration developer → case study (company chat agent)
- open-source Flutter music player → Samgeet case study (the repo already ranks for your name)

## Done in code

- **Title:** `Sambit Maity — Full-Stack Developer, Kolkata (Laravel & Node.js)`. **Description:** 154 characters, naming the city, stack, DevOps and n8n. Both are editable in Admin → Content → Seo.
- **Crawlable HTML:** the full portfolio (H1, about, experience, projects, skills, education, contact) is in the first HTML response. Crawlers that don't run JavaScript (Bing's first pass, LinkedIn, AI crawlers) can read it. React replaces it on load, so visitors never see it.
- **Hero H1:** now includes "Sambit Maity, Full-Stack Software Engineer in Kolkata, India" for search engines and screen readers. The visual slogan is unchanged.
- **Structured data:** a JSON-LD `@graph` with WebSite (gives Google your site name), ProfilePage and Person (name, job, Kolkata/West Bengal address, employer, college, skills, `sameAs` GitHub and LinkedIn).
- Dropped `meta keywords`, which Google ignores and Bing treats as a spam signal.
- Already in place: canonical URL, robots.txt, sitemap.xml, Open Graph and Twitter cards, favicon and icons, `noindex` outside production.

## Google tools: set these in `.env.production` on the server

```
GOOGLE_SITE_VERIFICATION=   # Search Console → Add property → URL prefix → HTML tag → the content="..." value
GOOGLE_TAG_MANAGER_ID=      # GTM-XXXXXXX
GOOGLE_ANALYTICS_ID=        # G-XXXXXXXXXX
```

Then run `docker compose up -d --build` (or `deploy/deploy.sh`).

- **Search Console:** the verification tag renders whenever the token is set.
- **GA4 and GTM:** load only in production and only when their ID is set. The admin panel never loads them.
- **GA4 is loaded directly** (gtag.js). **Don't add a GA4 / Google tag inside GTM too**, or every page view is counted twice. Use GTM for everything else (conversion pixels, Clarity, etc.).
- Your own cookie-free analytics in the admin keeps working alongside.

## After deploy, in order

1. **Search Console:** verify, then go to Sitemaps and submit `https://sambitmaity.com/sitemap.xml`.
2. **URL Inspection:** test `https://sambitmaity.com/` and click *Request indexing*.
3. **Rich Results Test:** paste the URL and confirm Person / ProfilePage are detected.
4. **Bing Webmaster Tools:** import from Search Console (one click; also feeds DuckDuckGo and ChatGPT search).
5. **Link back from every profile:** these links are the strongest name signal.
   - GitHub profile *Website* field and the profile README
   - LinkedIn *Contact info → Website* and *Featured*
   - Samgeet README ("Built by Sambit Maity", linking to the site)
   - n8n community profile, dev.to / Hashnode, Upwork or other freelance profiles
6. **Old domain:** add the Caddy redirect from sambitmaity.fun so any links to it pass to .com.
7. **After 4–6 weeks:** check Search Console Queries and update this file with real numbers.

## Next content (for Tier 2/3 rankings)

A one-page site can rank for your name, but generic service searches need pages written for them.
Highest value first:
1. **`/laravel-developer-kolkata`**: services, stack, 2–3 project summaries, rates or "contact for quote", FAQ.
2. **Case studies:** Wonati.ai, the company AI chat agent (n8n + Claude/OpenAI), Samgeet. One URL each, about 600–1,000 words with architecture and results.
3. **`/n8n-automation`**: what you automate, examples, contact.

Each new page goes into `sitemap.xml` (`SeoController`) with its own title, description and H1.
