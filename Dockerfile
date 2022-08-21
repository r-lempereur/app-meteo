FROM php:7.3.33-apache

# Install wkhtmltopdf
RUN apt-get update && \
    apt-get install -y \
    zip \
    unzip \
    wget \
    vim \
    fontconfig \
    libfreetype6-dev \
    libjpeg62-turbo \
    libjpeg-dev \
    libpng-dev \
    libwebp-dev \
    libxpm-dev \
    libpng16-16 \
    libx11-6 \
    libxcb1 \
    libxext6 \
    libxrender1 \
    xfonts-75dpi \
    xfonts-base

RUN wget https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6.1-2/wkhtmltox_0.12.6.1-2.bullseye_amd64.deb
RUN dpkg -i ./wkhtmltox_0.12.6.1-2.bullseye_amd64.deb
RUN rm ./wkhtmltox_0.12.6.1-2.bullseye_amd64.deb

RUN docker-php-ext-configure gd \
    --with-gd \
    --with-webp-dir \
    --with-jpeg-dir \
    --with-png-dir \
    --with-zlib-dir \
    --with-xpm-dir \
    --with-freetype-dir

RUN docker-php-ext-install gd

# -----------------------------------------------------------------------------
# Install Composer
# -----------------------------------------------------------------------------
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

COPY ./composer.json ./composer.json

COPY .env.local.dist .env

RUN composer install
