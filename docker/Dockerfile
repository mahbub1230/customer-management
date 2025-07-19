FROM php:8.2-apache

# Install required packages including OpenSSL
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip libonig-dev libxml2-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    gnupg2 ca-certificates lsb-release \
    openssl nano

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip gd

# Enable Apache modules
RUN a2enmod rewrite ssl

# Install Node.js (LTS version)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Apache SSL configuration
COPY ./docker/apache/ssl.conf /etc/apache2/sites-available/ssl.conf

# Copy runtime SSL generation script
COPY ./docker/scripts/generate-ssl.sh /usr/local/bin/generate-ssl.sh
RUN chmod +x /usr/local/bin/generate-ssl.sh

# Ensure SSL directory exists
RUN mkdir -p /etc/apache2/ssl

# Enable the SSL virtual host and disable default
RUN a2ensite ssl.conf && a2dissite 000-default.conf

# Entrypoint script will generate SSL certs if not already present
ENTRYPOINT ["generate-ssl.sh"]