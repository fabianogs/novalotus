# Dashboard Centralizado de Sincronizações - Documentação

Esta documentação descreve o dashboard centralizado para gerenciar todas as sincronizações da API externa.

## Base URL
```
{APP_URL}/sync-dashboard
```

## Visão Geral

O Dashboard Centralizado de Sincronizações é uma interface unificada que permite monitorar e gerenciar todas as sincronizações de dados da API externa em uma única tela.

## Funcionalidades

### 1. Monitoramento em Tempo Real
- **Estatísticas locais** de cada entidade
- **Status das APIs** externas
- **Logs recentes** de sincronização
- **Auto-atualização** a cada 30 segundos

### 2. Sincronização Manual
- **Botões individuais** para cada entidade
- **Progress bars animados** durante sincronização
- **Feedback visual** imediato
- **Tratamento de erros** com notificações

### 3. Entidades Gerenciadas

#### Especialidades
- **API**: `http://lotus-api.cloud.zielo.com.br/api/get_especialidades`
- **Comando**: `php artisan especialidades:sync`
- **Frequência**: Diária às 02:00
- **Campos**: `id`, `descricao`, `slug`

#### Cidades
- **API**: `http://lotus-api.cloud.zielo.com.br/api/get_cidades_prestadores`
- **Comando**: `php artisan cidades:sync`
- **Frequência**: Diária às 02:30
- **Campos**: `nome`, `uf`, `nome_completo`

#### Especialistas
- **API**: `http://lotus-api.cloud.zielo.com.br/api/get_credenciados`
- **Comando**: `php artisan especialistas:sync`
- **Frequência**: Diária às 03:00
- **Campos**: `nome`, `nome_fantasia`, `conselho`, `registro`, `registro_uf`, `enderecos`, `telefones`, `especialidades`

## Interface

### Layout
```
┌─────────────────────────────────────────────────────────────┐
│                    Dashboard de Sincronizações              │
├─────────────────┬─────────────────┬─────────────────────────┤
│   Especialidades │     Cidades     │     Especialistas      │
│                 │                 │                        │
│  Total: 59      │  Total: 150     │  Total: 330           │
│  Hoje: 5        │  Hoje: 12       │  Hoje: 25             │
│  Semana: 15     │  Semana: 45     │  Semana: 89           │
│                 │                 │                        │
│  API: Online    │  API: Online    │  API: Online          │
│  Total: 59      │  Total: 150     │  Total: 332           │
│  Páginas: 4     │  Páginas: 8     │  Páginas: 23          │
│                 │                 │                        │
│  [Sincronizar]  │  [Sincronizar]  │  [Sincronizar]        │
└─────────────────┴─────────────────┴─────────────────────────┘
│                                                                 │
│                    Logs Recentes de Sincronização              │
│                                                                 │
│  2025-01-28 15:30:25 | Sincronização de especialistas...      │
│  2025-01-28 15:25:10 | Sincronização de cidades concluída     │
│  2025-01-28 15:20:05 | Sincronização de especialidades...     │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### Cards de Entidade

Cada entidade possui um card com:

#### Estatísticas Locais
- **Total**: Quantidade de registros no banco local
- **Atualizados Hoje**: Registros modificados hoje
- **Atualizados na Semana**: Registros modificados na última semana
- **Última Atualização**: Timestamp da última modificação

#### Status da API
- **Online** 🟢: API funcionando normalmente
- **Erro** 🟡: API com erro de resposta
- **Offline** 🔴: API indisponível
- **Total na API**: Quantidade de itens disponíveis
- **Páginas**: Número de páginas para paginação

#### Botão de Sincronização
- **Estado normal**: "Sincronizar [Entidade]"
- **Durante sincronização**: "Sincronizando..." com spinner
- **Progress bar animado** durante operação
- **Feedback visual** com SweetAlert2

## Endpoints da API

### 1. Dashboard Principal
**GET** `/sync-dashboard`

Retorna a página principal do dashboard com todas as estatísticas.

### 2. Sincronização AJAX
**POST** `/sync-dashboard/sync`

Executa sincronização manual de uma entidade específica.

#### Parâmetros
```json
{
    "entity": "especialidades|cidades|especialistas"
}
```

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "message": "Sincronização de especialidades executada com sucesso!"
}
```

#### Resposta de Erro (500)
```json
{
    "success": false,
    "message": "Erro durante a sincronização: [detalhes do erro]"
}
```

### 3. Status AJAX
**GET** `/sync-dashboard/status`

Retorna status atualizado de todas as entidades.

