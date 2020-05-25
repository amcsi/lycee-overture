#!/usr/bin/env bash

# Migrates the test db.

set -e

APP_ENV=testing php artisan migrate:fresh
