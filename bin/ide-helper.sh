#!/usr/bin/env bash

PHP_BIN=bin/php

# Runs the ide-helper commands to generate PhpStorm files.
$PHP_BIN artisan ide-helper:eloquent
$PHP_BIN artisan ide-helper:generate
$PHP_BIN artisan ide-helper:meta
$PHP_BIN artisan ide-helper:models --write --smart-reset
