# Utiliser PHP 8.2 comme base
FROM php:8.2-fpm

# Installer les extensions nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /app

# Copier le projet Symfony
COPY . /app

# Installer les dépendances Symfony
RUN composer install --no-dev --optimize-autoloader

# Exposer le port
EXPOSE 8000

# Commande de démarrage
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]


COPY composer.json composer.lock /app/
RUN rm -rf var/cache/* && php bin/console cache:warmup --env=prod



# Copier le fichier .env dans le conteneur
COPY .env /app/.env



RUN rm -rf var/cache/* && php bin/console cache:warmup --env=prod
RUN echo "session.save_path = \"/tmp\"" > /usr/local/etc/php/conf.d/sessions.ini




