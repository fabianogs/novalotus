<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::orderBy('created_at', 'desc')->get();
        return view('banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'link' => 'nullable|url',
            'imagem' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ativo' => 'boolean'
        ]);

        // Upload da imagem
        $imagemName = null;
        if ($request->hasFile('imagem')) {
            $imagemName = $this->uploadImagem($request->file('imagem'));
        }

        // Criar o banner
        Banner::create([
            'titulo' => $request->titulo,
            'link' => $request->link,
            'imagem' => $imagemName,
            'ativo' => $request->has('ativo') ? true : false
        ]);

        return redirect()->route('banners.index')
                        ->with('success', 'Banner criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        return view('banners.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        return view('banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'link' => 'nullable|url',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ativo' => 'boolean'
        ]);

        $data = [
            'titulo' => $request->titulo,
            'link' => $request->link,
            'ativo' => $request->has('ativo') ? true : false
        ];

        // Upload da nova imagem se fornecida
        if ($request->hasFile('imagem')) {
            // Deletar imagem anterior
            if ($banner->imagem) {
                Storage::delete('public/img/banners/' . $banner->imagem);
            }
            
            $data['imagem'] = $this->uploadImagem($request->file('imagem'));
        }

        $banner->update($data);

        return redirect()->route('banners.index')
                        ->with('success', 'Banner atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        try {
            // Deletar imagem do storage
            if ($banner->imagem) {
                Storage::delete('public/img/banners/' . $banner->imagem);
            }

            $banner->delete();

            return redirect()->route('banners.index')
                            ->with('success', 'Banner excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('banners.index')
                            ->with('error', 'Erro ao excluir o banner. Tente novamente.');
        }
    }

    /**
     * Toggle status do banner via AJAX
     */
    public function toggleStatus(Request $request, Banner $banner)
    {
        $banner->update([
            'ativo' => $request->ativo
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status atualizado com sucesso!'
        ]);
    }

    /**
     * Upload da imagem para o storage
     */
    private function uploadImagem($file)
    {
        $originalName   = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $random         = uniqid();
        $fileName = $originalName.'_'.$random.'.'.$extension;
        
        // Salvar na pasta storage/banners
        $file->storeAs('img/banners', $fileName,'public');
        
        return $fileName;
    }
}
