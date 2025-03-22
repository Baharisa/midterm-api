# Stage 1: Base PHP-Apache image
FROM php:8.1-apache

# Install PostgreSQL support
RUN apt-get update && apt-get install -y libpq-dev unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Suppress Apache warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Enable Apache rewrite module
RUN a2enmod rewrite

# Fix AllowOverride to allow .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Use your custom virtual host config (optional)
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy all project files
COPY . .

# Install PHP dependencies (dotenv, etc)
RUN composer install

# Set permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Expose Apache port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
