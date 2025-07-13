<?php

namespace App\Http\Controllers;

use App\Models\Necessidade;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NecessidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $necessidades = Necessidade::orderBy('titulo', 'asc')
                                   ->get();
        
        return view('necessidades.index', compact('necessidades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('necessidades.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
        ], [
            'titulo.required' => 'O título da necessidade é obrigatório.',
            'titulo.string' => 'O título deve ser um texto válido.',
            'titulo.max' => 'O título deve ter no máximo 255 caracteres.',
        ]);

        // Gerar slug único
        $slug = $this->generateUniqueSlug($request->titulo);

        // Criar a necessidade
        Necessidade::create([
            'titulo' => $request->titulo,
            'slug' => $slug,
        ]);

        return redirect()->route('necessidades.index')
                        ->with('success', 'Necessidade criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Necessidade $necessidade)
    {
        $necessidade->load(['especialistas', 'parceiros']);
        return view('necessidades.show', compact('necessidade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Necessidade $necessidade)
    {
        return view('necessidades.edit', compact('necessidade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Necessidade $necessidade)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
        ], [
            'titulo.required' => 'O título da necessidade é obrigatório.',
            'titulo.string' => 'O título deve ser um texto válido.',
            'titulo.max' => 'O título deve ter no máximo 255 caracteres.',
        ]);

        // Gerar slug único se o título mudou
        $slug = $request->titulo === $necessidade->titulo 
            ? $necessidade->slug 
            : $this->generateUniqueSlug($request->titulo, $necessidade->id);

        $necessidade->update([
            'titulo' => $request->titulo,
            'slug' => $slug,
        ]);

        return redirect()->route('necessidades.index')
                        ->with('success', 'Necessidade atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Necessidade $necessidade)
    {
        try {
            // Verificar se a necessidade possui relacionamentos
            $especialistasCount = $necessidade->especialistas()->count();
            $parceirosCount = $necessidade->parceiros()->count();

            if ($especialistasCount > 0 || $parceirosCount > 0) {
                return redirect()->route('necessidades.index')
                                ->with('error', 'Não é possível excluir esta necessidade pois ela possui especialistas ou parceiros vinculados.');
            }

            $necessidade->delete();

            return redirect()->route('necessidades.index')
                            ->with('success', 'Necessidade excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('necessidades.index')
                            ->with('error', 'Erro ao excluir a necessidade. Tente novamente.');
        }
    }

    /**
     * Buscar necessidades por AJAX
     */
    public function buscar(Request $request)
    {
        $term = $request->get('term');
        
        $necessidades = Necessidade::where('titulo', 'LIKE', "%{$term}%")
                                   ->orWhere('slug', 'LIKE', "%{$term}%")
                                   ->orderBy('titulo')
                                   ->limit(10)
                                   ->get(['id', 'titulo', 'slug']);

        return response()->json($necessidades);
    }

    /**
     * Gerar slug único baseado no título
     */
    private function generateUniqueSlug($titulo, $excludeId = null)
    {
        $baseSlug = Str::slug($titulo);
        $slug = $baseSlug;
        $counter = 1;

        while (true) {
            $query = Necessidade::where('slug', $slug);
            
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