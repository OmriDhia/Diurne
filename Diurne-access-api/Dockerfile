# Utilisez une image de base PHP avec Apache
FROM php:7.4-apache

# Installation des dépendances en une seule couche pour optimiser la taille de l'image
RUN apt-get update && apt-get install -y mdbtools git unzip \
    && rm -rf /var/lib/apt/lists/* \
    && a2enmod rewrite

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définissez le répertoire de travail
WORKDIR /var/www/html

# Installation des dépendances PHP avec Composer
COPY composer.json src/composer.lock* ./
RUN composer install --no-scripts --no-autoloader \
    && composer dump-autoload --optimize

# Copiez vos scripts PHP dans le conteneur
COPY . .

# Exposez le port 80
EXPOSE 80