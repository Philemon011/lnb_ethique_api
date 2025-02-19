# Utiliser une image PHP avec FPM
FROM php:8.2-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    curl \
    git \
    nginx \
    && docker-php-ext-install pdo pdo_pgsql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier le projet Laravel
COPY . .

# Installer les dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Donner les permissions nécessaires
RUN chmod -R 777 storage bootstrap/cache

# Générer la clé Laravel
RUN php artisan key:generate

# Exposer le port 8000
EXPOSE 8000

# Lancer l'application Laravel avec PHP Built-in Server
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000
