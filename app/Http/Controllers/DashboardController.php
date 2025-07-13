<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Banner;
use App\Models\Cidade;
use App\Models\Especialidade;
use App\Models\Especialista;
use App\Models\Necessidade;
use App\Models\Parceiro;
use App\Models\Plano;
use App\Models\Seo;
use App\Models\Unidade;

class DashboardController extends Controller
{
    /**
     * Exibe a página do dashboard
     */
    public function index(): View
    {
        // Estatísticas principais
        $stats = [
            'total_especialistas' => Especialista::count(),
            'total_parceiros' => Parceiro::count(),
            'total_unidades' => Unidade::count(),
            'total_cidades' => Cidade::count(),
            'total_especialidades' => Especialidade::count(),
            'total_necessidades' => Necessidade::count(),
            'total_planos' => Plano::count(),
            'banners_ativos' => Banner::where('ativo', true)->count(),
            'banners_inativos' => Banner::where('ativo', false)->count(),
            'seos_ativos' => Seo::where('status', true)->count(),
            'seos_inativos' => Seo::where('status', false)->count(),
        ];

        // Itens recentes
        $recentes = [
            'especialistas' => Especialista::with(['especialidade', 'cidade'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'parceiros' => Parceiro::with(['cidade', 'necessidade'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'unidades' => Unidade::with('cidade')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get(),
            'planos' => Plano::orderBy('created_at', 'desc')
                ->limit(3)
                ->get(),
        ];

        return view('dashboard', compact('stats', 'recentes'));
    }
}
