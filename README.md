<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Projeto PHP Backend Challenge 20230105

## Introdução

Nesse desafio trabalhamos no desenvolvimento de uma REST API para utilizar os dados do projeto Open Food Facts.

O projeto tem por objetivo avaliar conhecimentos sobre a linguagem de programação PHP e o Framework Laravel, alem de
solução de problemas de tecnologia com desenvolvimento de APIs.

Projeto desenvolvido para conceito de avaliação pela [Coodesh](https://coodesh.com/).

> This is a challenge by [Coodesh](https://coodesh.com/)


Para esse projeto, as principias tecnologias utilizadas foram:

|         | Link                                             |
|---------|--------------------------------------------------|
| PHP     | <https://php.net/>                               |
| Laravel | <https://laravel.com/>                           |
| Docker  | <https://docs.docker.com/engine/install/ubuntu/> |
| PHPUnit | <https://phpunit.de/>                            |
| Swagger | <https://swagger.io/>                            |

> Obs.: Tendo em vista que o projeto foi desenvolvido para executar em ambiente dockerizado, as instruções a seguir,
> levarão em consideração que, o [Docker](https://docs.docker.com/engine/install/ubuntu/)
> e [Docker Compose](https://docs.docker.com/compose/install/) já se encontram devidamente instalados.
> Contudo, a utilização ou não do [Docker](https://docs.docker.com/engine/install/ubuntu/) fica a critério do testador
> da aplicação.

## Docker

> Na raiz do projeto será copiado o arquivo `.env.example` para o arquivo `.env`, que contém informações e variáveis de
> ambiente para a aplicação, por padrão a aplicação está acessível em `localhost`, com um path `/nome_do_recurso`.

1. Iniciando ambiente do projeto
   > Com o terminal, navegue até a pasta raiz do projeto e execute o comando abaixo. Ao final do processo, deverá
   > aparecer um `log` no terminal. Nesse ponto, a `api` já deve estar acessíveis em `localhost`, caso as mesmas não
   > tenham sido modificadas no arquivo `docker-compose.yml` da raiz projeto.

    ````bash
    docker run --rm \
     -u "$(id -u):$(id -g)" \
     -v "$(pwd):/var/www/html" \
     -w /var/www/html \
     laravelsail/php83-composer:latest \
     composer install
    ````

    ```bash
    ./vendor/bin/sail up -d
    ```

2. configs do projeto
   > Para continuar a configuração do projeto execute.

    ```bash
    ./vendor/bin/sail bash
    ```

   > apos o passo anterior rode mais dois comando para copiar o env e instalar os pacotes que são:

    ```bash
    cp .env.example .env && composer install && php artisan key:generate
    ```

   > use este comando para rodar os testes automatizados:

    ```bash
    php artisan test
    ```

   > quando finalizar a instalação execute mais este comando abaixo e já poderá desfrutar da api desenvolvida.

    ```bash
    php artisan migrate
    ```

   > e se quiser pode rodar uma seed para alguns clientes.

    ```bash
    php artisan db:seed --class=ProductSeeder
    ```

3. Derrubar os containers
   > Para finalizar os containers, basta executar no mesmo terminal `Ctrl + c`, por segurança, execute o comando abaixo.

    ```bash
    ./vendor/bin/sail down
    ```
