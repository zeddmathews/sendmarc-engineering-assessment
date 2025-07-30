#!/bin/bash

# Run any pending migrations
php artisan migrate

# Then run the original CMD (Laravel dev server, supervisor, or whatever)
exec "$@"