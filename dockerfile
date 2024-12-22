# Étape 1 : Utiliser une image PHP avec Apache
FROM php:8.1-apache

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Étape 2 : Mise à jour et installation des extensions nécessaires
RUN apt-get update && apt-get install -y --no-install-recommends \
    zip unzip git curl libpng-dev libjpeg-dev libfreetype6-dev \
    libxml2-dev libicu-dev libxslt-dev libzip-dev pkg-config libssl-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring intl gd opcache zip \
    && pecl install mongodb && docker-php-ext-enable mongodb \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Étape 3 : Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Étape 4 : Installer Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs 

# Étape 5 : Activer mod_rewrite pour Apache
RUN a2enmod rewrite

# Étape 6 : Définir les variables d'environnement pour Symfony
ENV APP_ENV=dev
ENV APP_DEBUG=1

# Étape 7 : Définir le répertoire de travail
WORKDIR /var/www/html

RUN mkdir -p var/cache var/log && \
    chown -R www-data:www-data var/cache var/log

# Étape 8 : Copier uniquement les fichiers nécessaires pour Composer et npm
COPY composer.json composer.lock package.json package-lock.json ./
#Webpack encore
COPY webpack.config.js ./

# Étape 9 : Installer les dépendances PHP
RUN composer self-update
RUN composer install --optimize-autoloader --no-scripts

# Étape 10 : Installer les dépendances Nodes et construction des assets
RUN npm cache clean --force
RUN npm install


# Étape 11 : Copier le reste du projet
COPY . .

RUN npm run build

# Étape 12 : Exposer le port 80
EXPOSE 80

# Étape 13 : Lancer Apache en mode foreground
CMD ["apache2-foreground"]




