# Use official PHP image with Apache
FROM php:8.1-apache

# Install required extensions
RUN docker-php-ext-install pdo pdo_pgsql

# Enable mod_rewrite for clean URLs
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy all files
COPY . /var/www/html/

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
