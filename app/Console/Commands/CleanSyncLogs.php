<?php

namespace App\Console\Commands;

use App\Models\SyncLog;
use Illuminate\Console\Command;

class CleanSyncLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:clean {--days=30 : Dias para manter os logs} {--dry-run : Apenas simular a limpeza}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpa logs antigos de sincronização';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $dryRun = $this->option('dry-run');

        $cutoffDate = now()->subDays($days);
        
        $query = SyncLog::where('started_at', '<', $cutoffDate);
        
        $count = $query->count();
        
        if ($count === 0) {
            $this->info("Nenhum log antigo encontrado (mais de {$days} dias).");
            return 0;
        }

        if ($dryRun) {
            $this->info("Simulação: {$count} logs seriam removidos (mais de {$days} dias).");
            $this->info("Data de corte: {$cutoffDate->format('d/m/Y H:i:s')}");
            
            $logs = $query->orderBy('started_at', 'desc')->limit(5)->get();
            
            if ($logs->isNotEmpty()) {
                $this->newLine();
                $this->info("Exemplos de logs que seriam removidos:");
                
                foreach ($logs as $log) {
                    $this->line("- ID {$log->id}: {$log->entity} ({$log->status}) - {$log->started_at->format('d/m/Y H:i:s')}");
                }
            }
            
            return 0;
        }

        if ($this->confirm("Tem certeza que deseja remover {$count} logs antigos (mais de {$days} dias)?")) {
            $deleted = $query->delete();
            
            $this->info("✅ {$deleted} logs removidos com sucesso!");
            $this->info("Data de corte: {$cutoffDate->format('d/m/Y H:i:s')}");
            
            return 0;
        }

        $this->info("Operação cancelada.");
        return 0;
    }
}
