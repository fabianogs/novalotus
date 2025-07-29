<?php

namespace App\Traits;

use App\Models\SyncLog;
use Illuminate\Support\Facades\Log;

trait SyncLoggable
{
    protected $syncLog = null;
    protected $syncStats = [
        'total_items' => 0,
        'created_items' => 0,
        'updated_items' => 0,
        'error_items' => 0,
        'details' => [],
    ];

    /**
     * Iniciar log de sincronização
     */
    protected function startSyncLog($entity)
    {
        $this->syncLog = SyncLog::create([
            'entity' => $entity,
            'status' => 'running',
            'started_at' => now(),
        ]);

        Log::info("Sincronização de {$entity} iniciada", [
            'sync_log_id' => $this->syncLog->id,
            'entity' => $entity,
        ]);

        return $this->syncLog;
    }

    /**
     * Finalizar log de sincronização com sucesso
     */
    protected function finishSyncLogSuccess($message = null)
    {
        if (!$this->syncLog) {
            return;
        }

        $this->syncLog->update([
            'status' => 'success',
            'total_items' => $this->syncStats['total_items'],
            'created_items' => $this->syncStats['created_items'],
            'updated_items' => $this->syncStats['updated_items'],
            'error_items' => $this->syncStats['error_items'],
            'details' => $this->syncStats['details'],
            'finished_at' => now(),
        ]);

        Log::info("Sincronização de {$this->syncLog->entity} concluída com sucesso", [
            'sync_log_id' => $this->syncLog->id,
            'stats' => $this->syncStats,
            'message' => $message,
        ]);
    }

    /**
     * Finalizar log de sincronização com erro
     */
    protected function finishSyncLogError($errorMessage, $exception = null)
    {
        if (!$this->syncLog) {
            return;
        }

        $this->syncLog->update([
            'status' => 'error',
            'total_items' => $this->syncStats['total_items'],
            'created_items' => $this->syncStats['created_items'],
            'updated_items' => $this->syncStats['updated_items'],
            'error_items' => $this->syncStats['error_items'],
            'error_message' => $errorMessage,
            'details' => $this->syncStats['details'],
            'finished_at' => now(),
        ]);

        Log::error("Erro na sincronização de {$this->syncLog->entity}", [
            'sync_log_id' => $this->syncLog->id,
            'error_message' => $errorMessage,
            'exception' => $exception ? $exception->getMessage() : null,
            'stats' => $this->syncStats,
        ]);
    }

    /**
     * Finalizar log de sincronização parcial
     */
    protected function finishSyncLogPartial($message = null)
    {
        if (!$this->syncLog) {
            return;
        }

        $this->syncLog->update([
            'status' => 'partial',
            'total_items' => $this->syncStats['total_items'],
            'created_items' => $this->syncStats['created_items'],
            'updated_items' => $this->syncStats['updated_items'],
            'error_items' => $this->syncStats['error_items'],
            'details' => $this->syncStats['details'],
            'finished_at' => now(),
        ]);

        Log::warning("Sincronização de {$this->syncLog->entity} concluída parcialmente", [
            'sync_log_id' => $this->syncLog->id,
            'stats' => $this->syncStats,
            'message' => $message,
        ]);
    }

    /**
     * Incrementar contador de itens criados
     */
    protected function incrementCreatedItems($count = 1)
    {
        $this->syncStats['created_items'] += $count;
        $this->syncStats['total_items'] += $count;
    }

    /**
     * Incrementar contador de itens atualizados
     */
    protected function incrementUpdatedItems($count = 1)
    {
        $this->syncStats['updated_items'] += $count;
        $this->syncStats['total_items'] += $count;
    }

    /**
     * Incrementar contador de itens com erro
     */
    protected function incrementErrorItems($count = 1)
    {
        $this->syncStats['error_items'] += $count;
        $this->syncStats['total_items'] += $count;
    }

    /**
     * Adicionar detalhes à sincronização
     */
    protected function addSyncDetail($key, $value)
    {
        $this->syncStats['details'][$key] = $value;
    }

    /**
     * Obter estatísticas da sincronização atual
     */
    protected function getSyncStats()
    {
        return $this->syncStats;
    }

    /**
     * Obter o log de sincronização atual
     */
    protected function getCurrentSyncLog()
    {
        return $this->syncLog;
    }
}