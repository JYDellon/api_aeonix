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

# Exporter les variables d'environnement (optionnel pour Docker)
ENV APP_USERNAME=JYD
ENV APP_PASSWORD='$2y$13$S2sU5I4sR3APdFHn5l1xG.JYdy/Qw9NO1Hj/n2sb60rZhHfO.4lNe'








# FROM php:8.2-cli

# # Installer Composer
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# # Installer les extensions PHP nécessaires
# RUN docker-php-ext-install pdo pdo_mysql

# # Définir le répertoire de travail
# WORKDIR /app

# # Copier les fichiers Composer
# COPY composer.json composer.lock ./

# # Installer les dépendances Symfony
# RUN composer install --no-dev --optimize-autoloader --no-scripts

# # Copier le reste des fichiers de l'application
# COPY . .

# # Préparer le cache Symfony
# RUN php bin/console cache:clear --env=prod
# RUN php bin/console cache:warmup --env=prod

# # Exposer le port et définir le CMD par défaut
# EXPOSE 8000
# CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
# RUN rm -rf var/cache/* && php bin/console cache:warmup --env=prod
