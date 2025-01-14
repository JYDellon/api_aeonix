# Étape 1 : Utiliser l'image PHP avec Apache
FROM php:8.2-apache

RUN chmod -R 775 var/cache var/log \
    && chown -R www-data:www-data var/cache var/log


# Étape 2 : Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libicu-dev libzip-dev zip unzip git curl \
    && docker-php-ext-install intl pdo pdo_mysql opcache

# Étape 3 : Copier Composer depuis une image officielle
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Étape 4 : Configurer le répertoire de travail
WORKDIR /app

# Étape 5 : Copier les fichiers du projet dans l'image
COPY . /app

# Étape 6 : Créer les répertoires et fixer les permissions
RUN mkdir -p var/cache var/log && chown -R www-data:www-data var/cache var/log

# Étape 7 : Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Étape 8 : Réchauffer le cache Symfony
RUN php bin/console cache:warmup --env=prod

# Étape 9 : Exposer le port
EXPOSE 8000

# Étape 10 : Lancer le serveur PHP
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
