#!/bin/sh

DB_PATH="/var/www/mvc/db/db.sqlite"

# Ensure the db directory exists
mkdir -p /var/www/mvc/db

# Ensure correct permissions
chown -R www-data:www-data /var/www/mvc/db
chmod -R 755 /var/www/mvc/db

# Check if db.sqlite exists
if [ ! -f "$DB_PATH" ]; then
    echo "Database not found. Creating db.sqlite..."
    sqlite3 "$DB_PATH" <<EOF
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
);
EOF
    echo "Database created."
else
    echo "Database already exists at $DB_PATH."
fi

exec docker-php-entrypoint php-fpm
