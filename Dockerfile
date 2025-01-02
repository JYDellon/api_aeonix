# # Utiliser PHP 8.2 comme base
# FROM php:8.2-fpm

# # Installer les extensions nécessaires
# RUN apt-get update && apt-get install -y \
#     libpq-dev \
#     git \
#     unzip \
#     && docker-php-ext-install pdo pdo_pgsql pdo_mysql

# # Installer Composer
# COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# # Définir le répertoire de travail
# WORKDIR /app

# # Copier le projet Symfony
# COPY . /app

# # Installer les dépendances Symfony
# RUN composer install --no-dev --optimize-autoloader

# # Exposer le port
# EXPOSE 8000

# # Commande de démarrage
# CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]














# Utiliser PHP 8.2 avec FPM (FastCGI Process Manager)
FROM php:8.2-fpm

# Installer les extensions nécessaires pour Symfony
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libicu-dev \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql intl

# Copier Composer depuis l'image officielle de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /app

# Copier tout le code dans le conteneur
COPY . /app

# Installer les dépendances Symfony
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Exposer le port (Railway gère automatiquement les ports, vous n'avez généralement pas besoin d'exposer explicitement)
EXPOSE 8000

# Démarrer PHP-FPM
CMD ["php-fpm"]
