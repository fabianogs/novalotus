# Changelog da API - NovaLotus

## Versão 2.0.0 - 2025-01-XX

### Mudanças Implementadas

#### 1. URLs Absolutas para Imagens
- **Trait ApiResponse**: Criado trait para padronizar respostas da API
- **Conversão automática**: Todas as URLs de imagens são convertidas para URLs absolutas
- **Campos suportados**: `imagem`, `foto`, `logo`, `logo_carrossel`
- **Comportamento**: URLs relativas são convertidas para URLs completas usando `URL::to()`

#### 2. Filtro de Status Ativo
- **Banner**: Sempre retorna apenas banners com `ativo = true`
- **SEO**: Sempre retorna apenas registros com `status = true`
- **Outros modelos**: Não possuem campo de status ativo, retornam todos os registros

#### 3. Padronização de Respostas
- **Estrutura consistente**: Todas as respostas seguem o mesmo padrão
- **Métodos padronizados**: `successResponse()` e `errorResponse()`
- **Tratamento de erros**: Melhor tratamento de exceções

### Controllers Atualizados

#### BannerApiController
- ✅ Usa trait ApiResponse
- ✅ Sempre filtra apenas banners ativos
- ✅ URLs de imagens convertidas para absolutas

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
    "imagem": "storage/banners/banner1.jpg"
}
```

#### Depois
```json
{
    "imagem": "http://localhost:8000/storage/banners/banner1.jpg"
}
```

### Compatibilidade

- ✅ Todas as rotas existentes mantidas
- ✅ Parâmetros de query string mantidos
- ✅ Estrutura de dados mantida
- ✅ Apenas URLs de imagens convertidas para absolutas
- ✅ Apenas registros ativos retornados (quando aplicável)

### Observações

1. **Banners**: Agora sempre retornam apenas banners ativos
2. **SEO**: Agora sempre retornam apenas registros ativos
3. **Imagens**: Todas as URLs são convertidas para absolutas automaticamente
4. **Performance**: Conversão de URLs é feita apenas na resposta final
5. **Debug**: Detalhes de erro são incluídos apenas quando `APP_DEBUG=true` 