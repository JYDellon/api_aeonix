# Utiliser une version de PHP compatible
FROM php:8.1-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libicu-dev libzip-dev zip unzip git \
    && docker-php-ext-install intl pdo pdo_mysql opcache

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier les fichiers de l'application
WORKDIR /app
COPY . /app

# Installer les dépendances Symfony
RUN composer install --no-dev --optimize-autoloader

# Exposer le port
EXPOSE 80

CMD ["apache2-foreground"]
