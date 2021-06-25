#!/bin/bash

IMAGEM=$1
docker stop 00-web
docker rm -f 00-web
docker run -d --name 00-web -p 80:80 $IMAGEM

