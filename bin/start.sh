#!/bin/bash
# Remove old pid file if it exists.
rm -f storage/logs/swoole_http.pid

php artisan swoole:http start
