FROM php:8.2.0-fpm-alpine

RUN set -ex \
  && apk --no-cache add \
    libzip-dev \
    zip \
    libpng-dev \
    libxslt-dev \
    bash \
    icu-dev \
    openssl \
    acl \
    wget \
    npm

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

RUN install-php-extensions \
    pdo \
    pdo_pgsql \
    zip \
    xsl \
    gd \
    intl \
    @composer \
    apcu

#SYMFONY_CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
#SYMFONY_CLI

WORKDIR /app

RUN echo 'pm.max_children = 30' >> /usr/local/etc/php-fpm.d/zz-docker.conf

#OPCACHE
RUN docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache
#OPCACHE

#APCU
RUN docker-php-ext-enable apcu
RUN echo "extension=apcu.so" > /usr/local/etc/php/php.ini
RUN echo "apc.enable_cli=1" > /usr/local/etc/php/php.ini
RUN echo "apc.enable=1" > /usr/local/etc/php/php.ini
#APCU

CMD ["php-fpm"]