#!/bin/bash
set -e
cd "$(dirname "$0")/.."

echo "Pausing containers and volumes"
docker compose stop