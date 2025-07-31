FROM php:8.3-fpm

# Instalar dependencias básicas
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev \
    && docker-php-ext-install gd \
    && rm -rf /var/lib/apt/lists/*

# Copiar código fuente
COPY . /var/www/html

# Asignar permisos
RUN chown -R www-data:www-data /var/www/html

# Configurar Nginx
COPY ./docker/nginx.conf /etc/nginx/sites-available/default

# Limpiar default site y activarlo
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default \
    && rm -f /etc/nginx/sites-enabled/default.conf

# Configurar PHP-FPM
COPY ./docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Supervisord config
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]