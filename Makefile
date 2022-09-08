DOCKER_BUILD := docker build -t tmpfile:latest ./
DOCKER_RUN := docker run --rm --interactive --tty --volume ${PWD}:/usr/local/packages/tmpfile tmpfile:latest

install:
	${DOCKER_BUILD}; \
	${DOCKER_RUN} composer update
