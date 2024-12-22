FROM php:8.3.13-apache

# Configurer le nom du serveur Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y --no-install-recommends \
    zip unzip git curl libpng-dev libjpeg-dev libfreetype6-dev \
    libxml2-dev libicu-dev libxslt-dev libzip-dev pkg-config libssl-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring intl gd opcache zip \
    && pecl install mongodb && docker-php-ext-enable mongodb \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs 

# Activer le module rewrite d'Apache
RUN a2enmod rewrite

# Modifier le répertoire racine d'Apache
RUN sed -i 's|/var/www/html|/var/www/public|g' /etc/apache2/sites-available/000-default.conf

# Copier la configuration Apache personnalisée
COPY docker/apache.conf /etc/apache2/conf-available/
RUN a2enconf apache

# Définir le répertoire de travail
WORKDIR /var/www

# Copier le contenu du projet
COPY . .

# Installer les dépendances PHP avec Composer
RUN composer install

# Installer les dépendances Node.js et construire le projet
RUN npm install && npm run build

# Créer les répertoires nécessaires et configurer les permissions
RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data /var/www/ \
    && chmod -R 777 var

# Exposer le port 80
EXPOSE 80

# Démarrer Apache en premier plan
CMD ["apache2-foreground"]





