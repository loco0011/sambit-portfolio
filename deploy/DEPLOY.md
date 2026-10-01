# Deploying the portfolio to the VPS (93.127.172.207)

```
/opt/apps/pmp-ornaments/              other app + shared infra (Caddy on 80/443, MariaDB)
/opt/apps/pmp-ornaments/deploy/infra/Caddyfile   ← shared Caddy config (our site block lives here)
/opt/apps/sambit-portfolio/           this repo → container "portfolio" (port 8080, no public ports)
```

Both apps join the external Docker network `proxy`; Caddy proxies `sambitmaity.com` to `portfolio:8080`
and issues the TLS certificate automatically.

## DNS

A records `@` and `www` → `93.127.172.207`.

## First-time setup

```bash
cd /opt/apps
git clone https://github.com/loco0011/sambit-portfolio.git
cd sambit-portfolio
cp .env.production.example .env.production
docker run --rm php:8.4-cli php -r 'echo "base64:".base64_encode(random_bytes(32)).PHP_EOL;'   # → APP_KEY
nano .env.production
docker compose up -d --build
```

Add `deploy/Caddyfile.snippet` to the shared Caddyfile, then:

```bash
docker exec caddy caddy validate --config /etc/caddy/Caddyfile --adapter caddyfile
docker exec caddy caddy reload   --config /etc/caddy/Caddyfile --adapter caddyfile
```

## Updating

```bash
cd /opt/apps/sambit-portfolio && git pull && docker compose up -d --build
```

## Useful commands

```bash
docker compose logs -f portfolio
docker compose exec portfolio php artisan tinker     # App\Models\ContactMessage::latest()->get()
docker logs caddy --tail 50                           # certificate / proxy issues
```
