# SysFin - Sistema de Controle Financeiro

## Projeto final das disciplinas:
* Desenvolvimento de Aplicações Web II
* Desenvolvimento de Aplicações Desktop

## Para usar este projeto:
* Clone o projeto
git clone https://github.com/prof-matioli/sysfin-4dsn.git

* Faça checkout para no branch __develop__
git checkout develop

* Instale as dependências
composer install

## Para contribuir com esse projeto
* crie um novo branch, a partir do branch __develop__  
`git checkout develop`  
`git checkout -b <nome_branch>`  

* ao terminar sua contribuição, faça __push__ para o servidor remoto no branch que você criou. Por exemplo:  
`git add .`  
`git commit -m "mensagem do commit"`  
`git push -u origin <nome_branch>`  

* crie um novo __PULL REQUEST__ no repositório, para que o seu branch seja analisado e, se estiver correto, seja adicionado ao branch __develop__ pelo gerente do projeto.

* ## Atualizando sua Branch com a Última Versão da `develop`

Antes de começar a trabalhar em uma nova tarefa ou após terminar de trabalhar em sua branch, é importante garantir que sua cópia local da branch `develop` esteja sempre atualizada. Para isso, siga os passos abaixo para puxar as últimas atualizações de `develop`:

### 1. Vá para a Branch `develop`
Primeiro, certifique-se de que você está na branch `develop`, onde todas as atualizações principais do projeto estão sendo feitas.

```bash
git checkout develop
git pull origin develop
