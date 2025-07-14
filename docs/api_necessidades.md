# API de Necessidades - Documentação

Esta documentação descreve os endpoints REST API criados para o modelo Necessidade, incluindo seus relacionamentos com Especialistas e Parceiros.

## Base URL
```
{APP_URL}/api/necessidades
```

## Endpoints Disponíveis

### 1. Listar Todas as Necessidades
**GET** `/api/necessidades`

Lista todas as necessidades cadastradas no sistema.

#### Parâmetros Opcionais (Query String)
- `search` (string): Busca por título da necessidade (busca parcial)
- `limit` (integer): Limita a quantidade de resultados (máximo: 200)
- `with_count` (boolean): Inclui contagem de especialistas e parceiros

#### Exemplos de Uso
```bash
# Listar todas as necessidades
GET /api/necessidades

# Buscar necessidades com "consulta" no título
GET /api/necessidades?search=consulta

# Listar as 10 primeiras necessidades
GET /api/necessidades?limit=10

# Listar necessidades com contagem de profissionais
GET /api/necessidades?with_count=true

# Combinar filtros
GET /api/necessidades?search=emergencia&with_count=true&limit=5
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "titulo": "Consulta de Rotina",
            "slug": "consulta-rotina",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z",
            "especialistas_count": 15,
            "parceiros_count": 8
        },
        {
            "id": 2,
            "titulo": "Emergência Médica",
            "slug": "emergencia-medica",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z",
            "especialistas_count": 5,
            "parceiros_count": 3
        }
    ],
    "count": 2,
    "message": "Necessidades listadas com sucesso"
}
```

### 2. Visualizar Necessidade Específica
**GET** `/api/necessidades/{id}`

Retorna os dados de uma necessidade específica com contagem de especialistas e parceiros.

#### Parâmetros
- `id` (integer, obrigatório): ID da necessidade

#### Exemplos de Uso
```bash
# Visualizar necessidade com ID 1
GET /api/necessidades/1
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": {
        "id": 1,
        "titulo": "Consulta de Rotina",
        "slug": "consulta-rotina",
        "created_at": "2025-01-09T12:00:00.000000Z",
        "updated_at": "2025-01-09T12:00:00.000000Z",
        "especialistas_count": 15,
        "parceiros_count": 8
    },
    "message": "Necessidade encontrada com sucesso"
}
```

### 3. Listar Especialistas de uma Necessidade
**GET** `/api/necessidades/{id}/especialistas`

Lista todos os especialistas que atendem uma necessidade específica.

#### Parâmetros
- `id` (integer, obrigatório): ID da necessidade

#### Parâmetros Opcionais (Query String)
- `especialidade_id` (integer): Filtra por especialidade
- `cidade_id` (integer): Filtra por cidade
- `limit` (integer): Limita a quantidade de resultados (máximo: 100)

#### Exemplos de Uso
```bash
# Todos os especialistas para consulta de rotina (ID 1)
GET /api/necessidades/1/especialistas

# Cardiologistas para consulta de rotina
GET /api/necessidades/1/especialistas?especialidade_id=1

# Especialistas em São Paulo para consulta de rotina
GET /api/necessidades/1/especialistas?cidade_id=1

# Primeiros 10 especialistas
GET /api/necessidades/1/especialistas?limit=10

# Combinar filtros - cardiologistas em SP
GET /api/necessidades/1/especialistas?especialidade_id=1&cidade_id=1
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "necessidade": {
        "id": 1,
        "titulo": "Consulta de Rotina",
        "slug": "consulta-rotina"
    },
    "data": [
        {
            "id": 1,
            "foto": "dr-joao-silva.jpg",
            "nome": "Dr. João Silva",
            "conselho": "CRM 12345/SP",
            "especialidade_id": 1,
            "cidade_id": 1,
            "endereco": "Rua das Flores, 123",
            "necessidade_id": 1,
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
            }
        }
    ],
    "count": 1,
    "message": "Especialistas da necessidade listados com sucesso"
}
```

### 4. Listar Parceiros de uma Necessidade
**GET** `/api/necessidades/{id}/parceiros`

Lista todos os parceiros que atendem uma necessidade específica.

#### Parâmetros
- `id` (integer, obrigatório): ID da necessidade

#### Parâmetros Opcionais (Query String)
- `cidade_id` (integer): Filtra por cidade
- `limit` (integer): Limita a quantidade de resultados (máximo: 100)

#### Exemplos de Uso
```bash
# Todos os parceiros para consulta de rotina (ID 1)
GET /api/necessidades/1/parceiros

# Parceiros em São Paulo
GET /api/necessidades/1/parceiros?cidade_id=1

# Primeiros 10 parceiros
GET /api/necessidades/1/parceiros?limit=10
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "necessidade": {
        "id": 1,
        "titulo": "Consulta de Rotina",
        "slug": "consulta-rotina"
    },
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
            "cidade": {
                "id": 1,
                "nome": "São Paulo",
                "slug": "sao-paulo",
                "uf": "SP",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z"
            }
        }
    ],
    "count": 1,
    "message": "Parceiros da necessidade listados com sucesso"
}
```

