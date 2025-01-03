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













# Utiliser PHP 8.2 comme base
FROM php:8.2-fpm

# Installer les dépendances système et les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \         # Pour la gestion des bases de données PostgreSQL
    libzip-dev \        # Pour gérer les fichiers ZIP
    git \               # Pour la gestion des versions de code
    unzip \             # Pour décompresser les fichiers ZIP
    && docker-php-ext-install \
    pdo \               # Pour la gestion des bases de données
    pdo_pgsql \         # Pour PostgreSQL
    pdo_mysql \         # Pour MySQL/MariaDB
    zip \               # Pour gérer les archives ZIP
    && apt-get clean

# Installer Composer (gestionnaire de dépendances PHP)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail dans le conteneur
WORKDIR /app

# Copier le fichier .env dans le conteneur (utile pour la configuration)
COPY .env /app/.env

# Copier le reste du projet Symfony dans le conteneur
COPY . /app

# Effacer les caches existants avant l'installation
RUN rm -rf /app/var/cache/*

# Installer les dépendances Symfony via Composer (en mode production)
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Exposer le port sur lequel Symfony va tourner
EXPOSE 8000

# Commande de démarrage de Symfony (serveur PHP intégré)
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
