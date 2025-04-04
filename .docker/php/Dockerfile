FROM php:8.4-fpm

# Alapcsomagok telepítése
RUN apt-get update \
    && apt-get install -y \
        build-essential \
        zlib1g-dev \
        default-mysql-client \
        curl \
        gnupg \
        procps \
        vim \
        git \
        unzip \
        libzip-dev \
        libpq-dev \
        libicu-dev \
        libonig-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        libwebp-dev \
        libcurl4-openssl-dev \
        pkg-config \
        nodejs \
        npm

ENV PKG_CONFIG_PATH=/usr/lib/x86_64-linux-gnu/pkgconfig


# PHP bővítmények telepítése
RUN docker-php-ext-configure gd --with-jpeg --with-freetype --with-webp \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip pdo_mysql pdo_pgsql pgsql mysqli bcmath curl opcache mbstring intl exif


# PCOV (Code Coverage)
RUN pecl install pcov && docker-php-ext-enable pcov

# Xdebug telepítés
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Node.js, NPM és Yarn frissítés
RUN curl -sL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@11 yarn sass

# Composer telepítése
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Konfigurációs fájlok másolása
COPY php.ini /usr/local/etc/php/
COPY docker.conf /usr/local/etc/php-fpm.d/docker.conf
COPY www-data /etc/sudoers.d/www-data
COPY entrypoint.sh /etc/entrypoint.sh
RUN chmod +x /etc/entrypoint.sh

# Felhasználói jogok beállítása
ARG USER_ID
ARG GROUP_ID

RUN if [ ${USER_ID:-0} -ne 0 ] && [ ${GROUP_ID:-0} -ne 0 ]; then \
    userdel -f www-data &&\
    if getent group www-data ; then groupdel www-data; fi &&\
    groupadd -g ${GROUP_ID} www-data &&\
    useradd -l -u ${USER_ID} -g www-data www-data &&\
    install -d -m 0755 -o www-data -g www-data /home/www-data &&\
    chown --changes --silent --no-dereference --recursive \
          --from=33:33 ${USER_ID}:${GROUP_ID} \
        /var/www/html \
;fi

# Jogosultságok beállítása
USER www-data

ENTRYPOINT ["/etc/entrypoint.sh"]
