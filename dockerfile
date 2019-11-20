FROM php:7.3-apache-stretch

RUN  docker-php-ext-install pdo_mysql opcache \
    && a2enmod rewrite negotiation

# (EXT) mbstring bcmath openssl tokenizer xml

COPY ./docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY . /srv/app

WORKDIR /srv/app