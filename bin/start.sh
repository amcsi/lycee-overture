#!/bin/bash
# Remove old pid file if it exists.
rm -f storage/logs/swoole_http.pid

# Migrate the DB. Need to force to migrate on production.
php artisan migrate --force

# Run cache commands.
php artisan config:cache
php artisan view:cache

# Do the import tasks (in the background).
php artisan lycee:import-all --translations --images &

# Start nginx.
nginx

# Run the Swoole HTTP server.
php artisan swoole:http start
