# Étape 1 : Utiliser une image PHP 8.2 comme base
FROM php:8.2-fpm

# Étape 2 : Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libicu-dev libzip-dev zip unzip git \
    && docker-php-ext-install intl pdo pdo_mysql opcache

# Étape 3 : Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Étape 4 : Définir le répertoire de travail
WORKDIR /app

# Étape 5 : Copier les fichiers de l'application
COPY . /app

# Étape 6 : Installer les dépendances Symfony
RUN composer install --no-dev --optimize-autoloader

# Étape 7 : Configurer le serveur d'application
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]

# Exposer le port
EXPOSE 8000
