# Utiliser une image officielle de PHP avec FPM sur Alpine Linux
FROM php:8.1-fpm-alpine

# Installer les dépendances système et extensions PHP nécessaires pour Symfony
RUN apk add --no-cache $PHPIZE_DEPS \
    && apk add --no-cache icu-dev libzip-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Copier Composer depuis l'image officielle de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail dans le container
WORKDIR /var/www

# Copier l'ensemble du projet dans le container
COPY . .

# Installer les dépendances PHP via Composer en mode production
RUN composer install --no-dev --optimize-autoloader

# Exposer le port sur lequel l'application sera accessible (ici le port 8000)
EXPOSE 8000

# Lancer le serveur PHP intégré pour servir l'application depuis le dossier public
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
