
build: vendor/autoload.php

test: vendor/autoload.php
	@php bin/phpunit -c phpunit.xml

lint: vendor/autoload.php
	@php bin/php-cs-fixer fix --config=.php_cs --dry-run --diff

fix: vendor/autoload.php
	@php bin/php-cs-fixer fix --config=.php_cs

clean:
	@rm -rf vendor bin

.PHONY: build test clean

vendor/autoload.php: bin/composer
	@php bin/composer install

bin/composer:
	@php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
	php -r "if (hash_file('SHA384', 'composer-setup.php') !== '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer corrupt' . PHP_EOL; unlink('composer-setup.php'); exit(1); }"  && \
	php composer-setup.php --install-dir=bin --filename=composer && \
	php -r "unlink('composer-setup.php');"
