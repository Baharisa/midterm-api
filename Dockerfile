 # Use an official PHP runtime as base image
FROM php:8.1-apache

# Install necessary extensions for PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy all files from the project into the container
COPY . .

# Expose port 80 for the web server
EXPOSE 80

# Start Apache when the container starts
CMD ["apache2-foreground"]

