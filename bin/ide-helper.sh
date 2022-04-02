#!/usr/bin/env bash

PHP_VERSION=8.1
VERSION_PHP_BIN="php$PHP_VERSION"

# If PHP_BIN was not provided...
if [ -z ${PHP_BIN+x} ]; then
  $VERSION_PHP_BIN -v
  if [ $? -eq 0 ]; then
    # Version-specific PHP executable is available. Use it.
    PHP_BIN=$VERSION_PHP_BIN
  else
    # Version-specific PHP executable is not available. Just use 'php'.
    PHP_BIN=php
  fi
fi

# Runs the ide-helper commands to generate PhpStorm files.
$PHP_BIN artisan ide-helper:eloquent
$PHP_BIN artisan ide-helper:generate
$PHP_BIN artisan ide-helper:meta
$PHP_BIN artisan ide-helper:models --write --smart-reset
