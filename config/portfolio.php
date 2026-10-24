<?php

/*
|--------------------------------------------------------------------------
| Portfolio content
|--------------------------------------------------------------------------
| Single source of truth for everything rendered on the site. Edit here,
| no frontend rebuild needed — the data is injected into the page at runtime.
*/

return [

    'profile' => [
        'name' => 'Sambit Maity',
        'role' => 'Full-Stack Software Engineer',
        'headline' => 'Laravel & Node.js engineer who owns products end to end — from architecture and APIs to servers, automation and the pixels on screen.',
        'location' => 'Kolkata, India',
        'timezone' => 'Asia/Kolkata',
        'email' => 'official.sambitmaity@gmail.com',
        'phone' => '+91 74781 23847',
        'available' => true,
        'availability' => 'Immediate joiner, open to full-stack & backend engineering roles: on-site, hybrid or remote',
        // The hero chip cycles through these: "Open to ___ roles, on-site, hybrid or remote". Empty shows availability as is.
        'open_to' => ['full-stack', 'backend', 'Laravel', 'Node.js', 'React'],
        // Where you work now. Leave company blank between jobs: the hero then shows "Open to new roles".
        'current' => ['title' => '', 'company' => ''],
        'links' => [
            ['label' => 'GitHub', 'handle' => 'loco0011', 'url' => 'https://github.com/loco0011'],
            ['label' => 'LinkedIn', 'handle' => 'in/sambitmaity', 'url' => 'https://www.linkedin.com/in/sambitmaity/'],
        ],
    ],

    // Search result title and description. Keep the title under ~60 characters and the description under ~155.
    'seo' => [
        'title' => 'Sambit Maity — Full-Stack Developer, Kolkata (Laravel & Node.js)',
        'description' => 'Sambit Maity is a full-stack software engineer in Kolkata, India building Laravel, Node.js and React apps, Linux/Docker DevOps and AI automation with n8n.',
    ],

    'manifesto' => 'I don\'t hand off at the API boundary. I design the architecture, write the backend, build the interface, provision the servers, wire the automations and stay on call for what I ship. Three years across four teams taught me that the best engineering is the kind nobody has to think about — fast, reliable, and quietly doing its job.',

    // Headline numbers shown under the About statement.
    'stats' => [
        ['value' => 3, 'suffix' => '+', 'label' => 'Years shipping production software'],
        ['value' => 25, 'suffix' => '+', 'label' => 'Projects shipped across 4 teams, India & NZ'],
    ],

    'stack' => [
        'PHP', 'Laravel', 'Node.js', 'BullMQ', 'TypeScript', 'React', 'Next.js', 'Vue.js', 'Flutter', 'PostgreSQL', 'MySQL', 'MongoDB', 'Redis',
        'Supabase', 'Docker', 'Nginx', 'Caddy', 'AWS', 'Linux', 'CI/CD', 'n8n', 'Anthropic API (Claude)', 'OpenAI', 'Stripe', 'Razorpay', 'Twilio', 'MSG91', 'Pusher', 'Tailwind CSS', 'ZoneMTA',
    ],

    /*
    | Skills table. Shorthand per item:
    |   '*Laravel'                       → daily driver (gets a lime dot)
    |   'VPS administration|5+ instances' → name + a quieter detail
    */
    'skills' => [
        ['group' => 'Languages', 'items' => ['*PHP', '*JavaScript', '*TypeScript', 'Java', 'Dart']],
        ['group' => 'Frontend', 'items' => ['*React.js', 'Next.js', 'Vue.js', '*Flutter', '*Tailwind CSS', 'UI animation|motion & micro-interactions', 'Bootstrap', 'HTML/CSS']],
        ['group' => 'Backend', 'items' => ['*Laravel|pairs with React & Vue', '*Node.js', '*REST APIs', 'BullMQ|job queues', 'HyperBase', 'ZoneMTA|mail transfer', 'OOP · MVC', 'Microservices', 'Webhooks', 'Third-party SDKs']],
        ['group' => 'Data & Services', 'items' => ['*MySQL', '*PostgreSQL', 'MongoDB', 'Redis', 'Supabase', 'Stripe|payments', 'Razorpay|payments', 'Twilio', 'MSG91|real-time OTP', 'Pusher|realtime', 'Brevo|SMTP']],
        ['group' => 'Cloud & DevOps', 'items' => ['*VPS administration|5+ instances', '*Linux', '*Nginx', '*Docker', 'CI/CD', 'SSH deployments', 'Monitoring', 'AWS', 'Apache', 'Caddy', 'Bash']],
        ['group' => 'Testing', 'items' => ['PHPUnit', 'Jest', 'Vitest']],
        ['group' => 'Security', 'items' => ['HMAC signing|requests & webhooks', '2FA / MFA', 'OTP verification', 'Role-based access', 'JWT / OAuth']],
        ['group' => 'AI & Automation', 'items' => ['*Anthropic API (Claude)', 'OpenAI API', '*n8n|self-hosted', 'AI chat agents|on company data', 'Multi-model workflows']],
        ['group' => 'Practices', 'items' => ['System design|ERD & data modelling', 'Queues & jobs', 'Agile / SDLC']],
    ],

    'experience' => [
        [
            'role' => 'Software Developer',
            'company' => 'Collabmate',
            'location' => 'Salt Lake Sector V, Kolkata',
            'period' => 'Jun 2026 — Oct 2026',
            'current' => false,
            'summary' => 'Single-handedly owned the technical foundation: architecture, infrastructure, automation and AI, across web and mobile, until the company ceased operations in October 2026.',
            'points' => [
                'Provisioned, secured and ran 5+ production VPS instances single-handedly, including a self-hosted n8n instance: Linux, Nginx, Docker, deployments and monitoring.',
                'Automated deployments with SSH-based CI/CD pipelines and self-hosted n8n workflows, removing manual release steps.',
                'Rebuilt the primary website and raised its performance score from ~80 to 95+ through caching, asset and image optimisation, and server tuning.',
                'Built a company chat agent grounded in real company data, orchestrating Anthropic Claude and OpenAI models through n8n.',
                'Automated bulk email campaigns through Brevo SMTP, wired into n8n workflows.',
                'Designed and shipped web applications and Flutter apps end to end, from architecture to production, and optimised the existing Flutter apps.',
            ],
            'tags' => ['DevOps', 'VPS', 'Docker', 'Linux', 'Nginx', 'CI/CD', 'Self-hosted n8n', 'Anthropic API (Claude)', 'AI agents', 'Monitoring', 'Flutter', 'Brevo'],
        ],
        [
            'role' => 'Software Developer',
            'company' => 'Triophase Global Services',
            'location' => 'Newtown, India',
            'period' => 'Nov 2025 — May 2026',
            'summary' => 'Shipped three distinct platforms across education, email infrastructure and mentorship.',
            'points' => [
                'Built full-stack applications on Laravel, Node.js, React, Docker, Nginx and HyperBase.',
                'Delivered a college management system, a mail server platform, and a client-service platform for mentor-led guidance.',
                'Owned API development, feature delivery, debugging, deployment and ongoing maintenance.',
            ],
            'tags' => ['Laravel', 'Node.js', 'Docker', 'Nginx', 'HyperBase'],
        ],
        [
            'role' => 'Software Development Engineer (SDE-1)',
            'company' => 'Spiral Compute',
            'location' => 'Remote — New Zealand',
            'period' => 'Mar 2024 — Oct 2025',
            'summary' => 'Worked remotely with a New Zealand team across time zones on production Laravel + React products.',
            'points' => [
                'Built scalable full-stack features in Laravel and React, improving maintainability and delivery speed.',
                'Designed REST APIs and reusable interface components; integrated AI services and third-party SDKs.',
                'Worked across backend services, frontend workflows and production engineering practices.',
                'Built features in Node.js and Next.js, integrating Strapi CMS and HubSpot CRM.',
                'Owned end-to-end delivery of a key project following the SDLC: system design, ERD and database modelling, API and frontend development.',
            ],
            'tags' => ['Laravel', 'React', 'Node.js', 'Next.js', 'Strapi', 'HubSpot', 'REST', 'AI SDKs'],
        ],
        [
            'role' => 'Web Developer',
            'company' => 'FXBS',
            'location' => 'Kolkata, India',
            'period' => 'Jul 2023 — Feb 2024',
            'summary' => 'Where it started — shipping client work on the fundamentals.',
            'points' => [
                'Built and maintained responsive web applications in Core PHP, HTML, CSS and JavaScript.',
                'Took part in debugging, testing, optimisation and production releases across client projects.',
            ],
            'tags' => ['PHP', 'JavaScript', 'Responsive UI'],
        ],
    ],

    'projects' => [
        [
            'slug' => 'wonati',
            'name' => 'Wonati.ai',
            'kind' => 'AI content platform',
            'role' => 'Full-Stack Developer',
            'blurb' => 'A multi-model AI content platform that routes work across OpenAI and Anthropic models — with billing, real-time events and role-based workflows built in.',
            'highlights' => [
                'Model-routing layer over OpenAI and Anthropic APIs behind one interface',
                'Stripe subscriptions and usage-based payments',
                'Real-time generation status streamed over Pusher',
                'Role-based workflows for teams and reviewers',
            ],
            'stack' => ['React', 'TypeScript', 'Axios', 'Context API', 'Laravel', 'PostgreSQL', 'Stripe', 'Pusher'],
            'diagram' => [
                'nodes' => [
                    ['id' => 'ui', 'label' => 'React · TS', 'x' => 12, 'y' => 50],
                    ['id' => 'api', 'label' => 'Laravel API', 'x' => 42, 'y' => 50, 'core' => true],
                    ['id' => 'llm', 'label' => 'Anthropic API', 'x' => 78, 'y' => 18],
                    ['id' => 'img', 'label' => 'OpenAI API', 'x' => 84, 'y' => 44],
                    ['id' => 'db', 'label' => 'PostgreSQL', 'x' => 76, 'y' => 82],
                    ['id' => 'pay', 'label' => 'Stripe', 'x' => 42, 'y' => 88],
                    ['id' => 'rt', 'label' => 'Pusher', 'x' => 24, 'y' => 14],
                ],
                'edges' => [['ui', 'api'], ['api', 'llm'], ['api', 'img'], ['api', 'db'], ['api', 'pay'], ['api', 'rt'], ['rt', 'ui']],
            ],
        ],
        [
            'slug' => 'finvena',
            'name' => 'Finvena',
            'kind' => 'Fintech job marketplace',
            'role' => 'Full-Stack Developer',
            'blurb' => 'A two-sided marketplace for fintech hiring, with role-specific dashboards, real-time chat, payments and SMS/voice communication flows.',
            'highlights' => [
                'Separate dashboards and permissions for candidates, employers and admins',
                'Real-time chat between both sides of the marketplace',
                'Stripe payments for listings and services',
                'Twilio-powered communication workflows',
            ],
            'stack' => ['Laravel', 'MySQL', 'jQuery', 'Stripe', 'Twilio'],
            'links' => [
                ['label' => 'Live site', 'url' => 'https://finvena.com/'],
            ],
            'diagram' => [
                'nodes' => [
                    ['id' => 'cand', 'label' => 'Candidates', 'x' => 12, 'y' => 22],
                    ['id' => 'emp', 'label' => 'Employers', 'x' => 12, 'y' => 78],
                    ['id' => 'app', 'label' => 'Laravel core', 'x' => 46, 'y' => 50, 'core' => true],
                    ['id' => 'chat', 'label' => 'Realtime chat', 'x' => 82, 'y' => 16],
                    ['id' => 'db', 'label' => 'MySQL', 'x' => 86, 'y' => 50],
                    ['id' => 'pay', 'label' => 'Stripe', 'x' => 78, 'y' => 84],
                    ['id' => 'sms', 'label' => 'Twilio', 'x' => 46, 'y' => 90],
                ],
                'edges' => [['cand', 'app'], ['emp', 'app'], ['app', 'chat'], ['app', 'db'], ['app', 'pay'], ['app', 'sms']],
            ],
        ],
        [
            'slug' => 'ehlostack',
            'name' => 'EhloStack',
            'kind' => 'Mail server platform',
            'role' => 'Full-Stack Developer',
            'blurb' => 'Self-hosted email infrastructure with a control plane: ZoneMTA delivery, containerised services and dashboards for managing and monitoring it all.',
            'highlights' => [
                'Outbound delivery pipeline built on ZoneMTA',
                'Infrastructure management UI for domains and services',
                'Monitoring dashboards for delivery health',
                'Containerised with Docker behind Nginx',
            ],
            'stack' => ['React', 'TypeScript', 'Node.js', 'ZoneMTA', 'Docker', 'Nginx', 'Sentry'],
            'diagram' => [
                'nodes' => [
                    ['id' => 'dash', 'label' => 'React console', 'x' => 12, 'y' => 50],
                    ['id' => 'nginx', 'label' => 'Nginx', 'x' => 34, 'y' => 50],
                    ['id' => 'node', 'label' => 'Node.js API', 'x' => 58, 'y' => 30, 'core' => true],
                    ['id' => 'mta', 'label' => 'ZoneMTA', 'x' => 58, 'y' => 74],
                    ['id' => 'mon', 'label' => 'Monitoring', 'x' => 86, 'y' => 18],
                    ['id' => 'out', 'label' => 'SMTP out', 'x' => 88, 'y' => 78],
                ],
                'edges' => [['dash', 'nginx'], ['nginx', 'node'], ['node', 'mta'], ['node', 'mon'], ['mta', 'out'], ['mta', 'mon']],
            ],
        ],
    ],

    /*
    | Extra case studies with their own page at /work/{slug}, beyond the featured
    | projects above (which get pages automatically). Same shape as a project,
    | plus optional `links` and `sections` (titled paragraphs).
    */
    'case_studies' => [
        [
            'slug' => 'samgeet',
            'name' => 'Samgeet',
            'kind' => 'Open-source Android music player',
            'role' => 'Creator · design, app, backend',
            'blurb' => 'An open-source, ad-free music player for Android: up to 320 kbps streaming, offline downloads, mood-matched autoplay, on-device taste learning and a library that syncs across phones.',
            'highlights' => [
                'On-device taste profile that learns from likes, skips and completions and feeds a pure ranking recommender for autoplay',
                'Mood theme: the whole app recolours itself to the inferred mood of the current song',
                'Library sync across phones with merging (deletions included); the password is stretched with PBKDF2 on the phone, so the server only ever stores a hash',
                'Small PHP + MySQL backend with signed requests, session tokens and an admin panel for listening reports, app updates and in-app messages',
                'Short share links with Android App Links: they open straight in the app, with a web page as the fallback',
                'Equalizer with saved custom sounds, offline downloads, background playback with lock-screen controls, and voice control through Google Assistant and Bixby',
            ],
            'stack' => ['Flutter', 'Dart', 'PHP', 'MySQL', 'Android'],
            'links' => [
                ['label' => 'Source on GitHub', 'url' => 'https://github.com/loco0011/samgeet'],
                ['label' => 'Download the APK', 'url' => 'https://github.com/loco0011/samgeet/releases/latest'],
            ],
            'sections' => [
                ['title' => 'Why I built it', 'body' => 'I wanted a music player without ads that still felt smart: good recommendations, a library on every phone I use and sound I could tune. Samgeet is that app, built end to end and released as open source under the MIT licence.'],
                ['title' => 'How it is put together', 'body' => 'The Flutter app is split into four layers: data (API client, models, library store, account sync, share links), engine (taste profile, a pure ranking recommender and candidate gathering), player (queue, autoplay refills, sleep timer, error recovery, equalizer) and UI. Keeping the recommender a pure function made it easy to unit-test offline.'],
                ['title' => 'Privacy and accounts', 'body' => 'Signing in needs only an email and a password. The password never leaves the phone: PBKDF2 with 150,000 rounds turns it into the account key and the server stores only a hash. Every API request is signed with a key built into release builds, so the server only answers the app, and each account can only reach its own data.'],
                ['title' => 'Details that took the longest', 'body' => 'Background playback had to survive the app being swiped away, with working lock-screen controls on Android 13+. A release-only bug, where the resource shrinker deleted the notification icons the audio plugin loads by name, was fixed with an explicit keep rule. The layout switches from a bottom bar to a side rail at 720 dp and was verified on phones and tablets in both orientations.'],
            ],
            'diagram' => [
                'nodes' => [
                    ['id' => 'ui', 'label' => 'Flutter UI', 'x' => 12, 'y' => 50],
                    ['id' => 'player', 'label' => 'Player', 'x' => 36, 'y' => 18],
                    ['id' => 'engine', 'label' => 'Taste engine', 'x' => 36, 'y' => 82],
                    ['id' => 'data', 'label' => 'Data & sync', 'x' => 52, 'y' => 50, 'core' => true],
                    ['id' => 'api', 'label' => 'PHP API', 'x' => 76, 'y' => 30],
                    ['id' => 'db', 'label' => 'MySQL', 'x' => 90, 'y' => 62],
                    ['id' => 'share', 'label' => 'Share pages', 'x' => 72, 'y' => 86],
                ],
                'edges' => [['ui', 'player'], ['ui', 'engine'], ['ui', 'data'], ['engine', 'data'], ['data', 'api'], ['api', 'db'], ['api', 'share']],
            ],
        ],
    ],

    /*
    | Total shown on the site ("25+ projects"). Bump it as you ship more.
    */
    'projects_total' => 25,

    /*
    | "More projects" list under the featured cards. One line per project, no
    | diagram needed. Required: name, kind. Optional: year, org, stack (array),
    | url (adds an ↗ link), card (chat|infra|cms|crm|earning) promotes the
    | project to an animated card in the "Also built" grid instead of a row.
    | Blank years show as "—" and sort after dated ones.
    | The first 8 show, the rest sit behind a "Show all" button.
    */
    'archive' => [
        ['name' => 'Company AI chat agent', 'card' => 'chat', 'kind' => 'AI agent on company data', 'year' => '2026', 'org' => 'Collabmate', 'stack' => ['n8n', 'Anthropic API (Claude)', 'OpenAI']],
        ['name' => 'Primary website rebuild', 'kind' => 'Performance ~80 → 95+', 'year' => '2026', 'org' => 'Collabmate', 'stack' => ['Laravel', 'Tailwind', 'Nginx']],
        ['name' => 'Self-hosted infra & automation', 'card' => 'infra', 'kind' => '5+ VPS, self-hosted n8n, CI/CD', 'year' => '2026', 'org' => 'Collabmate', 'stack' => ['Docker', 'Nginx', 'n8n', 'Linux']],
        ['name' => 'Bulk email automation', 'kind' => 'Campaign pipeline', 'year' => '2026', 'org' => 'Collabmate', 'stack' => ['n8n', 'Brevo SMTP']],
        ['name' => 'Cross-platform mobile app', 'kind' => 'Flutter app on a shared API', 'year' => '2026', 'org' => 'Collabmate', 'stack' => ['Flutter', 'Dart', 'REST']],
        ['name' => 'College management system', 'kind' => 'Education ERP', 'year' => '2026', 'org' => 'Triophase', 'stack' => ['Laravel', 'React', 'MySQL']],
        ['name' => 'Mentor-led guidance platform', 'kind' => 'Client-service platform', 'year' => '2025', 'org' => 'Triophase', 'stack' => ['Laravel', 'Node.js', 'React', 'Docker']],
        ['name' => 'Earning platform app', 'card' => 'earning', 'kind' => 'Mobile rewards & earning app', 'stack' => ['Flutter', 'Dart']],
        ['name' => 'Astrology platform', 'kind' => 'Consumer astrology product'],
        ['name' => 'Inventory management system', 'kind' => 'Stock tracking for a business', 'org' => 'Spiral Compute'],
        ['name' => 'CRM systems', 'card' => 'crm', 'kind' => 'Custom CRMs for businesses: drag-and-drop deal pipelines, proposals to invoices, and meetings booked on Google Calendar with Meet links', 'stack' => ['Laravel', 'React', 'Google Calendar API', 'Google Meet']],
        ['name' => 'ID card management system', 'kind' => 'Issue & manage identity cards'],
        ['name' => 'Business & company websites', 'kind' => 'Multiple sites for different businesses'],
        ['name' => 'Fintello', 'kind' => 'Marketing platform for financial and insurance advisors: leads, LinkedIn outreach, ad and email campaigns, websites and funnels', 'url' => 'https://fintello.com/'],
        ['name' => 'Multi-LLM content SaaS', 'card' => 'cms', 'kind' => 'Writes custom content from a prompt across several AI models, then publishes it to HubSpot and Strapi', 'stack' => ['Next.js', 'Node.js', 'OpenAI', 'Anthropic API (Claude)', 'HubSpot', 'Strapi']],
        ['name' => 'Open-source contributions', 'kind' => 'Contributions to public projects', 'url' => 'https://github.com/loco0011'],
    ],

    /*
    | "After hours": products built on my own time, shown one at a time on a live
    | stage beside their system map. `visual` picks the animated mini-UI
    | (samgeet|hiringmap|savr|xypher|kashew|klyro); `diagram` has the same shape as a
    | featured project's. `icon` is a path under public/.
    | The rest feeds the app's own page at /apps/{slug}: `downloads` lists the APK
    | builds (uploaded in the admin panel, or `external` for a link such as a GitHub release), `screenshots` are paths under public/,
    | and each feature's `viz` is [chat, [line, line]], [rows, [[label, note, on]]]
    | or [bars, [[label, percent, value]]].
    */
    'side_projects' => [
        [
            'slug' => 'samgeet',
            'name' => 'Samgeet',
            'tag' => 'music player',
            'meta' => 'Personal · Android · open source',
            'icon' => '/img/apps/samgeet.png',
            'visual' => 'samgeet',
            'kind' => 'An open-source, ad-free music player: up to 320 kbps streaming, offline downloads, mood-matched autoplay and a library that syncs across phones.',
            'highlights' => [
                'An on-device taste profile learns from likes, skips and full plays, and feeds a pure ranking recommender for autoplay',
                'Library sync across phones; the password is stretched with PBKDF2 on the phone, so the server only ever stores a hash',
            ],
            'stack' => ['Flutter', 'Dart', 'PHP', 'MySQL', 'Android'],
            'diagram' => [
                'nodes' => [
                    ['id' => 'ui', 'label' => 'Flutter UI', 'x' => 10, 'y' => 50],
                    ['id' => 'player', 'label' => 'Player', 'x' => 34, 'y' => 12],
                    ['id' => 'engine', 'label' => 'Taste engine', 'x' => 34, 'y' => 88],
                    ['id' => 'data', 'label' => 'Data & sync', 'x' => 52, 'y' => 50, 'core' => true],
                    ['id' => 'api', 'label' => 'PHP API', 'x' => 80, 'y' => 24],
                    ['id' => 'db', 'label' => 'MySQL', 'x' => 92, 'y' => 62],
                    ['id' => 'share', 'label' => 'Share pages', 'x' => 74, 'y' => 92],
                ],
                'edges' => [['ui', 'player'], ['ui', 'engine'], ['ui', 'data'], ['engine', 'data'], ['data', 'api'], ['api', 'db'], ['api', 'share']],
            ],
            'platform' => 'android',
            'category' => 'MultimediaApplication',
            'color' => '#e8455b',
            'status' => 'Available',
            'website' => null,
            'repo' => 'https://github.com/loco0011/samgeet',
            'headline' => 'All your music.',
            'headline_accent' => 'None of the ads.',
            'lede' => 'Samgeet plays millions of songs in Hindi, Bengali, Punjabi, English and more, learns what you love, and keeps your playlists on every phone you sign in on. No ads, no subscription.',
            'features' => [
                ['eyebrow' => 'Millions of songs', 'title' => 'Every language,', 'title_accent' => 'every mood.', 'body' => 'From Bollywood to K-pop, in up to 320 kbps. Download songs and whole albums to listen offline in the same quality.', 'tags' => ['Hindi', 'Bengali', 'Punjabi', 'English', 'Tamil', 'Telugu', 'Marathi', 'Gujarati', 'Kannada', 'Malayalam', 'K-pop'], 'tags_alt' => ['Chill', 'Workout', 'Heartbreak', 'Focus', 'Rainy day', '90s', 'Party', 'Devotional']],
                ['title' => 'Autoplay that gets you', 'body' => 'It learns from your likes, skips and full plays, then keeps the music going in the same mood.', 'viz' => ['chat', ['Up next: more like Kesariya', 'Matched to your mood']]],
                ['title' => 'Tune the sound', 'body' => 'An equalizer with presets, or your own curve saved as a custom sound.', 'viz' => ['bars', [['bass', 78, '+6'], ['mids', 52, '0'], ['vocals', 66, '+3'], ['treble', 58, '+2']]]],
                ['title' => 'Your library, everywhere', 'body' => 'Sign in and your playlists, likes and history follow you to any phone. Share a song or playlist with a short link.', 'viz' => ['rows', [['Playlists', 'synced', true], ['Liked songs', 'synced', true], ['Downloads', 'on this phone', false]]]],
                ['stat' => '320', 'title' => 'kbps', 'body' => 'Streaming and offline downloads in the same quality.', 'tags' => ['Offline', 'Lock screen', 'Voice']],
            ],
            'free' => 'Open source, no ads.',
            'screenshots' => [
                ['src' => '/img/apps/samgeet/cover.jpg', 'title' => 'Cover', 'caption' => 'Big artwork, classic bar'],
                ['src' => '/img/apps/samgeet/disc.jpg', 'title' => 'Disc', 'caption' => 'The spinning record'],
                ['src' => '/img/apps/samgeet/immersive.jpg', 'title' => 'Immersive', 'caption' => 'Art behind frosted glass'],
                ['src' => '/img/apps/samgeet/minimal.jpg', 'title' => 'Minimal', 'caption' => 'Big type, nothing else'],
            ],
            'downloads' => [['key' => 'app', 'label' => 'Download Samgeet', 'file' => 'Samgeet', 'external' => 'https://github.com/loco0011/samgeet/releases/latest/download/Samgeet.apk', 'source' => 'GitHub Releases']],
            'permissions' => 'Samgeet needs internet to stream and notifications for the lock-screen player. An approximate, city-level location is optional and off by default. It never asks for your contacts, photos or microphone.',
            'faq' => [
                ['q' => 'Is it really free?', 'a' => 'Yes. No ads and no subscription, and the code is open source under the MIT licence.'],
                ['q' => 'Do I need an account?', 'a' => 'No. You can listen as a guest. Signing in syncs your library across phones and lets you share playlists.'],
                ['q' => 'Which phones does it work on?', 'a' => 'Android 7.0 or newer. Play Protect may warn because it is not from the Play Store; choose "Install anyway".'],
                ['q' => 'Does it work on iPhone?', 'a' => 'Not yet. Samgeet is Android-only for now.'],
            ],
        ],
        [
            'slug' => 'xypher',
            'name' => 'Xypher',
            'tag' => 'password manager',
            'meta' => 'Personal · Android + web · v1.2',
            'icon' => '/img/apps/xypher.png',
            'visual' => 'xypher',
            'kind' => 'A password vault that cannot read your passwords. Logins, cards and notes, with a built-in 2FA authenticator, offline by default.',
            'highlights' => [
                'Zero-knowledge: Argon2id (64 MiB) derives the key, AES-256-GCM seals the vault, and the server only ever stores ciphertext',
                'The breach scan sends 5 characters of a hash, never the password',
            ],
            'stack' => ['Flutter', 'Argon2id', 'Keystore', 'PHP · MySQL'],
            'platform' => 'android',
            'category' => 'SecurityApplication',
            'color' => '#2bd4c4',
            'status' => 'Available',
            'website' => null,
            'repo' => 'https://github.com/loco0011/xypher',
            'headline' => 'A password vault',
            'headline_accent' => 'that can\'t read your passwords.',
            'lede' => 'Keep your logins, cards, notes and Wi-Fi passwords in one place, locked with a master password only you know. It works offline, with optional encrypted sync to your other devices.',
            'features' => [
                ['eyebrow' => 'One vault', 'title' => 'All your logins,', 'title_accent' => 'locked by you.', 'body' => 'Logins, payment cards, secure notes, identities and Wi-Fi, with tags, favourites and search. Bring your passwords over from the browser or manager you use now.', 'tags' => ['Logins', 'Cards', 'Secure notes', 'Identities', 'Wi-Fi', 'Custom fields', 'Favourites'], 'tags_alt' => ['Import: Chrome', 'Bitwarden', '1Password', 'LastPass', 'Firefox', 'Safari', 'Edge', 'Dashlane', 'Proton Pass']],
                ['title' => '2FA codes built in', 'body' => 'Keep your two-factor codes next to the passwords they protect, with a live countdown.', 'viz' => ['rows', [['GitHub', 'code ready', true], ['Google', 'code ready', false]]]],
                ['title' => 'Security check-up', 'body' => 'A health score, plus lists of weak, reused, old and breached passwords to fix.', 'viz' => ['bars', [['strong', 86, ''], ['weak', 9, ''], ['reused', 5, '']]]],
                ['title' => 'Unlock with your fingerprint', 'body' => 'The vault locks itself when you leave the app, copied passwords clear themselves, and screenshots are blocked.', 'viz' => ['rows', [['Fingerprint', 'unlocked', true], ['Auto-lock', 'when you leave', false], ['Clipboard', 'clears itself', false]]]],
                ['stat' => '5', 'title' => 'characters', 'body' => 'All a breach check sends. Your password never leaves the phone.', 'tags' => ['Offline', 'Encrypted', 'Zero-knowledge']],
            ],
            'free' => 'Works offline.',
            'screenshots' => [
                ['src' => '/img/apps/xypher/vault.jpg', 'title' => 'Vault', 'caption' => 'Everything in one encrypted vault'],
                ['src' => '/img/apps/xypher/authenticator.jpg', 'title' => 'Authenticator', 'caption' => '2FA codes next to your logins'],
                ['src' => '/img/apps/xypher/radar.jpg', 'title' => 'Security radar', 'caption' => 'A health score for your passwords'],
                ['src' => '/img/apps/xypher/generator.jpg', 'title' => 'Generator', 'caption' => 'Strong passwords in one tap'],
            ],
            'downloads' => [['key' => 'app', 'label' => 'Download Xypher', 'file' => 'Xypher']],
            'permissions' => 'Xypher only uses the internet for the optional sync and breach check, and your fingerprint or face to unlock. It never asks for your contacts, photos or location.',
            'faq' => [
                ['q' => 'Is it really free?', 'a' => 'Yes. No ads, no subscription.'],
                ['q' => 'What if I forget my master password?', 'a' => 'Nobody can reset it, not even me. That\'s what keeps the vault private. Export an encrypted backup from Settings and keep your master password somewhere safe.'],
                ['q' => 'Can I use it on my computer?', 'a' => 'Yes. Turn on cloud sync in Settings and open the web vault in your browser. It is decrypted on your computer, never on the server.'],
                ['q' => 'Does it work on iPhone?', 'a' => 'Not yet. Xypher is Android-only for now.'],
            ],
            'diagram' => [
                'nodes' => [
                    ['id' => 'pw', 'label' => 'Master password', 'x' => 8, 'y' => 16],
                    ['id' => 'kdf', 'label' => 'Argon2id', 'x' => 8, 'y' => 84],
                    ['id' => 'vault', 'label' => 'AES-GCM vault', 'x' => 46, 'y' => 50, 'core' => true],
                    ['id' => 'bio', 'label' => 'Keystore · biometric', 'x' => 48, 'y' => 4],
                    ['id' => 'cloud', 'label' => 'Cloud (ciphertext)', 'x' => 88, 'y' => 24],
                    ['id' => 'hibp', 'label' => 'HIBP k-anon', 'x' => 88, 'y' => 80],
                ],
                'edges' => [['pw', 'kdf'], ['kdf', 'vault'], ['bio', 'vault'], ['vault', 'cloud'], ['vault', 'hibp']],
            ],
        ],
        [
            'slug' => 'kolkata-hiring-map',
            'name' => 'Kolkata Hiring Map',
            'tag' => 'hiring map',
            'meta' => 'Personal · web · launch-ready',
            'icon' => '/img/apps/kolkata-hiring-map.svg',
            'visual' => 'hiringmap',
            'kind' => 'A public map of who is hiring in Kolkata, where, and at what pay. A discovery layer, not a job portal: every role links out to where it was actually posted.',
            'highlights' => [
                'A Django fetcher reads nine job-board systems (Greenhouse, Lever, Keka, Darwinbox and more), keeps the Kolkata roles and queues them for review',
                'A nightly link check re-confirms every posting and closes dead ones, so nothing stale stays on the map',
            ],
            'stack' => ['Laravel', 'Inertia · React', 'Django', 'Leaflet', 'Docker'],
            'platform' => 'web',
            'category' => 'BusinessApplication',
            'color' => '#22c487',
            'status' => 'Launching soon',
            'website' => null,
            'repo' => null,
            'headline' => 'Who\'s hiring in Kolkata,',
            'headline_accent' => 'on one map.',
            'lede' => 'See which companies are hiring, in which part of the city, and what they pay. Every role links straight to where it was posted, so you apply on the company\'s own page. No sign-up, no resumes, no middleman.',
            'features' => [
                ['eyebrow' => 'Every area', 'title' => 'See who\'s hiring', 'title_accent' => 'near you.', 'body' => 'Pins show how many open roles each company has, grouped by area: Salt Lake Sector V, Rajarhat New Town, Park Street and more.', 'tags' => ['Salt Lake Sector V', 'Rajarhat New Town', 'Park Street', 'Camac Street', 'Ballygunge', 'Howrah', 'Garia', 'Dum Dum', 'Jadavpur', 'Behala'], 'tags_alt' => ['Internships', 'Freshers', 'This week', 'IT', 'Manufacturing', 'Finance', 'Sales', 'Design']],
                ['title' => 'Real roles, real links', 'body' => 'Every role opens the company\'s own posting. You never apply through us.', 'viz' => ['chat', ['Open posting ↗', 'Opens on the company\'s careers page']]],
                ['title' => 'Checked every night', 'body' => 'Links are re-checked nightly, and dead postings come off the map.', 'viz' => ['rows', [['Posting still live', 'kept', true], ['Link went dead', 'removed', false]]]],
                ['title' => 'Filters that matter', 'body' => 'Show only internships, roles for freshers, or what was posted this week.', 'viz' => ['rows', [['Internships', 'on', true], ['Freshers', 'on', true], ['Posted this week', 'off', false]]]],
                ['stat' => '18', 'title' => 'hiring areas', 'body' => 'Across Kolkata, from Sector V to Howrah.', 'tags' => ['Sector V', 'New Town', 'Howrah', '+15']],
            ],
            'free' => 'Free, no sign-up.',
            'screenshots' => [
                ['src' => '/img/apps/kolkata-hiring-map/map.jpg', 'title' => 'The map', 'caption' => 'Who\'s hiring, area by area'],
                ['src' => '/img/apps/kolkata-hiring-map/company.jpg', 'title' => 'A company', 'caption' => 'Its open roles and a link to the real posting'],
                ['src' => '/img/apps/kolkata-hiring-map/openings.jpg', 'title' => 'Openings', 'caption' => 'Every live role in one list'],
            ],
            'downloads' => [],
            'permissions' => null,
            'faq' => [
                ['q' => 'Is this a job portal?', 'a' => 'No. It\'s a map of who is hiring. There are no applications or resumes here: every role links to the company\'s own posting, and you apply there.'],
                ['q' => 'How fresh are the listings?', 'a' => 'Every posting\'s link is checked every night. If a link stops working, the role is taken off the map.'],
                ['q' => 'Can I add a company or an opening?', 'a' => 'Yes. Use the suggestion form on the site. Every suggestion is reviewed before it goes on the map.'],
                ['q' => 'Does it cost anything?', 'a' => 'No. It\'s free to use, with no sign-up.'],
            ],
            'diagram' => [
                'nodes' => [
                    ['id' => 'ats', 'label' => 'Job boards', 'x' => 8, 'y' => 16],
                    ['id' => 'fetch', 'label' => 'Django fetcher', 'x' => 12, 'y' => 84],
                    ['id' => 'api', 'label' => 'Laravel', 'x' => 48, 'y' => 50, 'core' => true],
                    ['id' => 'cron', 'label' => 'Nightly link check', 'x' => 52, 'y' => 6],
                    ['id' => 'map', 'label' => 'React · Leaflet', 'x' => 88, 'y' => 24],
                    ['id' => 'mod', 'label' => 'Moderation', 'x' => 88, 'y' => 76],
                    ['id' => 'db', 'label' => 'MariaDB', 'x' => 52, 'y' => 96],
                ],
                'edges' => [['ats', 'fetch'], ['fetch', 'api'], ['cron', 'api'], ['api', 'map'], ['mod', 'api'], ['api', 'db']],
            ],
        ],
        [
            'slug' => 'savr',
            'name' => 'Savr',
            'tag' => 'price comparison',
            'meta' => 'Personal · Android · in testing',
            'icon' => '/img/apps/savr.png',
            'visual' => 'savr',
            'kind' => 'Which app is cheapest for this, right now? Compares what you would actually pay across quick commerce, grocery, shopping, food and ride apps.',
            'highlights' => [
                'No public price APIs, and plain requests get bot-blocked, so it reads each platform\'s own search page in a hidden WebView on the phone',
                'Blinkit, Zepto and Instamart fetched in parallel in about 5 s, with no server',
            ],
            'stack' => ['Flutter', 'Dart', 'WebView', 'Share intents'],
            'platform' => 'android',
            'category' => 'ShoppingApplication',
            'color' => '#2fbf8a',
            'status' => 'In testing',
            'website' => null,
            'repo' => 'https://github.com/loco0011/savr',
            'headline' => 'Find the cheapest price',
            'headline_accent' => 'before you order.',
            'lede' => 'Search for anything once and Savr shows what it costs on Blinkit, Zepto and Instamart right now, delivery fee included. Share a product from any shopping app to compare it in one tap.',
            'features' => [
                ['eyebrow' => 'One search', 'title' => 'Every app,', 'title_accent' => 'side by side.', 'body' => 'Compare quick commerce, groceries, shopping, food delivery and rides without opening five apps.', 'tags' => ['Blinkit', 'Zepto', 'Swiggy Instamart', 'BigBasket', 'Amazon', 'Flipkart', 'Myntra', 'Zomato', 'Uber', 'Ola', 'Rapido'], 'tags_alt' => ['Cheapest: Zepto', 'Fastest: Blinkit', 'Delivery fee included', 'Price per 100 g', 'In stock now']],
                ['title' => 'Share to compare', 'body' => 'Looking at something in Blinkit or Amazon? Tap Share, pick Savr, and it finds the same thing elsewhere.', 'viz' => ['chat', ['Shared from Blinkit: Amul Taaza 500 ml', 'Found on 2 more apps']]],
                ['title' => 'What you\'d actually pay', 'body' => 'Delivery and handling fees are added in, so the cheapest really is the cheapest.', 'viz' => ['rows', [['Blinkit', '₹28 · 9 min', false], ['Zepto', '₹27 · 11 min', true], ['Instamart', '₹29 · 14 min', false]]]],
                ['title' => 'Rides, too', 'body' => 'See estimated fares for Uber, Ola, Rapido and more before you book. Estimates are always marked as estimates.', 'viz' => ['rows', [['Rapido bike', 'estimate', true], ['Ola auto', 'estimate', false], ['Uber Go', 'estimate', false]]]],
                ['stat' => '25+', 'title' => 'apps compared', 'body' => 'Groceries, shopping, food and rides.', 'tags' => ['Quick', 'Grocery', 'Shopping', 'Food', 'Rides']],
            ],
            'free' => 'No sign-up, no ads.',
            'screenshots' => [
                ['src' => '/img/apps/savr/home.jpg', 'title' => 'Compare', 'caption' => 'Search once, or paste a link'],
                ['src' => '/img/apps/savr/checking.jpg', 'title' => 'Live prices', 'caption' => 'Checks every app near you'],
                ['src' => '/img/apps/savr/rides.jpg', 'title' => 'Rides', 'caption' => 'Fares across Rapido, Ola and Uber'],
                ['src' => '/img/apps/savr/welcome.jpg', 'title' => 'Welcome', 'caption' => 'Use it as a guest, no sign-up'],
            ],
            'downloads' => [['key' => 'app', 'label' => 'Download Savr', 'file' => 'Savr']],
            'permissions' => 'Savr only asks for your location (to show prices for your area) and the microphone (for voice search), and you can say no to both. It never asks for your contacts or photos.',
            'faq' => [
                ['q' => 'Is it really free?', 'a' => 'Yes. No ads, no subscription and no sign-up.'],
                ['q' => 'Why do prices change by area?', 'a' => 'Quick-commerce apps price by location and stock. Set your address once for each app under Settings → Live prices, and Savr shows the prices for your area.'],
                ['q' => 'Why isn\'t it on the Play Store?', 'a' => 'It\'s still in testing, so for now it\'s shared here directly. Downloading it from this page gets you the full app.'],
                ['q' => 'Does it work on iPhone?', 'a' => 'Not yet. Savr is Android-only for now.'],
            ],
            'diagram' => [
                'nodes' => [
                    ['id' => 'ui', 'label' => 'Search · Share', 'x' => 8, 'y' => 50],
                    ['id' => 'core', 'label' => 'Price engine', 'x' => 42, 'y' => 50, 'core' => true],
                    ['id' => 'bl', 'label' => 'Blinkit', 'x' => 86, 'y' => 8],
                    ['id' => 'ze', 'label' => 'Zepto', 'x' => 92, 'y' => 40],
                    ['id' => 'in', 'label' => 'Instamart', 'x' => 86, 'y' => 72],
                    ['id' => 'ride', 'label' => 'Ride estimator', 'x' => 26, 'y' => 96],
                    ['id' => 'srv', 'label' => 'Price server', 'x' => 62, 'y' => 96],
                ],
                'edges' => [['ui', 'core'], ['core', 'bl'], ['core', 'ze'], ['core', 'in'], ['core', 'ride'], ['core', 'srv']],
            ],
        ],
        [
            'slug' => 'kashew',
            'name' => 'Kashew',
            'tag' => 'expense tracker',
            'meta' => 'Personal · Android · v1.1',
            'icon' => '/img/apps/kashew.svg',
            'visual' => 'kashew',
            'kind' => 'An expense tracker that logs itself. Bank and UPI payments are read from SMS, and cash takes one tap or a sentence like "50 chai".',
            'highlights' => [
                'Parses messages from about 45 banks and skips OTPs, mandates, collect requests and failed payments',
                'Removes duplicates by UPI reference, and treats card-bill payments as transfers so nothing counts twice',
            ],
            'stack' => ['Flutter', 'SMS parsing', 'Voice entry', 'On-device'],
            'platform' => 'android',
            'category' => 'FinanceApplication',
            'color' => '#14b8a6',
            'status' => 'Available',
            'website' => null,
            'repo' => null,
            'headline' => 'An expense tracker',
            'headline_accent' => 'that logs itself.',
            'lede' => 'Typing in every expense never lasts. Kashew makes it effortless: log cash in one tap or by saying "50 chai", and, if you like, let it read your bank texts so card and UPI payments add themselves. No account, no internet.',
            'features' => [
                ['eyebrow' => 'Bank texts, sorted', 'title' => 'Payments that', 'title_accent' => 'add themselves.', 'body' => 'Your bank already texts you about every payment. The Auto-SMS version reads those texts on your phone and files each one: amount, shop and account.', 'tags' => ['HDFC', 'SBI', 'ICICI', 'Axis', 'Kotak', 'PNB', 'Bank of Baroda', 'IDFC', 'Yes Bank', 'IndusInd'], 'tags_alt' => ['Food', 'Groceries', 'Travel', 'Bills', 'Subscriptions', 'People', 'Shopping', 'Fuel']],
                ['title' => 'Say it, it\'s saved', 'body' => 'Say or type "spent 2k on groceries" and the amount, note and category fill in.', 'viz' => ['chat', ['"50 chai"', 'Chai · Food · −₹50']]],
                ['title' => 'Only real payments', 'body' => 'OTPs, reminders, failed payments and duplicates are skipped automatically.', 'viz' => ['rows', [['OTP message', 'skipped', false], ['Payment reminder', 'skipped', false], ['₹120 to Swiggy', 'saved', true]]]],
                ['title' => 'Cash counts too', 'body' => 'ATM withdrawals fill your cash wallet, so even cash you forget to log still shows up in the month.', 'viz' => ['rows', [['ATM withdrawal', '+₹2,000 cash', true], ['Chai', '−₹20', false], ['Cash not logged', 'still counted', false]]]],
                ['stat' => '~45', 'title' => 'banks', 'body' => 'Bank and card texts it understands.', 'tags' => ['UPI', 'Cards', 'ATM', 'NEFT']],
            ],
            'free' => 'No account, no internet.',
            'screenshots' => [
                ['src' => '/img/apps/kashew/overview.jpg', 'title' => 'Overview', 'caption' => 'Your month at a glance'],
                ['src' => '/img/apps/kashew/add.jpg', 'title' => 'Add expense', 'caption' => 'Log anything in two taps'],
                ['src' => '/img/apps/kashew/insights.jpg', 'title' => 'Insights', 'caption' => 'Where the money goes'],
                ['src' => '/img/apps/kashew/transactions.jpg', 'title' => 'Transactions', 'caption' => 'Every payment in one list'],
            ],
            'downloads' => [
                ['key' => 'standard', 'label' => 'Download Kashew', 'file' => 'Kashew'],
                ['key' => 'sms', 'label' => 'Auto-SMS version', 'file' => 'Kashew-AutoSMS', 'note' => 'Reads bank texts for you. Android\'s Play Protect may block it when you install from a browser; tap "Install anyway" if it\'s offered.'],
            ],
            'permissions' => 'The standard version asks for nothing. It only uses your fingerprint for the optional app lock. The Auto-SMS version asks to read your texts, but only when you turn automatic tracking on, and the texts never leave your phone: the app has no internet access.',
            'faq' => [
                ['q' => 'Which version should I get?', 'a' => 'Start with the standard one: it installs normally and you log spending by tap or voice. Get the Auto-SMS version if you want card and UPI payments added for you from your bank texts. You can switch later and keep your data.'],
                ['q' => 'Is my data safe?', 'a' => 'Everything stays on your phone. There is no account and no server, and the app can\'t go online.'],
                ['q' => 'What happens if I uninstall it?', 'a' => 'Your data is deleted with the app, because there is no cloud backup yet.'],
                ['q' => 'Does it work on iPhone?', 'a' => 'No. iPhones don\'t let apps read bank texts, so Kashew is Android-only.'],
            ],
            'diagram' => [
                'nodes' => [
                    ['id' => 'sms', 'label' => 'Bank SMS', 'x' => 8, 'y' => 16],
                    ['id' => 'voice', 'label' => 'Voice · tap', 'x' => 8, 'y' => 84],
                    ['id' => 'parse', 'label' => 'Parser', 'x' => 42, 'y' => 50, 'core' => true],
                    ['id' => 'filter', 'label' => 'Skip + dedupe', 'x' => 44, 'y' => 4],
                    ['id' => 'rules', 'label' => 'Category rules', 'x' => 84, 'y' => 20],
                    ['id' => 'db', 'label' => 'Local ledger', 'x' => 86, 'y' => 84],
                ],
                'edges' => [['sms', 'parse'], ['voice', 'parse'], ['parse', 'filter'], ['parse', 'rules'], ['rules', 'db'], ['parse', 'db']],
            ],
        ],
        [
            'slug' => 'klyro',
            'name' => 'Klyro',
            'tag' => 'interview prep',
            'meta' => 'Personal · cross-platform · in progress',
            'icon' => '/img/apps/klyro.png',
            'visual' => 'klyro',
            'kind' => 'Prepare for the interview you are actually going to face. It reads your CV and the job description, then quizzes you on what you claim.',
            'highlights' => [
                'Claude returns structured JSON that scores every answer on accuracy, completeness, relevance and clarity',
                'Mock interviews follow up on your answers and end with a debrief',
            ],
            'stack' => ['Flutter', 'Claude API', 'PDF · DOCX'],
            'platform' => 'android',
            'category' => 'EducationalApplication',
            'color' => '#6c63ff',
            'status' => 'Early access',
            'website' => null,
            'repo' => null,
            'headline' => 'Prepare for the real interview.',
            'headline_accent' => 'Not a generic one.',
            'lede' => 'Klyro reads your CV and the job you\'re applying for, then asks the questions that interviewer is likely to ask, scores your answers, and runs mock interviews that follow up like a real person.',
            'features' => [
                ['eyebrow' => 'From your CV', 'title' => 'Questions about', 'title_accent' => 'what you claim.', 'body' => 'Upload your CV as a PDF or Word file. Klyro picks out your skills and projects, and asks about them the way an interviewer would.', 'tags' => ['Laravel', 'React', 'PostgreSQL', 'Docker', 'REST APIs', 'Node.js', 'System design', 'TypeScript'], 'tags_alt' => ['Missing: Kubernetes', 'Weak: caching', 'Match: Laravel', 'Missing: GraphQL', 'Match: React', 'Weak: testing']],
                ['title' => 'Mock interviews that follow up', 'body' => 'The interviewer adapts to your answers and ends with a debrief.', 'viz' => ['chat', ['How would you cache an expensive API call?', 'Why that expiry time?']]],
                ['title' => 'Feedback you can use', 'body' => 'Every answer gets scores, what you missed and a better answer.', 'viz' => ['bars', [['accuracy', 86, '8.6'], ['completeness', 72, '7.2'], ['relevance', 91, '9.1'], ['clarity', 80, '8.0']]]],
                ['title' => 'A day-by-day plan', 'body' => 'Paste a job description and get a plan that covers your weak and missing skills before the date.', 'viz' => ['rows', [['Laravel queues', 'Day 1 ✓', true], ['Caching basics', 'Day 2 ✓', true], ['Mock interview', 'Day 3', false]]]],
                ['stat' => '26', 'title' => 'technologies', 'body' => 'Curated questions with explanations, code and links.', 'tags' => ['PHP', 'Python', 'Java', 'React', 'SQL', '+21']],
            ],
            'free' => 'Your data stays on your phone.',
            'screenshots' => [
                ['src' => '/img/apps/klyro/home.jpg', 'title' => 'Home', 'caption' => 'Start from your CV, a job or a topic'],
                ['src' => '/img/apps/klyro/practice.jpg', 'title' => 'Practice', 'caption' => 'Quick quizzes on your own stack'],
                ['src' => '/img/apps/klyro/prep.jpg', 'title' => 'Prep', 'caption' => 'Plans built from a job description'],
                ['src' => '/img/apps/klyro/interview.jpg', 'title' => 'Mock interview', 'caption' => 'Rehearse the real thing'],
            ],
            'downloads' => [['key' => 'app', 'label' => 'Download Klyro', 'file' => 'Klyro']],
            'permissions' => 'Klyro only needs internet access. It doesn\'t ask for your contacts, photos, location or microphone, and it only reads a CV when you pick one.',
            'faq' => [
                ['q' => 'Is it really free?', 'a' => 'Yes. No ads and no subscription. The practice questions work without any setup.'],
                ['q' => 'Do I need anything else for the AI features?', 'a' => 'Scoring your answers, reading your CV and mock interviews use Claude, an AI from Anthropic. You add your own Claude API key in Profile and pay Anthropic only for what you use. Without a key you still get curated questions across 26 technologies, bookmarks, practice and plans.'],
                ['q' => 'Where does my data go?', 'a' => 'Everything is stored on your phone. When you use an AI feature, the text it needs, such as your CV or your answer, is sent to Claude to be scored, and nowhere else.'],
                ['q' => 'Does it work on iPhone?', 'a' => 'Not yet. Klyro is Android-only for now.'],
            ],
            'diagram' => [
                'nodes' => [
                    ['id' => 'cv', 'label' => 'CV · JD', 'x' => 8, 'y' => 18],
                    ['id' => 'ans', 'label' => 'Your answer', 'x' => 8, 'y' => 82],
                    ['id' => 'ai', 'label' => 'AI service', 'x' => 44, 'y' => 50, 'core' => true],
                    ['id' => 'claude', 'label' => 'Claude API', 'x' => 88, 'y' => 14],
                    ['id' => 'plan', 'label' => 'Planner', 'x' => 88, 'y' => 60],
                    ['id' => 'store', 'label' => 'Local store', 'x' => 60, 'y' => 96],
                ],
                'edges' => [['cv', 'ai'], ['ans', 'ai'], ['ai', 'claude'], ['ai', 'plan'], ['plan', 'store']],
            ],
        ],
    ],

    'principles' => [
        ['title' => 'Own the whole system', 'body' => 'Schema, API, UI, server, deploy, alerts. Owning every layer is how you find the bug nobody else can.'],
        ['title' => 'Performance is a feature', 'body' => 'Speed shapes how a product feels. I measure it, budget it and treat regressions like bugs.'],
        ['title' => 'Automate the second time', 'body' => 'Do it once by hand to understand it. The second time, it becomes a workflow, a script or a pipeline.'],
        ['title' => 'Interfaces should feel good', 'body' => 'Smooth, beautiful UI is part of the product, not decoration. I experiment across stacks, Laravel with React, Laravel with Vue, to find what feels best for each one.'],
        ['title' => 'Boring infrastructure, bold product', 'body' => 'Proven tools underneath (Laravel, Postgres, Nginx) so the product itself can take the risks.'],
    ],

    'education' => [
        'school' => 'Pailan College of Management & Technology',
        'degree' => 'B.Tech, Computer Science & Engineering',
        'period' => 'Aug 2019 — Jun 2023',
        'location' => 'Kolkata, India',
        'score' => 'CGPA 9.08 / 10',
    ],

];
