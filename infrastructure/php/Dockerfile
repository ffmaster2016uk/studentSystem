FROM php:8.1-fpm-alpine

RUN apk update && apk add --no-cache \
    bash \
    build-base shadow vim curl \
    php8 \
    php8-fpm \
    php8-common \
    php8-pdo \
    php8-pdo_mysql \
    php8-mysqli \
    php8-mbstring \
    php8-xml \
    php8-openssl \
    php8-json \
    php8-phar \
    php8-zip \
    php8-gd \
    php8-dom \
    php8-session \
    php8-zlib \
    zip libzip-dev

#RUN apk add --upgrade gnu-libiconv
#ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so

RUN docker-php-ext-install pdo pdo_mysql

RUN docker-php-ext-install zip exif

RUN apk add --no-cache \
      freetype \
      libjpeg-turbo \
      libpng \
      freetype-dev \
      libjpeg-turbo-dev \
      libpng-dev \
    && docker-php-ext-configure gd \
      --with-freetype=/usr/include/ \
      # --with-png=/usr/include/ \ # No longer necessary as of 7.4; https://github.com/docker-library/php/pull/910#issuecomment-559383597
      --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable gd \
    && apk del --no-cache \
      freetype-dev \
      libjpeg-turbo-dev \
      libpng-dev \
    && rm -rf /tmp/*

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add nodejs and npm
#RUN apk add --update nodejs npm

# Remove Cache
RUN rm -rf /var/cache/apk/*

EXPOSE 9000
CMD ["php-fpm"]
