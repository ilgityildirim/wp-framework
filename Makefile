.PHONY: test analyse mess sniff cs cpd metrics

test:
	./vendor/bin/phpunit -c ./

analyse:
	./vendor/bin/phpstan analyse src tests

mess:
	./vendor/bin/phpmd src text codesize

sniff:
	./vendor/bin/phpcs src
	./vendor/bin/phpcbf src

cs:
	./vendor/bin/phpcs fix src

cpd:
	./vendor/bin/phpcpd --fuzzy src

metrics:
	./vendor/bin/phpmetrics --report-html=reports .

