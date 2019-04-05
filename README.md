# Broker web application

[![CircleCI](https://circleci.com/gh/aasa-global/broker-frontend-public.svg?style=shield&circle-token=08389d4d974569df4b92e070aa1ffbb510a0d5df)](https://circleci.com/gh/aasa-global/broker-frontend-public)

This is the base web application skeleton for the Broker project. The main business logic is handled by [aasa-global/broker](https://github.com/aasa-global/broker)
repository.

## Setting up the Application

Clone the project and run the following commands:

    docker-compose up -d
    docker run --rm --interactive --volume $PWD:/app composer install
    docker-compose exec php vendor/bin/phinx migrate -c phinx.php
    docker-compose exec php vendor/bin/phinx seed:run -s PartnerSeed -s ApplicationSeed -s OfferSeed -s SampleAppOfferSeed -c phinx.php

Now you should have a local development up and running on port 8100 with some initial sample data. Please do note that the seed command
should only be ran once to prevent duplicate data.

## Updating the base system

As the base system lives in another repository, you can update the local version manually to latest master branch (substitute $PWD with %cd% when using Windows):

    docker run --rm --interactive --volume $PWD:/app composer update aasa/broker
    
## Running the application

    docker-compose up -d
    
In case someone else has pulled in a new core version and updated the remote repository, then running the application will always update your local
core system as well.


### Running tests

    docker-compose exec php vendor/bin/phpunit tests/Unit --bootstrap phpunit-bootstrap.php
    
    
### Updating translations

The project uses [gettext](http://php.net/manual/en/book.gettext.php) for internationalization. After making modifications to translatable source files,
please run the following command to collect modifications to translatable strings.

    docker-compose exec php /bin/bash -c /app/infrastructure/php/scanGettext.sh
    
Running the above command will create/update the language file `locale/broker.pot`. You can use software like Poedit
to open the newly created `.pot` file. After you're finished with the translations, please save the generated `.po` file to
folder `locale/pl_PL/LC_MESSAGES/`.
