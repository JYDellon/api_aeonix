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












# # Utiliser PHP 8.2 comme base
# FROM php:8.2-fpm

# # Installer les extensions nécessaires
# RUN apt-get update && apt-get install -y \
#     libpq-dev \
#     libicu-dev \
#     libxml2-dev \
#     libonig-dev \
#     git \
#     unzip \
#     && docker-php-ext-install \
#     pdo \
#     pdo_pgsql \
#     pdo_mysql \
#     intl \
#     xml \
#     mbstring \
#     && apt-get clean

# # Créer le dossier var/sessions et configurer les permissions
# RUN mkdir -p /app/var/sessions && \
#     chmod -R 775 /app/var/sessions && \
#     chown -R www-data:www-data /app/var/sessions


    
#     # Définir les permissions sur le dossier sessions
# RUN chmod -R 775 /app/var/sessions && chown -R www-data:www-data /app/var/sessions




# # Installer Composer depuis l'image officielle Composer
# COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# # Définir le répertoire de travail
# WORKDIR /app

# # Copier le projet Symfony dans le conteneur
# COPY . /app

# # Installer les dépendances Symfony
# RUN composer install --no-dev --optimize-autoloader --prefer-dist

# # Exposer le port (optionnel, ajustez en fonction de votre configuration)
# EXPOSE 8000

# # Commande de démarrage
# CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]

















# # Étape 1 : Image de base pour PHP
# FROM php:8.2-fpm-alpine

# # Étape 2 : Mise à jour des dépendances et installation des extensions nécessaires
# RUN apk add --no-cache \
#     bash \
#     git \
#     curl \
#     unzip \
#     libzip-dev \
#     libpng-dev \
#     libjpeg-turbo-dev \
#     freetype-dev \
#     oniguruma-dev \
#     icu-dev \
#     mysql-client \
#     nodejs \
#     npm \
#     && docker-php-ext-configure gd --with-freetype --with-jpeg \
#     && docker-php-ext-install pdo pdo_mysql intl zip gd opcache

# # Étape 3 : Installation de Composer
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# # Étape 4 : Définition du répertoire de travail
# WORKDIR /app

# # Étape 5 : Copier les fichiers de votre projet
# COPY . /app

# # Étape 6 : Installation des dépendances PHP et création du cache Symfony
# RUN composer install --no-dev --optimize-autoloader \
#     && php bin/console cache:clear --env=prod \
#     && php bin/console cache:warmup --env=prod

# # Étape 7 : Optimisation d'OPcache pour la production
# RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
#     && echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/opcache.ini \
#     && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
#     && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
#     && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
#     && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

# # Étape 8 : Permissions pour l'utilisateur et fichiers temporaires
# RUN chmod -R 775 /app/var

# # Étape 9 : Exposition du port pour PHP-FPM
# EXPOSE 9000

# # Étape 10 : Commande de démarrage par défaut
# CMD ["php-fpm"]

# RUN docker-php-ext-install redis

















# Étape 1 : Utiliser PHP 8.2 avec Alpine Linux comme base
FROM php:8.2-fpm-alpine

# Étape 2 : Mise à jour des dépendances et installation des extensions nécessaires
RUN apk add --no-cache \
    bash \
    git \
    curl \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    icu-dev \
    mysql-client \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql intl zip gd opcache

# Étape 3 : Installer l'extension Redis avec PECL
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

# Étape 4 : Installation de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Étape 5 : Définir le répertoire de travail
WORKDIR /app

# Étape 6 : Copier les fichiers de votre projet Symfony dans le conteneur
COPY . /app

# Étape 7 : Installation des dépendances Symfony et création du cache
RUN composer install --no-dev --optimize-autoloader \
    && php bin/console cache:clear --env=prod \
    && php bin/console cache:warmup --env=prod

# Étape 8 : Optimiser OPcache pour l'environnement de production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

# Étape 9 : Assurer les permissions pour les fichiers et répertoires temporaires
RUN chmod -R 775 /app/var \
    && chown -R www-data:www-data /app/var

# Étape 10 : Exposer le port utilisé par PHP-FPM
EXPOSE 9000

# Étape 11 : Définir la commande de démarrage par défaut
CMD ["php-fpm"]
