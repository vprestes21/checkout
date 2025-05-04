<?php
$title = 'Criar Novo Produto - CheckoutPro';
ob_start();
?>

<div class="container py-4">
    <h1 class="mb-4">Criar Novo Produto</h1>
    
    <div class="card dashboard-card">
        <div class="card-body">
            <form action="<?= BASE_URL ?>/product/create" method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label for="title" class="form-label">Título do Produto *</label>
                        <input type="text" class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>" 
                               id="title" name="title" value="<?= $old['title'] ?? '' ?>" required>
                        <?php if(isset($errors['title'])): ?>
                            <div class="invalid-feedback"><?= $errors['title'] ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <label for="price" class="form-label">Preço (R$) *</label>
                        <input type="number" step="0.01" min="1" class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>" 
                               id="price" name="price" value="<?= $old['price'] ?? '' ?>" required>
                        <?php if(isset($errors['price'])): ?>
                            <div class="invalid-feedback"><?= $errors['price'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea class="form-control" id="description" name="description" rows="5"><?= $old['description'] ?? '' ?></textarea>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Logo do Produto</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="logo_url" class="form-label">URL da Imagem</label>
                                    <input type="url" class="form-control <?= isset($errors['logo_url']) ? 'is-invalid' : '' ?>" 
                                           id="logo_url" name="logo_url" value="<?= $old['logo_url'] ?? '' ?>">
                                    <div class="form-text">Insira a URL de uma imagem online para usar como logo</div>
                                    <?php if(isset($errors['logo_url'])): ?>
                                        <div class="invalid-feedback"><?= $errors['logo_url'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="logo_width" class="form-label">Largura (px)</label>
                                        <input type="number" class="form-control" id="logo_width" name="logo_width" 
                                               value="<?= $old['logo_width'] ?? 200 ?>" min="50" max="800">
                                    </div>
                                    <div class="col-6">
                                        <label for="logo_height" class="form-label">Altura (px)</label>
                                        <input type="number" class="form-control" id="logo_height" name="logo_height" 
                                               value="<?= $old['logo_height'] ?? 200 ?>" min="50" max="800">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <div id="logo-preview" class="text-center p-3" style="border: 1px dashed #ccc; display: none;">
                                <img id="preview-img" src="" alt="Preview" style="max-width: 100%; max-height: 200px;">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Métodos de Pagamento</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Selecione as opções de pagamento *</label>
                            <?php if(isset($errors['payment_methods'])): ?>
                                <div class="text-danger mb-2"><?= $errors['payment_methods'] ?></div>
                            <?php endif; ?>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="payment_methods[]" value="pix" id="pix" 
                                       <?= isset($old['payment_methods']) && in_array('pix', $old['payment_methods']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="pix">PIX</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="payment_methods[]" value="card" id="card" 
                                       <?= isset($old['payment_methods']) && in_array('card', $old['payment_methods']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="card">Cartão de Crédito</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Personalização</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="template" class="form-label">Modelo de Checkout</label>
                                    <select class="form-select" id="template" name="template">
                                        <option value="modern" <?= ($old['template'] ?? 'modern') == 'modern' ? 'selected' : '' ?>>Moderno</option>
                                        <option value="classic" <?= ($old['template'] ?? '') == 'classic' ? 'selected' : '' ?>>Clássico</option>
                                        <option value="minimal" <?= ($old['template'] ?? '') == 'minimal' ? 'selected' : '' ?>>Minimalista</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="primary_color" class="form-label">Cor Primária</label>
                                    <input type="color" class="form-control form-control-color w-100" id="primary_color" 
                                           name="primary_color" value="<?= $old['primary_color'] ?? '#3490dc' ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="secondary_color" class="form-label">Cor Secundária</label>
                                    <input type="color" class="form-control form-control-color w-100" id="secondary_color" 
                                           name="secondary_color" value="<?= $old['secondary_color'] ?? '#38c172' ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Informações do Cliente -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Informações do Cliente</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="text-muted mb-3">Escolha quais informações serão solicitadas durante o checkout:</p>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="require_email" name="require_email"
                                    <?= isset($old['require_email']) ? ($old['require_email'] ? 'checked' : '') : 'checked' ?>>
                                <label class="form-check-label" for="require_email">Email do comprador</label>
                                <small class="form-text text-muted d-block">Email é sempre obrigatório para notificações.</small>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="require_phone" name="require_phone"
                                    <?= !empty($old['require_phone']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="require_phone">Telefone do comprador</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="require_cpf" name="require_cpf"
                                    <?= !empty($old['require_cpf']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="require_cpf">CPF do comprador</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="require_address" name="require_address"
                                    <?= !empty($old['require_address']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="require_address">Endereço completo</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Configuração de UTMs -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Configuração de UTMs</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="text-muted mb-3">Configure o rastreamento de campanhas usando parâmetros UTM:</p>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="enable_utm" name="enable_utm"
                                    <?= isset($old['enable_utm']) ? ($old['enable_utm'] ? 'checked' : '') : 'checked' ?>>
                                <label class="form-check-label" for="enable_utm">Habilitar rastreamento de UTMs</label>
                                <small class="form-text text-muted d-block">Rastreie a origem dos seus clientes (ex: Google, Facebook)</small>
                            </div>
                            <div class="mt-3 utm-settings" <?= isset($old['enable_utm']) && !$old['enable_utm'] ? 'style="display:none;"' : '' ?>>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="default_utm_source" class="form-label">UTM Source padrão</label>
                                            <input type="text" class="form-control" id="default_utm_source" name="default_utm_source"
                                                value="<?= $old['default_utm_source'] ?? '' ?>" placeholder="Ex: direct">
                                            <small class="form-text text-muted">Usado quando não há UTM na URL</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="utm_api_key" class="form-label">Chave API UTM.io (opcional)</label>
                                            <input type="text" class="form-control" id="utm_api_key" name="utm_api_key"
                                                value="<?= $old['utm_api_key'] ?? '' ?>" placeholder="Chave API para integrações">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Conteúdo de Entrega -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Conteúdo de Entrega</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="delivery_type" class="form-label">Tipo de Conteúdo *</label>
                            <select class="form-select" id="delivery_type" name="delivery_type" required onchange="toggleDeliveryFields()">
                                <option value="">Selecione...</option>
                                <option value="link" <?= ($old['delivery_type'] ?? '') == 'link' ? 'selected' : '' ?>>Link para vídeo/arquivo</option>
                                <option value="upload" <?= ($old['delivery_type'] ?? '') == 'upload' ? 'selected' : '' ?>>Arquivo para download</option>
                                <option value="text" <?= ($old['delivery_type'] ?? '') == 'text' ? 'selected' : '' ?>>Mensagem personalizada</option>
                            </select>
                        </div>
                        <div class="mb-3 delivery-field delivery-link" style="display:none;">
                            <label for="delivery_link" class="form-label">URL do Conteúdo</label>
                            <input type="url" class="form-control" id="delivery_link" name="delivery_link" value="<?= $old['delivery_link'] ?? '' ?>">
                            <div class="form-text">Exemplo: https://youtube.com/...</div>
                        </div>
                        <div class="mb-3 delivery-field delivery-upload" style="display:none;">
                            <label for="delivery_file" class="form-label">Arquivo para Download</label>
                            <input type="file" class="form-control" id="delivery_file" name="delivery_file">
                            <div class="form-text">Envie um arquivo para entregar após a compra.</div>
                        </div>
                        <div class="mb-3 delivery-field delivery-text" style="display:none;">
                            <label for="delivery_text" class="form-label">Mensagem personalizada</label>
                            <textarea class="form-control" id="delivery_text" name="delivery_text" rows="3"><?= $old['delivery_text'] ?? '' ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="<?= BASE_URL ?>/product/index" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Criar Produto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Preview da logo
    document.getElementById('logo_url').addEventListener('input', function() {
        const url = this.value;
        const previewContainer = document.getElementById('logo-preview');
        const previewImg = document.getElementById('preview-img');
        
        if (url) {
            previewImg.src = url;
            previewContainer.style.display = 'block';
            
            // Verificar se a imagem carregou corretamente
            previewImg.onload = function() {
                previewContainer.style.display = 'block';
            };
            
            previewImg.onerror = function() {
                previewContainer.style.display = 'none';
            };
        } else {
            previewContainer.style.display = 'none';
        }
    });
    
    // Trigger preview if URL is already filled
    if (document.getElementById('logo_url').value) {
        document.getElementById('logo_url').dispatchEvent(new Event('input'));
    }
    
    function toggleDeliveryFields() {
        var type = document.getElementById('delivery_type').value;
        document.querySelector('.delivery-link').style.display = (type === 'link') ? 'block' : 'none';
        document.querySelector('.delivery-upload').style.display = (type === 'upload') ? 'block' : 'none';
        document.querySelector('.delivery-text').style.display = (type === 'text') ? 'block' : 'none';
    }
    document.getElementById('delivery_type').addEventListener('change', toggleDeliveryFields);
    window.addEventListener('DOMContentLoaded', function() { toggleDeliveryFields(); });
    
    document.getElementById('enable_utm').addEventListener('change', function() {
        const utmSettings = document.querySelector('.utm-settings');
        if (this.checked) {
            utmSettings.style.display = 'block';
        } else {
            utmSettings.style.display = 'none';
        }
    });
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>
