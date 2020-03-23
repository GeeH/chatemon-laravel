.PHONY: deploy static php test sniff

static:
	npm run production
	aws s3 sync public/img s3://chatemon-static-assets/img --delete
	aws s3 sync public/css s3://chatemon-static-assets/css --delete
	aws s3 sync public/js s3://chatemon-static-assets/js --delete

php:
	php artisan cache:clear
	serverless deploy

test:
	vendor/bin/phpunit --testdox --testsuite Chatemon

sniff:
	vendor/bin/phpcs --standard=PSR12 chatemon/src

deploy: test php static
