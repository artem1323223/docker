FROM php:8.2-fpm

RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    nginx \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

COPY default.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

COPY www/ /var/www/html/
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

EXPOSE 80

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
