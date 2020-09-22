# API Carros OLX Crawler

## Pré requisitos

- Docker compose

## Como instalar

Dentro da pasta do projeto excutar o comando: `./install.sh`

Caso tenha algum conflito de portas do docker alterar as diretivas abaixo no arquivo laradock/.env:

```
NGINX_HOST_HTTP_PORT
REDIS_PORT
WORKSPACE_BROWSERSYNC_HOST_PORT
WORKSPACE_BROWSERSYNC_UI_HOST_PORT
WORKSPACE_VUE_CLI_SERVE_HOST_PORT
WORKSPACE_VUE_CLI_UI_HOST_PORT
WORKSPACE_ANGULAR_CLI_SERVE_HOST_PORT
```

## Documentação da API

A documentação se encontra na rota `/docs`
