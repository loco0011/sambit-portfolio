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
        'availability' => 'Open to full-stack & backend engineering roles, remote or on-site',
        'current' => ['title' => 'Software Developer', 'company' => 'Collabmate'],
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
        'PHP', 'Laravel', 'Node.js', 'BullMQ', 'TypeScript', 'React', 'Vue.js', 'Flutter', 'PostgreSQL', 'MySQL', 'MongoDB',
        'Supabase', 'Docker', 'Nginx', 'Linux', 'CI/CD', 'n8n', 'Anthropic API (Claude)', 'OpenAI', 'Stripe', 'Razorpay', 'Twilio', 'MSG91', 'Pusher', 'Tailwind CSS', 'ZoneMTA',
    ],

    /*
    | Skills table. Shorthand per item:
    |   '*Laravel'                       → daily driver (gets a lime dot)
    |   'VPS administration|5+ instances' → name + a quieter detail
    */
    'skills' => [
        ['group' => 'Languages', 'items' => ['*PHP', '*JavaScript', '*TypeScript', 'Java', 'Dart']],
        ['group' => 'Frontend', 'items' => ['*React.js', 'Vue.js', '*Flutter', '*Tailwind CSS', 'UI animation|motion & micro-interactions', 'Bootstrap', 'HTML/CSS']],
        ['group' => 'Backend', 'items' => ['*Laravel|pairs with React & Vue', '*Node.js', '*REST APIs', 'BullMQ|job queues', 'HyperBase', 'ZoneMTA|mail transfer']],
        ['group' => 'Data & Services', 'items' => ['*MySQL', '*PostgreSQL', 'MongoDB', 'Supabase', 'Stripe|payments', 'Razorpay|payments', 'Twilio', 'MSG91|real-time OTP', 'Pusher|realtime', 'Brevo|SMTP']],
        ['group' => 'Cloud & DevOps', 'items' => ['*VPS administration|5+ instances', '*Linux', '*Nginx', '*Docker', 'CI/CD', 'SSH deployments', 'Monitoring']],
        ['group' => 'Security', 'items' => ['HMAC signing|requests & webhooks', '2FA', 'OTP verification', 'Role-based access']],
        ['group' => 'AI & Automation', 'items' => ['*Anthropic API (Claude)', 'OpenAI API', '*n8n|self-hosted', 'AI chat agents|on company data', 'Multi-model workflows']],
    ],

    'experience' => [
        [
            'role' => 'Software Developer',
            'company' => 'Collabmate',
            'location' => 'Kolkata, India',
            'period' => 'Jun 2026 — Present',
            'current' => true,
            'summary' => 'Single-handedly own the technical foundation: architecture, infrastructure, automation and AI, across web and mobile.',
            'points' => [
                'Provision, secure and run 5+ production VPS instances single-handedly, including a self-hosted n8n instance: Linux, Nginx, Docker, deployments and monitoring.',
                'Automated deployments with SSH-based CI/CD pipelines and self-hosted n8n workflows, removing manual release steps.',
                'Rebuilt the primary website and raised its performance score from ~80 to 95+.',
                'Built a company chat agent grounded in real company data, orchestrating Anthropic Claude and OpenAI models through n8n.',
                'Automated bulk email campaigns through Brevo SMTP, wired into n8n workflows.',
                'Designed and shipped web applications and Flutter apps end to end, from architecture to production.',
            ],
            'tags' => ['DevOps', 'VPS', 'Docker', 'Linux', 'Nginx', 'CI/CD', 'Self-hosted n8n', 'Anthropic API (Claude)', 'AI agents', 'Monitoring'],
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
            ],
            'tags' => ['Laravel', 'React', 'REST', 'AI SDKs'],
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
            'stack' => ['React', 'TypeScript', 'Laravel', 'PostgreSQL', 'Stripe', 'Pusher'],
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
            'stack' => ['Laravel', 'MySQL', 'Stripe', 'Twilio'],
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
            'stack' => ['React', 'TypeScript', 'Node.js', 'ZoneMTA', 'Docker', 'Nginx'],
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
    | url (adds an ↗ link), card (chat|infra|music|crm|earning) promotes the
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
        ['name' => 'CRM systems', 'card' => 'crm', 'kind' => 'Multiple custom CRMs for businesses'],
        ['name' => 'ID card management system', 'kind' => 'Issue & manage identity cards'],
        ['name' => 'Business & company websites', 'kind' => 'Multiple sites for different businesses'],
        ['name' => 'Samgeet, ad-free music player', 'card' => 'music', 'kind' => 'Open-source Android app, built to skip ads', 'stack' => ['Flutter', 'Dart'], 'url' => '/work/samgeet'],
        ['name' => 'Open-source contributions', 'kind' => 'Contributions to public projects', 'url' => 'https://github.com/loco0011'],
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
