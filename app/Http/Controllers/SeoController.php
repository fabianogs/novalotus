<?php

namespace App\Http\Controllers;

use App\Models\Seo;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function index()
    {
        $seos = Seo::orderBy('nome')->get();
        return view('seos.index', compact('seos'));
    }

    public function create()
    {
        return view('seos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string|max:255',
            'nome' => 'required|string|max:255',
            'script' => 'nullable|string',
            'status' => 'boolean',
        ], [
            'tipo.required' => 'O tipo é obrigatório.',
            'tipo.string' => 'O tipo deve ser um texto.',
            'tipo.max' => 'O tipo não pode ter mais de 255 caracteres.',
            'nome.required' => 'O nome é obrigatório.',
            'nome.string' => 'O nome deve ser um texto.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',
            'script.string' => 'O script deve ser um texto.',
            'status.boolean' => 'O status deve ser verdadeiro ou falso.',
        ]);

        Seo::create([
            'tipo' => $request->tipo,
            'nome' => $request->nome,
            'script' => $request->script,
            'status' => $request->has('status') ? true : false,
        ]);

        return redirect()->route('seos.index')
            ->with('success', 'SEO criado com sucesso!');
    }

    public function show(Seo $seo)
    {
        return view('seos.show', compact('seo'));
    }

    public function edit(Seo $seo)
    {
        return view('seos.edit', compact('seo'));
    }

    public function update(Request $request, Seo $seo)
    {
        $request->validate([
            'tipo' => 'required|string|max:255',
            'nome' => 'required|string|max:255',
            'script' => 'nullable|string',
            'status' => 'boolean',
        ], [
            'tipo.required' => 'O tipo é obrigatório.',
            'tipo.string' => 'O tipo deve ser um texto.',
            'tipo.max' => 'O tipo não pode ter mais de 255 caracteres.',
            'nome.required' => 'O nome é obrigatório.',
            'nome.string' => 'O nome deve ser um texto.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',
            'script.string' => 'O script deve ser um texto.',
            'status.boolean' => 'O status deve ser verdadeiro ou falso.',
        ]);

        $seo->update([
            'tipo' => $request->tipo,
            'nome' => $request->nome,
            'script' => $request->script,
            'status' => $request->has('status') ? true : false,
        ]);

        return redirect()->route('seos.index')
            ->with('success', 'SEO atualizado com sucesso!');
    }

    public function destroy(Seo $seo)
    {
        $seo->delete();

        return redirect()->route('seos.index')
            ->with('success', 'SEO deletado com sucesso!');
    }
} 