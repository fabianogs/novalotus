# API de Especialistas - Documentação

Esta documentação descreve os endpoints REST API criados para o modelo Especialista.

## Base URL
```
{APP_URL}/api/especialistas
```

## Endpoints Disponíveis

### 1. Listar Todos os Especialistas
**GET** `/api/especialistas`

Lista todos os especialistas cadastrados no sistema com todas as chaves estrangeiras expandidas.

#### Parâmetros Opcionais (Query String)
- `especialidade_id` (integer): Filtra por especialidade específica
- `cidade_id` (integer): Filtra por cidade específica
- `necessidade_id` (integer): Filtra por necessidade específica
- `search` (string): Busca por nome do especialista (busca parcial)
- `limit` (integer): Limita a quantidade de resultados (máximo: 100)

#### Exemplos de Uso
```bash
# Listar todos os especialistas
GET /api/especialistas

# Especialistas de cardiologia (especialidade ID 1)
GET /api/especialistas?especialidade_id=1

# Especialistas em São Paulo (cidade ID 1)
GET /api/especialistas?cidade_id=1

# Especialistas para consultas de rotina (necessidade ID 2)
GET /api/especialistas?necessidade_id=2

# Buscar especialistas com "João" no nome
GET /api/especialistas?search=João

# Primeiros 20 especialistas
GET /api/especialistas?limit=20

# Combinar filtros - cardiologistas em SP para consultas de rotina
GET /api/especialistas?especialidade_id=1&cidade_id=1&necessidade_id=2
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "foto": "dr-joao-silva.jpg",
            "nome": "Dr. João Silva",
            "conselho": "CRM 12345/SP",
            "especialidade_id": 1,
            "cidade_id": 1,
            "endereco": "Rua das Flores, 123 - Centro",
            "necessidade_id": 2,
            "slug": "dr-joao-silva",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z",
            "especialidade": {
                "id": 1,
                "nome": "Cardiologia",
                "slug": "cardiologia",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z"
            },
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
    "message": "Especialistas listados com sucesso"
}
```

### 2. Visualizar Especialista Específico
**GET** `/api/especialistas/{id}`

Retorna os dados de um especialista específico com todas as chaves estrangeiras expandidas.

#### Parâmetros
- `id` (integer, obrigatório): ID do especialista

#### Exemplos de Uso
```bash
# Visualizar especialista com ID 1
GET /api/especialistas/1
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": {
        "id": 1,
        "foto": "dr-joao-silva.jpg",
        "nome": "Dr. João Silva",
        "conselho": "CRM 12345/SP",
        "especialidade_id": 1,
        "cidade_id": 1,
        "endereco": "Rua das Flores, 123 - Centro",
        "necessidade_id": 2,
        "slug": "dr-joao-silva",
        "created_at": "2025-01-09T12:00:00.000000Z",
        "updated_at": "2025-01-09T12:00:00.000000Z",
        "especialidade": {
            "id": 1,
            "nome": "Cardiologia",
            "slug": "cardiologia",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z"
        },
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
    },
    "message": "Especialista encontrado com sucesso"
}
```

### 3. Especialistas Agrupados por Especialidade
**GET** `/api/especialistas/by-especialidade`

Lista todos os especialistas agrupados por especialidade.

#### Exemplos de Uso
```bash
# Listar especialistas agrupados por especialidade
GET /api/especialistas/by-especialidade
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": {
        "Cardiologia": [
            {
                "id": 1,
                "foto": "dr-joao-silva.jpg",
                "nome": "Dr. João Silva",
                "conselho": "CRM 12345/SP",
                "especialidade_id": 1,
                "cidade_id": 1,
                "endereco": "Rua das Flores, 123",
                "necessidade_id": 2,
                "slug": "dr-joao-silva",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z",
                "especialidade": {...},
                "cidade": {...},
                "necessidade": {...}
            }
        ],
        "Psicologia": [
            {
                "id": 2,
                "foto": "dra-maria-santos.jpg",
                "nome": "Dra. Maria Santos",
                "conselho": "CRP 54321/SP",
                "especialidade_id": 2,
                "cidade_id": 1,
                "endereco": "Av. Paulista, 456",
                "necessidade_id": 1,
                "slug": "dra-maria-santos",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z",
                "especialidade": {...},
                "cidade": {...},
                "necessidade": {...}
            }
        ]
    },
    "message": "Especialistas agrupados por especialidade listados com sucesso"
}
```

