#!/bin/sh
# The SQLite database lives in the persistent storage volume; create it on first boot
# so the automatic migrations (50-laravel-automations) have a file to migrate.
mkdir -p /var/www/html/storage/database
touch /var/www/html/storage/database/database.sqlite
