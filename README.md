# API RestFull 

API RestFull Desenvolvida com PHP puro (sem frameworks externos) com objetivo de ser utilizada como base para iniciar projetos futuros com o básico já feito.

O projeto busca implementar tecnologias e padrões que garantem a organização, escalabilidade e manutenções futuras. 

## Tecnologias, Padrões e Arquiteturas
- PHP 8.3
- Organização de Rotas Personalizadas
- Autenticação via JWT
- Autenticação via OAuth2 (Google API)
- Composer
- DDD
- Clean Architecture
- Arquitetura Hexagonal

## Arquitetura do Projeto
A arquitetura do projeto segue princípios de **DDD**, **Clean Architecture** e **Arquitetura Hexagonal**
```
app
├── logs
├── public
└── src
    ├── Config
    ├── Domain
    │   ├── Models
    │   └── Repositories
    ├── Http
    │   ├── Controllers
    │   ├── Request
    │   └── Transformer
    ├── Infra
    │   ├── Persistence
    │   └── Services
    ├── Routers
    ├── Utils
    ├── composer.json
    ├── composer.lock
    ├── .env
    ├── index.php
```

## Funcionalidades 

- Autenticação e Segurança via JWT
- Autenticação com OAuth2 (Google API)
- Rotas dinâmicas e personalizadas
- Sistema de logs personalizáveis
- Upload dinâmico de arquivos
- Sistema de notificação de email
- Customização de variáveis de ambiente via `.env`

## Execução do Projeto

### 1 - Clonar repositório

```bash
git clone https://github.com/andreeezinho/sistema-pdv.git
```

### 2 - Remover '.example.' de `src/.env.example`

### 3 - Inserir valores nas variáveis
Insira os valores de acordo com o seus dados
```bash
SITE_NAME='nome-api'
API_URL='http://localhost:8888'
PERMITTED_HOST='*' #host permitido para utilizar a API, use * para liberar para todos (não recomendado) ou o ip correto do front-end (ex: http://localhost:5173)

DB_HOST='local-database'
DB_NAME='nome-database'
DB_USER='user-database'
DB_PASSWORD='senha-database'

JWT_SECRET='senha-jwt' ## Senha para personalizar o token JWT

GOOGLE_CREDENTIALS= 'nome_dor_arquivo_credenciais.json' #nome do arquivo json das credenciais para autenticação com google
GOOGLE_REDIRECT_URI='http://localhost:5173' #url para o redirecionamento após o login com o google

EMAIL = 'seuemail@gmail.com' 
EMAIL_CODE = 'gfte esjt eqes qhmm'; ## Senha do SMTP que precisa cadastrar
```

### 4 - Executar o script `db.sql` para o banco de dados
```bash
mysql -u root -p api-db < db.sql
```

O script vem com um usuário padrão com todas as permissões inicialmente:

```
email: admin@admin.com
senha: password
```

### 5 - Executar projeto
```bash
php -S localhost:8888 -t ./
```

## Endpoints
O endpoint para fazer a autenticação com o usuário não necessita de validação de nenhum token, somente das suas credenciais

**POST** `/auth`

 - **Headers:** `""`
 - **Resposta:** 
    ```bash
    {
        "message": "Sucesso ao logar"
        "data": {token}
    }
    ```

### Endpoints Protegidos
Todos os endpoints que são protegitos por autenticação necessitam de um token `Bearer` via JWT

**GET** `/usuarios`

 - **Headers:** `"Authorization: Bearer {token}"`
 - **Resposta:** 
    ```bash
    {
        "message": "Usuários listados"
        "data": [
            {
                "uuid": "0661993e-7ae8-4146-8602-403f5edb92ea",
                "usuario": "adm",
                "nome": "Administrador André",
                "email": "admin@admin.com",
                "cpf": "111.222.333-44",
                "telefone": "(75) 9988-7766",
                "ativo": 1,
                "is_admin": 0,
                "icone": "69701adfcf4bd_1768954591.jpg",
                "created_at": "2025-03-01 16:04:15",
                "updated_at": "2026-01-20 21:16:31"
            }
        ]
    }
    ```

## Autenticação com Google via OAuth2

Antes de começar é necessário criar uma [credencial](https://support.google.com/workspacemigrate/answer/9222992?hl=PT) JSON, inserir na diretório do projeto e o nome em `.env` `GOOGLE_CREDENTIALS=''`

Para autenticação via Google, existem dois endpoints que são necessários:

1:

**GET** `/google-link`

 - **Headers:** `""`
 - **Resposta:** 
    ```bash
    {
        "message": "Sucesso ao gerar link",
        "data": "https://link-do-google-auth"
    }
    ```
Esse endpoint gera o link para a tela de login do google e redireciona para o endpoint definido em `.env` `GOOGLE_REDIRECT_URI=''`

Ao redirecionar para o local desejado, ele insere um código como parâmetro na URI `http://localhost:5173?code=codigo_que_ira_aparecer`

2:

É necessário passar o código para esse endpoint como `code`

**POST** `/google-auth`

 - **Headers:** `""`
 - **Resposta:** 
    ```bash
    {
        "message": "Sucesso ao logar com o Google",
        "data": {token}
    }
    ```

O endpoint acessa a API do Google para verificar o código e retornar os dados do usuário.

Se o usuário já estiver cadastro, ele gera o token JWT. Se não, ele cadastra o usuário no database e depois retorna o token.