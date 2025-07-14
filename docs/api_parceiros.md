# API de Parceiros - Documentação

Esta documentação descreve os endpoints REST API criados para o modelo Parceiro.

## Base URL
```
{APP_URL}/api/parceiros
```

## Endpoints Disponíveis

### 1. Listar Todos os Parceiros
**GET** `/api/parceiros`

Lista todos os parceiros cadastrados no sistema com todas as chaves estrangeiras expandidas.

#### Parâmetros Opcionais (Query String)
- `necessidade_id` (integer): Filtra por necessidade específica
- `cidade_id` (integer): Filtra por cidade específica
- `search` (string): Busca por nome do parceiro (busca parcial)
- `limit` (integer): Limita a quantidade de resultados (máximo: 100)

#### Exemplos de Uso
```bash
# Listar todos os parceiros
GET /api/parceiros

# Parceiros para consulta de rotina (necessidade ID 1)
GET /api/parceiros?necessidade_id=1

# Parceiros em São Paulo (cidade ID 1)
GET /api/parceiros?cidade_id=1

# Buscar parceiros com "laboratório" no nome
GET /api/parceiros?search=laboratório

# Primeiros 20 parceiros
GET /api/parceiros?limit=20

# Combinar filtros - labs para consultas em SP
GET /api/parceiros?necessidade_id=1&cidade_id=1&search=lab
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "logo": "laboratorio-abc.jpg",
            "logo_carrossel": "laboratorio-abc-carrossel.jpg",
            "nome": "Laboratório ABC",
            "descricao": "Exames laboratoriais completos com tecnologia de ponta",
            "cidade_id": 1,
            "endereco": "Av. Principal, 456 - Centro",
            "necessidade_id": 1,
            "slug": "laboratorio-abc",
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
                "id": 1,
                "titulo": "Consulta de Rotina",
                "slug": "consulta-rotina",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z"
            }
        }
    ],
    "count": 1,
    "message": "Parceiros listados com sucesso"
}
```

### 2. Visualizar Parceiro Específico
**GET** `/api/parceiros/{id}`

Retorna os dados de um parceiro específico com todas as chaves estrangeiras expandidas.

#### Parâmetros
- `id` (integer, obrigatório): ID do parceiro

#### Exemplos de Uso
```bash
# Visualizar parceiro com ID 1
GET /api/parceiros/1
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": {
        "id": 1,
        "logo": "laboratorio-abc.jpg",
        "logo_carrossel": "laboratorio-abc-carrossel.jpg",
        "nome": "Laboratório ABC",
        "descricao": "Exames laboratoriais completos com tecnologia de ponta",
        "cidade_id": 1,
        "endereco": "Av. Principal, 456 - Centro",
        "necessidade_id": 1,
        "slug": "laboratorio-abc",
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
            "id": 1,
            "titulo": "Consulta de Rotina",
            "slug": "consulta-rotina",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z"
        }
    },
    "message": "Parceiro encontrado com sucesso"
}
```

### 3. Parceiros Agrupados por Necessidade
**GET** `/api/parceiros/by-necessidade`

Lista todos os parceiros agrupados por necessidade.

#### Exemplos de Uso
```bash
# Listar parceiros agrupados por necessidade
GET /api/parceiros/by-necessidade
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": {
        "Consulta de Rotina": [
            {
                "id": 1,
                "logo": "laboratorio-abc.jpg",
                "logo_carrossel": "laboratorio-abc-carrossel.jpg",
                "nome": "Laboratório ABC",
                "descricao": "Exames laboratoriais completos",
                "cidade_id": 1,
                "endereco": "Av. Principal, 456",
                "necessidade_id": 1,
                "slug": "laboratorio-abc",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z",
                "cidade": {...},
                "necessidade": {...}
            }
        ],
        "Emergência Médica": [
            {
                "id": 2,
                "logo": "hospital-xyz.jpg",
                "logo_carrossel": "hospital-xyz-carrossel.jpg",
                "nome": "Hospital XYZ",
                "descricao": "Atendimento 24h emergencial",
                "cidade_id": 1,
                "endereco": "Rua da Saúde, 789",
                "necessidade_id": 2,
                "slug": "hospital-xyz",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z",
                "cidade": {...},
                "necessidade": {...}
            }
        ]
    },
    "message": "Parceiros agrupados por necessidade listados com sucesso"
}
```

### 4. Parceiros Agrupados por Cidade
**GET** `/api/parceiros/by-cidade`

Lista todos os parceiros agrupados por cidade.

#### Exemplos de Uso
```bash
# Listar parceiros agrupados por cidade
GET /api/parceiros/by-cidade
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": {
        "São Paulo": [
            {
                "id": 1,
                "logo": "laboratorio-abc.jpg",
                "logo_carrossel": "laboratorio-abc-carrossel.jpg",
                "nome": "Laboratório ABC",
                "descricao": "Exames laboratoriais completos",
                "cidade_id": 1,
                "endereco": "Av. Principal, 456",
                "necessidade_id": 1,
                "slug": "laboratorio-abc",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z",
                "cidade": {...},
                "necessidade": {...}
            }
        ],
        "Rio de Janeiro": [
            {
                "id": 3,
                "logo": "clinica-rj.jpg",
                "logo_carrossel": "clinica-rj-carrossel.jpg",
                "nome": "Clínica Rio",
                "descricao": "Especialidades médicas diversas",
                "cidade_id": 2,
                "endereco": "Copacabana, 321",
                "necessidade_id": 1,
                "slug": "clinica-rio",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z",
                "cidade": {...},
                "necessidade": {...}
            }
        ]
    },
    "message": "Parceiros agrupados por cidade listados com sucesso"
}
```

