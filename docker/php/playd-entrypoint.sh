#!/bin/sh
set -e
if ! php -m 2>/dev/null | grep -qi '^mysqli$'; then
  docker-php-ext-install mysqli
fi
a2enmod rewrite 2>/dev/null || true
exec docker-php-entrypoint apache2-foreground "$@"
