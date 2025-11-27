# BRY Desafio Backend

O projeto consiste em uma API RESTful para gerenciamento de usuários (clientes e funcionários) e empresas, incluindo funcionalidades de autenticação via JWT e upload de documentos.

**Desenvolvido com Laravel 12 e PHP 8.2**

## Sumário

Devido à natureza do desafio, o foco foi garantir uma estrutura sólida de banco de dados e tratamento de erros consistente. Optei por utilizar a arquitetura padrão MVC do Laravel, mas com uma diferenciação importante na camada de **Models**: utilizei o conceito de *Single Table Inheritance* (Herança de Tabela Única) para diferenciar `Employee` e `Customer` na mesma tabela de usuários, aplicando *Global Scopes* para facilitar as consultas.

A autenticação foi implementada via **JWT** (JSON Web Token) para garantir a segurança e a natureza *stateless* da API.

## Acesso

Pode-se acessar a API através do seguinte endereço: `https://bry-desafio-backend-production.up.railway.app`.

Credenciais de um usuário "admin":
- E-mail: `admin@admin.com`
- Senha: `admin123`

As rotas são essas: [BRy - Rotas do Desafio Backend.postman_collection.json](https://github.com/user-attachments/files/23742576/BRy.-.Rotas.do.Desafio.Backend.postman_collection.json)

## Requisitos da Questão 1 Atendidos no Desafio

- ✔ CRUD completo de funcionários;

- ✔ CRUD completo de clientes;

- ✔ CRUD completo de empresas;

- ✔ Relacionamento Many-to-Many entre empresas e usuários;

- ✔ Exibição das empresas vinculadas no GET de funcionário;

- ✔ Exibição das empresas vinculadas no GET de cliente;

- ✔ Exibição dos funcionários e clientes vinculados no GET de empresa;

- ✔ Campos obrigatórios implementados (login, nome, cpf, e-mail, endereço, senha);

- ✔ Upload de documento (PDF/JPG) para clientes e funcionários;

- ✔ Documento vinculado ao usuário no retorno da API;

- ✔ Tratamento de erros adequado e padronizado;

- ✔ Projeto desenvolvido em Laravel 12 / PHP 8.2;

- ✔ Banco configurado e dockerizado;

- ✔ Postman exportado;

- ✔ Deploy publicado e funcional.

## Decisões de Projeto (não especificadas no PDF)

O enunciado não detalhava algumas regras de negócio ou abordagens técnicas.  
As decisões abaixo foram tomadas para garantir segurança, coerência e robustez:

### **1. API-First Approach**
Adotei API-first pois:
- É a abordagem que estou acostumado a lidar em meu emprego atual, então já possuo experiência adaptando front ao back;
- Facilita garantir segurança e boas práticas desde o começo;
- É mais fácil adaptar o front a uma API sólida do que adaptar uma API às limitações do front;
- Melhora a testabilidade e o fluxo de desenvolvimento.

---

### **2. Single Table Inheritance (STI)**
Não estava definido no PDF como modelar funcionários e clientes.  
Motivos para usar STI:

- Evita duplicação de tabelas e validações;
- Simplifica consultas e relacionamentos;
- Facilita aplicar *global scopes*;
- Mantém o design limpo e escalável.

---

### **3. Autenticação JWT**
O PDF não exigia autenticação, mas implementei JWT para:

- Proteger endpoints sensíveis;
- Permitir logout via blacklist;
- Garantir arquitetura stateless;
- Melhorar segurança geral do sistema.

---

### **4. ACL Simplificada**
O PDF não definia regras de permissão.
Decidi:

- Clientes **podem** criar empresas;
- Clientes **podem** listar outros usuários, já que o escopo do desafio não detalhava limites de visibilidade;
- Qualquer employee pode vincular usuários a empresas.

Justificativa:
> O desafio não especificava regras de permissão detalhadas, portanto optei por permitir operações amplas para clientes e employees, garantindo funcionalidade completa dentro do escopo proposto.

Idealmente, haveria não apenas um controle de tipo do usuário, mas de permissões e acessos.

---

### **5. Tratamento de Erros Moderno**
Implementei `ApiException` usando *promoted properties* do PHP 8:

- Reduz boilerplate;
- Melhora clareza;
- Segue padrões modernos recomendados pelo Laravel e pelo ecossistema PHP atual;
- Centraliza e padroniza respostas de erro.

## Algumas coisas que NÃO implementei por não estar no escopo e pelo tempo disponível

- Permissões granularizadas por empresa;

- Hierarquia de roles;

- Ownership na criação de empresas;

- Policies/Gates.

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
*   **CompanyController:** Gerencia empresas. Criação é aberta a qualquer usuário, enquanto atualização e exclusão são restritas a funcionários.

### Exceptions

Criei classes de Exceção personalizadas (ex: `ForbiddenException`, `InvalidCredentialsException`) que estendem de uma `ApiException` base. Isso centraliza a renderização dos erros, garantindo que a API sempre retorne um JSON padronizado com o código de erro interno e o status HTTP correto.

## Observações e Comentários

*   **Registro de Usuários:** No arquivo `api.php`, deixei comentado a rota de registro público de clientes (`/customers`). Devido ao prazo, optei por remover a possibilidade de registro sem autenticação para focar na segurança das rotas protegidas, assumindo que a criação de usuários seria feita via *seeders* ou por um administrador logado.
*   **ACL (Controle de Acesso):** Em vez de criar *Policies* ou *Gates* complexos do Laravel (que adicionariam mais arquivos e complexidade para um escopo pequeno), implementei verificações de permissão diretamente nos *Controllers* (ex: `if ($auth->type !== 'employee') throw new ForbiddenException...`). Isso agilizou o desenvolvimento mantendo a segurança.
*   **Armazenamento:** O upload de arquivos está configurado para o driver `local` (pasta `storage/app`), mas a estrutura está pronta para S3 ou outros drivers suportados pelo Laravel.

## Pacotes Externos

Utilizei pacotes essenciais para agilizar o desenvolvimento de funcionalidades robustas:

### php-open-source-saver/jwt-auth
Padrão de mercado para autenticação JWT em Laravel. Escolhido por sua facilidade de integração, permitindo criar rotas protegidas, gerar tokens com tempo de expiração e realizar *blacklist* de tokens no logout.

### Tests (PHPUnit)
O projeto inclui testes automatizados (`tests/Feature` e `tests/Unit`) para garantir que:
1.  A herança de `Employee` retorne apenas funcionários.
2.  O Login funcione corretamente (e falhe com credenciais inválidas).
3.  A listagem de empresas só ocorra quando autenticado.

Para rodar os testes:
```bash
docker compose exec app php artisan test
```

## Instruções para execução local do projeto

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
