FROM php:7.4-fpm

RUN apt update && apt install -y vim git curl wget zip unzip postgresql-client libpq-dev nodejs npm

RUN apt clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pgsql pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

RUN npm install -g yarn

CMD ["php-fpm"]
