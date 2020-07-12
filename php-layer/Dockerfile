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
    ${PHP_PACKAGE} \
    ${PHP_PACKAGE}-gd \
    ${PHP_PACKAGE}-json \
    ${PHP_PACKAGE}-mbstring \
    ${PHP_PACKAGE}-process \
    ${PHP_PACKAGE}-xml

WORKDIR /opt

RUN mkdir bin \
    && cp /usr/bin/{phar,php} bin \
    && curl -sL https://getcomposer.org/installer | bin/php -- --install-dir=bin/ --filename=composer

RUN mkdir lib \
    && cp \
        /usr/lib64/libedit.so* \
        /usr/lib64/libncurses.so* \
        /usr/lib64/libpcre.so* \
        /usr/lib64/libtinfo.so* \
        lib

RUN mkdir lib/php \
    && cp -a /usr/lib64/php/${PHP_VERSION}/modules lib/php

COPY bootstrap bootstrap.php ./
