# Deploying the portfolio to the VPS

Layout on the VPS:

```
/opt/apps/pmp-ornaments/   existing app + Nginx container (ports 80/443)  ← untouched apart from 1 config file + network
/opt/apps/sambit-portfolio/            this repo → container "portfolio" (no public ports)
```

The two containers talk over a shared Docker network called `proxy`.

## 1. DNS (at your domain registrar)

| Type | Name | Value         |
|------|------|---------------|
| A    | @    | 93.127.172.207   |
| A    | www  | 93.127.172.207   |

Check with `nslookup sambitmaity.com` (can take 5 min – a few hours).

## 2. Push code to GitHub (on your PC)

```bash
git init && git add . && git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/loco0011/sambit-portfolio.git   # create a PRIVATE repo first
git push -u origin main
```

## 3. Shared network (on the VPS, once)

```bash
docker network create proxy
```

Then edit the EXISTING project's docker-compose.yml so its nginx service joins it:

```yaml
services:
  nginx:              # your existing nginx service
    # ...existing config...
    networks:
      - default       # keep its current network(s)!
      - proxy

networks:
  proxy:
    external: true
```

`docker compose up -d` in that folder (recreates only nginx, a few seconds).

## 4. Clone & configure the portfolio

```bash
sudo mkdir -p /opt/apps/sambit-portfolio && sudo chown $USER /opt/apps/sambit-portfolio
git clone https://github.com/loco0011/sambit-portfolio.git /opt/apps/sambit-portfolio
cd /opt/apps/sambit-portfolio
cp .env.production.example .env.production
nano .env.production          # domain, mail settings
```

Generate an APP_KEY and paste it into `.env.production`:

```bash
docker run --rm php:8.4-cli php -r 'echo "base64:".base64_encode(random_bytes(32)).PHP_EOL;'
```

## 5. Build & start

```bash
docker compose up -d --build
docker compose logs -f        # migrations run automatically on boot
```

## 6. Nginx site + SSL

1. Copy `deploy/nginx-portfolio.conf` into the existing Nginx's conf folder, replace `sambitmaity.com`.
2. First time only: comment out the two `443` server blocks (the cert doesn't exist yet).
3. Reload: `docker exec <nginx-container> nginx -t && docker exec <nginx-container> nginx -s reload`
4. Issue the certificate the same way your existing project does (e.g. certbot container with webroot `/var/www/certbot`).
5. Uncomment the 443 blocks, test and reload again.

## Updating later

```bash
cd /opt/apps/sambit-portfolio && git pull && docker compose up -d --build
```

## Useful commands

```bash
docker compose logs -f portfolio
docker compose exec portfolio php artisan tinker     # e.g. App\Models\ContactMessage::latest()->get()
```
