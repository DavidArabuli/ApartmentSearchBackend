#!/bin/bash
set -e

# Run database bootstrap (create tables)
echo "Running database bootstrap..."
php /var/www/html/database/bootstrap.php
echo "Database bootstrap completed."

# Start Apache in foreground
echo "Starting Apache..."
exec apache2-foreground
