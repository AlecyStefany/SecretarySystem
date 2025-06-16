# SecretarySystem

Sistema de Gerenciamento de Secretaria Escolar

1. Pré-requisitos
Antes de começar, você precisará de:
PHP >= 7.3
Composer (para gerenciar dependências)
MySQL.

2. Instalação
Siga os passos abaixo para configurar o projeto em sua máquina local:

Clone o repositório:
git clone https://github.com/AlecyStefany/SecretarySystem.git
cd SecretarySystem
Instalar dependências do Composer:

Se você ainda não tem o Composer instalado, clique aqui para aprender a instalá-lo. Depois, execute:
composer install

Configuração do Banco de Dados:
Certifique-se de ter o MySQL instalado e configurado. Crie um banco de dados com o nome secretaryDb.

Comando MySQL (Exemplo):
O script de criação de tabela esta no arquivo dump.sql

Configuração do arquivo .env:

crie o arquivo .env e configure as variáveis de ambiente com os dados de conexão do seu banco de dados:
DB_HOST=localhost
DB_NAME=secretaryDb
DB_USER=seu_usuario
DB_PASSWORD=sua_senha

Rodando o Projeto:

php -S localhost:8000 -t public/
Agora, acesse http://localhost:8000 no seu navegador.

Para o Front
Caso utilize o VS code pode instalar a extensão Live Server

1. Instalar a Extensão Live Server
Abra o Visual Studio Code (VS Code).
Vá para a aba de Extensões no menu lateral (ou pressione Ctrl+Shift+X).
Na barra de pesquisa, procure por Live Server.
Clique em Instalar na extensão chamada Live Server por Ritwick Dey.
Link para a Extensão: Live Server - VS Code

2. Iniciar o Live Server
Abra o arquivo index.html ou qualquer outro arquivo HTML que você deseje visualizar no navegador.
Clique com o botão direito no arquivo e selecione "Open with Live Server".
Ou, simplesmente clique no ícone Go Live no canto inferior direito do VS Code.
Isso iniciará um servidor local, e seu navegador será aberto automaticamente para exibir o conteúdo. O endereço padrão será http://127.0.0.1:5500 ou http://localhost:5500.