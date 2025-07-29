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
- `search` (string): Busca por descrição da especialidade (busca parcial)
- `limit` (integer): Limita a quantidade de resultados (máximo: 100)

#### Exemplos de Uso
```bash
# Listar todas as especialidades
GET /api/especialidades

# Buscar especialidades com "cardio" na descrição
GET /api/especialidades?search=cardio

# Primeiras 20 especialidades
GET /api/especialidades?limit=20
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "descricao": "Cardiologia",
            "slug": "cardiologia",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z"
        },
        {
            "id": 2,
            "descricao": "Clínica Médica",
            "slug": "clinica-medica",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z"
        }
    ],
    "count": 2,
    "message": "Especialidades listadas com sucesso"
}
```

### 2. Visualizar Especialidade Específica
**GET** `/api/especialidades/{id}`

Retorna os dados de uma especialidade específica.

#### Parâmetros
- `id` (integer, obrigatório): ID da especialidade

#### Exemplo de Uso
```bash
GET /api/especialidades/1
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": {
        "id": 1,
        "descricao": "Cardiologia",
        "slug": "cardiologia",
        "created_at": "2025-01-09T12:00:00.000000Z",
        "updated_at": "2025-01-09T12:00:00.000000Z"
    },
    "message": "Especialidade encontrada com sucesso"
}
```

### 3. Buscar Especialidades
**GET** `/api/especialidades/buscar`

Endpoint para busca dinâmica de especialidades via AJAX.

#### Parâmetros Opcionais (Query String)
- `search` (string): Termo de busca (descrição da especialidade)

#### Exemplo de Uso
```bash
GET /api/especialidades/buscar?search=cardio
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "descricao": "Cardiologia"
        },
        {
            "id": 3,
            "descricao": "Cardiologia Pediátrica"
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
- **404 Not Found**: Especialidade não encontrada
- **422 Unprocessable Entity**: Dados de validação inválidos

### Erro do Servidor
- **500 Internal Server Error**: Erro interno do servidor

## Estrutura de Dados

### Especialidade
```json
{
    "id": "integer",
    "descricao": "string",
    "slug": "string",
    "created_at": "datetime",
    "updated_at": "datetime"
}
```

## Notas Importantes

### Campo Principal
- **`descricao`**: Campo principal da especialidade (anteriormente `nome`)
- **`id`**: ID único da API externa (não auto-incremento)
- **`slug`**: Gerado automaticamente a partir da descrição

### Sincronização
Os dados das especialidades são sincronizados automaticamente da API externa:
- **Endpoint**: `http://lotus-api.cloud.zielo.com.br/api/get_especialidades`
- **Frequência**: Diária às 02:00
- **Comando**: `php artisan especialidades:sync`

### Filtros Disponíveis
- **Por descrição**: `?search=cardio`
- **Limite de resultados**: `?limit=20`

## Exemplos de Uso Completo

### Buscar Especialidades com "Cardio"
```bash
GET /api/especialidades?search=cardio
```

### Listar Primeiras 10 Especialidades
```bash
GET /api/especialidades?limit=10
```

### Busca Dinâmica via AJAX
```bash
GET /api/especialidades/buscar?search=cardio
```

## Observações

- **Todos os endpoints retornam dados em formato JSON**
- **As especialidades são ordenadas alfabeticamente por descrição**
- **O limite máximo de resultados por requisição é 100**
- **A busca é case-insensitive e parcial**
- **Os dados são sincronizados da API externa, não criados manualmente**
- **O campo `id` corresponde ao ID da API externa**
- **O slug é gerado automaticamente a partir da descrição** 