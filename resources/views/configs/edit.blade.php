@extends('adminlte::page')

@section('title', 'Configurações do Sistema')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Configurações do Sistema</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
    </div>
@stop

@section('content')
    <form action="{{ route('configs.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Dados da Empresa -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-building"></i> Dados da Empresa</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="razao_social">Razão Social</label>
                            <input type="text" class="form-control @error('razao_social') is-invalid @enderror" 
                                   id="razao_social" name="razao_social" 
                                   value="{{ old('razao_social', $config->razao_social) }}"
                                   placeholder="Ex: Empresa LTDA">
                            @error('razao_social')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cnpj">CNPJ</label>
                            <input type="text" class="form-control @error('cnpj') is-invalid @enderror" 
                                   id="cnpj" name="cnpj" value="{{ old('cnpj', $config->cnpj) }}"
                                   placeholder="Ex: 00.000.000/0001-00">
                            @error('cnpj')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="endereco">Endereço</label>
                            <input type="text" class="form-control @error('endereco') is-invalid @enderror" 
                                   id="endereco" name="endereco" value="{{ old('endereco', $config->endereco) }}"
                                   placeholder="Ex: Rua das Flores, 123 - Centro">
                            @error('endereco')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="expediente">Expediente</label>
                            <input type="text" class="form-control @error('expediente') is-invalid @enderror" 
                                   id="expediente" name="expediente" value="{{ old('expediente', $config->expediente) }}"
                                   placeholder="Ex: Segunda a Sexta - 8h às 18h">
                            @error('expediente')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contatos -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-phone"></i> Contatos</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email Principal</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $config->email) }}"
                                   placeholder="Ex: contato@empresa.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="celular">Celular</label>
                            <input type="text" class="form-control @error('celular') is-invalid @enderror" 
                                   id="celular" name="celular" value="{{ old('celular', $config->celular) }}"
                                   placeholder="Ex: (11) 99999-9999">
                            @error('celular')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fone1">Telefone 1</label>
                            <input type="text" class="form-control @error('fone1') is-invalid @enderror" 
                                   id="fone1" name="fone1" value="{{ old('fone1', $config->fone1) }}"
                                   placeholder="Ex: (11) 3333-3333">
                            @error('fone1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fone2">Telefone 2</label>
                            <input type="text" class="form-control @error('fone2') is-invalid @enderror" 
                                   id="fone2" name="fone2" value="{{ old('fone2', $config->fone2) }}"
                                   placeholder="Ex: (11) 3333-3334">
                            @error('fone2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="whatsapp">WhatsApp</label>
                            <input type="text" class="form-control @error('whatsapp') is-invalid @enderror" 
                                   id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $config->whatsapp) }}"
                                   placeholder="Ex: (11) 99999-9999">
                            @error('whatsapp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Redes Sociais -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-share-alt"></i> Redes Sociais</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="facebook">
                                <i class="fab fa-facebook text-primary"></i> Facebook
                            </label>
                            <input type="url" class="form-control @error('facebook') is-invalid @enderror" 
                                   id="facebook" name="facebook" value="{{ old('facebook', $config->facebook) }}"
                                   placeholder="Ex: https://facebook.com/empresa">
                            @error('facebook')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="instagram">
                                <i class="fab fa-instagram text-danger"></i> Instagram
                            </label>
                            <input type="url" class="form-control @error('instagram') is-invalid @enderror" 
                                   id="instagram" name="instagram" value="{{ old('instagram', $config->instagram) }}"
                                   placeholder="Ex: https://instagram.com/empresa">
                            @error('instagram')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="twitter">
                                <i class="fab fa-twitter text-info"></i> Twitter
                            </label>
                            <input type="url" class="form-control @error('twitter') is-invalid @enderror" 
                                   id="twitter" name="twitter" value="{{ old('twitter', $config->twitter) }}"
                                   placeholder="Ex: https://twitter.com/empresa">
                            @error('twitter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="youtube">
                                <i class="fab fa-youtube text-danger"></i> YouTube
                            </label>
                            <input type="url" class="form-control @error('youtube') is-invalid @enderror" 
                                   id="youtube" name="youtube" value="{{ old('youtube', $config->youtube) }}"
                                   placeholder="Ex: https://youtube.com/empresa">
                            @error('youtube')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="linkedin">
                                <i class="fab fa-linkedin text-primary"></i> LinkedIn
                            </label>
                            <input type="url" class="form-control @error('linkedin') is-invalid @enderror" 
                                   id="linkedin" name="linkedin" value="{{ old('linkedin', $config->linkedin) }}"
                                   placeholder="Ex: https://linkedin.com/company/empresa">
                            @error('linkedin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="maps">
                                <i class="fas fa-map-marker-alt text-success"></i> Google Maps
                            </label>
                            <input type="url" class="form-control @error('maps') is-invalid @enderror" 
                                   id="maps" name="maps" value="{{ old('maps', $config->maps) }}"
                                   placeholder="Ex: https://maps.google.com/...">
                            @error('maps')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configurações de Email -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-envelope"></i> Configurações de Email</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="form_email_to">Email de Destino (Formulários)</label>
                            <input type="email" class="form-control @error('form_email_to') is-invalid @enderror" 
                                   id="form_email_to" name="form_email_to" 
                                   value="{{ old('form_email_to', $config->form_email_to) }}"
                                   placeholder="Ex: contato@empresa.com">
                            @error('form_email_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="form_email_cc">Email de Cópia (CC)</label>
                            <input type="email" class="form-control @error('form_email_cc') is-invalid @enderror" 
                                   id="form_email_cc" name="form_email_cc" 
                                   value="{{ old('form_email_cc', $config->form_email_cc) }}"
                                   placeholder="Ex: backup@empresa.com">
                            @error('form_email_cc')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email_host">Servidor SMTP</label>
                            <input type="text" class="form-control @error('email_host') is-invalid @enderror" 
                                   id="email_host" name="email_host" 
                                   value="{{ old('email_host', $config->email_host) }}"
                                   placeholder="Ex: smtp.gmail.com">
                            @error('email_host')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email_port">Porta SMTP</label>
                            <input type="number" class="form-control @error('email_port') is-invalid @enderror" 
                                   id="email_port" name="email_port" 
                                   value="{{ old('email_port', $config->email_port) }}"
                                   placeholder="Ex: 587" min="1" max="65535">
                            @error('email_port')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email_username">Usuário SMTP</label>
                            <input type="text" class="form-control @error('email_username') is-invalid @enderror" 
                                   id="email_username" name="email_username" 
                                   value="{{ old('email_username', $config->email_username) }}"
                                   placeholder="Ex: usuario@empresa.com">
                            @error('email_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email_password">Senha SMTP</label>
                            <input type="password" class="form-control @error('email_password') is-invalid @enderror" 
                                   id="email_password" name="email_password" 
                                   value="{{ old('email_password', $config->email_password) }}"
                                   placeholder="Digite a senha do email">
                            @error('email_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Textos e Documentos -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-alt"></i> Textos e Documentos</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="texto_contrato">Texto do Contrato</label>
                            <textarea class="form-control @error('texto_contrato') is-invalid @enderror" 
                                      id="texto_contrato" name="texto_contrato" rows="5"
                                      placeholder="Digite o texto padrão do contrato...">{{ old('texto_contrato', $config->texto_contrato) }}</textarea>
                            @error('texto_contrato')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="texto_lgpd">Texto da LGPD</label>
                            <textarea class="form-control @error('texto_lgpd') is-invalid @enderror" 
                                      id="texto_lgpd" name="texto_lgpd" rows="5"
                                      placeholder="Digite o texto sobre LGPD...">{{ old('texto_lgpd', $config->texto_lgpd) }}</textarea>
                            @error('texto_lgpd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="arquivo_lgpd">Arquivo da LGPD</label>
                            
                            @if($config->arquivo_lgpd)
                                <div class="mb-2">
                                    <div class="alert alert-info">
                                        <i class="fas fa-file-pdf"></i>
                                        <strong>Arquivo atual:</strong> {{ $config->arquivo_lgpd }}
                                        <a href="{{ asset('storage/documentos/' . $config->arquivo_lgpd) }}" 
                                           target="_blank" class="btn btn-sm btn-outline-primary ml-2">
                                            <i class="fas fa-download"></i> Baixar
                                        </a>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('arquivo_lgpd') is-invalid @enderror" 
                                           id="arquivo_lgpd" name="arquivo_lgpd" accept=".pdf,.doc,.docx">
                                    <label class="custom-file-label" for="arquivo_lgpd">
                                        {{ $config->arquivo_lgpd ? 'Escolher novo arquivo' : 'Escolher arquivo' }}
                                    </label>
                                </div>
                            </div>
                            @error('arquivo_lgpd')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Formatos aceitos: PDF, DOC, DOCX. Tamanho máximo: 2MB.
                                @if($config->arquivo_lgpd)
                                    <br>Deixe em branco para manter o arquivo atual.
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botões -->
        <div class="card">
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar Configurações
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </div>
    </form>
@stop

@section('css')
    <style>
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .card-header {
            background-color: #f8f9fa;
        }
        
        .fab {
            margin-right: 5px;
        }
        
        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }
    </style>
@stop

@section('js')
    <script>
        $(function() {
            // Atualizar label do arquivo
            $('#arquivo_lgpd').on('change', function() {
                const fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').text(fileName || 'Escolher arquivo');
            });
            
            // Focar no primeiro campo
            $('#razao_social').focus();
        });
    </script>
@stop 