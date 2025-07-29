<?php

namespace App\Console\Commands;

use App\Models\SyncLog;
use Illuminate\Console\Command;

class ShowSyncLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:logs {--entity= : Filtrar por entidade específica} {--status= : Filtrar por status} {--days=7 : Número de dias para buscar} {--limit=20 : Limite de registros}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exibe logs de sincronização';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $entity = $this->option('entity');
        $status = $this->option('status');
        $days = $this->option('days');
        $limit = $this->option('limit');

        $query = SyncLog::query();

        if ($entity) {
            $query->forEntity($entity);
        }

        if ($status) {
            $query->withStatus($status);
        }

        $query->recent($days)->orderBy('started_at', 'desc')->limit($limit);

        $logs = $query->get();

        if ($logs->isEmpty()) {
            $this->info('Nenhum log de sincronização encontrado.');
            return 0;
        }

        $this->info("Logs de Sincronização (últimos {$days} dias):");
        $this->newLine();

        $headers = ['ID', 'Entidade', 'Status', 'Início', 'Duração', 'Criados', 'Atualizados', 'Erros', 'Resumo'];
        $rows = [];

        foreach ($logs as $log) {
            $statusColor = $this->getStatusColor($log->status);
            $statusText = $this->getStatusText($log->status);

            $rows[] = [
                $log->id,
                ucfirst($log->entity),
                "<{$statusColor}>{$statusText}</{$statusColor}>",
                $log->started_at->format('d/m/Y H:i:s'),
                $log->duration_formatted,
                $log->created_items,
                $log->updated_items,
                $log->error_items,
                $log->summary ?: 'N/A'
            ];
        }

        $this->table($headers, $rows);

        // Estatísticas
        $this->newLine();
        $this->info('Estatísticas:');
        $this->line("Total de logs: {$logs->count()}");
        $this->line("Sucessos: " . $logs->where('status', 'success')->count());
        $this->line("Erros: " . $logs->where('status', 'error')->count());
        $this->line("Parciais: " . $logs->where('status', 'partial')->count());

        return 0;
    }

    /**
     * Obter cor do status
     */
    private function getStatusColor($status)
    {
        return match($status) {
            'success' => 'green',
            'error' => 'red',
            'partial' => 'yellow',
            'running' => 'blue',
            default => 'white'
        };
    }

    /**
     * Obter texto do status
     */
    private function getStatusText($status)
    {
        return match($status) {
            'success' => 'Sucesso',
            'error' => 'Erro',
            'partial' => 'Parcial',
            'running' => 'Executando',
            default => 'Desconhecido'
        };
    }
}
