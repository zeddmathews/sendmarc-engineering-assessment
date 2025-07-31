#!/bin/bash
set -e
cd "$(dirname "$0")/.."

echo "Cleaning containers and volumes"
docker compose down -v