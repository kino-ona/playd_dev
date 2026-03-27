#!/bin/sh
cd "$(dirname "$0")"
exec php -d short_open_tag=On -S localhost:8080 -t . router.php
