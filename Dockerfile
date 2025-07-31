FROM php:8.3-fpm

# Instalar todas las dependencias del sistema
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libmysqlclient-dev \
    zip \
    unzip \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    gd \
    opcache \
    zip \
    mbstring \
    exif \
    pcntl \
    bcmath \
    sockets

# Copiar código fuente
COPY . /var/www/html

# Asignar permisos
RUN chown -R www-data:www-data /var/www/html

# Configurar Nginx
COPY ./nginx.conf /etc/nginx/sites-available/default

# Limpiar default site y activarlo
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default \
    && rm -f /etc/nginx/sites-enabled/default.conf

# Configurar PHP-FPM
COPY ./php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Supervisord config
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Exponer puerto
EXPOSE 80

# Comando de inicio
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]