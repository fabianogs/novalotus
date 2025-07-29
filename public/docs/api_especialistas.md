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
            "nome_fantasia": "Clínica Dr. João Silva",
            "conselho": "CRM",
            "registro": "12345",
            "registro_uf": "SP",
            "cidade_id": 1,
            "endereco": "Rua das Flores, 123 - Centro",
            "necessidade_id": 2,
            "slug": "dr-joao-silva",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z",
            "especialidades": [
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
            "cidade": {
                "id": 1,
                "nome": "São Paulo",
                "slug": "sao-paulo",
                "uf": "SP",
                "nome_completo": "São Paulo - SP",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z"
            },
            "necessidade": {
                "id": 2,
                "nome": "Consulta de Rotina",
                "slug": "consulta-rotina",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z"
            },
            "enderecos": [
                {
                    "id": 1,
                    "especialista_id": 1,
                    "uf": "SP",
                    "cidade_nome": "São Paulo",
                    "cep": "01234-567",
                    "bairro": "Centro",
                    "logradouro": "Rua das Flores",
                    "numero": "123",
                    "complemento": "Sala 45",
                    "created_at": "2025-01-09T12:00:00.000000Z",
                    "updated_at": "2025-01-09T12:00:00.000000Z"
                }
            ],
            "telefones": [
                {
                    "id": 1,
                    "especialista_id": 1,
                    "numero": "(11) 99999-9999",
                    "observacao": "WhatsApp",
                    "created_at": "2025-01-09T12:00:00.000000Z",
                    "updated_at": "2025-01-09T12:00:00.000000Z"
                },
                {
                    "id": 2,
                    "especialista_id": 1,
                    "numero": "(11) 88888-8888",
                    "observacao": "Consultório",
                    "created_at": "2025-01-09T12:00:00.000000Z",
                    "updated_at": "2025-01-09T12:00:00.000000Z"
                }
            ]
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

#### Exemplo de Uso
```bash
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
        "nome_fantasia": "Clínica Dr. João Silva",
        "conselho": "CRM",
        "registro": "12345",
        "registro_uf": "SP",
        "cidade_id": 1,
        "endereco": "Rua das Flores, 123 - Centro",
        "necessidade_id": 2,
        "slug": "dr-joao-silva",
        "created_at": "2025-01-09T12:00:00.000000Z",
        "updated_at": "2025-01-09T12:00:00.000000Z",
        "especialidades": [
            {
                "id": 1,
                "descricao": "Cardiologia",
                "slug": "cardiologia",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z"
            }
        ],
        "cidade": {
            "id": 1,
            "nome": "São Paulo",
            "slug": "sao-paulo",
            "uf": "SP",
            "nome_completo": "São Paulo - SP",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z"
        },
        "necessidade": {
            "id": 2,
            "nome": "Consulta de Rotina",
            "slug": "consulta-rotina",
            "created_at": "2025-01-09T12:00:00.000000Z",
            "updated_at": "2025-01-09T12:00:00.000000Z"
        },
        "enderecos": [
            {
                "id": 1,
                "especialista_id": 1,
                "uf": "SP",
                "cidade_nome": "São Paulo",
                "cep": "01234-567",
                "bairro": "Centro",
                "logradouro": "Rua das Flores",
                "numero": "123",
                "complemento": "Sala 45",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z"
            }
        ],
        "telefones": [
            {
                "id": 1,
                "especialista_id": 1,
                "numero": "(11) 99999-9999",
                "observacao": "WhatsApp",
                "created_at": "2025-01-09T12:00:00.000000Z",
                "updated_at": "2025-01-09T12:00:00.000000Z"
            }
        ]
    },
    "message": "Especialista encontrado com sucesso"
}
```

### 3. Buscar Especialistas por Especialidade
**GET** `/api/especialistas/by-especialidade/{especialidade_id}`

Retorna todos os especialistas de uma especialidade específica, agrupados por especialidade.

#### Parâmetros
- `especialidade_id` (integer, obrigatório): ID da especialidade

#### Exemplo de Uso
```bash
GET /api/especialistas/by-especialidade/1
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": {
        "especialidade": {
            "id": 1,
            "descricao": "Cardiologia",
            "slug": "cardiologia"
        },
        "especialistas": [
            {
                "id": 1,
                "nome": "Dr. João Silva",
                "nome_fantasia": "Clínica Dr. João Silva",
                "conselho": "CRM",
                "registro": "12345",
                "registro_uf": "SP",
                "cidade": {
                    "id": 1,
                    "nome": "São Paulo",
                    "uf": "SP"
                }
            }
        ]
    },
    "count": 1,
    "message": "Especialistas encontrados com sucesso"
}
```

### 4. Buscar Especialistas
**GET** `/api/especialistas/buscar`

Endpoint para busca dinâmica de especialistas via AJAX.

#### Parâmetros Opcionais (Query String)
- `search` (string): Termo de busca (nome do especialista)
- `especialidade_id` (integer): Filtra por especialidade
- `cidade_id` (integer): Filtra por cidade

#### Exemplo de Uso
```bash
GET /api/especialistas/buscar?search=João&especialidade_id=1
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nome": "Dr. João Silva",
            "nome_fantasia": "Clínica Dr. João Silva",
            "conselho": "CRM",
            "registro": "12345",
            "registro_uf": "SP",
            "especialidades": [
                {
                    "id": 1,
                    "descricao": "Cardiologia"
                }
            ],
            "cidade": {
                "id": 1,
                "nome": "São Paulo",
                "uf": "SP"
            }
        }
    ],
    "count": 1,
    "message": "Busca realizada com sucesso"
}
```

## Códigos de Resposta

### Sucesso
- **200 OK**: Requisição processada com sucesso
- **201 Created**: Recurso criado com sucesso

### Erro do Cliente
- **400 Bad Request**: Parâmetros inválidos ou malformados
- **404 Not Found**: Especialista não encontrado
- **422 Unprocessable Entity**: Dados de validação inválidos

### Erro do Servidor
- **500 Internal Server Error**: Erro interno do servidor

## Estrutura de Dados

### Especialista
```json
{
    "id": "integer",
    "foto": "string|null",
    "nome": "string",
    "nome_fantasia": "string|null",
    "conselho": "string|null",
    "registro": "string|null",
    "registro_uf": "string|null",
    "cidade_id": "integer|null",
    "endereco": "string|null",
    "necessidade_id": "integer|null",
    "slug": "string",
    "created_at": "datetime",
    "updated_at": "datetime",
    "especialidades": "array",
    "cidade": "object|null",
    "necessidade": "object|null",
    "enderecos": "array",
    "telefones": "array"
}
```

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

### Endereço
```json
{
    "id": "integer",
    "especialista_id": "integer",
    "uf": "string|null",
    "cidade_nome": "string|null",
    "cep": "string|null",
    "bairro": "string|null",
    "logradouro": "string|null",
    "numero": "string|null",
    "complemento": "string|null",
    "created_at": "datetime",
    "updated_at": "datetime"
}
```

### Telefone
```json
{
    "id": "integer",
    "especialista_id": "integer",
    "numero": "string",
    "observacao": "string|null",
    "created_at": "datetime",
    "updated_at": "datetime"
}
```

## Notas Importantes

### Relacionamentos
- **Especialidades**: Relacionamento many-to-many (um especialista pode ter várias especialidades)
- **Endereços**: Relacionamento one-to-many (um especialista pode ter vários endereços)
- **Telefones**: Relacionamento one-to-many (um especialista pode ter vários telefones)
- **Cidade**: Relacionamento one-to-many (um especialista pertence a uma cidade)
- **Necessidade**: Relacionamento one-to-many (um especialista atende uma necessidade)

### Campos Novos
- `nome_fantasia`: Nome fantasia do especialista/clínica
- `registro`: Número do registro profissional
- `registro_uf`: UF do registro profissional
- `enderecos`: Array de endereços do especialista
- `telefones`: Array de telefones do especialista

### Sincronização
Os dados dos especialistas são sincronizados automaticamente da API externa:
- **Endpoint**: `http://lotus-api.cloud.zielo.com.br/api/get_credenciados`
- **Frequência**: Diária às 03:00
- **Comando**: `php artisan especialistas:sync`

### Filtros Disponíveis
- **Por especialidade**: `?especialidade_id=1`
- **Por cidade**: `?cidade_id=1`
- **Por necessidade**: `?necessidade_id=2`
- **Por nome**: `?search=João`
- **Limite de resultados**: `?limit=20`

## Exemplos de Uso Completo

### Buscar Cardiologistas em São Paulo
```bash
GET /api/especialistas?especialidade_id=1&cidade_id=1
```

### Buscar Especialistas com "Silva" no Nome
```bash
GET /api/especialistas?search=Silva
```

### Listar Primeiros 10 Especialistas
```bash
GET /api/especialistas?limit=10
```

### Combinar Múltiplos Filtros
```bash
GET /api/especialistas?especialidade_id=1&cidade_id=1&necessidade_id=2&limit=50
``` 