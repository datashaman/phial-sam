PHP_MAJOR_VERSION = 7
PHP_MINOR_VERSION = 3
EPEL_VERSION = 7

PHP_PACKAGE = php$(PHP_MAJOR_VERSION)$(PHP_MINOR_VERSION)
PHP_VERSION = $(PHP_MAJOR_VERSION).$(PHP_MINOR_VERSION)

PWD := $(shell pwd)

build-PhpLayer:
	yum update -y
	rpm --import https://download.fedoraproject.org/pub/epel/RPM-GPG-KEY-EPEL-7
	yum install -y \
		https://dl.fedoraproject.org/pub/epel/epel-release-latest-$(EPEL_VERSION).noarch.rpm
	yum install -y \
		libargon2 \
		libpq \
		oniguruma \
		$(PHP_PACKAGE) \
		$(PHP_PACKAGE)-json \
		$(PHP_PACKAGE)-mbstring \
		$(PHP_PACKAGE)-mysql \
		$(PHP_PACKAGE)-pdo \
		$(PHP_PACKAGE)-pgsql \
		$(PHP_PACKAGE)-process \
		$(PHP_PACKAGE)-xml
	cd $(ARTIFACTS_DIR) \
		&& mkdir lib \
		&& cp \
			/usr/lib64/libargon2.so* \
			/usr/lib64/libedit.so* \
			/usr/lib64/libncurses.so* \
			/usr/lib64/libonig.so* \
			/usr/lib64/libpcre.so* \
			/usr/lib64/libpq.so* \
			/usr/lib64/libtinfo.so* \
			lib
	mkdir $(ARTIFACTS_DIR)/$(PHP_PACKAGE) \
		&& cd $(ARTIFACTS_DIR)/$(PHP_PACKAGE) \
		&& mkdir -p lib/php \
		&& cp -a /usr/lib64/php/${PHP_VERSION}/modules \
			lib/php
		&& mkdir bin \
		&& cp /usr/bin/{phar,php} bin \
		&& curl -sL https://getcomposer.org/installer | bin/php -- --install-dir=bin/ --filename=composer
		&& cp $(PWD)/composer.json $(PWD)/composer.lock ./
		&& bin/composer install
		&& cp $(PWD)/bootstrap.php $(PWD)/config.php $(PWD)/php.ini ./
		&& sed -i 's/\${PHP_PACKAGE}/${PHP_PACKAGE}/g' php.ini
	cd $(ARTIFACTS_DIR) \
		&& cp $(PWD)/bootstrap .

build-HelloWorldFunction:
	echo hi
