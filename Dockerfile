FROM php:8.2-fpm

# Установка PHP расширений
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Установка Nginx, Supervisor и утилит
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    nginx \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

# Копируем конфиг Nginx
COPY nginx/default.conf /etc/nginx/sites-available/default
RUN ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Копируем файлы проекта
COPY www/ /var/www/html/

# Копируем конфиг Supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

EXPOSE 80

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
