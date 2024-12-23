FROM php:8.0-apache

# Installer les bibliothèques nécessaires
RUN apt-get update && apt-get install -y libssl1.0-dev libonig-dev libzip-dev unzip \
    && docker-php-ext-install pdo pdo_mysql zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurer le dossier de l'application
WORKDIR /var/www/html

# Copier le projet Symfony
COPY . .

# Exposer le port 80
EXPOSE 80
