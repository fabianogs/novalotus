# API de Banners - Documentação

Esta documentação descreve os endpoints REST API criados para o modelo Banner.

## Base URL
```
{APP_URL}/api/banners
```

## Endpoints Disponíveis

### 1. Listar Todos os Banners
**GET** `/api/banners`

Lista todos os banners cadastrados no sistema.

#### Parâmetros Opcionais (Query String)
- `ativo` (boolean): Filtra por status ativo (`true` ou `false`)
- `limit` (integer): Limita a quantidade de resultados (máximo: 100)

#### Exemplos de Uso
```bash
# Listar todos os banners
GET /api/banners

# Listar apenas banners ativos
GET /api/banners?ativo=true

# Listar apenas banners inativos
GET /api/banners?ativo=false

# Listar os 10 banners mais recentes
GET /api/banners?limit=10

# Combinar filtros
GET /api/banners?ativo=true&limit=5
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "imagem": "banner1.jpg",
            "titulo": "Banner Principal",
            "link": "https://exemplo.com",
            "ativo": true,
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z"
        }
    ],
    "count": 1,
    "message": "Banners listados com sucesso"
}
```

### 2. Listar Apenas Banners Ativos
**GET** `/api/banners/active`

Lista apenas os banners com status ativo (`ativo = true`).

#### Parâmetros Opcionais (Query String)
- `limit` (integer): Limita a quantidade de resultados (máximo: 100)

#### Exemplos de Uso
```bash
# Listar todos os banners ativos
GET /api/banners/active

# Listar os 5 banners ativos mais recentes
GET /api/banners/active?limit=5
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "imagem": "banner1.jpg",
            "titulo": "Banner Principal",
            "link": "https://exemplo.com",
            "ativo": true,
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z"
        }
    ],
    "count": 1,
    "message": "Banners ativos listados com sucesso"
}
```

### 3. Visualizar Banner Específico
**GET** `/api/banners/{id}`

Retorna os dados de um banner específico.

#### Parâmetros
- `id` (integer, obrigatório): ID do banner

#### Exemplos de Uso
```bash
# Visualizar banner com ID 1
GET /api/banners/1
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": {
        "id": 1,
        "imagem": "banner1.jpg",
        "titulo": "Banner Principal",
        "link": "https://exemplo.com",
        "ativo": true,
        "created_at": "2025-01-09T12:00:00.000000Z",
        "updated_at": "2025-01-09T12:00:00.000000Z"
    },
    "message": "Banner encontrado com sucesso"
}
```

#### Resposta de Erro - Banner Não Encontrado (404)
```json
{
    "success": false,
    "message": "Banner não encontrado"
}
```

## Códigos de Status HTTP

- **200 OK**: Sucesso
- **404 Not Found**: Banner não encontrado
- **500 Internal Server Error**: Erro interno do servidor

## Estrutura dos Dados

Cada banner contém os seguintes campos:

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | integer | ID único do banner |
| `imagem` | string\|null | Nome do arquivo de imagem |
| `titulo` | string | Título do banner |
| `link` | string\|null | URL de destino do banner |
| `ativo` | boolean | Status ativo/inativo |
| `created_at` | datetime | Data de criação |
| `updated_at` | datetime | Data da última atualização |

## Observações

- Todos os endpoints retornam dados em formato JSON
- Os banners são ordenados por data de criação (mais recentes primeiro)
- O limite máximo de resultados por requisição é 100
- Em modo de debug, detalhes de erros internos são expostos 