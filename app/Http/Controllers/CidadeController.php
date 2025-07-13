<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cidades = Cidade::withCount(['especialistas', 'parceiros', 'unidades'])
                         ->orderBy('nome', 'asc')
                         ->get();
        
        return view('cidades.index', compact('cidades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cidades.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'uf' => 'required|string|size:2'
        ], [
            'nome.required' => 'O nome da cidade é obrigatório.',
            'uf.required' => 'O estado (UF) é obrigatório.',
            'uf.size' => 'O estado deve ter exatamente 2 caracteres.'
        ]);

        // Gerar slug único
        $slug = $this->generateUniqueSlug($request->nome);

        // Criar a cidade
        Cidade::create([
            'nome' => $request->nome,
            'slug' => $slug,
            'uf' => strtoupper($request->uf)
        ]);

        return redirect()->route('cidades.index')
                        ->with('success', 'Cidade criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cidade $cidade)
    {
        $cidade->load(['especialistas', 'parceiros', 'unidades']);
        return view('cidades.show', compact('cidade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cidade $cidade)
    {
        $cidade->loadCount(['especialistas', 'parceiros', 'unidades']);
        return view('cidades.edit', compact('cidade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cidade $cidade)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'uf' => 'required|string|size:2'
        ], [
            'nome.required' => 'O nome da cidade é obrigatório.',
            'uf.required' => 'O estado (UF) é obrigatório.',
            'uf.size' => 'O estado deve ter exatamente 2 caracteres.'
        ]);

        // Gerar slug único se o nome mudou
        $slug = $request->nome === $cidade->nome 
            ? $cidade->slug 
            : $this->generateUniqueSlug($request->nome, $cidade->id);

        $cidade->update([
            'nome' => $request->nome,
            'slug' => $slug,
            'uf' => strtoupper($request->uf)
        ]);

        return redirect()->route('cidades.index')
                        ->with('success', 'Cidade atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cidade $cidade)
    {
        try {
            // Verificar se a cidade possui relacionamentos
            $especialistasCount = $cidade->especialistas()->count();
            $parceirosCount = $cidade->parceiros()->count();
            $unidadesCount = $cidade->unidades()->count();

            if ($especialistasCount > 0 || $parceirosCount > 0 || $unidadesCount > 0) {
                return redirect()->route('cidades.index')
                                ->with('error', 'Não é possível excluir esta cidade pois ela possui especialistas, parceiros ou unidades vinculadas.');
            }

            $cidade->delete();

            return redirect()->route('cidades.index')
                            ->with('success', 'Cidade excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('cidades.index')
                            ->with('error', 'Erro ao excluir a cidade. Tente novamente.');
        }
    }

    /**
     * Buscar cidades por AJAX
     */
    public function buscar(Request $request)
    {
        $term = $request->get('term');
        
        $cidades = Cidade::where('nome', 'LIKE', "%{$term}%")
                        ->orWhere('slug', 'LIKE', "%{$term}%")
                        ->orWhere('uf', 'LIKE', "%{$term}%")
                        ->orderBy('nome')
                        ->limit(10)
                        ->get(['id', 'nome', 'uf', 'slug']);

        return response()->json($cidades);
    }

    /**
     * Gerar slug único baseado no nome
     */
    private function generateUniqueSlug($nome, $excludeId = null)
    {
        $baseSlug = Str::slug($nome);
        $slug = $baseSlug;
        $counter = 1;

        while (true) {
            $query = Cidade::where('slug', $slug);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
