# Broker web application

[![CircleCI](https://circleci.com/gh/aasa-global/broker-frontend-public.svg?style=shield&circle-token=08389d4d974569df4b92e070aa1ffbb510a0d5df)](https://circleci.com/gh/aasa-global/broker-frontend-public)

This is the base web application skeleton for the Broker project. The main business logic is handled by [aasa-global/broker](https://github.com/aasa-global/broker)
repository.

## Setting up the Application

Clone the project and run the following commands:

    docker-compose up -d
    docker-compose exec php vendor/bin/phinx migrate -c phinx.php
    docker-compose exec php vendor/bin/phinx seed:run -s PartnerSeed -s ApplicationSeed -s OfferSeed -s SampleAppOfferSeed -c phinx.php

Now you should have a local development up and running on port 8100 with some initial sample data.

## Updating the base system

As the base system lives in another repository, you can update the local version manually to latest master branch (substitute $PWD with %cd% when using Windows):

    docker run --rm --interactive --volume $PWD:/app composer update aasa/broker
    
## Running the application

    docker-compose up -d
    
In case someone else has pulled in a new core version and updated the remote repository, then running the application will always update your local
core system as well.


### Running tests

    docker-compose exec php vendor/bin/phpunit tests/Unit --bootstrap phpunit-bootstrap.php