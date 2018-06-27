FROM php:7.2-cli

RUN DEBIAN_FRONTEND=noninteractive && \
  apt-get update && \
  apt-get -y install \
    unzip \
    wget \
    zip \
  && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
  --filename=composer --install-dir=/usr/local/bin

# Install Xdebug
RUN pecl install xdebug \
  && docker-php-ext-enable xdebug

# Add symlink for phpunit for easier running
RUN ln -s /app/vendor/bin/phpunit /usr/local/bin/phpunit

# Create a user
RUN useradd -ms /bin/bash user

RUN mkdir /app
COPY ./composer.* /app/
COPY ./src /app/src
COPY ./test /app/test
COPY ./phpunit.xml.dist /app/phpunit.xml.dist

RUN chown user:user /app

WORKDIR /app

USER user

RUN composer install
