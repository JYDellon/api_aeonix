# # Utiliser PHP 8.2 comme base
# FROM php:8.2-fpm

# # Installer les extensions nécessaires
# RUN apt-get update && apt-get install -y \
#     libpq-dev \
#     git \
#     unzip \
#     && docker-php-ext-install pdo pdo_pgsql pdo_mysql
#     FROM php:8.2-fpm

#     # Installer les extensions nécessaires
# RUN apt-get update && apt-get install -y \
#     libicu-dev \
#     libxml2-dev \
#     libonig-dev \
#     && docker-php-ext-install intl xml mbstring pdo pdo_mysql
    
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

# RUN apt-get update && apt-get install -y libicu-dev && docker-php-ext-install intl












# Utiliser PHP 8.2 comme base
FROM php:8.2-fpm

# Installer les extensions nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    git \
    unzip \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pdo_mysql \
    intl \
    xml \
    mbstring \
    && apt-get clean

# Installer Composer depuis l'image officielle Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /app

# Copier le projet Symfony dans le conteneur
COPY . /app

# Installer les dépendances Symfony
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Exposer le port (optionnel, ajustez en fonction de votre configuration)
EXPOSE 8000

# Commande de démarrage
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
