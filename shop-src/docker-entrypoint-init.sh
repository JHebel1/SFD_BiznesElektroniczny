#!/bin/bash
set -e

# czekamy aż volume będzie dostępne
while [ ! -f /etc/apache2/sites-available/ssl.conf ]; do
  echo "Waiting for ssl.conf..."
  sleep 1
done

a2enmod ssl
a2ensite ssl

exec apache2-foreground

