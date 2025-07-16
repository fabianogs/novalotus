<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Lista os logs de atividade
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('action')) {
            $query->byAction($request->action);
        }

        if ($request->filled('model')) {
            $query->byModel($request->model);
        }

        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->byPeriod($request->start_date, $request->end_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->paginate(20);

        // Dados para filtros
        $actions = ActivityLog::distinct()->pluck('action')->sort();
        $models = ActivityLog::distinct()->pluck('model')->filter()->sort();
        $users = User::orderBy('name')->get();

        return view('activity-logs.index', compact('logs', 'actions', 'models', 'users'));
    }

    /**
     * Mostra detalhes de um log específico
     */
    public function show(ActivityLog $activityLog)
    {
        return view('activity-logs.show', compact('activityLog'));
    }

    /**
     * Exporta logs para CSV
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        // Aplicar mesmos filtros da listagem
        if ($request->filled('action')) {
            $query->byAction($request->action);
        }

        if ($request->filled('model')) {
            $query->byModel($request->model);
        }

        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->byPeriod($request->start_date, $request->end_date);
        }

        $logs = $query->get();

        $filename = 'activity_logs_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Cabeçalho
            fputcsv($file, [
                'ID', 'Usuário', 'Ação', 'Modelo', 'Descrição', 
                'IP', 'Data/Hora', 'URL'
            ]);

            // Dados
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user ? $log->user->name : 'Sistema',
                    $log->action_name,
                    $log->model_name,
                    $log->description,
                    $log->ip_address,
                    $log->created_at->format('d/m/Y H:i:s'),
                    $log->url
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
