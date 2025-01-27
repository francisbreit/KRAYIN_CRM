FROM php:8.3-apache

# Instalação de dependências do sistema
RUN apt-get update && apt-get install -y \
    apparmor-utils \
    nano \
    coreutils \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    zlib1g-dev \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip intl calendar

# Instalação do Composer
RUN curl -sS https://getcomposer.org/installer -o composer-installer.php \
    && php composer-installer.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-installer.php

# Configuração do Apache
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/krayin/public|' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Configuração do Timezone
RUN ln -sf /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime \
    && echo "America/Sao_Paulo" > /etc/timezone

# Configuração do formato de data/hora no PHP
RUN echo "date.timezone = America/Sao_Paulo" >> /usr/local/etc/php/conf.d/timezone.ini \
    && echo "date.format = 'd-m-Y H:i:s'" >> /usr/local/etc/php/conf.d/timezone.ini

WORKDIR /var/www/html

# Configuração do Git
RUN git config --global --add safe.directory /var/www/html/krayin

# Copiar os arquivos da aplicação
COPY . krayin

WORKDIR /var/www/html/krayin

# Variável de ambiente para o Composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Instalação de dependências do Composer e do Krayin
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN php artisan key:generate --force
RUN composer require krayin/rest-api --no-interaction
RUN php artisan krayin-rest-api:install --no-interaction

# Instalação e build do frontend
RUN npm install
RUN npm run build

# Configuração de permissões
RUN chown -R www-data:www-data /var/www/html/krayin
RUN chmod -R 775 /var/www/html/krayin

# Expor a porta do Apache
EXPOSE 80

# Comando de inicialização do Apache
CMD ["apache2-foreground"]
