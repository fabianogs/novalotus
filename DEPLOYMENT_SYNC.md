# 🔄 Configuração de Sincronização em Produção

## 📋 Visão Geral

Este documento explica como configurar a sincronização automática de especialidades da API externa em ambiente de produção.

## 🚀 Opções de Implementação

### 1. **Agendamento Automático (Recomendado)**

#### Configuração do Cron
Adicione ao crontab do servidor:

```bash
# Acesse o crontab
crontab -e

# Adicione esta linha (ajuste o caminho para sua aplicação)
* * * * * cd /path/to/your/app && php artisan schedule:run >> /dev/null 2>&1
```

#### Verificação
```bash
# Teste o comando manualmente
php artisan especialidades:schedule-sync --silent

# Verifique os logs
tail -f storage/logs/laravel.log
```

### 2. **Sincronização Manual via Interface**

- Acesse `/especialidades` no painel admin
- Clique em "Sincronizar da API"
- Aguarde a conclusão

### 3. **Sincronização via Comando Manual**

```bash
# Sincronização com output detalhado
php artisan especialidades:sync

# Sincronização silenciosa (para cron)
php artisan especialidades:schedule-sync --silent
```

## ⚙️ Configurações de Produção

### Variáveis de Ambiente
```env
# Configurações de log
LOG_CHANNEL=daily
LOG_LEVEL=info

# Timeout da API (em segundos)
HTTP_TIMEOUT=30
```

### Configuração de Logs
```php
// config/logging.php
'daily' => [
    'driver' => 'daily',
    'path' => storage_path('logs/laravel.log'),
    'level' => env('LOG_LEVEL', 'debug'),
    'days' => 14,
],
```

## 🔍 Monitoramento

### Logs de Sincronização
```bash
# Ver logs de sincronização
grep "Sincronização" storage/logs/laravel.log

# Ver erros
grep "ERROR" storage/logs/laravel.log
```

### Verificação de Status
```bash
# Verificar quantas especialidades existem
php artisan tinker --execute="echo 'Total: ' . App\Models\Especialidade::count();"

# Verificar última sincronização
php artisan tinker --execute="echo 'Última atualização: ' . App\Models\Especialidade::max('updated_at');"
```

## 🛠️ Troubleshooting

### Problemas Comuns

1. **API indisponível**
   ```bash
   # Teste a conectividade
   curl -I http://lotus-api.cloud.zielo.com.br/api/get_especialidades
   ```

2. **Timeout da API**
   ```bash
   # Aumente o timeout no comando
   php artisan especialidades:schedule-sync --silent
   ```

3. **Permissões de arquivo**
   ```bash
   # Verifique permissões
   chmod -R 755 storage/
   chown -R www-data:www-data storage/
   ```

### Logs de Debug
```bash
# Ativar logs detalhados
php artisan especialidades:sync 2>&1 | tee sync.log
```

## 📊 Métricas de Sincronização

### Comandos Úteis
```bash
# Estatísticas de sincronização
php artisan tinker --execute="
    \$stats = [
        'total' => App\Models\Especialidade::count(),
        'hoje' => App\Models\Especialidade::whereDate('updated_at', today())->count(),
        'ultima_sync' => App\Models\Especialidade::max('updated_at')
    ];
    print_r(\$stats);
"
```

## 🔐 Segurança

### Recomendações
1. **Firewall**: Configure para permitir apenas acessos necessários
2. **Rate Limiting**: Implemente se necessário
3. **Logs**: Mantenha logs de acesso e erro
4. **Backup**: Faça backup antes de sincronizações

### Configuração de Segurança
```bash
# Restringir acesso ao comando
chmod 755 artisan
chown www-data:www-data artisan
```

## 📞 Suporte

### Contatos
- **Desenvolvedor**: [Seu contato]
- **API Externa**: [Contato da API]
- **Logs**: `storage/logs/laravel.log`

### Comandos de Emergência
```bash
# Parar sincronização
pkill -f "especialidades:schedule-sync"

# Forçar sincronização
php artisan especialidades:sync --force

# Rollback (se necessário)
php artisan migrate:rollback --step=1
```

---

**Última atualização**: $(date)
**Versão**: 1.0