# Étape 1 : Construction des assets front-end
FROM node:18 AS frontend-build
WORKDIR /app

# Copier les fichiers nécessaires pour les dépendances front-end
COPY package.json yarn.lock ./
RUN yarn install

# Copier tous les fichiers du projet pour construire les assets
COPY . ./
RUN yarn build

# Étape 2 : Construction de l'application PHP
FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    unzip \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install intl pdo_mysql zip

# Activer le module Apache Rewrite
RUN a2enmod rewrite

# Définir le répertoire de travail
WORKDIR /app

# Copier le code de l'application
COPY . ./

# Copier les assets générés depuis la première étape
COPY --from=frontend-build /app/public/build /app/public/build

# Installer Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Fixer les permissions pour les répertoires utilisés par Symfony
RUN chmod -R 775 var/cache var/log var/sessions \
    && chown -R www-data:www-data var/cache var/log var/sessions

# Exposer le port pour l'application
EXPOSE 8000

# Commande par défaut pour démarrer le serveur Apache
CMD ["apache2-foreground"]
