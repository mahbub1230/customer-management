#!/bin/bash

if [ ! -f /etc/apache2/ssl/ssl.crt ]; then
  echo "Generating self-signed SSL certificate..."
  openssl req -x509 -nodes -days 365 \
    -newkey rsa:2048 \
    -keyout /etc/apache2/ssl/ssl.key \
    -out /etc/apache2/ssl/ssl.crt \
    -subj "/C=AU/ST=NSW/L=Sydney/O=Slipstram/CN=demo.slipstram.com"
fi

exec apache2-foreground
