#!/bin/bash

IMAGEM=$1
docker run -d --name 00-web -p 80:80 $IMAGEM
