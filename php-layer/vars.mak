PHP_MAJOR_VERSION = 7
PHP_MINOR_VERSION = 3
EPEL_VERSION = 7

PHP_PACKAGE = php$(PHP_MAJOR_VERSION)$(PHP_MINOR_VERSION)
PHP_VERSION = $(PHP_MAJOR_VERSION).$(PHP_MINOR_VERSION)

PWD := $(shell pwd)
