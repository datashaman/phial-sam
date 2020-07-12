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
	sam local invoke --no-verify-ssl

local-invoke:
	aws lambda invoke \
		--endpoint-url http://127.0.0.1:3001 \
		--function-name=HelloWorldFunction \
		--no-verify-ssl \
		out.txt \
		&& cat out.txt \
		&& echo ""

local-start:
	sam local start-lambda

build-HelloWorldFunction:
	cp composer.* config.php php.ini $(ARTIFACTS_DIR)
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
