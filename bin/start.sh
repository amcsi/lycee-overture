#!/bin/bash
# Remove old pid file if it exists.
rm -f storage/logs/swoole_http.pid

# Migrate the DB.
php artisan migrate

# Do the import tasks (in the background).
php artisan lycee:import-all --translations &

# Start nginx.
nginx

# Run the Swoole HTTP server.
php artisan swoole:http start
