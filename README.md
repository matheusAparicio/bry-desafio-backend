# BRY Desafio Backend

O projeto consiste em uma API RESTful para gerenciamento de usuários (clientes e funcionários) e empresas, incluindo funcionalidades de autenticação via JWT e upload de documentos.

**Desenvolvido com Laravel 12 e PHP 8.2**

## Sumário

Devido à natureza do desafio, o foco foi garantir uma estrutura sólida de banco de dados e tratamento de erros consistente. Optei por utilizar a arquitetura padrão MVC do Laravel, mas com uma diferenciação importante na camada de **Models**: utilizei o conceito de *Single Table Inheritance* (Herança de Tabela Única) para diferenciar `Employee` e `Customer` na mesma tabela de usuários, aplicando *Global Scopes* para facilitar as consultas.

A autenticação foi implementada via **JWT** (JSON Web Token) para garantir a segurança e a natureza *stateless* da API.

Pode-se acessar a API através do seguinte endereço: `https://bry-desafio-backend-production.up.railway.app`.

Credenciais de um usuário "Admin":

E-mail: `admin@admin.com`

Senha: `admin123`

As rotas são essas: [BRy - Rotas do Desafio Backend.postman_collection.json](https://github.com/user-attachments/files/23742576/BRy.-.Rotas.do.Desafio.Backend.postman_collection.json)

## Estrutura de Arquivos

A estrutura segue os padrões do Laravel, com destaque para as seguintes pastas personalizadas ou centrais para a lógica de negócio:

```
app
 ├── Exceptions     # Tratamento de erros personalizados da API
 ├── Http
 │   ├── Controllers # Lógica de entrada e saída da API (ACL manual inclusa)
 │   └── Middleware  # Interceptadores (Auth JWT)
 └── Models         # Representação do banco e regras de negócio (Herança de User)
database
 ├── migrations     # Definição do esquema do banco
 └── seeders        # População inicial do banco para testes
tests
 ├── Feature        # Testes de integração (Fluxos de API)
 └── Unit           # Testes unitários (Lógica de Models)
docker              # Configurações de ambiente (Apache/PHP)
```

### Models

Camada responsável pela abstração do banco de dados. Um ponto chave aqui é a classe `User`, que serve de base para `Employee` e `Customer`.
*   **User:** Classe pai. Define atributos comuns e criptografia de senha.
*   **Employee / Customer:** Estendem de `User`. No método `boot`, aplicam automaticamente o `type` correto na criação e filtram as buscas via *Global Scope*. Isso permite fazer `Employee::all()` e trazer apenas funcionários, mesmo estando tudo na tabela `users`.
*   **DocumentFile:** Gerencia o caminho e metadados dos arquivos enviados.

### Controllers

Responsáveis por receber as requisições, validar os dados e retornar as respostas JSON.
*   **AuthController:** Gerencia Login e Logout (invalidação de token).
*   **UserController (e filhos):** CRUD de usuários. Possui regras de ACL manuais (ex: Clientes só podem editar a si mesmos).
*   **FileController:** Lida com upload (armazenamento local/storage) e download de arquivos.
*   **CompanyController:** Gerencia empresas, restrito majoritariamente a funcionários.

### Exceptions

Criei classes de Exceção personalizadas (ex: `ForbiddenException`, `InvalidCredentialsException`) que estendem de uma `ApiException` base. Isso centraliza a renderização dos erros, garantindo que a API sempre retorne um JSON padronizado com o código de erro interno e o status HTTP correto.

## Observações e Comentários

*   **Registro de Usuários:** No arquivo `api.php`, deixei comentado a rota de registro público de clientes (`/customers`). Devido ao prazo, optei por remover a possibilidade de registro sem autenticação para focar na segurança das rotas protegidas, assumindo que a criação de usuários seria feita via *seeders* ou por um administrador logado.
*   **ACL (Controle de Acesso):** Em vez de criar *Policies* ou *Gates* complexos do Laravel (que adicionariam mais arquivos e complexidade para um escopo pequeno), implementei verificações de permissão diretamente nos *Controllers* (ex: `if ($auth->type !== 'employee') throw new ForbiddenException...`). Isso agilizou o desenvolvimento mantendo a segurança.
*   **Armazenamento:** O upload de arquivos está configurado para o driver `local` (pasta `storage/app`), mas a estrutura está pronta para S3 ou outros drivers suportados pelo Laravel.

## Instruções para execução do projeto

O projeto está totalmente dockerizado para facilitar a execução.

1.  **Pré-requisitos:** Ter Docker e Docker Compose instalados.
2.  **Configuração:** Renomeie o arquivo `.env.example` para `.env`.
3.  **Execução:** Na raiz do projeto, execute:

```bash
docker compose up -d --build
```

O *entrypoint* do container se encarregará de instalar as dependências do Composer e ajustar as permissões.

4.  **Banco de Dados e Seeds:**
    Após os containers subirem, execute as migrações e popule o banco:

```bash
docker compose exec app php artisan migrate --seed
```
*Isso criará usuários padrão como `admin` (senha: `admin`) e `non-admin` (senha: `nonadmin`).*

5.  **Acesso:** A API estará disponível em `http://localhost:8080`.

## Pacotes Externos

Utilizei pacotes essenciais para agilizar o desenvolvimento de funcionalidades robustas:

### php-open-source-saver/jwt-auth
Padrão de mercado para autenticação JWT em Laravel. Escolhido por sua facilidade de integração, permitindo criar rotas protegidas, gerar tokens com tempo de expiração e realizar *blacklist* de tokens no logout.

### laravel/framework (v12)
A versão mais recente do framework foi utilizada para aproveitar as melhorias de performance e sintaxe do PHP 8.2+.

### Tests (PHPUnit)
O projeto inclui testes automatizados (`tests/Feature` e `tests/Unit`) para garantir que:
1.  A herança de `Employee` retorne apenas funcionários.
2.  O Login funcione corretamente (e falhe com credenciais inválidas).
3.  A listagem de empresas só ocorra quando autenticado.

Para rodar os testes:
```bash
docker compose exec app php artisan test
```
