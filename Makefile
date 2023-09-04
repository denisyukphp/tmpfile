init:
	docker build -t tmpfile:8.2 ./

exec:
	docker run --name tmpfile --rm --interactive --tty --volume ${PWD}:/usr/local/packages/tmpfile/ tmpfile:8.2 /bin/bash
