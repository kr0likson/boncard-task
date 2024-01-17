#!/bin/bash

set -e

# Run mysqldump and load optimized dump only on first run
if [ ! -e /var/lib/mysql/.initialized ]; then
  # Perform mysqldump to generate optimized dump file
  mysqldump -h database > /docker-entrypoint-initdb.d/init.sql

  # Mark initialization as complete
  touch /var/lib/mysql/.initialized
fi

mysql -h database < /docker-entrypoint-initdb.d/init.sql

# Start the MySQL server
exec "$@"