#### Resposta de Sucesso (200)
```json
{
    "success": true,
    "stats": {
        "especialidades": {
            "total": 59,
            "ultima_atualizacao": "2025-01-28T15:30:25.000000Z",
            "atualizadas_hoje": 5,
            "atualizadas_semana": 15
        },
        "cidades": {
            "total": 150,
            "ultima_atualizacao": "2025-01-28T15:25:10.000000Z",
            "atualizadas_hoje": 12,
            "atualizadas_semana": 45
        },
        "especialistas": {
            "total": 330,
            "ultima_atualizacao": "2025-01-28T15:30:25.000000Z",
            "atualizados_hoje": 25,
            "atualizados_semana": 89
        }
    },
    "apiStatus": {
        "especialidades": {
            "status": "online",
            "total": 59,
            "pages": 4,
            "message": "API funcionando normalmente"
        },
        "cidades": {
            "status": "online",
            "total": 150,
            "pages": 8,
            "message": "API funcionando normalmente"
        },
        "especialistas": {
            "status": "online",
            "total": 332,
            "pages": 23,
            "message": "API funcionando normalmente"
        }
    }
}
```

## Funcionalidades JavaScript

### Auto-atualização
```javascript
// Atualiza status a cada 30 segundos
setInterval(refreshStatus, 30000);
```

### Sincronização com Progress Bar
```javascript
function syncEntity(entity) {
    // Desabilita botão
    // Mostra progress bar
    // Faz requisição AJAX
    // Atualiza progress bar
    // Mostra resultado
    // Reabilita botão
}
```

### Tratamento de Erros
- **Timeout**: 10 segundos para requisições HTTP
- **Retry**: Tentativas automáticas em caso de falha
- **Logs**: Registro detalhado de todas as operações
- **Notificações**: SweetAlert2 para feedback visual

## Logs de Sincronização

### Filtros Aplicados
- **Sincronização**: Logs com "sincronização"
- **Sync**: Logs com "sync"
- **Entidades**: Logs com "especialidades", "cidades", "especialistas"

### Formato dos Logs
```
[2025-01-28 15:30:25] local.INFO: Sincronização de especialistas iniciada
[2025-01-28 15:30:30] local.INFO: Processando página 1...
[2025-01-28 15:30:35] local.INFO: ✓ Criado: Dr. João Silva
[2025-01-28 15:30:40] local.INFO: Sincronização concluída! Total: 330
```

## Configuração de Produção

### Cron Jobs
```bash
# Adicionar ao crontab
0 2 * * * cd /path/to/project && php artisan especialidades:schedule-sync --silent
30 2 * * * cd /path/to/project && php artisan cidades:schedule-sync --silent
0 3 * * * cd /path/to/project && php artisan especialistas:schedule-sync --silent
```

### Permissões
```bash
# Garantir permissões de escrita para logs
chmod -R 775 storage/logs
chown -R www-data:www-data storage/logs
```

### Monitoramento
```bash
# Verificar status das sincronizações
php artisan especialidades:status
php artisan cidades:status
php artisan especialistas:status

# Verificar logs recentes
tail -f storage/logs/laravel.log | grep -E "(sincronização|sync)"
```

## Comandos Artisan Disponíveis

### Sincronização Manual
```bash
php artisan especialidades:sync
php artisan cidades:sync
php artisan especialistas:sync
```

### Sincronização Agendada
```bash
php artisan especialidades:schedule-sync --silent
php artisan cidades:schedule-sync --silent
php artisan especialistas:schedule-sync --silent
```

### Verificação de Status
```bash
php artisan especialidades:status --json
php artisan cidades:status --json
php artisan especialistas:status --json
```

## Observações Importantes

### Segurança
- **Autenticação**: Acesso restrito a usuários autenticados
- **Validação**: Parâmetros validados antes da execução
- **Logs**: Todas as operações são registradas
- **Timeout**: Proteção contra requisições longas

### Performance
- **Cache**: Status das APIs cacheado por 5 minutos
- **Eager Loading**: Relacionamentos carregados eficientemente
- **Pagination**: Processamento em lotes para grandes volumes
- **Background**: Sincronizações agendadas executam em background

### Monitoramento
- **Health Checks**: Verificação automática de status
- **Alertas**: Notificações em caso de falha
- **Métricas**: Estatísticas de sucesso/falha
- **Logs**: Histórico completo de operações

## Troubleshooting

### Problemas Comuns

#### API Offline
```bash
# Verificar conectividade
curl -I http://lotus-api.cloud.zielo.com.br/api/get_especialidades

# Verificar logs
tail -f storage/logs/laravel.log | grep "API indisponível"
```

#### Sincronização Falhando
```bash
# Verificar permissões
ls -la storage/logs/

# Verificar espaço em disco
df -h

# Verificar memória
free -h
```

#### Dados Desatualizados
```bash
# Forçar sincronização manual
php artisan especialidades:sync --force
php artisan cidades:sync --force
php artisan especialistas:sync --force
```

### Logs de Debug
```bash
# Ativar modo debug
APP_DEBUG=true

# Ver logs detalhados
tail -f storage/logs/laravel.log
```