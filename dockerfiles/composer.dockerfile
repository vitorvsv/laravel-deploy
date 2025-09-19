FROM composer

# RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

# USER laravel

WORKDIR /var/www/html

# This ENTRYPOINT garants that only composer utility will be executed in the container
# and setting up the workdir to /var/www/html garants that the command is only in that directory
ENTRYPOINT [ "composer", "--ignore-platform-reqs" ]