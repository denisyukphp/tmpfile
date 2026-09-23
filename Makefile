up:
	docker build -t tmpfile:php8.5-cli-trixie ./

shell:
	docker run \
		--name tmpfile \
		--rm \
		--interactive \
		--tty \
		--volume ${PWD}:/usr/local/packages/tmpfile/ \
		tmpfile:php8.5-cli-trixie /bin/bash ;
