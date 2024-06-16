#!/bin/sh

if [ -f /var/www/mvc/db/db.sqlite ]; then
    exit 0
else
    exit 1
fi
