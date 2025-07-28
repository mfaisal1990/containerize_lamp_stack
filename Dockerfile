FROM php:8.2-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy custom Apache config
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Copy PHP files
COPY php/ /var/www/html/
