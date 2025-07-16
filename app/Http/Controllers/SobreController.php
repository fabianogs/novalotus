<?php

namespace App\Http\Controllers;

use App\Models\Sobre;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SobreController extends Controller
{
    /**
     * Mostra o formulário de edição do texto sobre
     */
    public function edit()
    {
        $sobre = Sobre::firstOrCreate(['id' => 1], ['texto' => '']);
        
        return view('sobre.edit', compact('sobre'));
    }

    /**
     * Atualiza o texto sobre
     */
    public function update(Request $request)
    {
        $request->validate([
            'texto' => 'required|string'
        ]);

        $sobre = Sobre::firstOrCreate(['id' => 1], ['texto' => '']);
        $sobre->update(['texto' => $request->texto]);

        return redirect()->route('sobre.edit')->with('success', 'Texto atualizado com sucesso!');
    }

    /**
     * Retorna o texto sobre via API
     */
    public function show(): JsonResponse
    {
        $sobre = Sobre::firstOrCreate(['id' => 1], ['texto' => '']);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $sobre->id,
                'texto' => $sobre->texto,
                'updated_at' => $sobre->updated_at
            ]
        ]);
    }
}
