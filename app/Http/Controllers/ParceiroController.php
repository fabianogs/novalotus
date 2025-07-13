<?php

namespace App\Http\Controllers;

use App\Models\Parceiro;
use App\Models\Cidade;
use App\Models\Necessidade;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ParceiroController extends Controller
{
    public function index()
    {
        $parceiros = Parceiro::with(['cidade', 'necessidade'])
            ->orderBy('nome')
            ->get();

        return view('parceiros.index', compact('parceiros'));
    }

    public function create()
    {
        $cidades = Cidade::orderBy('nome')->get();
        $necessidades = Necessidade::orderBy('titulo')->get();

        // Verificar se existem registros necessários
        $warnings = [];
        $canCreate = true;
        
        if ($cidades->isEmpty()) {
            $warnings[] = [
                'message' => 'Nenhuma cidade encontrada. É recomendado cadastrar cidades antes de criar parceiros.',
                'type' => 'warning',
                'route' => route('cidades.create'),
                'button_text' => 'Cadastrar Cidade'
            ];
        }
        
        if ($necessidades->isEmpty()) {
            $warnings[] = [
                'message' => 'Nenhuma necessidade encontrada. É recomendado cadastrar necessidades antes de criar parceiros.',
                'type' => 'warning',
                'route' => route('necessidades.create'),
                'button_text' => 'Cadastrar Necessidade'
            ];
        }

        return view('parceiros.create', compact('cidades', 'necessidades', 'warnings', 'canCreate'));
    }

    public function store(Request $request)
    {
        // Validação customizada para campos relacionados
        $customValidation = $this->validateRelatedFields($request);
        if ($customValidation !== true) {
            return back()->withInput()->withErrors($customValidation);
        }
        
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'cidade_id' => 'nullable|exists:cidades,id',
            'endereco' => 'nullable|string|max:255',
            'necessidade_id' => 'nullable|exists:necessidades,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_carrossel' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'nome.string' => 'O nome deve ser um texto.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',
            'descricao.string' => 'A descrição deve ser um texto.',
            'cidade_id.exists' => 'A cidade selecionada não existe. Verifique se ela não foi removida.',
            'endereco.string' => 'O endereço deve ser um texto.',
            'endereco.max' => 'O endereço não pode ter mais de 255 caracteres.',
            'necessidade_id.exists' => 'A necessidade selecionada não existe. Verifique se ela não foi removida.',
            'logo.image' => 'O logo deve ser uma imagem.',
            'logo.mimes' => 'O logo deve ser um arquivo nos formatos: jpeg, png, jpg, gif, svg.',
            'logo.max' => 'O logo não pode ter mais de 2MB.',
            'logo_carrossel.image' => 'O logo do carrossel deve ser uma imagem.',
            'logo_carrossel.mimes' => 'O logo do carrossel deve ser um arquivo nos formatos: jpeg, png, jpg, gif, svg.',
            'logo_carrossel.max' => 'O logo do carrossel não pode ter mais de 2MB.',
        ]);

        $logoPath = null;
        $logoCarrosselPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $this->uploadImage($request->file('logo'), 'logo');
        }

        if ($request->hasFile('logo_carrossel')) {
            $logoCarrosselPath = $this->uploadImage($request->file('logo_carrossel'), 'logo_carrossel');
        }

        Parceiro::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'cidade_id' => $request->cidade_id,
            'endereco' => $request->endereco,
            'necessidade_id' => $request->necessidade_id,
            'logo' => $logoPath,
            'logo_carrossel' => $logoCarrosselPath,
            'slug' => $this->generateUniqueSlug($request->nome),
        ]);

        return redirect()->route('parceiros.index')
            ->with('success', 'Parceiro criado com sucesso!');
    }

    public function show(Parceiro $parceiro)
    {
        $parceiro->load(['cidade', 'necessidade']);
        return view('parceiros.show', compact('parceiro'));
    }

    public function edit(Parceiro $parceiro)
    {
        $cidades = Cidade::orderBy('nome')->get();
        $necessidades = Necessidade::orderBy('titulo')->get();

        // Verificar se existem registros necessários
        $warnings = [];
        $canEdit = true;
        
        if ($cidades->isEmpty()) {
            $warnings[] = [
                'message' => 'Nenhuma cidade encontrada. É recomendado cadastrar cidades antes de editar parceiros.',
                'type' => 'warning',
                'route' => route('cidades.create'),
                'button_text' => 'Cadastrar Cidade'
            ];
        }
        
        if ($necessidades->isEmpty()) {
            $warnings[] = [
                'message' => 'Nenhuma necessidade encontrada. É recomendado cadastrar necessidades antes de editar parceiros.',
                'type' => 'warning',
                'route' => route('necessidades.create'),
                'button_text' => 'Cadastrar Necessidade'
            ];
        }

        return view('parceiros.edit', compact('parceiro', 'cidades', 'necessidades', 'warnings', 'canEdit'));
    }

    public function update(Request $request, Parceiro $parceiro)
    {
        // Validação customizada para campos relacionados
        $customValidation = $this->validateRelatedFields($request);
        if ($customValidation !== true) {
            return back()->withInput()->withErrors($customValidation);
        }
        
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'cidade_id' => 'nullable|exists:cidades,id',
            'endereco' => 'nullable|string|max:255',
            'necessidade_id' => 'nullable|exists:necessidades,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_carrossel' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'nome.string' => 'O nome deve ser um texto.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',
            'descricao.string' => 'A descrição deve ser um texto.',
            'cidade_id.exists' => 'A cidade selecionada não existe. Verifique se ela não foi removida.',
            'endereco.string' => 'O endereço deve ser um texto.',
            'endereco.max' => 'O endereço não pode ter mais de 255 caracteres.',
            'necessidade_id.exists' => 'A necessidade selecionada não existe. Verifique se ela não foi removida.',
            'logo.image' => 'O logo deve ser uma imagem.',
            'logo.mimes' => 'O logo deve ser um arquivo nos formatos: jpeg, png, jpg, gif, svg.',
            'logo.max' => 'O logo não pode ter mais de 2MB.',
            'logo_carrossel.image' => 'O logo do carrossel deve ser uma imagem.',
            'logo_carrossel.mimes' => 'O logo do carrossel deve ser um arquivo nos formatos: jpeg, png, jpg, gif, svg.',
            'logo_carrossel.max' => 'O logo do carrossel não pode ter mais de 2MB.',
        ]);

        $logoPath = $parceiro->logo;
        $logoCarrosselPath = $parceiro->logo_carrossel;

        if ($request->hasFile('logo')) {
            // Deletar logo antigo
            if ($parceiro->logo) {
                $oldLogoPath = public_path('storage/img/parceiros/' . $parceiro->logo);
                if (File::exists($oldLogoPath)) {
                    File::delete($oldLogoPath);
                }
            }
            $logoPath = $this->uploadImage($request->file('logo'), 'logo');
        }

        if ($request->hasFile('logo_carrossel')) {
            // Deletar logo do carrossel antigo
            if ($parceiro->logo_carrossel) {
                $oldLogoCarrosselPath = public_path('storage/img/parceiros/' . $parceiro->logo_carrossel);
                if (File::exists($oldLogoCarrosselPath)) {
                    File::delete($oldLogoCarrosselPath);
                }
            }
            $logoCarrosselPath = $this->uploadImage($request->file('logo_carrossel'), 'logo_carrossel');
        }

        $parceiro->update([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'cidade_id' => $request->cidade_id,
            'endereco' => $request->endereco,
            'necessidade_id' => $request->necessidade_id,
            'logo' => $logoPath,
            'logo_carrossel' => $logoCarrosselPath,
            'slug' => $this->generateUniqueSlug($request->nome, $parceiro->id),
        ]);

        return redirect()->route('parceiros.index')
            ->with('success', 'Parceiro atualizado com sucesso!');
    }

    public function destroy(Parceiro $parceiro)
    {
        // Deletar imagens
        if ($parceiro->logo) {
            $logoPath = public_path('storage/img/parceiros/' . $parceiro->logo);
            if (File::exists($logoPath)) {
                File::delete($logoPath);
            }
        }

        if ($parceiro->logo_carrossel) {
            $logoCarrosselPath = public_path('storage/img/parceiros/' . $parceiro->logo_carrossel);
            if (File::exists($logoCarrosselPath)) {
                File::delete($logoCarrosselPath);
            }
        }

        $parceiro->delete();

        return redirect()->route('parceiros.index')
            ->with('success', 'Parceiro deletado com sucesso!');
    }

    private function uploadImage($file, $type)
    {
        $extension = $file->getClientOriginalExtension();
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = Str::slug($fileName);
        $uniqueId = uniqid();
        $finalName = $fileName . '_' . $uniqueId . '.' . $extension;

        $directory = public_path('storage/img/parceiros');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $file->move($directory, $finalName);

        return $finalName;
    }

    private function generateUniqueSlug($nome, $id = null)
    {
        $slug = Str::slug($nome);
        $count = 1;
        $originalSlug = $slug;

        while (Parceiro::where('slug', $slug)->when($id, function ($query) use ($id) {
            return $query->where('id', '!=', $id);
        })->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Validar campos relacionados de forma customizada
     */
    private function validateRelatedFields(Request $request)
    {
        $errors = [];

        // Verificar se a cidade existe e está disponível
        if ($request->filled('cidade_id')) {
            $cidade = Cidade::find($request->cidade_id);
            if (!$cidade) {
                $errors['cidade_id'] = 'A cidade selecionada não foi encontrada. Pode ter sido removida por outro usuário.';
            }
        }

        // Verificar se a necessidade existe e está disponível
        if ($request->filled('necessidade_id')) {
            $necessidade = Necessidade::find($request->necessidade_id);
            if (!$necessidade) {
                $errors['necessidade_id'] = 'A necessidade selecionada não foi encontrada. Pode ter sido removida por outro usuário.';
            }
        }

        return empty($errors) ? true : $errors;
    }
} 