# API de Cidades - Documentação

Esta documentação descreve os endpoints REST API criados para o modelo Cidade.

## Base URL
```
{APP_URL}/api/cidades
```

## Endpoints Disponíveis

### 1. Listar Todas as Cidades
**GET** `/api/cidades`

Lista todas as cidades cadastradas no sistema.

#### Parâmetros Opcionais (Query String)
- `uf` (string): Filtra por estado (2 caracteres - ex: SP, RJ, MG)
- `search` (string): Busca por nome da cidade (busca parcial)
- `limit` (integer): Limita a quantidade de resultados (máximo: 500)

#### Exemplos de Uso
```bash
# Listar todas as cidades
GET /api/cidades

# Listar cidades de São Paulo
GET /api/cidades?uf=SP

# Buscar cidades com "São" no nome
GET /api/cidades?search=São

# Listar as 50 primeiras cidades
GET /api/cidades?limit=50

# Combinar filtros - cidades do RJ com "Rio" no nome
GET /api/cidades?uf=RJ&search=Rio

# Listar as 10 primeiras cidades de MG
GET /api/cidades?uf=MG&limit=10
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nome": "São Paulo",
            "slug": "sao-paulo",
            "uf": "SP",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z"
        },
        {
            "id": 2,
            "nome": "Rio de Janeiro",
            "slug": "rio-de-janeiro",
            "uf": "RJ",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z"
        }
    ],
    "count": 2,
    "message": "Cidades listadas com sucesso"
}
```

### 2. Listar Cidades Agrupadas por UF
**GET** `/api/cidades/by-uf`

Lista todas as cidades agrupadas por estado (UF).

#### Exemplos de Uso
```bash
# Listar cidades agrupadas por estado
GET /api/cidades/by-uf
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": {
        "RJ": [
            {
                "id": 2,
                "nome": "Rio de Janeiro",
                "slug": "rio-de-janeiro",
                "uf": "RJ",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z"
            },
            {
                "id": 3,
                "nome": "Niterói",
                "slug": "niteroi",
                "uf": "RJ",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z"
            }
        ],
        "SP": [
            {
                "id": 1,
                "nome": "São Paulo",
                "slug": "sao-paulo",
                "uf": "SP",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z"
            }
        ]
    },
    "message": "Cidades agrupadas por UF listadas com sucesso"
}
```

### 3. Listar Estados Disponíveis
**GET** `/api/cidades/estados`

Lista apenas os estados (UFs) que possuem cidades cadastradas.

#### Exemplos de Uso
```bash
# Listar estados disponíveis
GET /api/cidades/estados
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": [
        "RJ",
        "SP",
        "MG"
    ],
    "count": 3,
    "message": "Estados listados com sucesso"
}
```

## Códigos de Status HTTP

- **200 OK**: Sucesso
- **500 Internal Server Error**: Erro interno do servidor

## Estrutura dos Dados

Cada cidade contém os seguintes campos:

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | integer | ID único da cidade |
| `nome` | string | Nome da cidade |
| `slug` | string | Slug único da cidade (para URLs amigáveis) |
| `uf` | string | Sigla do estado (2 caracteres) |
| `created_at` | datetime | Data de criação |
| `updated_at` | datetime | Data da última atualização |

## Observações

- Todos os endpoints retornam dados em formato JSON
- As cidades são ordenadas alfabeticamente por nome (exceto no agrupamento por UF)
- No agrupamento por UF, as cidades são ordenadas primeiro por estado, depois por nome
- O filtro por UF aceita apenas códigos de 2 caracteres (maiúsculas ou minúsculas)
- A busca por nome é case-insensitive e busca parcial
- O limite máximo de resultados por requisição é 500
- Em modo de debug, detalhes de erros internos são expostos 