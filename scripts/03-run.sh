#!/bin/bash
set -e
cd "$(dirname "$0")/.."

echo "Starting containers..."
docker compose up -d db app

echo "Checking and seeding player data"
docker compose run --rm seed

echo "All set!"