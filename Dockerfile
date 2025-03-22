FROM php:8.1-apache

# Install PostgreSQL dependencies
RUN apt-get update && apt-get install -y libpq-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql

# Suppress Apache "ServerName" warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Enable mod_rewrite
RUN a2enmod rewrite

# Use custom Apache config
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy app files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy your app files into container
COPY . .

# Install dependencies (like vlucas/phpdotenv)
RUN composer install

