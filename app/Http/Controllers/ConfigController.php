<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfigController extends Controller
{
    /**
     * Show the form for editing the configuration.
     */
    public function edit()
    {
        // Buscar sempre o registro id=1 ou criar se não existir
        $config = Config::firstOrCreate(['id' => 1], [
            'razao_social' => 'Nova Lotus',
            'cnpj' => null,
            'endereco' => null,
            'expediente' => null,
            'email' => null,
            'celular' => null,
            'fone1' => null,
            'fone2' => null,
            'whatsapp' => null,
            'facebook' => null,
            'instagram' => null,
            'twitter' => null,
            'youtube' => null,
            'linkedin' => null,
            'maps' => null,
            'form_email_to' => null,
            'form_email_cc' => null,
            'email_host' => null,
            'email_port' => null,
            'email_username' => null,
            'email_password' => null,
            'texto_contrato' => null,
            'texto_lgpd' => null,
            'arquivo_lgpd' => null,
        ]);

        return view('configs.edit', compact('config'));
    }

    /**
     * Update the configuration in storage.
     */
    public function update(Request $request)
    {
        // Sempre trabalhar com o registro id=1
        $config = Config::findOrFail(1);

        $request->validate([
            'celular' => 'nullable|string|max:255',
            'fone1' => 'nullable|string|max:255',
            'fone2' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'endereco' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'maps' => 'nullable|url',
            'form_email_to' => 'nullable|email|max:255',
            'form_email_cc' => 'nullable|email|max:255',
            'email_port' => 'nullable|integer|min:1|max:65535',
            'email_username' => 'nullable|string|max:255',
            'email_password' => 'nullable|string|max:255',
            'email_host' => 'nullable|string|max:255',
            'texto_contrato' => 'nullable|string',
            'cnpj' => 'nullable|string|max:255',
            'expediente' => 'nullable|string|max:255',
            'razao_social' => 'nullable|string|max:255',
            'arquivo_lgpd' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'texto_lgpd' => 'nullable|string',
        ], [
            'email.email' => 'O email deve ser um endereço válido.',
            'facebook.url' => 'O link do Facebook deve ser uma URL válida.',
            'instagram.url' => 'O link do Instagram deve ser uma URL válida.',
            'twitter.url' => 'O link do Twitter deve ser uma URL válida.',
            'youtube.url' => 'O link do YouTube deve ser uma URL válida.',
            'linkedin.url' => 'O link do LinkedIn deve ser uma URL válida.',
            'maps.url' => 'O link do Maps deve ser uma URL válida.',
            'form_email_to.email' => 'O email de destino deve ser um endereço válido.',
            'form_email_cc.email' => 'O email de cópia deve ser um endereço válido.',
            'email_port.integer' => 'A porta do email deve ser um número inteiro.',
            'email_port.min' => 'A porta do email deve ser maior que 0.',
            'email_port.max' => 'A porta do email deve ser menor que 65536.',
            'arquivo_lgpd.file' => 'O arquivo LGPD deve ser um arquivo válido.',
            'arquivo_lgpd.mimes' => 'O arquivo LGPD deve ser nos formatos: PDF, DOC ou DOCX.',
            'arquivo_lgpd.max' => 'O arquivo LGPD deve ter no máximo 2MB.',
        ]);

        // Upload do novo arquivo LGPD
        $arquivoLgpdPath = $config->arquivo_lgpd;
        if ($request->hasFile('arquivo_lgpd')) {
            // Deletar arquivo anterior se existir
            if ($config->arquivo_lgpd && Storage::disk('public')->exists('documentos/' . $config->arquivo_lgpd)) {
                Storage::disk('public')->delete('documentos/' . $config->arquivo_lgpd);
            }
            
            $arquivo = $request->file('arquivo_lgpd');
            $nomeArquivo = 'lgpd_' . time() . '.' . $arquivo->getClientOriginalExtension();
            $arquivo->storeAs('documentos', $nomeArquivo, 'public');
            $arquivoLgpdPath = $nomeArquivo;
        }

        $config->update([
            'celular' => $request->celular,
            'fone1' => $request->fone1,
            'fone2' => $request->fone2,
            'email' => $request->email,
            'endereco' => $request->endereco,
            'whatsapp' => $request->whatsapp,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
            'youtube' => $request->youtube,
            'linkedin' => $request->linkedin,
            'maps' => $request->maps,
            'form_email_to' => $request->form_email_to,
            'form_email_cc' => $request->form_email_cc,
            'email_port' => $request->email_port,
            'email_username' => $request->email_username,
            'email_password' => $request->email_password,
            'email_host' => $request->email_host,
            'texto_contrato' => $request->texto_contrato,
            'cnpj' => $request->cnpj,
            'expediente' => $request->expediente,
            'razao_social' => $request->razao_social,
            'arquivo_lgpd' => $arquivoLgpdPath,
            'texto_lgpd' => $request->texto_lgpd,
        ]);

        return redirect()->route('configs.edit')
                        ->with('success', 'Configurações atualizadas com sucesso!');
    }

    /**
     * Display the configuration.
     */
    public function show()
    {
        $config = Config::firstOrFail();
        return view('configs.show', compact('config'));
    }
} 