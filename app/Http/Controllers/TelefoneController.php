<?php

namespace App\Http\Controllers;

use App\Models\Telefone;
use App\Models\Especialista;
use Illuminate\Http\Request;

class TelefoneController extends Controller
{
    /**
     * Display a listing of telefones for a specific especialista.
     */
    public function index(Especialista $especialista)
    {
        $telefones = $especialista->telefones()->orderBy('created_at', 'desc')->get();
        return view('telefones.index', compact('especialista', 'telefones'));
    }

    /**
     * Show the form for creating a new telefone.
     */
    public function create(Especialista $especialista)
    {
        return view('telefones.create', compact('especialista'));
    }

    /**
     * Store a newly created telefone in storage.
     */
    public function store(Request $request, Especialista $especialista)
    {
        $request->validate([
            'numero' => 'required|string|max:20',
            'observacao' => 'nullable|string|max:255',
        ], [
            'numero.required' => 'O número do telefone é obrigatório.',
            'numero.max' => 'O número deve ter no máximo 20 caracteres.',
            'observacao.max' => 'A observação deve ter no máximo 255 caracteres.',
        ]);

        $especialista->telefones()->create($request->all());

        return redirect()->route('especialistas.show', $especialista)
                        ->with('success', 'Telefone adicionado com sucesso!');
    }

    /**
     * Show the form for editing the specified telefone.
     */
    public function edit(Especialista $especialista, Telefone $telefone)
    {
        // Verificar se o telefone pertence ao especialista
        if ($telefone->especialista_id !== $especialista->id) {
            abort(404);
        }

        return view('telefones.edit', compact('especialista', 'telefone'));
    }

    /**
     * Update the specified telefone in storage.
     */
    public function update(Request $request, Especialista $especialista, Telefone $telefone)
    {
        // Verificar se o telefone pertence ao especialista
        if ($telefone->especialista_id !== $especialista->id) {
            abort(404);
        }

        $request->validate([
            'numero' => 'required|string|max:20',
            'observacao' => 'nullable|string|max:255',
        ], [
            'numero.required' => 'O número do telefone é obrigatório.',
            'numero.max' => 'O número deve ter no máximo 20 caracteres.',
            'observacao.max' => 'A observação deve ter no máximo 255 caracteres.',
        ]);

        $telefone->update($request->all());

        return redirect()->route('especialistas.show', $especialista)
                        ->with('success', 'Telefone atualizado com sucesso!');
    }

    /**
     * Remove the specified telefone from storage.
     */
    public function destroy(Especialista $especialista, Telefone $telefone)
    {
        // Verificar se o telefone pertence ao especialista
        if ($telefone->especialista_id !== $especialista->id) {
            abort(404);
        }

        $telefone->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Telefone removido com sucesso!'
            ]);
        }

        return redirect()->route('especialistas.show', $especialista)
                        ->with('success', 'Telefone removido com sucesso!');
    }
}