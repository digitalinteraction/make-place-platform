#!/usr/bin/env bash

# Check for requirements
php _check_env.php DB_HOST DB_USER DB_PASS DB_NAME


# copy env vars for cronjobs
env | sed 's/^\(.\)/export \1/' >> /app/.cron_env


# Build the database
framework/sake dev/build
