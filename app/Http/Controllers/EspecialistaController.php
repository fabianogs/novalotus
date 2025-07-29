<?php

namespace App\Http\Controllers;

use App\Models\Especialista;
use App\Models\Especialidade;
use App\Models\Cidade;
use App\Models\Necessidade;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EspecialistaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $especialistas = Especialista::with(['especialidades', 'cidade', 'necessidade'])
                                   ->orderBy('nome', 'asc')
                                   ->get();
        
        return view('especialistas.index', compact('especialistas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $especialidades = Especialidade::orderBy('descricao', 'asc')->get();
        $cidades = Cidade::orderBy('nome', 'asc')->get();
        $necessidades = Necessidade::orderBy('titulo', 'asc')->get();
        
        // Verificar se existem registros necessários
        $warnings = [];
        $canCreate = true;
        
        if ($especialidades->isEmpty()) {
            $warnings[] = [
                'message' => 'Nenhuma especialidade encontrada. É recomendado cadastrar especialidades antes de criar especialistas.',
                'type' => 'warning',
                'route' => route('especialidades.create'),
                'button_text' => 'Cadastrar Especialidade'
            ];
        }
        
        if ($cidades->isEmpty()) {
            $warnings[] = [
                'message' => 'Nenhuma cidade encontrada. É recomendado cadastrar cidades antes de criar especialistas.',
                'type' => 'warning',
                'route' => route('cidades.create'),
                'button_text' => 'Cadastrar Cidade'
            ];
        }
        
        if ($necessidades->isEmpty()) {
            $warnings[] = [
                'message' => 'Nenhuma necessidade encontrada. É recomendado cadastrar necessidades antes de criar especialistas.',
                'type' => 'warning',
                'route' => route('necessidades.create'),
                'button_text' => 'Cadastrar Necessidade'
            ];
        }
        
        return view('especialistas.create', compact('especialidades', 'cidades', 'necessidades', 'warnings', 'canCreate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação customizada para campos relacionados
        $customValidation = $this->validateRelatedFields($request);
        if ($customValidation !== true) {
            return back()->withInput()->withErrors($customValidation);
        }
        
        $request->validate([
            'nome' => 'required|string|max:255',
            'conselho' => 'nullable|string|max:255',
            'especialidades' => 'nullable|array',
            'especialidades.*' => 'exists:especialidades,id',
            'cidade_id' => 'nullable|exists:cidades,id',
            'endereco' => 'nullable|string|max:500',
            'necessidade_id' => 'nullable|exists:necessidades,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nome.required' => 'O nome do especialista é obrigatório.',
            'nome.string' => 'O nome deve ser um texto válido.',
            'nome.max' => 'O nome deve ter no máximo 255 caracteres.',
            'conselho.max' => 'O conselho deve ter no máximo 255 caracteres.',
            'especialidades.array' => 'As especialidades devem ser selecionadas corretamente.',
            'especialidades.*.exists' => 'Uma das especialidades selecionadas não existe.',
            'cidade_id.exists' => 'A cidade selecionada não existe. Verifique se ela não foi removida.',
            'endereco.max' => 'O endereço deve ter no máximo 500 caracteres.',
            'necessidade_id.exists' => 'A necessidade selecionada não existe. Verifique se ela não foi removida.',
            'foto.image' => 'O arquivo deve ser uma imagem.',
            'foto.mimes' => 'A foto deve ser nos formatos: JPEG, PNG, JPG ou GIF.',
            'foto.max' => 'A foto deve ter no máximo 2MB.',
        ]);

        // Upload da foto
        $fotoName = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = $foto->getClientOriginalName() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('img/especialistas', $fotoName, 'public');
        }

        // Gerar slug único
        $slug = $this->generateUniqueSlug($request->nome);

        // Criar o especialista
        $especialista = Especialista::create([
            'nome' => $request->nome,
            'conselho' => $request->conselho,
            'cidade_id' => $request->cidade_id,
            'endereco' => $request->endereco,
            'necessidade_id' => $request->necessidade_id,
            'foto' => $fotoName,
            'slug' => $slug,
        ]);

        // Associar especialidades
        if ($request->has('especialidades')) {
            $especialista->especialidades()->attach($request->especialidades);
        }

        return redirect()->route('especialistas.index')
                        ->with('success', 'Especialista criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Especialista $especialista)
    {
        $especialista->load(['especialidades', 'cidade', 'necessidade', 'enderecos', 'telefones']);
        return view('especialistas.show', compact('especialista'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Especialista $especialista)
    {
        $especialidades = Especialidade::orderBy('descricao', 'asc')->get();
        $cidades = Cidade::orderBy('nome', 'asc')->get();
        $necessidades = Necessidade::orderBy('titulo', 'asc')->get();
        
        // Carregar especialidades do especialista
        $especialista->load('especialidades');
        
        // Verificar se existem registros necessários
        $warnings = [];
        $canEdit = true;
        
        if ($especialidades->isEmpty()) {
            $warnings[] = [
                'message' => 'Nenhuma especialidade encontrada. É recomendado cadastrar especialidades antes de editar especialistas.',
                'type' => 'warning',
                'route' => route('especialidades.create'),
                'button_text' => 'Cadastrar Especialidade'
            ];
        }
        
        if ($cidades->isEmpty()) {
            $warnings[] = [
                'message' => 'Nenhuma cidade encontrada. É recomendado cadastrar cidades antes de editar especialistas.',
                'type' => 'warning',
                'route' => route('cidades.create'),
                'button_text' => 'Cadastrar Cidade'
            ];
        }
        
        if ($necessidades->isEmpty()) {
            $warnings[] = [
                'message' => 'Nenhuma necessidade encontrada. É recomendado cadastrar necessidades antes de editar especialistas.',
                'type' => 'warning',
                'route' => route('necessidades.create'),
                'button_text' => 'Cadastrar Necessidade'
            ];
        }
        
        return view('especialistas.edit', compact('especialista', 'especialidades', 'cidades', 'necessidades', 'warnings', 'canEdit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Especialista $especialista)
    {
        // Validação customizada para campos relacionados
        $customValidation = $this->validateRelatedFields($request);
        if ($customValidation !== true) {
            return back()->withInput()->withErrors($customValidation);
        }
        
        $request->validate([
            'nome' => 'required|string|max:255',
            'conselho' => 'nullable|string|max:255',
            'especialidades' => 'nullable|array',
            'especialidades.*' => 'exists:especialidades,id',
            'cidade_id' => 'nullable|exists:cidades,id',
            'endereco' => 'nullable|string|max:500',
            'necessidade_id' => 'nullable|exists:necessidades,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nome.required' => 'O nome do especialista é obrigatório.',
            'nome.string' => 'O nome deve ser um texto válido.',
            'nome.max' => 'O nome deve ter no máximo 255 caracteres.',
            'conselho.max' => 'O conselho deve ter no máximo 255 caracteres.',
            'especialidades.array' => 'As especialidades devem ser selecionadas corretamente.',
            'especialidades.*.exists' => 'Uma das especialidades selecionadas não existe.',
            'cidade_id.exists' => 'A cidade selecionada não existe. Verifique se ela não foi removida.',
            'endereco.max' => 'O endereço deve ter no máximo 500 caracteres.',
            'necessidade_id.exists' => 'A necessidade selecionada não existe. Verifique se ela não foi removida.',
            'foto.image' => 'O arquivo deve ser uma imagem.',
            'foto.mimes' => 'A foto deve ser nos formatos: JPEG, PNG, JPG ou GIF.',
            'foto.max' => 'A foto deve ter no máximo 2MB.',
        ]);

        // Upload da nova foto
        $fotoName = $especialista->foto;
        if ($request->hasFile('foto')) {
            // Deletar foto antiga se existir
            if ($especialista->foto && Storage::disk('public')->exists('img/especialistas/' . $especialista->foto)) {
                Storage::disk('public')->delete('img/especialistas/' . $especialista->foto);
            }
            
            $foto = $request->file('foto');
            $fotoName = $foto->getClientOriginalName() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('img/especialistas', $fotoName, 'public');
        }

        // Gerar slug único se o nome mudou
        $slug = $request->nome === $especialista->nome 
            ? $especialista->slug 
            : $this->generateUniqueSlug($request->nome, $especialista->id);

        $especialista->update([
            'nome' => $request->nome,
            'conselho' => $request->conselho,
            'cidade_id' => $request->cidade_id,
            'endereco' => $request->endereco,
            'necessidade_id' => $request->necessidade_id,
            'foto' => $fotoName,
            'slug' => $slug,
        ]);

        // Sincronizar especialidades
        $especialidades = $request->has('especialidades') ? $request->especialidades : [];
        $especialista->especialidades()->sync($especialidades);

        return redirect()->route('especialistas.index')
                        ->with('success', 'Especialista atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Especialista $especialista)
    {
        try {
            // Deletar foto se existir
            if ($especialista->foto && Storage::disk('public')->exists('img/especialistas/' . $especialista->foto)) {
                Storage::disk('public')->delete('img/especialistas/' . $especialista->foto);
            }

            $especialista->delete();

            return redirect()->route('especialistas.index')
                            ->with('success', 'Especialista excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('especialistas.index')
                            ->with('error', 'Erro ao excluir o especialista. Tente novamente.');
        }
    }

    /**
     * Buscar especialistas por AJAX
     */
    public function buscar(Request $request)
    {
        $term = $request->get('term');
        
        $especialistas = Especialista::with(['especialidades', 'cidade'])
                                   ->where('nome', 'LIKE', "%{$term}%")
                                   ->orWhere('conselho', 'LIKE', "%{$term}%")
                                   ->orWhere('slug', 'LIKE', "%{$term}%")
                                   ->orderBy('nome')
                                   ->limit(10)
                                   ->get(['id', 'nome', 'conselho', 'slug', 'cidade_id']);

        return response()->json($especialistas);
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
            $query = Especialista::where('slug', $slug);
            
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

    /**
     * Validar campos relacionados de forma customizada
     */
    private function validateRelatedFields(Request $request)
    {
        $errors = [];

        // Verificar se as especialidades existem e estão disponíveis
        if ($request->has('especialidades') && is_array($request->especialidades)) {
            foreach ($request->especialidades as $especialidadeId) {
                $especialidade = Especialidade::find($especialidadeId);
                if (!$especialidade) {
                    $errors['especialidades'] = 'Uma das especialidades selecionadas não foi encontrada. Pode ter sido removida por outro usuário.';
                    break;
                }
            }
        }

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