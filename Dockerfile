FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    apparmor-utils \
    nano \
    coreutils \
    git \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    zlib1g-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip intl calendar

RUN curl -sS https://getcomposer.org/installer -o composer-installer.php \
    && php composer-installer.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-installer.php

RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/krayin/public|' /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

WORKDIR /var/www/html

RUN git config --global --add safe.directory /var/www/html/krayin

COPY . krayin

WORKDIR /var/www/html/krayin
 
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN php artisan key:generate --force

RUN chown -R www-data:www-data /var/www/html/krayin
RUN chmod -R 775 /var/www/html/krayin

RUN composer require krayin/rest-api --no-interaction
RUN php artisan krayin-rest-api:install --no-interaction

EXPOSE 80

CMD ["apache2-foreground"]
