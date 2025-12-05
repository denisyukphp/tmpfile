init:
	docker build -t tmpfile:php8.3-cli-trixie ./

exec:
	docker run --name tmpfile --rm --interactive --tty --volume ${PWD}:/usr/local/packages/tmpfile/ tmpfile:php8.3-cli-trixie /bin/bash