### 5. Listar Todos os Profissionais de uma Necessidade
**GET** `/api/necessidades/{id}/profissionais`

Lista tanto especialistas quanto parceiros que atendem uma necessidade específica em uma única requisição.

#### Parâmetros
- `id` (integer, obrigatório): ID da necessidade

#### Parâmetros Opcionais (Query String)
- `cidade_id` (integer): Filtra por cidade (aplicado a ambos)
- `especialidade_id` (integer): Filtra especialistas por especialidade
- `limit_especialistas` (integer): Limita especialistas (máximo: 50)
- `limit_parceiros` (integer): Limita parceiros (máximo: 50)

#### Exemplos de Uso
```bash
# Todos os profissionais para consulta de rotina
GET /api/necessidades/1/profissionais

# Profissionais em São Paulo
GET /api/necessidades/1/profissionais?cidade_id=1

# Cardiologistas e todos os parceiros
GET /api/necessidades/1/profissionais?especialidade_id=1

# Limitar resultados
GET /api/necessidades/1/profissionais?limit_especialistas=5&limit_parceiros=3

# Combinar filtros
GET /api/necessidades/1/profissionais?cidade_id=1&especialidade_id=1&limit_especialistas=10
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "necessidade": {
        "id": 1,
        "titulo": "Consulta de Rotina",
        "slug": "consulta-rotina"
    },
    "data": {
        "especialistas": [
            {
                "id": 1,
                "foto": "dr-joao-silva.jpg",
                "nome": "Dr. João Silva",
                "conselho": "CRM 12345/SP",
                "especialidade_id": 1,
                "cidade_id": 1,
                "endereco": "Rua das Flores, 123",
                "necessidade_id": 1,
                "slug": "dr-joao-silva",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z",
                "especialidade": {...},
                "cidade": {...}
            }
        ],
        "parceiros": [
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
                "cidade": {...}
            }
        ]
    },
    "count": {
        "especialistas": 1,
        "parceiros": 1,
        "total": 2
    },
    "message": "Profissionais da necessidade listados com sucesso"
}
```

#### Resposta de Erro - Necessidade Não Encontrada (404)
```json
{
    "success": false,
    "message": "Necessidade não encontrada"
}
```

## Códigos de Status HTTP

- **200 OK**: Sucesso
- **404 Not Found**: Necessidade não encontrada
- **500 Internal Server Error**: Erro interno do servidor

## Estrutura dos Dados

### Necessidade
| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | integer | ID único da necessidade |
| `titulo` | string | Título da necessidade |
| `slug` | string | Slug único da necessidade (para URLs amigáveis) |
| `created_at` | datetime | Data de criação |
| `updated_at` | datetime | Data da última atualização |
| `especialistas_count` | integer | Contagem de especialistas (quando solicitado) |
| `parceiros_count` | integer | Contagem de parceiros (quando solicitado) |

### Especialista (nos endpoints relacionados)
| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | integer | ID único do especialista |
| `foto` | string\|null | Nome do arquivo da foto |
| `nome` | string | Nome completo do especialista |
| `conselho` | string\|null | Número do conselho profissional |
| `especialidade_id` | integer\|null | ID da especialidade |
| `cidade_id` | integer\|null | ID da cidade |
| `endereco` | string\|null | Endereço completo |
| `necessidade_id` | integer\|null | ID da necessidade |
| `slug` | string | Slug único do especialista |
| `created_at` | datetime | Data de criação |
| `updated_at` | datetime | Data da última atualização |
| `especialidade` | object | Dados da especialidade (com eager loading) |
| `cidade` | object | Dados da cidade (com eager loading) |

### Parceiro (nos endpoints relacionados)
| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | integer | ID único do parceiro |
| `logo` | string\|null | Nome do arquivo do logo |
| `logo_carrossel` | string\|null | Nome do arquivo do logo para carrossel |
| `nome` | string | Nome do parceiro |
| `descricao` | string\|null | Descrição do parceiro |
| `cidade_id` | integer\|null | ID da cidade |
| `endereco` | string\|null | Endereço completo |
| `necessidade_id` | integer\|null | ID da necessidade |
| `slug` | string | Slug único do parceiro |
| `created_at` | datetime | Data de criação |
| `updated_at` | datetime | Data da última atualização |
| `cidade` | object | Dados da cidade (com eager loading) |

## Observações

- **Relacionamentos implementados**: Necessidade → Especialistas e Necessidade → Parceiros
- **Eager loading automático**: Todos os endpoints incluem relacionamentos expandidos
- Todos os endpoints retornam dados em formato JSON
- As necessidades são ordenadas alfabeticamente por título
- Especialistas e parceiros são ordenados alfabeticamente por nome
- O endpoint `/profissionais` é útil quando você precisa de ambos os tipos em uma única requisição
- Os filtros podem ser combinados para busca refinada
- Limites máximos: 200 para necessidades, 100 para especialistas/parceiros individuais, 50 cada no endpoint combinado
- Em modo de debug, detalhes de erros internos são expostos 