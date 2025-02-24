# Usar uma imagem base do PHP com Apache
FROM php:8.1-apache

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pdo_mysql

# Instalar o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar os arquivos da aplicação para o container
COPY application/ /var/www/html/

# Instalar dependências do Composer (se houver um composer.json)
WORKDIR /var/www/html
RUN if [ -f "composer.json" ]; then composer install --no-dev --optimize-autoloader; fi

# Configurar permissões
RUN chown -R www-data:www-data /var/www/html

# Expor a porta 80
EXPOSE 80