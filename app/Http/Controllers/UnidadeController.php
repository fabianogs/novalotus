<?php

namespace App\Http\Controllers;

use App\Models\Unidade;
use App\Models\Cidade;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class UnidadeController extends Controller
{
    public function index()
    {
        $unidades = Unidade::with('cidade')->orderBy('created_at', 'desc')->get();
        return view('unidades.index', compact('unidades'));
    }

    public function create()
    {
        $cidades = Cidade::orderBy('nome')->get();
        return view('unidades.create', compact('cidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cidade_id' => 'required|exists:cidades,id',
            'telefone' => 'nullable|string|max:255',
            'endereco' => 'nullable|string|max:255',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'cidade_id.required' => 'A cidade é obrigatória.',
            'cidade_id.exists' => 'A cidade selecionada não existe.',
            'telefone.string' => 'O telefone deve ser um texto.',
            'telefone.max' => 'O telefone não pode ter mais de 255 caracteres.',
            'endereco.string' => 'O endereço deve ser um texto.',
            'endereco.max' => 'O endereço não pode ter mais de 255 caracteres.',
            'imagem.image' => 'A imagem deve ser um arquivo de imagem.',
            'imagem.mimes' => 'A imagem deve ser um arquivo nos formatos: jpeg, png, jpg, gif, svg.',
            'imagem.max' => 'A imagem não pode ter mais de 2MB.',
        ]);

        $imagemPath = null;

        if ($request->hasFile('imagem')) {
            $imagemPath = $this->uploadImage($request->file('imagem'));
        }

        $cidade = Cidade::find($request->cidade_id);
        $slugBase = $cidade->nome;
        if ($request->telefone) {
            $slugBase .= ' ' . $request->telefone;
        } elseif ($request->endereco) {
            $slugBase .= ' ' . $request->endereco;
        }

        Unidade::create([
            'cidade_id' => $request->cidade_id,
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
            'imagem' => $imagemPath,
            'slug' => $this->generateUniqueSlug($slugBase),
        ]);

        return redirect()->route('unidades.index')
            ->with('success', 'Unidade criada com sucesso!');
    }

    public function show(Unidade $unidade)
    {
        $unidade->load('cidade');
        return view('unidades.show', compact('unidade'));
    }

    public function edit(Unidade $unidade)
    {
        $cidades = Cidade::orderBy('nome')->get();
        return view('unidades.edit', compact('unidade', 'cidades'));
    }

    public function update(Request $request, Unidade $unidade)
    {
        $request->validate([
            'cidade_id' => 'required|exists:cidades,id',
            'telefone' => 'nullable|string|max:255',
            'endereco' => 'nullable|string|max:255',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'cidade_id.required' => 'A cidade é obrigatória.',
            'cidade_id.exists' => 'A cidade selecionada não existe.',
            'telefone.string' => 'O telefone deve ser um texto.',
            'telefone.max' => 'O telefone não pode ter mais de 255 caracteres.',
            'endereco.string' => 'O endereço deve ser um texto.',
            'endereco.max' => 'O endereço não pode ter mais de 255 caracteres.',
            'imagem.image' => 'A imagem deve ser um arquivo de imagem.',
            'imagem.mimes' => 'A imagem deve ser um arquivo nos formatos: jpeg, png, jpg, gif, svg.',
            'imagem.max' => 'A imagem não pode ter mais de 2MB.',
        ]);

        $imagemPath = $unidade->imagem;

        if ($request->hasFile('imagem')) {
            // Deletar imagem antiga
            if ($unidade->imagem) {
                $oldImagePath = public_path('storage/img/unidades/' . $unidade->imagem);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            $imagemPath = $this->uploadImage($request->file('imagem'));
        }

        $cidade = Cidade::find($request->cidade_id);
        $slugBase = $cidade->nome;
        if ($request->telefone) {
            $slugBase .= ' ' . $request->telefone;
        } elseif ($request->endereco) {
            $slugBase .= ' ' . $request->endereco;
        }

        $unidade->update([
            'cidade_id' => $request->cidade_id,
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
            'imagem' => $imagemPath,
            'slug' => $this->generateUniqueSlug($slugBase, $unidade->id),
        ]);

        return redirect()->route('unidades.index')
            ->with('success', 'Unidade atualizada com sucesso!');
    }

    public function destroy(Unidade $unidade)
    {
        // Deletar imagem
        if ($unidade->imagem) {
            $imagemPath = public_path('storage/img/unidades/' . $unidade->imagem);
            if (File::exists($imagemPath)) {
                File::delete($imagemPath);
            }
        }

        $unidade->delete();

        return redirect()->route('unidades.index')
            ->with('success', 'Unidade deletada com sucesso!');
    }

    private function uploadImage($file)
    {
        $extension = $file->getClientOriginalExtension();
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = Str::slug($fileName);
        $uniqueId = uniqid();
        $finalName = $fileName . '_' . $uniqueId . '.' . $extension;

        $directory = public_path('storage/img/unidades');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $file->move($directory, $finalName);

        return $finalName;
    }

    private function generateUniqueSlug($slugBase, $id = null)
    {
        $slug = Str::slug($slugBase);
        $count = 1;
        $originalSlug = $slug;

        while (Unidade::where('slug', $slug)->when($id, function ($query) use ($id) {
            return $query->where('id', '!=', $id);
        })->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
} 