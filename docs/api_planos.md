# API de Planos

Esta documentação descreve os endpoints disponíveis para gerenciar Planos no sistema.

## Base URL
```
/api/planos
```

## Estrutura do Plano

| Campo     | Tipo      | Descrição                                |
|-----------|-----------|------------------------------------------|
| id        | integer   | ID único do plano                        |
| titulo    | string    | Título do plano                          |
| descricao | text      | Descrição detalhada do plano (nullable) |
| sintese   | text      | Resumo/síntese do plano (nullable)      |
| imagem    | string    | URL da imagem do plano (nullable)       |
| link      | string    | Link externo do plano (nullable)        |
| slug      | string    | Slug único para URLs amigáveis          |
| created_at| datetime  | Data de criação                          |
| updated_at| datetime  | Data da última atualização              |

## Endpoints Disponíveis

### 1. Listar Planos
Retorna uma lista paginada de planos com filtros opcionais.

**Endpoint:** `GET /api/planos`

**Parâmetros Query:**
- `search` (string, opcional): Busca por título, descrição ou síntese
- `limit` (integer, opcional): Número de resultados por página (padrão: 10, máximo: 100)
- `page` (integer, opcional): Página atual (padrão: 1)

**Exemplo de Request:**
```bash
GET /api/planos?search=saude&limit=5&page=1
```

**Exemplo de Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "titulo": "Plano Básico de Saúde",
            "descricao": "Plano de saúde com cobertura básica para consultas e exames.",
            "sintese": "Cobertura básica de saúde",
            "imagem": "/images/plano-basico.jpg",
            "link": "https://example.com/plano-basico",
            "slug": "plano-basico-saude",
            "created_at": "2025-01-15T14:30:00.000000Z",
            "updated_at": "2025-01-15T14:30:00.000000Z"
        }
    ],
    "count": 1,
    "total": 15,
    "current_page": 1,
    "last_page": 3,
    "message": "Planos listados com sucesso"
}
```

### 2. Visualizar Plano Específico
Retorna os detalhes de um plano específico pelo ID.

**Endpoint:** `GET /api/planos/{id}`

**Parâmetros:**
- `id` (integer, obrigatório): ID do plano

**Exemplo de Request:**
```bash
GET /api/planos/1
```

**Exemplo de Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "titulo": "Plano Básico de Saúde",
        "descricao": "Plano de saúde com cobertura básica para consultas e exames.",
        "sintese": "Cobertura básica de saúde",
        "imagem": "/images/plano-basico.jpg",
        "link": "https://example.com/plano-basico",
        "slug": "plano-basico-saude",
        "created_at": "2025-01-15T14:30:00.000000Z",
        "updated_at": "2025-01-15T14:30:00.000000Z"
    },
    "message": "Plano encontrado com sucesso"
}
```

### 3. Buscar Plano por Slug
Retorna um plano específico utilizando seu slug único.

**Endpoint:** `GET /api/planos/slug/{slug}`

**Parâmetros:**
- `slug` (string, obrigatório): Slug do plano

**Exemplo de Request:**
```bash
GET /api/planos/slug/plano-basico-saude
```

**Exemplo de Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "titulo": "Plano Básico de Saúde",
        "descricao": "Plano de saúde com cobertura básica para consultas e exames.",
        "sintese": "Cobertura básica de saúde",
        "imagem": "/images/plano-basico.jpg",
        "link": "https://example.com/plano-basico",
        "slug": "plano-basico-saude",
        "created_at": "2025-01-15T14:30:00.000000Z",
        "updated_at": "2025-01-15T14:30:00.000000Z"
    },
    "message": "Plano encontrado com sucesso"
}
```

### 4. Lista Simples de Planos
Retorna uma lista simplificada de planos (apenas id, título, slug e imagem).

**Endpoint:** `GET /api/planos/simple`

**Exemplo de Request:**
```bash
GET /api/planos/simple
```

**Exemplo de Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "titulo": "Plano Básico de Saúde",
            "slug": "plano-basico-saude",
            "imagem": "/images/plano-basico.jpg"
        },
        {
            "id": 2,
            "titulo": "Plano Premium",
            "slug": "plano-premium",
            "imagem": "/images/plano-premium.jpg"
        }
    ],
    "count": 2,
    "message": "Lista simples de planos"
}
```

## Códigos de Status HTTP

| Código | Descrição                                    |
|--------|----------------------------------------------|
| 200    | Sucesso                                      |
| 404    | Plano não encontrado                         |
| 422    | Dados de entrada inválidos                   |
| 500    | Erro interno do servidor                     |

## Estrutura de Resposta de Erro

```json
{
    "success": false,
    "message": "Mensagem de erro",
    "error": "Detalhes técnicos do erro (em modo debug)"
}
```

## Exemplos de Uso

### Buscar planos de saúde
```bash
curl -X GET "https://seudominio.com/api/planos?search=saude&limit=10"
```

### Obter plano específico
```bash
curl -X GET "https://seudominio.com/api/planos/1"
```

### Buscar por slug
```bash
curl -X GET "https://seudominio.com/api/planos/slug/plano-basico-saude"
```

### Lista simplificada para seletores
```bash
curl -X GET "https://seudominio.com/api/planos/simple"
```

## Notas de Implementação

- Todos os endpoints retornam dados em formato JSON
- A paginação é automática para endpoints de listagem
- O campo `slug` é único e indexado para buscas rápidas
- Filtros de busca são aplicados com LIKE (case-insensitive)
- O limite máximo de resultados por página é 100
- Campos opcionais (nullable) podem retornar `null` na resposta 