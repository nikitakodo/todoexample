FROM phpdockerio/php:7.4-fpm
WORKDIR "/project"

RUN apt-get update; \
    apt-get -y --no-install-recommends install \
        php7.4-pgsql \
        php7.4-redis; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
RUN chmod a+rw -R /project
