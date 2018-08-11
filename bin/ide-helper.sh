#!/usr/bin/env bash

# Runs the ide-helper commands to generate PhpStorm files.
php artisan ide-helper:eloquent
php artisan ide-helper:generate
php artisan ide-helper:meta
php artisan ide-helper:models
