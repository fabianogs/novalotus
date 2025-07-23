# Changelog da API - NovaLotus

## Versão 2.0.0 - 2025-01-XX

### Mudanças Implementadas

#### 1. URLs Absolutas para Imagens
- **Trait ApiResponse**: Criado trait para padronizar respostas da API
- **Conversão automática**: Todas as URLs de imagens são convertidas para URLs absolutas
- **Campos suportados**: `imagem`, `foto`, `logo`, `logo_carrossel`
- **Comportamento**: URLs relativas são convertidas para URLs completas usando `URL::to()`
- **Caminhos específicos**: Cada tipo de imagem tem seu diretório específico

#### 2. Filtro de Status Ativo
- **Banner**: Sempre retorna apenas banners com `ativo = true`
- **SEO**: Sempre retorna apenas registros com `status = true`
- **Outros modelos**: Não possuem campo de status ativo, retornam todos os registros
- **Campos removidos**: Campos `ativo` e `status` são removidos das respostas da API

#### 3. Padronização de Respostas
- **Estrutura consistente**: Todas as respostas seguem o mesmo padrão
- **Métodos padronizados**: `successResponse()` e `errorResponse()`
- **Tratamento de erros**: Melhor tratamento de exceções

### Controllers Atualizados

#### BannerApiController
- ✅ Usa trait ApiResponse
- ✅ Sempre filtra apenas banners ativos
- ✅ URLs de imagens convertidas para absolutas
- ✅ Campo `ativo` removido da resposta

#### ParceiroApiController
- ✅ Usa trait ApiResponse
- ✅ URLs de logos convertidas para absolutas
- ✅ Não possui campo de status ativo

#### EspecialistaApiController
- ✅ Usa trait ApiResponse
- ✅ URLs de fotos convertidas para absolutas
- ✅ Não possui campo de status ativo

#### PlanoApiController
- ✅ Usa trait ApiResponse
- ✅ URLs de imagens convertidas para absolutas
- ✅ Não possui campo de status ativo

#### CidadeApiController
- ✅ Usa trait ApiResponse
- ✅ Não possui imagens
- ✅ Não possui campo de status ativo

#### EspecialidadeApiController
- ✅ Usa trait ApiResponse
- ✅ Não possui imagens
- ✅ Não possui campo de status ativo

#### NecessidadeApiController
- ✅ Usa trait ApiResponse
- ✅ Não possui imagens
- ✅ Não possui campo de status ativo

#### SobreController
- ✅ Usa trait ApiResponse
- ✅ Não possui imagens
- ✅ Não possui campo de status ativo

### Estrutura de Resposta Padronizada

#### Sucesso
```json
{
    "success": true,
    "data": [...],
    "count": 10,
    "message": "Registros listados com sucesso"
}
```

#### Erro
```json
{
    "success": false,
    "message": "Erro interno do servidor",
    "error": "Detalhes do erro (apenas em modo debug)"
}
```

### URLs de Imagens

#### Antes
```json
{
    "imagem": "12-1360x580_68783d0eb0e8a.jpg"
}
```

#### Depois
```json
{
    "imagem": "https://novalotuser.feelinghost.com.br/area_restrita/public/storage/img/banners/12-1360x580_68783d0eb0e8a.jpg"
}
```

### Mapeamento de Caminhos de Imagens

| Campo | Diretório |
|-------|-----------|
| `imagem` (Banner) | `storage/img/banners/` |
| `foto` (Especialista) | `storage/img/especialistas/` |
| `logo` (Parceiro) | `storage/img/parceiros/` |
| `logo_carrossel` (Parceiro) | `storage/img/parceiros/` |
| `imagem` (Plano) | `storage/img/planos/` |

### Campos Removidos

#### Antes (Banner)
```json
{
    "id": 1,
    "imagem": "https://novalotuser.feelinghost.com.br/area_restrita/public/storage/img/banners/banner1.jpg",
    "titulo": "Banner Principal",
    "link": "https://example.com",
    "ativo": true,
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z"
}
```

#### Depois (Banner)
```json
{
    "id": 1,
    "imagem": "https://novalotuser.feelinghost.com.br/area_restrita/public/storage/img/banners/banner1.jpg",
    "titulo": "Banner Principal",
    "link": "https://example.com",
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z"
}
```

### Compatibilidade

- ✅ Todas as rotas existentes mantidas
- ✅ Parâmetros de query string mantidos
- ✅ Estrutura de dados mantida
- ✅ URLs de imagens convertidas para absolutas com caminhos corretos
- ✅ Apenas registros ativos retornados (quando aplicável)
- ✅ Campos de status removidos das respostas

### Observações

1. **Banners**: Agora sempre retornam apenas banners ativos e o campo `ativo` é removido da resposta
2. **SEO**: Agora sempre retornam apenas registros ativos e o campo `status` é removido da resposta
3. **Imagens**: Todas as URLs são convertidas para absolutas automaticamente com caminhos específicos
4. **Performance**: Conversão de URLs e remoção de campos é feita apenas na resposta final
5. **Debug**: Detalhes de erro são incluídos apenas quando `APP_DEBUG=true`
6. **Campos removidos**: `ativo` e `status` são automaticamente removidos de todas as respostas
7. **Caminhos específicos**: Cada tipo de imagem tem seu diretório específico mapeado 