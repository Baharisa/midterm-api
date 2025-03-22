# Use official PHP-Apache image
FROM php:8.1-apache

# Install PostgreSQL and system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    curl \
    git \
    zip \
    && docker-php-ext-install pdo pdo_pgsql

# Suppress Apache warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Enable .htaccess via mod_rewrite
RUN a2enmod rewrite
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Optional: Use your Apache config
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# 🧠 Install Composer directly
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies (like vlucas/phpdotenv)
RUN composer install

# Fix file permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Expose Apache port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
