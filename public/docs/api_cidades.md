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
- `search` (string): Busca por nome da cidade (busca parcial)
- `uf` (string): Filtra por UF específica
- `limit` (integer): Limita a quantidade de resultados (máximo: 100)

#### Exemplos de Uso
```bash
# Listar todas as cidades
GET /api/cidades

# Buscar cidades com "São" no nome
GET /api/cidades?search=São

# Cidades de São Paulo
GET /api/cidades?uf=SP

# Primeiras 20 cidades
GET /api/cidades?limit=20

# Combinar filtros - cidades de SP com "São" no nome
GET /api/cidades?uf=SP&search=São
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
            "nome_completo": "São Paulo - SP",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z"
        },
        {
            "id": 2,
            "nome": "Ribeirão Preto",
            "slug": "ribeirao-preto",
            "uf": "SP",
            "nome_completo": "Ribeirão Preto - SP",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z"
        }
    ],
    "count": 2,
    "message": "Cidades listadas com sucesso"
}
```

### 2. Visualizar Cidade Específica
**GET** `/api/cidades/{id}`

Retorna os dados de uma cidade específica.

#### Parâmetros
- `id` (integer, obrigatório): ID da cidade

#### Exemplo de Uso
```bash
GET /api/cidades/1
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": {
        "id": 1,
        "nome": "São Paulo",
        "slug": "sao-paulo",
        "uf": "SP",
        "nome_completo": "São Paulo - SP",
        "created_at": "2025-01-09T12:00:00.000000Z",
        "updated_at": "2025-01-09T12:00:00.000000Z"
    },
    "message": "Cidade encontrada com sucesso"
}
```

### 3. Buscar Cidades
**GET** `/api/cidades/buscar`

Endpoint para busca dinâmica de cidades via AJAX.

#### Parâmetros Opcionais (Query String)
- `search` (string): Termo de busca (nome da cidade)
- `uf` (string): Filtra por UF

#### Exemplo de Uso
```bash
GET /api/cidades/buscar?search=São&uf=SP
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nome": "São Paulo",
            "uf": "SP",
            "nome_completo": "São Paulo - SP"
        },
        {
            "id": 3,
            "nome": "São José dos Campos",
            "uf": "SP",
            "nome_completo": "São José dos Campos - SP"
        }
    ],
    "count": 2,
    "message": "Busca realizada com sucesso"
}
```

## Códigos de Resposta

### Sucesso
- **200 OK**: Requisição processada com sucesso
- **201 Created**: Recurso criado com sucesso

### Erro do Cliente
- **400 Bad Request**: Parâmetros inválidos ou malformados
- **404 Not Found**: Cidade não encontrada
- **422 Unprocessable Entity**: Dados de validação inválidos

### Erro do Servidor
- **500 Internal Server Error**: Erro interno do servidor

## Estrutura de Dados

### Cidade
```json
{
    "id": "integer",
    "nome": "string",
    "slug": "string",
    "uf": "string",
    "nome_completo": "string",
    "created_at": "datetime",
    "updated_at": "datetime"
}
```

## Notas Importantes

### Campos
- **`nome`**: Nome da cidade
- **`uf`**: Sigla do estado (2 caracteres)
- **`nome_completo`**: Nome completo da cidade com UF (ex: "São Paulo - SP")
- **`slug`**: Gerado automaticamente a partir do nome

### Sincronização
Os dados das cidades são sincronizados automaticamente da API externa:
- **Endpoint**: `http://lotus-api.cloud.zielo.com.br/api/get_cidades_prestadores`
- **Frequência**: Diária às 02:30
- **Comando**: `php artisan cidades:sync`

### Filtros Disponíveis
- **Por nome**: `?search=São`
- **Por UF**: `?uf=SP`
- **Limite de resultados**: `?limit=20`

## Exemplos de Uso Completo

### Buscar Cidades de São Paulo
```bash
GET /api/cidades?uf=SP
```

### Buscar Cidades com "São" no Nome
```bash
GET /api/cidades?search=São
```

### Listar Primeiras 10 Cidades
```bash
GET /api/cidades?limit=10
```

### Combinar Múltiplos Filtros
```bash
GET /api/cidades?uf=SP&search=São&limit=50
```

### Busca Dinâmica via AJAX
```bash
GET /api/cidades/buscar?search=São&uf=SP
```

## Observações

- **Todos os endpoints retornam dados em formato JSON**
- **As cidades são ordenadas alfabeticamente por nome**
- **O limite máximo de resultados por requisição é 100**
- **A busca é case-insensitive e parcial**
- **Os dados são sincronizados da API externa, não criados manualmente**
- **O campo `nome_completo` facilita a exibição na interface**
- **O slug é gerado automaticamente a partir do nome** 