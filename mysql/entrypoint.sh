#!/bin/bash

set -e
rm -rf /var/lib/mysql/redo_logs/*
# Run mysqldump and load optimized dump only on first run
if [ ! -e /var/lib/mysql/.initialized ]; then
  # Perform mysqldump to generate optimized dump file
  mysqldump -h db > /docker-entrypoint-initdb.d/dump.sql

  # Mark initialization as complete
  touch /var/lib/mysql/.initialized
fi

# Start the MySQL server
exec /entrypoint.sh "$@"
