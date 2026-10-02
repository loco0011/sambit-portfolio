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
        ['name' => 'Ad-free music player', 'card' => 'music', 'kind' => 'Personal app, built to skip ads', 'stack' => ['Flutter', 'Dart']],
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
