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
SITE_NAME='' #nome do sistema
API_URL='' #url da api 
PERMITTED_HOST='*' #host permitido para utilizar a API, use * para liberar para todos (não recomendado) ou o ip correto do front-end (ex: http://localhost:5173)

DB_HOST='' #host do banco de dados
DB_NAME='' #nome do banco de dados
DB_USER='' #usuario
DB_PASSWORD='' #senha

JWT_SECRET='{sua_chave}-jwt-secret' #secret do JWT

#credenciais para funcoes da NFe
CERTIFICATE='certificate.pfx' #arquivo do certificado digital na raiz do projeto
CERTIFICATE_PASSWORD='' #senha do certificado digital
AMBIENTE=2 #ambiente 1 = producao; 2 = homologacao
RAZAO_SOCIAL=''
NOME_FANTASIA=''
CNPJ=''
IE='' #IE RG da empresa (se tiver)
UF=''
CODIGO_UF=''
CODIGO='' #codigo IBGE da cidade
RUA=''
NUMERO=''
BAIRRO=''
CIDADE=''
TELEFONE=''
CEP=''

EMAIL='' #email do service de Emails
EMAIL_CODE='' #codigo da API do Google para permitir envio de emails

CONTACT_DOC='' #documento da empresa responsável técnica
CONTACT_NUMBER='' #telefone da empresa responsável técnica
CONTACT_EMAIL='' #email de contato da empresa responsável técnica
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