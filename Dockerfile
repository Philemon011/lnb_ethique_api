# Utiliser une image PHP avec FPM
FROM php:8.2-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    curl \
    git \
    nginx \
    libssl-dev \
    && docker-php-ext-install pdo pdo_pgsql openssl

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier le projet Laravel
COPY . .

# Copier le fichier .env si nécessaire
RUN cp .env.example .env

# Installer les dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Donner les permissions nécessaires pour les répertoires
RUN chmod -R 777 storage bootstrap/cache

# Définir les variables d'environnement
ENV APP_KEY=${APP_KEY}
ENV APP_NAME=${APP_NAME}
ENV APP_URL=${APP_URL}
ENV APP_ENV=${APP_ENV}
ENV APP_DEBUG=${APP_DEBUG}
ENV LOG_CHANNEL=${LOG_CHANNEL}
ENV DB_CONNECTION=${DB_CONNECTION}
ENV DB_HOST=${DB_HOST}
ENV DB_PORT=${DB_PORT}
ENV DB_DATABASE=${DB_DATABASE}
ENV DB_USERNAME=${DB_USERNAME}
ENV DB_PASSWORD=${DB_PASSWORD}
ENV BROADCAST_DRIVER=${BROADCAST_DRIVER}
ENV CACHE_DRIVER=${CACHE_DRIVER}
ENV QUEUE_CONNECTION=${QUEUE_CONNECTION}
ENV SESSION_DRIVER=${SESSION_DRIVER}
ENV SESSION_LIFETIME=${SESSION_LIFETIME}
ENV REDIS_HOST=${REDIS_HOST}
ENV REDIS_PASSWORD=${REDIS_PASSWORD}
ENV REDIS_PORT=${REDIS_PORT}
ENV MAIL_MAILER=${MAIL_MAILER}
ENV MAIL_HOST=${MAIL_HOST}
ENV MAIL_PORT=${MAIL_PORT}
ENV MAIL_USERNAME=${MAIL_USERNAME}
ENV MAIL_PASSWORD=${MAIL_PASSWORD}
ENV MAIL_ENCRYPTION=${MAIL_ENCRYPTION}
ENV MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS}
ENV MAIL_FROM_NAME=${MAIL_FROM_NAME}
ENV PUSHER_APP_ID=${PUSHER_APP_ID}
ENV PUSHER_APP_KEY=${PUSHER_APP_KEY}
ENV PUSHER_APP_SECRET=${PUSHER_APP_SECRET}
ENV PUSHER_APP_CLUSTER=${PUSHER_APP_CLUSTER}

# Générer la clé Laravel et afficher l'environnement
RUN echo "Environment: $(cat .env)" && php artisan key:generate

# Exposer le port 8000
EXPOSE 8000

# Lancer l'application Laravel avec PHP Built-in Server
CMD php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=8000
