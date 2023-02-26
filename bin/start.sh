#!/bin/bash
# Migrate the DB. Need to force to migrate on production.
php artisan migrate --force

# Run cache commands.
php artisan config:cache
php artisan view:cache

# Do the import tasks (in the background).
php artisan lycee:import-all --no-cache --translations --images --lackey &

# Start nginx.
nginx

# Run the Octane HTTP server.
php artisan octane:start --host=127.0.0.1 --port=1215
