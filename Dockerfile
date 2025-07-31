FROM php:8.3-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar código
COPY . .

# Crear carpetas necesarias si existen
RUN mkdir -p storage/logs bootstrap/cache \
    && chown -R www-data:www-data /var/www/html

# Copiar configuraciones de Nginx y PHP-FPM
COPY ./docker/nginx.conf /etc/nginx/sites-available/default
COPY ./docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]