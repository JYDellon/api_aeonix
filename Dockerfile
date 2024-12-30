# Étape 1 : Utiliser une image PHP avec Apache
FROM php:8.2-apache

# Étape 2 : Installer les extensions nécessaires pour Symfony
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install \
    intl \
    pdo \
    pdo_mysql \
    opcache \
    zip \
    && docker-php-ext-enable opcache

# Étape 3 : Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Étape 4 : Copier les fichiers de l'application dans le conteneur
COPY . /var/www/html

# Étape 5 : Configurer Apache pour servir le répertoire public
RUN sed -i "s|/var/www/html|/var/www/html/public|g" /etc/apache2/sites-available/000-default.conf

# Étape 6 : Activer mod_rewrite pour Symfony
RUN a2enmod rewrite \
    && sed -i "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf

# Étape 7 : Installer les dépendances Symfony via Composer
WORKDIR /var/www/html

# Ajouter un utilisateur non-root
RUN useradd -m symfony && chown -R symfony:symfony /var/www/html
USER symfony

# Désactiver les auto-scripts pour éviter les erreurs, et exécuter Composer
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Revenir à l'utilisateur root pour ajuster les permissions
USER root
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html

# Étape 8 : Ajouter ServerName pour Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Étape 9 : Exposer le port HTTP par défaut (Apache)
EXPOSE 80

# Étape 10 : Commande de démarrage du serveur Apache
CMD ["apache2-foreground"]
