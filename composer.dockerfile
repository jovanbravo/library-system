FROM composer:2.8.5
ARG COMPOSER_USER=composer_default
ARG COMPOSER_GROUP=composer_default

RUN adduser -g ${COMPOSER_GROUP} -s /bin/sh -D ${COMPOSER_USER}