# SysFin - Sistema de Controle Financeiro

## Projeto final das disciplinas:
* Desenvolvimento de Aplicações Web II
* Desenvolvimento de Aplicações Desktop

## Para usar este projeto:
* Clone o projeto
git clone https://github.com/ProfMatioli-TDS2/sysfin-tds2.git

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

`git checkout develop`
`git pull origin develop`


## Voltando para sua Branch de Trabalho e Fazendo o Merge com `develop`

Após garantir que a branch `develop` está atualizada, o próximo passo é voltar para a sua branch de trabalho e integrar as últimas alterações da `develop` nela. Isso garante que você esteja trabalhando com a versão mais atualizada do projeto, evitando conflitos futuros ao fazer o merge de volta para a `develop`. Siga os passos abaixo:

### 1. Volte para a Sua Branch de Trabalho
Se você estava na branch `develop` para atualizar o código, agora é hora de voltar para a sua branch de trabalho, onde você estava desenvolvendo suas alterações. Use o seguinte comando para mudar para a sua branch de trabalho:

```bash
git checkout <nome_da_sua_branch>

git merge develop

