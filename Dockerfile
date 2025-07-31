FROM php:8.3-cli

# Crear directorio
RUN mkdir -p /var/www/html

# Copiar archivos
COPY . /var/www/html

# Establecer working directory
WORKDIR /var/www/html

# Exponer puerto
EXPOSE 80

# Comando principal
CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/html"]