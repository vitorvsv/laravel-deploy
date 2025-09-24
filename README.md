# Description

Project that provide a complete development docker environment for Laravel. You can use it in your localhost and deploy the artifact with `dockerfiles/artifact.dockerfile` and publish in a kubernetes cluster.

## How to run locally

- Copy the Laravel code to `/src` folder or create a new Laravel application \
`docker-compose run --rm composer create-project laravel/laravel .`

- Installing dependencies with (skip it if it was did in the last step) \
`docker-compose run --rm composer install`

- Run the server with \
`docker-compose up -d server`

## How deploy to a kubernetes cluster

[TBD]
