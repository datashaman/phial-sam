include php-layer/vars.mak

.PHONY: build
build: build-function

build-function:
	sam build

build-layer:
	sam build PhpLayer

deploy: build
	sam deploy

invoke:
	sam local invoke

build-HelloWorldFunction:
	cp composer.* php.ini $(ARTIFACTS_DIR)
	cp -a app $(ARTIFACTS_DIR)
	composer install \
		--no-ansi \
		--no-dev \
		--no-interaction \
		--no-plugins \
		--no-progress \
		--no-scripts \
		--no-suggest \
		--optimize-autoloader \
		--working-dir=$(ARTIFACTS_DIR)
