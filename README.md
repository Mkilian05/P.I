# Sistema-PI

# ⚡ Watt's Up!

> **Monitore e reduza o consumo de energia da sua casa de forma inteligente.**

![Status](https://img.shields.io/badge/Status-Em_Desenvolvimento-yellow)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql&logoColor=white)
![Frontend](https://img.shields.io/badge/HTML5_CSS3_JS-Frontend-orange)

O **Watt's Up** é um sistema web desenvolvido para ajudar usuários a gerenciar o consumo energético de suas residências, cadastrando casas, ambientes e dispositivos, além de visualizar relatórios de gastos e definir metas de economia.

---

## 🛠️ Pré-requisitos

Para rodar este projeto, você precisará das seguintes ferramentas:

1.  **Laragon** (Servidor Web + PHP + MySQL)
2.  **DBeaver** (Gerenciador de Banco de Dados)
3.  **Git** (Para clonar o projeto)

---

## 🚀 Guia de Instalação Passo a Passo

Siga os passos abaixo rigorosamente para configurar o ambiente.

### 1️⃣ Instalando o Laragon (Servidor)

O Laragon criará o ambiente local para rodar o PHP e o MySQL.

1.  Baixe o Laragon Full: [Download Oficial](https://laragon.org/download/).
2.  Instale mantendo as configurações padrão.
3.  Abra o Laragon e clique no botão **"Start All"** para iniciar o Apache e o MySQL.

### 2️⃣ Instalando o DBeaver (Banco de Dados)

1.  Baixe o DBeaver Community: [Download Oficial](https://dbeaver.io/download/).
2.  Instale e abra o programa.

---

### 3️⃣ Clonando o Projeto

Vamos colocar o projeto dentro da pasta do servidor para que ele funcione no navegador.

1.  Abra seu terminal (Git Bash ou CMD).
2.  Navegue até a pasta pública do Laragon:
    ```bash
    cd C:\laragon\www
    ```
3.  Clone o repositório (substitua pelo link do seu GitHub):
    ```bash
    git clone [https://github.com/SEU_USUARIO/wattsup.git](https://github.com/SEU_USUARIO/wattsup.git)
    ```
    *(Seu projeto ficará em `C:\laragon\www\wattsup`)*

---

### 4️⃣ Configurando o Banco de Dados

Agora vamos importar a estrutura do banco para o DBeaver.

1.  No **DBeaver**, clique no ícone de tomada (🔌) ou em `Database > New Database Connection`.
2.  Selecione **MySQL** e clique em Avançar.
3.  Use as credenciais padrão do Laragon:
    * **Server Host:** `localhost`
    * **Port:** `3306`
    * **Username:** `root`
    * **Password:** *(deixe em branco / vazio)*
4.  Clique em **Finish**.
5.  Na aba lateral, clique com o botão direito na conexão criada e escolha **Create > Database**.
6.  Nomeie o banco como: `wattsup` e clique em OK.
7.  Clique com o botão direito no banco `wattsup` recém-criado -> **Tools** -> **Restore database** (ou abra o Script SQL).
    * Localize o arquivo `.sql` que está dentro da pasta do projeto clonado (geralmente `wattsup.sql`).
    * Execute o script para criar as tabelas (`usuarios`, `casa`, `ambiente`, `dispositivos`, `historico_consumo`, etc).

> **Nota:** Certifique-se de que o arquivo `config/database.php` do projeto está apontando para o usuário `root` e senha vazia.

---

## 🌍 Como Acessar

Com o Laragon rodando (Botão "Start All" verde):

1.  O Laragon costuma criar uma "Virtual Host" automática. Tente acessar:
    * 👉 **http://wattsup.test**
2.  Caso não funcione, acesse pelo caminho tradicional:
    * 👉 **http://localhost/wattsup**

---

## 📂 Estrutura do Projeto

* `/config` - Conexão com banco e scripts de processamento (backend).
* `/views` - Telas do sistema (Dashboard, Cadastros, Relatórios).
* `/assets` - CSS, Imagens e JavaScript (Chart.js).
* `/includes` - Cabeçalhos, rodapés e autenticação (`auth.php`).

---

## 🤝 Contribuição

Projeto desenvolvido para fins acadêmicos e de portfólio.

---
*Desenvolvido com 💜 e muita cafeína.*
