#!/usr/bin/env bash

# Expects service to be called app in docker-compose.yml
SERVICE_ID=$(docker-compose ps -q php)

docker exec -it $SERVICE_ID /bin/sh