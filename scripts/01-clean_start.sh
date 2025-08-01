#!/bin/bash
set -e
cd "$(dirname "$0")/.."

if lsof -i :5432 >/dev/null 2>&1; then
    echo "Port 5432 is already in use. Likely due to a local PostgreSQL instance."
    echo "Please stop the local service or change the port in docker-compose.yml in service:db:ports"
    exit 1
fi

echo "Port 5432 is free. Continuing"

echo "Cleaning containers and volumes"
docker compose down -v --remove-orphans
docker volume prune -f

echo "Applying permissions to scripts"
chmod +x scripts/entrypoint.sh
chmod +x scripts/03-run.sh
chmod +x scripts/02-stop.sh
chmod +x scripts/04-cleanup.sh

echo "Rebuilding Docker images"
docker compose build --no-cache

echo "Starting DB and API services in background"
docker compose up -d db nginx app

echo "Waiting for the DB to be ready..."
until docker exec postgres pg_isready -U postgres >/dev/null 2>&1; do
    sleep 1
done

echo "DB is ready."

echo "Seeding the database"
docker compose run --rm seed

echo "Starting services"
echo "You can now access the API at http://localhost:8088"