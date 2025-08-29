## Description
- symfony app
- budget tracker

## Requirements
- make
- docker with compose

## Quick start
```console
$ make start_app
$ make init_app # while services from the first step are running
```

## Curl examples
➜  dttracker git:(main) ✗ curl --request POST \
  --url http://localhost:12345/register \
  --header 'content-type: application/json' \
  --header 'user-agent: vscode-restclient' \
  --data '{"email": "example@example.com","password": "password"}'
{"result":"success"}%

➜  dttracker git:(main) ✗ curl --cookie cookies.txt --request POST \
  --url http://localhost:12345/login \
  --header 'content-type: application/json' \
  --header 'user-agent: vscode-restclient' \
  --data '{"email": "example@example.com","password": "password"}'
{"result":"success"}%

➜  dttracker git:(main) ✗ curl --cookie cookies.txt --request GET \
  --url 'http://localhost:12345/api/categories?start=0&size=10' \
  --header 'accept: application/json' \
  --header 'user-agent: vscode-restclient'
{"categories":[{"id":17,"name":"food"},{"id":18,"name":"car"},{"id":19,"name":"accommodation"},{"id":20,"name":"gifts"}]}%

➜  dttracker git:(main) ✗ curl --cookie cookies.txt --request POST \
  --url http://localhost:12345/logout \
  --header 'content-type: application/json' \
  --header 'user-agent: vscode-restclient'
{"result":"success"}%

## Run code quality tools
- make phpstan
- make sniffer

## Run unit tests
- make init_test
- make test

## TODOs
- merge expense and income to transaction
- add categories to incomes
