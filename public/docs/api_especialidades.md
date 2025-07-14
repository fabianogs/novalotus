# API de Especialidades - Documentação

Esta documentação descreve os endpoints REST API criados para o modelo Especialidade.

## Base URL
```
{APP_URL}/api/especialidades
```

## Endpoints Disponíveis

### 1. Listar Todas as Especialidades
**GET** `/api/especialidades`

Lista todas as especialidades cadastradas no sistema.

#### Parâmetros Opcionais (Query String)
- `search` (string): Busca por nome da especialidade (busca parcial)
- `limit` (integer): Limita a quantidade de resultados (máximo: 200)
- `with_count` (boolean): Inclui contagem de especialistas por especialidade

#### Exemplos de Uso
```bash
# Listar todas as especialidades
GET /api/especialidades

# Buscar especialidades com "cardio" no nome
GET /api/especialidades?search=cardio

# Listar as 10 primeiras especialidades
GET /api/especialidades?limit=10

# Listar especialidades com contagem de especialistas
GET /api/especialidades?with_count=true

# Combinar filtros
GET /api/especialidades?search=psico&with_count=true&limit=5
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nome": "Cardiologia",
            "slug": "cardiologia",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z",
            "especialistas_count": 5
        },
        {
            "id": 2,
            "nome": "Psicologia",
            "slug": "psicologia",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z",
            "especialistas_count": 3
        }
    ],
    "count": 2,
    "message": "Especialidades listadas com sucesso"
}
```

### 2. Visualizar Especialidade Específica
**GET** `/api/especialidades/{id}`

Retorna os dados de uma especialidade específica com contagem de especialistas.

#### Parâmetros
- `id` (integer, obrigatório): ID da especialidade

#### Exemplos de Uso
```bash
# Visualizar especialidade com ID 1
GET /api/especialidades/1
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": {
        "id": 1,
        "nome": "Cardiologia",
        "slug": "cardiologia",
        "created_at": "2025-01-09T12:00:00.000000Z",
        "updated_at": "2025-01-09T12:00:00.000000Z",
        "especialistas_count": 5
    },
    "message": "Especialidade encontrada com sucesso"
}
```

### 3. Listar Especialistas de uma Especialidade
**GET** `/api/especialidades/{id}/especialistas`

Lista todos os especialistas de uma especialidade específica.

#### Parâmetros
- `id` (integer, obrigatório): ID da especialidade

#### Parâmetros Opcionais (Query String)
- `cidade_id` (integer): Filtra especialistas por cidade
- `necessidade_id` (integer): Filtra especialistas por necessidade
- `limit` (integer): Limita a quantidade de resultados (máximo: 100)

#### Exemplos de Uso
```bash
# Listar todos os especialistas de cardiologia (ID 1)
GET /api/especialidades/1/especialistas

# Especialistas de cardiologia em São Paulo (cidade ID 1)
GET /api/especialidades/1/especialistas?cidade_id=1

# Especialistas de cardiologia por necessidade específica
GET /api/especialidades/1/especialistas?necessidade_id=2

# Primeiros 10 especialistas de cardiologia
GET /api/especialidades/1/especialistas?limit=10

# Combinar filtros
GET /api/especialidades/1/especialistas?cidade_id=1&necessidade_id=2&limit=5
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "especialidade": {
        "id": 1,
        "nome": "Cardiologia",
        "slug": "cardiologia"
    },
    "data": [
        {
            "id": 1,
            "foto": "especialista1.jpg",
            "nome": "Dr. João Silva",
            "conselho": "CRM 12345/SP",
            "especialidade_id": 1,
            "cidade_id": 1,
            "endereco": "Rua das Flores, 123",
            "necessidade_id": 2,
            "slug": "dr-joao-silva",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z",
            "cidade": {
                "id": 1,
                "nome": "São Paulo",
                "slug": "sao-paulo",
                "uf": "SP",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z"
            },
            "necessidade": {
                "id": 2,
                "nome": "Consulta de Rotina",
                "slug": "consulta-rotina",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z"
            }
        }
    ],
    "count": 1,
    "message": "Especialistas da especialidade listados com sucesso"
}
```

#### Resposta de Erro - Especialidade Não Encontrada (404)
```json
{
    "success": false,
    "message": "Especialidade não encontrada"
}
```

## Códigos de Status HTTP

- **200 OK**: Sucesso
- **404 Not Found**: Especialidade não encontrada
- **500 Internal Server Error**: Erro interno do servidor

## Estrutura dos Dados

### Especialidade
| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | integer | ID único da especialidade |
| `nome` | string | Nome da especialidade |
| `slug` | string | Slug único da especialidade (para URLs amigáveis) |
| `created_at` | datetime | Data de criação |
| `updated_at` | datetime | Data da última atualização |
| `especialistas_count` | integer | Contagem de especialistas (quando solicitado) |

### Especialista (retornado em `/especialistas`)
| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | integer | ID único do especialista |
| `foto` | string\|null | Nome do arquivo da foto |
| `nome` | string | Nome do especialista |
| `conselho` | string | Número do conselho profissional |
| `especialidade_id` | integer | ID da especialidade |
| `cidade_id` | integer | ID da cidade |
| `endereco` | string | Endereço do especialista |
| `necessidade_id` | integer | ID da necessidade atendida |
| `slug` | string | Slug único do especialista |
| `created_at` | datetime | Data de criação |
| `updated_at` | datetime | Data da última atualização |
| `cidade` | object | Dados da cidade (com eager loading) |
| `necessidade` | object | Dados da necessidade (com eager loading) |

## Observações

- Todos os endpoints retornam dados em formato JSON
- As especialidades são ordenadas alfabeticamente por nome
- Os especialistas são ordenados alfabeticamente por nome
- O endpoint de especialistas inclui eager loading para cidade e necessidade
- Os limites máximos são: 200 para especialidades, 100 para especialistas
- Em modo de debug, detalhes de erros internos são expostos
- Os filtros por `cidade_id` e `necessidade_id` são opcionais e podem ser combinados 