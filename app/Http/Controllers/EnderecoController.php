<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Models\Especialista;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    /**
     * Display a listing of endereços for a specific especialista.
     */
    public function index(Especialista $especialista)
    {
        $enderecos = $especialista->enderecos()->orderBy('created_at', 'desc')->get();
        return view('enderecos.index', compact('especialista', 'enderecos'));
    }

    /**
     * Show the form for creating a new endereço.
     */
    public function create(Especialista $especialista)
    {
        return view('enderecos.create', compact('especialista'));
    }

    /**
     * Store a newly created endereço in storage.
     */
    public function store(Request $request, Especialista $especialista)
    {
        $request->validate([
            'uf' => 'nullable|string|max:2',
            'cidade_nome' => 'nullable|string|max:255',
            'cep' => 'nullable|string|max:10',
            'bairro' => 'nullable|string|max:255',
            'logradouro' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:20',
            'complemento' => 'nullable|string|max:255',
        ], [
            'uf.max' => 'A UF deve ter no máximo 2 caracteres.',
            'cidade_nome.max' => 'O nome da cidade deve ter no máximo 255 caracteres.',
            'cep.max' => 'O CEP deve ter no máximo 10 caracteres.',
            'bairro.max' => 'O bairro deve ter no máximo 255 caracteres.',
            'logradouro.max' => 'O logradouro deve ter no máximo 255 caracteres.',
            'numero.max' => 'O número deve ter no máximo 20 caracteres.',
            'complemento.max' => 'O complemento deve ter no máximo 255 caracteres.',
        ]);

        $especialista->enderecos()->create($request->all());

        return redirect()->route('especialistas.show', $especialista)
                        ->with('success', 'Endereço adicionado com sucesso!');
    }

    /**
     * Show the form for editing the specified endereço.
     */
    public function edit(Especialista $especialista, Endereco $endereco)
    {
        // Verificar se o endereço pertence ao especialista
        if ($endereco->especialista_id !== $especialista->id) {
            abort(404);
        }

        return view('enderecos.edit', compact('especialista', 'endereco'));
    }

    /**
     * Update the specified endereço in storage.
     */
    public function update(Request $request, Especialista $especialista, Endereco $endereco)
    {
        // Verificar se o endereço pertence ao especialista
        if ($endereco->especialista_id !== $especialista->id) {
            abort(404);
        }

        $request->validate([
            'uf' => 'nullable|string|max:2',
            'cidade_nome' => 'nullable|string|max:255',
            'cep' => 'nullable|string|max:10',
            'bairro' => 'nullable|string|max:255',
            'logradouro' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:20',
            'complemento' => 'nullable|string|max:255',
        ], [
            'uf.max' => 'A UF deve ter no máximo 2 caracteres.',
            'cidade_nome.max' => 'O nome da cidade deve ter no máximo 255 caracteres.',
            'cep.max' => 'O CEP deve ter no máximo 10 caracteres.',
            'bairro.max' => 'O bairro deve ter no máximo 255 caracteres.',
            'logradouro.max' => 'O logradouro deve ter no máximo 255 caracteres.',
            'numero.max' => 'O número deve ter no máximo 20 caracteres.',
            'complemento.max' => 'O complemento deve ter no máximo 255 caracteres.',
        ]);

        $endereco->update($request->all());

        return redirect()->route('especialistas.show', $especialista)
                        ->with('success', 'Endereço atualizado com sucesso!');
    }

    /**
     * Remove the specified endereço from storage.
     */
    public function destroy(Especialista $especialista, Endereco $endereco)
    {
        // Verificar se o endereço pertence ao especialista
        if ($endereco->especialista_id !== $especialista->id) {
            abort(404);
        }

        $endereco->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Endereço removido com sucesso!'
            ]);
        }

        return redirect()->route('especialistas.show', $especialista)
                        ->with('success', 'Endereço removido com sucesso!');
    }
}