### 4. Especialistas Agrupados por Cidade
**GET** `/api/especialistas/by-cidade`

Lista todos os especialistas agrupados por cidade.

#### Exemplos de Uso
```bash
# Listar especialistas agrupados por cidade
GET /api/especialistas/by-cidade
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": {
        "São Paulo": [
            {
                "id": 1,
                "foto": "dr-joao-silva.jpg",
                "nome": "Dr. João Silva",
                "conselho": "CRM 12345/SP",
                "especialidade_id": 1,
                "cidade_id": 1,
                "endereco": "Rua das Flores, 123",
                "necessidade_id": 2,
                "slug": "dr-joao-silva",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z",
                "especialidade": {...},
                "cidade": {...},
                "necessidade": {...}
            }
        ],
        "Rio de Janeiro": [
            {
                "id": 3,
                "foto": "dr-carlos-lima.jpg",
                "nome": "Dr. Carlos Lima",
                "conselho": "CRM 67890/RJ",
                "especialidade_id": 1,
                "cidade_id": 2,
                "endereco": "Copacabana, 789",
                "necessidade_id": 2,
                "slug": "dr-carlos-lima",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z",
                "especialidade": {...},
                "cidade": {...},
                "necessidade": {...}
            }
        ]
    },
    "message": "Especialistas agrupados por cidade listados com sucesso"
}
```

### 5. Buscar Especialista por Slug
**GET** `/api/especialistas/slug/{slug}`

Busca um especialista específico pelo seu slug.

#### Parâmetros
- `slug` (string, obrigatório): Slug do especialista

#### Exemplos de Uso
```bash
# Buscar especialista pelo slug
GET /api/especialistas/slug/dr-joao-silva
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": {
        "id": 1,
        "foto": "dr-joao-silva.jpg",
        "nome": "Dr. João Silva",
        "conselho": "CRM 12345/SP",
        "especialidade_id": 1,
        "cidade_id": 1,
        "endereco": "Rua das Flores, 123 - Centro",
        "necessidade_id": 2,
        "slug": "dr-joao-silva",
        "created_at": "2025-01-09T12:00:00.000000Z",
        "updated_at": "2025-01-09T12:00:00.000000Z",
        "especialidade": {...},
        "cidade": {...},
        "necessidade": {...}
    },
    "message": "Especialista encontrado com sucesso"
}
```

#### Resposta de Erro - Especialista Não Encontrado (404)
```json
{
    "success": false,
    "message": "Especialista não encontrado"
}
```

## Códigos de Status HTTP

- **200 OK**: Sucesso
- **404 Not Found**: Especialista não encontrado
- **500 Internal Server Error**: Erro interno do servidor

## Estrutura dos Dados

### Especialista (dados principais)
| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | integer | ID único do especialista |
| `foto` | string\|null | Nome do arquivo da foto |
| `nome` | string | Nome completo do especialista |
| `conselho` | string\|null | Número do conselho profissional |
| `especialidade_id` | integer\|null | ID da especialidade (chave estrangeira) |
| `cidade_id` | integer\|null | ID da cidade (chave estrangeira) |
| `endereco` | string\|null | Endereço completo |
| `necessidade_id` | integer\|null | ID da necessidade (chave estrangeira) |
| `slug` | string | Slug único do especialista |
| `created_at` | datetime | Data de criação |
| `updated_at` | datetime | Data da última atualização |

### Relacionamentos (sempre incluídos)
| Relacionamento | Descrição |
|----------------|-----------|
| `especialidade` | Dados completos da especialidade (id, nome, slug, timestamps) |
| `cidade` | Dados completos da cidade (id, nome, slug, uf, timestamps) |
| `necessidade` | Dados completos da necessidade (id, nome, slug, timestamps) |

## Observações

- **Todos os endpoints incluem eager loading** das chaves estrangeiras (especialidade, cidade, necessidade)
- Todos os endpoints retornam dados em formato JSON
- Os especialistas são ordenados alfabeticamente por nome
- Os filtros podem ser combinados para busca refinada
- O limite máximo de resultados por requisição é 100
- Busca por slug é útil para URLs amigáveis
- Agrupamentos facilitam a organização da interface
- Em modo de debug, detalhes de erros internos são expostos 