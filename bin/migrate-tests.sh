#!/usr/bin/env bash

# Migrates the test db.

set -e

APP_ENV=testing bin/php artisan migrate:fresh
