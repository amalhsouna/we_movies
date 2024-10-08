# Utiliser une image PHP avec les extensions nécessaires
FROM php:7.4-fpm

# Installer les dépendances
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    npm \
    && docker-php-ext-install pdo mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer Node.js et NPM (ou Yarn si vous préférez)
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash - && \
    apt-get install -y nodejs && \
    npm install --global yarn

# Copier le fichier de configuration PHP personnalisé si nécessaire
COPY ./docker/php/php.ini /usr/local/etc/php/

# Copier les fichiers du projet
COPY . /var/www

# Définir le répertoire de travail
WORKDIR /var/www

# Installer les dépendances PHP et Node.js
RUN composer install --no-dev --optimize-autoloader
RUN yarn install
RUN yarn build

# Assurez-vous que les permissions sont correctes
RUN chown -R www-data:www-data /var/www

# Exposer le port 9000 pour PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
