<?php

namespace App\Http\Controllers;

use App\Models\Especialidade;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EspecialidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $especialidades = Especialidade::orderBy('nome', 'asc')
                                      ->get();
        
        return view('especialidades.index', compact('especialidades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('especialidades.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ], [
            'nome.required' => 'O nome da especialidade é obrigatório.',
            'nome.string' => 'O nome deve ser um texto válido.',
            'nome.max' => 'O nome deve ter no máximo 255 caracteres.',
        ]);

        // Gerar slug único
        $slug = $this->generateUniqueSlug($request->nome);

        // Criar a especialidade
        Especialidade::create([
            'nome' => $request->nome,
            'slug' => $slug,
        ]);

        return redirect()->route('especialidades.index')
                        ->with('success', 'Especialidade criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Especialidade $especialidade)
    {
        $especialidade->load(['especialistas']);
        return view('especialidades.show', compact('especialidade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Especialidade $especialidade)
    {
        return view('especialidades.edit', compact('especialidade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Especialidade $especialidade)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ], [
            'nome.required' => 'O nome da especialidade é obrigatório.',
            'nome.string' => 'O nome deve ser um texto válido.',
            'nome.max' => 'O nome deve ter no máximo 255 caracteres.',
        ]);

        // Gerar slug único se o nome mudou
        $slug = $request->nome === $especialidade->nome 
            ? $especialidade->slug 
            : $this->generateUniqueSlug($request->nome, $especialidade->id);

        $especialidade->update([
            'nome' => $request->nome,
            'slug' => $slug,
        ]);

        return redirect()->route('especialidades.index')
                        ->with('success', 'Especialidade atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Especialidade $especialidade)
    {
        try {
            // Verificar se a especialidade possui relacionamentos
            $especialistasCount = $especialidade->especialistas()->count();

            if ($especialistasCount > 0) {
                return redirect()->route('especialidades.index')
                                ->with('error', 'Não é possível excluir esta especialidade pois ela possui especialistas vinculados.');
            }

            $especialidade->delete();

            return redirect()->route('especialidades.index')
                            ->with('success', 'Especialidade excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('especialidades.index')
                            ->with('error', 'Erro ao excluir a especialidade. Tente novamente.');
        }
    }

    /**
     * Buscar especialidades por AJAX
     */
    public function buscar(Request $request)
    {
        $term = $request->get('term');
        
        $especialidades = Especialidade::where('nome', 'LIKE', "%{$term}%")
                                      ->orWhere('slug', 'LIKE', "%{$term}%")
                                      ->orderBy('nome')
                                      ->limit(10)
                                      ->get(['id', 'nome', 'slug']);

        return response()->json($especialidades);
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
            $query = Especialidade::where('slug', $slug);
            
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
