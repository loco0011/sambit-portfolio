#!/usr/bin/env bash
# One-command deploy on the VPS:  ./deploy.sh
# Pulls the latest code, rebuilds and restarts the container (migrations run on boot),
# waits until it's healthy, and creates the admin login the first time.
set -euo pipefail

cd "$(dirname "$0")"

CONTAINER=portfolio
step() { printf '\n\033[1;33m==> %s\033[0m\n' "$1"; }
fail() { printf '\n\033[1;31m✗ %s\033[0m\n' "$1"; exit 1; }

[ -f .env.production ] || fail ".env.production is missing. Run: cp .env.production.example .env.production && nano .env.production"
docker network inspect proxy >/dev/null 2>&1 || fail "Docker network 'proxy' not found (Caddy runs on it)."

step "Pulling latest code"
git pull --ff-only

step "Building and starting container"
docker compose up -d --build

step "Waiting for the app to become healthy"
for i in $(seq 1 60); do
    status=$(docker inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}{{.State.Status}}{{end}}' "$CONTAINER" 2>/dev/null || echo missing)
    case "$status" in
        healthy) break ;;
        unhealthy|exited|dead|missing)
            docker compose logs --tail 40 "$CONTAINER"
            fail "Container is $status — logs above." ;;
    esac
    sleep 2
done
[ "$status" = healthy ] || { docker compose logs --tail 40 "$CONTAINER"; fail "Timed out waiting (status: $status)."; }
echo "✓ healthy"

admins=$(docker compose exec -T "$CONTAINER" php artisan tinker --execute='echo App\Models\User::count();' 2>/dev/null | tr -dc '0-9')
if [ "${admins:-0}" = 0 ]; then
    step "No admin login yet — creating one"
    read -rp "Admin email: " email
    docker compose exec "$CONTAINER" php artisan admin:user "$email" --name=Sambit
fi

step "Cleaning up old images"
docker image prune -f >/dev/null
echo "✓ done"

printf '\n\033[1;32m✓ Deployed %s\033[0m  →  https://sambitmaity.com  ·  admin: https://sambitmaity.com/admin\n' "$(git log -1 --format='%h %s')"
