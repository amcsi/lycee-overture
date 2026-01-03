#!/usr/bin/env bash

# Migrates the test db.

set -e

if [[ ! -f ".env.testing" ]]; then
  echo ".env.testing not found — stopping."
  exit 1
fi

APP_ENV=testing bin/php artisan migrate:fresh
