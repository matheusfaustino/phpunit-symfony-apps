FROM php:5.6-cli

COPY php.ini /usr/local/etc/php/

# install php dependency
RUN apt-get update && apt-get install -y \
      bash-completion \
      vim \
      curl \
      libmcrypt-dev \
      libicu-dev \
      zlib1g-dev \
      php5-intl \
      libfreetype6-dev \
      libjpeg62-turbo-dev \
      libpng12-dev \
  && docker-php-ext-install -j$(nproc) iconv mcrypt intl mysql mysqli pdo pdo_mysql mbstring exif zip \
  && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
  && docker-php-ext-install -j$(nproc) gd

RUN pecl install apcu-4.0.10 && \
    docker-php-ext-enable apcu

# change php timezone
RUN echo "date.timezone = America/Sao_Paulo" >> /usr/local/etc/php/php.ini

# add composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./phpunitbot /var/www/phpunitbot

WORKDIR '/var/www/phpunitbot'

RUN /usr/local/bin/composer install

ENTRYPOINT ["/usr/local/bin/php", "./vendor/bin/robo"]

CMD ["watch:tests"]
