FROM php:fpm
RUN apt-get update && apt-get install -y \
    git curl unzip libzip-dev zip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip

COPY --from=node:latest /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node:latest /usr/local/bin/node /usr/local/bin/node
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

ARG DOCKER_UID
ARG DOCKER_GID

RUN groupadd -g ${DOCKER_GID} www && \
    useradd -u ${DOCKER_UID} -g www -s /bin/bash -m www
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
USER ${DOCKER_UID}:${DOCKER_GID}
