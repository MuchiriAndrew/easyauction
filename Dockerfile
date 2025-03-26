# Use webdevops/php-nginx as base image
FROM webdevops/php-nginx:8.2

##change the shell
SHELL ["/bin/bash", "-c"]

ENV PHP_MAX_EXECUTION_TIME 110
# Copy the project files into the container
COPY . /production/easyauction

# Set the laravel web folder
ARG WEB_PATH=/production/easyauction/public
ENV WEB_DOCUMENT_ROOT=$WEB_PATH

# set the correct laravel app foler
ARG LARAVEL_PATH=/production/easyauction
WORKDIR $LARAVEL_PATH

# # Install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs



# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip


# Install JavaScript dependencies
RUN npm install && npm run build


# configure packages
# RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install Composer and PHP dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install

# Copy the project files into the container
COPY . .

EXPOSE 80

# Laravel specific commands
RUN php artisan storage:link && \
    mkdir -p bootstrap/cache && \
    touch storage/logs/laravel.log && \
    chmod -R 777 storage && \
    chmod -R 777 bootstrap

ARG DB_CONNECTION
ARG DB_HOST
ARG DB_PORT
ARG DB_DATABASE
ARG DB_USERNAME
ARG DB_PASSWORD

ARG MPESA_CONSUMER_KEY='Xb21W9yMyo3ifgaAdoKyTAnS6ibgZkrk1RClzcuJ2MrtWEvN'
ARG MPESA_CONSUMER_SECRET='Iy6se2lcCl4iGK6wBvuUNecwjvlXy3oQyR1pKZr7IhCq6HhZLWyTU0GrbI491J8y'
ARG MPESA_SHORTCODE='4265572'
ARG MPESA_PASSKEY='f6dd6e654087c22b870f7f1637272938e825d95e861c338a546178c75c63b081'
ARG MPESA_ENVIRONMENT=live
ARG MPESA_CALLBACK_URL='https://mpesa-callback-handler.projects.muchiriandrew.tech/handle'
ARG MPESA_INITIATOR_NAME=
ARG MPESA_INITIATOR_SHORTCODE=
ARG MPESA_INITIATOR_PASSWORD=
ARG MAIL_MAILER=smtp
ARG MAIL_HOST=live.smtp.mailtrap.io
ARG MAIL_PORT=587
ARG MAIL_USERNAME=api
ARG MAIL_PASSWORD=0431b6bf4c3eb8378f02d4aa22b23c32
ARG MAIL_ENCRYPTION=tls
ARG MAIL_FROM_ADDRESS="no-reply@demomailtrap.com"
ARG MAIL_FROM_NAME=EasyAuction