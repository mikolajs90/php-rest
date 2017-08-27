#!/bin/bash
(cd src/api && composer install)
(cd src/client && composer install)
docker-compose build
