include vars.mak

build-PhpLayer:
	docker build -t $(PHP_PACKAGE) .
	CONTAINER_ID=$(shell docker run -d -t $(PHP_PACKAGE) bash) \
		&& docker cp $${CONTAINER_ID}:/opt/bootstrap $(ARTIFACTS_DIR) \
		&& docker cp $${CONTAINER_ID}:/opt/php $(ARTIFACTS_DIR) \
		&& docker rm --force $${CONTAINER_ID}
