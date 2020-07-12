FROM lambci/lambda:build-provided

ARG PHP_MAJOR_VERSION=7
ARG PHP_MINOR_VERSION=3
ARG EPEL_VERSION=7

ENV PHP_PACKAGE=php${PHP_MAJOR_VERSION}${PHP_MINOR_VERSION}
ENV PHP_VERSION=${PHP_MAJOR_VERSION}.${PHP_MINOR_VERSION}

RUN yum update -y

RUN rpm --import https://download.fedoraproject.org/pub/epel/RPM-GPG-KEY-EPEL-${EPEL_VERSION}

RUN yum install -y \
    https://dl.fedoraproject.org/pub/epel/epel-release-latest-${EPEL_VERSION}.noarch.rpm

RUN yum install -y \
    libargon2 \
    libpq \
    oniguruma \
    ${PHP_PACKAGE} \
    ${PHP_PACKAGE}-json \
    ${PHP_PACKAGE}-mbstring \
    ${PHP_PACKAGE}-mysql \
    ${PHP_PACKAGE}-pdo \
    ${PHP_PACKAGE}-pgsql \
    ${PHP_PACKAGE}-process \
    ${PHP_PACKAGE}-xml

RUN mkdir /opt/php
WORKDIR /opt/php

RUN mkdir lib \
    && cp \
        /usr/lib64/libargon2.so* \
        /usr/lib64/libedit.so* \
        /usr/lib64/libncurses.so* \
        /usr/lib64/libonig.so* \
        /usr/lib64/libpcre.so* \
        /usr/lib64/libpq.so* \
        /usr/lib64/libtinfo.so* \
        lib

RUN mkdir -p lib/php \
    && cp -a /usr/lib64/php/${PHP_VERSION}/modules lib/php

RUN mkdir bin \
    && cp /usr/bin/{phar,php} bin \
    && curl -sL https://getcomposer.org/installer | bin/php -- --install-dir=bin/ --filename=composer

COPY bootstrap.php .

WORKDIR /opt

COPY bootstrap .