### 5. Parceiros Agrupados por Estado
**GET** `/api/parceiros/by-estado`

Lista parceiros agrupados por estado (UF).

#### Parâmetros Opcionais (Query String)
- `uf` (string): Filtra por estado específico (2 caracteres)
- `limit` (integer): Limita a quantidade de resultados (máximo: 100)

#### Exemplos de Uso
```bash
# Todos os parceiros agrupados por estado
GET /api/parceiros/by-estado

# Apenas parceiros de São Paulo
GET /api/parceiros/by-estado?uf=SP

# Primeiros 50 parceiros por estado
GET /api/parceiros/by-estado?limit=50
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": {
        "SP": [
            {
                "id": 1,
                "logo": "laboratorio-abc.jpg",
                "logo_carrossel": "laboratorio-abc-carrossel.jpg",
                "nome": "Laboratório ABC",
                "descricao": "Exames laboratoriais completos",
                "cidade_id": 1,
                "endereco": "Av. Principal, 456",
                "necessidade_id": 1,
                "slug": "laboratorio-abc",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z",
                "cidade": {...},
                "necessidade": {...}
            }
        ],
        "RJ": [
            {
                "id": 3,
                "logo": "clinica-rj.jpg",
                "logo_carrossel": "clinica-rj-carrossel.jpg",
                "nome": "Clínica Rio",
                "descricao": "Especialidades médicas diversas",
                "cidade_id": 2,
                "endereco": "Copacabana, 321",
                "necessidade_id": 1,
                "slug": "clinica-rio",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z",
                "cidade": {...},
                "necessidade": {...}
            }
        ]
    },
    "message": "Parceiros agrupados por estado listados com sucesso"
}
```

### 6. Parceiros para Carrossel
**GET** `/api/parceiros/carrossel`

Lista apenas parceiros que possuem logo para carrossel (campo `logo_carrossel` preenchido).

#### Parâmetros Opcionais (Query String)
- `limit` (integer): Limita a quantidade de resultados (máximo: 50)

#### Exemplos de Uso
```bash
# Todos os parceiros com logo de carrossel
GET /api/parceiros/carrossel

# Primeiros 10 parceiros para carrossel
GET /api/parceiros/carrossel?limit=10
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "logo": "laboratorio-abc.jpg",
            "logo_carrossel": "laboratorio-abc-carrossel.jpg",
            "nome": "Laboratório ABC",
            "descricao": "Exames laboratoriais completos",
            "cidade_id": 1,
            "endereco": "Av. Principal, 456",
            "necessidade_id": 1,
            "slug": "laboratorio-abc",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z",
            "cidade": {...},
            "necessidade": {...}
        }
    ],
    "count": 1,
    "message": "Parceiros para carrossel listados com sucesso"
}
```

### 7. Buscar Parceiro por Slug
**GET** `/api/parceiros/slug/{slug}`

Busca um parceiro específico pelo seu slug.

#### Parâmetros
- `slug` (string, obrigatório): Slug do parceiro

#### Exemplos de Uso
```bash
# Buscar parceiro pelo slug
GET /api/parceiros/slug/laboratorio-abc
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": {
        "id": 1,
        "logo": "laboratorio-abc.jpg",
        "logo_carrossel": "laboratorio-abc-carrossel.jpg",
        "nome": "Laboratório ABC",
        "descricao": "Exames laboratoriais completos com tecnologia de ponta",
        "cidade_id": 1,
        "endereco": "Av. Principal, 456 - Centro",
        "necessidade_id": 1,
        "slug": "laboratorio-abc",
        "created_at": "2025-01-09T12:00:00.000000Z",
        "updated_at": "2025-01-09T12:00:00.000000Z",
        "cidade": {...},
        "necessidade": {...}
    },
    "message": "Parceiro encontrado com sucesso"
}
```

#### Resposta de Erro - Parceiro Não Encontrado (404)
```json
{
    "success": false,
    "message": "Parceiro não encontrado"
}
```

## Códigos de Status HTTP

- **200 OK**: Sucesso
- **404 Not Found**: Parceiro não encontrado
- **500 Internal Server Error**: Erro interno do servidor

## Estrutura dos Dados

### Parceiro (dados principais)
| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | integer | ID único do parceiro |
| `logo` | string\|null | Nome do arquivo do logo principal |
| `logo_carrossel` | string\|null | Nome do arquivo do logo para carrossel |
| `nome` | string | Nome do parceiro |
| `descricao` | text\|null | Descrição detalhada do parceiro |
| `cidade_id` | integer\|null | ID da cidade (chave estrangeira) |
| `endereco` | string\|null | Endereço completo |
| `necessidade_id` | integer\|null | ID da necessidade (chave estrangeira) |
| `slug` | string | Slug único do parceiro |
| `created_at` | datetime | Data de criação |
| `updated_at` | datetime | Data da última atualização |

### Relacionamentos (sempre incluídos)
| Relacionamento | Descrição |
|----------------|-----------|
| `cidade` | Dados completos da cidade (id, nome, slug, uf, timestamps) |
| `necessidade` | Dados completos da necessidade (id, titulo, slug, timestamps) |

## Observações

- **Todos os endpoints incluem eager loading** das chaves estrangeiras (cidade, necessidade)
- Todos os endpoints retornam dados em formato JSON
- Os parceiros são ordenados alfabeticamente por nome
- Os filtros podem ser combinados para busca refinada
- O limite máximo de resultados por requisição é 100 (50 para carrossel)
- Busca por slug é útil para URLs amigáveis
- Agrupamentos facilitam a organização da interface
- Endpoint `/carrossel` é específico para parceiros com logo de carrossel
- Endpoint `/by-estado` permite filtrar por UF específico
- Em modo de debug, detalhes de erros internos são expostos 