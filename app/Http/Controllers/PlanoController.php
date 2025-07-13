<?php

namespace App\Http\Controllers;

use App\Models\Plano;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PlanoController extends Controller
{
    public function index()
    {
        $planos = Plano::orderBy('titulo')->get();
        return view('planos.index', compact('planos'));
    }

    public function create()
    {
        return view('planos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'sintese' => 'nullable|string',
            'link' => 'nullable|string|max:255',
            'link_pdf' => 'nullable|string|max:255',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'titulo.required' => 'O título é obrigatório.',
            'titulo.string' => 'O título deve ser um texto.',
            'titulo.max' => 'O título não pode ter mais de 255 caracteres.',
            'descricao.string' => 'A descrição deve ser um texto.',
            'sintese.string' => 'A síntese deve ser um texto.',
            'link.string' => 'O link deve ser um texto.',
            'link.max' => 'O link não pode ter mais de 255 caracteres.',
            'link_pdf.string' => 'O link do PDF deve ser um texto.',
            'link_pdf.max' => 'O link do PDF não pode ter mais de 255 caracteres.',
            'imagem.image' => 'A imagem deve ser um arquivo de imagem.',
            'imagem.mimes' => 'A imagem deve ser um arquivo nos formatos: jpeg, png, jpg, gif, svg.',
            'imagem.max' => 'A imagem não pode ter mais de 2MB.',
        ]);

        $imagemPath = null;

        if ($request->hasFile('imagem')) {
            $imagemPath = $this->uploadImage($request->file('imagem'));
        }

        Plano::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'sintese' => $request->sintese,
            'link' => $request->link,
            'link_pdf' => $request->link_pdf,
            'imagem' => $imagemPath,
            'slug' => $this->generateUniqueSlug($request->titulo),
        ]);

        return redirect()->route('planos.index')
            ->with('success', 'Plano criado com sucesso!');
    }

    public function show(Plano $plano)
    {
        return view('planos.show', compact('plano'));
    }

    public function edit(Plano $plano)
    {
        return view('planos.edit', compact('plano'));
    }

    public function update(Request $request, Plano $plano)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'sintese' => 'nullable|string',
            'link' => 'nullable|string|max:255',
            'link_pdf' => 'nullable|string|max:255',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'titulo.required' => 'O título é obrigatório.',
            'titulo.string' => 'O título deve ser um texto.',
            'titulo.max' => 'O título não pode ter mais de 255 caracteres.',
            'descricao.string' => 'A descrição deve ser um texto.',
            'sintese.string' => 'A síntese deve ser um texto.',
            'link.string' => 'O link deve ser um texto.',
            'link.max' => 'O link não pode ter mais de 255 caracteres.',
            'link_pdf.string' => 'O link do PDF deve ser um texto.',
            'link_pdf.max' => 'O link do PDF não pode ter mais de 255 caracteres.',
            'imagem.image' => 'A imagem deve ser um arquivo de imagem.',
            'imagem.mimes' => 'A imagem deve ser um arquivo nos formatos: jpeg, png, jpg, gif, svg.',
            'imagem.max' => 'A imagem não pode ter mais de 2MB.',
        ]);

        $imagemPath = $plano->imagem;

        if ($request->hasFile('imagem')) {
            // Deletar imagem antiga
            if ($plano->imagem) {
                $oldImagePath = public_path('storage/img/planos/' . $plano->imagem);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            $imagemPath = $this->uploadImage($request->file('imagem'));
        }

        $plano->update([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'sintese' => $request->sintese,
            'link' => $request->link,
            'link_pdf' => $request->link_pdf,
            'imagem' => $imagemPath,
            'slug' => $this->generateUniqueSlug($request->titulo, $plano->id),
        ]);

        return redirect()->route('planos.index')
            ->with('success', 'Plano atualizado com sucesso!');
    }

    public function destroy(Plano $plano)
    {
        // Deletar imagem
        if ($plano->imagem) {
            $imagemPath = public_path('storage/img/planos/' . $plano->imagem);
            if (File::exists($imagemPath)) {
                File::delete($imagemPath);
            }
        }

        $plano->delete();

        return redirect()->route('planos.index')
            ->with('success', 'Plano deletado com sucesso!');
    }

    private function uploadImage($file)
    {
        $extension = $file->getClientOriginalExtension();
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = Str::slug($fileName);
        $uniqueId = uniqid();
        $finalName = $fileName . '_' . $uniqueId . '.' . $extension;

        $directory = public_path('storage/img/planos');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $file->move($directory, $finalName);

        return $finalName;
    }

    private function generateUniqueSlug($titulo, $id = null)
    {
        $slug = Str::slug($titulo);
        $count = 1;
        $originalSlug = $slug;

        while (Plano::where('slug', $slug)->when($id, function ($query) use ($id) {
            return $query->where('id', '!=', $id);
        })->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
} 