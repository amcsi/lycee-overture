#!/bin/bash
# Migrate the DB. Need to force to migrate on production.
bin/php artisan migrate --force

# Run cache commands.
bin/php artisan config:cache
bin/php artisan view:cache

# Do the import tasks (in the background).
bin/php -d memory_limit=256M artisan lycee:import-all \
  --no-cache \
  --images \
  --lackey \
  &

# Start nginx.
nginx

# Run the Octane HTTP server.
bin/php artisan octane:start --host=127.0.0.1 --port=1215
