<?php
// Extract data if available
$name = $data['name'] ?? '';
$description = $data['description'] ?? '';
$price = $data['price'] ?? '';
$image = $data['image'] ?? '';

// Checkout customization fields
$checkoutTitle = $data['checkout_title'] ?? 'Complete sua compra';
$checkoutDescription = $data['checkout_description'] ?? '';
$checkoutButtonText = $data['checkout_button_text'] ?? 'Finalizar Compra';
$primaryColor = $data['primary_color'] ?? '#3498db';
$secondaryColor = $data['secondary_color'] ?? '#f1c40f';
$checkoutBackground = $data['checkout_background'] ?? '#ffffff';
$showPix = $data['show_pix'] ?? true;
$showCard = $data['show_card'] ?? true;
$pixDiscount = $data['pix_discount'] ?? 0;
$cardInstallments = $data['card_installments'] ?? 1;
$cardInterest = $data['card_interest'] ?? 0;
$requireAddress = $data['require_address'] ?? true;
$requirePhone = $data['require_phone'] ?? true;
$checkoutModel = $data['checkout_model'] ?? 'classic';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Produto</title>
    <link rel="stylesheet" href="/checkout/assets/css/admin.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .form-section h2 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 8px 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .color-picker {
            height: 40px;
            padding: 5px;
            cursor: pointer;
        }
        
        .checkbox-group {
            margin-top: 8px;
        }
        
        .checkbox-label {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            cursor: pointer;
        }
        
        .checkbox-label input {
            margin-right: 10px;
        }
        
        .btn-primary {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
        }
        
        .theme-preview {
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            height: 200px;
            background-color: #f9f9f9;
            position: relative;
            overflow: hidden;
        }
        
        .theme-preview-item {
            width: 100%;
            height: 100%;
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            transition: opacity 0.3s ease;
            position: absolute;
            top: 0;
            left: 0;
        }
        
        .theme-classic-preview {
            background-image: url('/checkout/assets/img/theme-classic.jpg');
        }
        
        .theme-modern-preview {
            background-image: url('/checkout/assets/img/theme-modern.jpg');
        }
        
        .theme-minimalist-preview {
            background-image: url('/checkout/assets/img/theme-minimalist.jpg');
        }
        
        .theme-active {
            opacity: 1;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-col {
            flex: 1;
        }
        
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Criar Novo Produto</h1>
        
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
                <?= $_SESSION['flash']['message'] ?>
            </div>
        <?php endif; ?>
        
        <form action="/checkout/create" method="post" class="form-container">
            <!-- Product Information -->
            <div class="form-section">
                <h2>Informações do Produto</h2>
                
                <div class="form-group">
                    <label for="name">Nome do Produto*</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= $name ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Descrição</label>
                    <textarea id="description" name="description" class="form-control" rows="3"><?= $description ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="price">Preço*</label>
                    <input type="number" id="price" name="price" step="0.01" class="form-control" value="<?= $price ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="image">URL da Imagem</label>
                    <input type="text" id="image" name="image" class="form-control" value="<?= $image ?>" placeholder="https://exemplo.com/imagem.jpg">
                </div>
            </div>
            
            <!-- Checkout Appearance -->
            <div class="form-section">
                <h2>Aparência do Checkout</h2>
                
                <div class="form-group">
                    <label for="checkout_model">Modelo de Checkout</label>
                    <select id="checkout_model" name="checkout_model" class="form-control">
                        <option value="minimalist" <?= $checkoutModel === 'minimalist' ? 'selected' : '' ?>>Minimalista</option>
                        <option value="classic" <?= $checkoutModel === 'classic' ? 'selected' : '' ?>>Clássico</option>
                        <option value="modern" <?= $checkoutModel === 'modern' ? 'selected' : '' ?>>Moderno</option>
                    </select>
                </div>
                
                <div class="theme-preview">
                    <div class="theme-preview-item theme-minimalist-preview <?= $checkoutModel === 'minimalist' ? 'theme-active' : '' ?>"></div>
                    <div class="theme-preview-item theme-classic-preview <?= $checkoutModel === 'classic' ? 'theme-active' : '' ?>"></div>
                    <div class="theme-preview-item theme-modern-preview <?= $checkoutModel === 'modern' ? 'theme-active' : '' ?>"></div>
                </div>
                
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="primary_color">Cor Primária</label>
                            <input type="color" id="primary_color" name="primary_color" class="color-picker" value="<?= $primaryColor ?>">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="secondary_color">Cor Secundária</label>
                            <input type="color" id="secondary_color" name="secondary_color" class="color-picker" value="<?= $secondaryColor ?>">
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="checkout_background">Cor de Fundo</label>
                            <input type="color" id="checkout_background" name="checkout_background" class="color-picker" value="<?= $checkoutBackground ?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="checkout_title">Título do Checkout</label>
                    <input type="text" id="checkout_title" name="checkout_title" class="form-control" value="<?= $checkoutTitle ?>">
                </div>
                
                <div class="form-group">
                    <label for="checkout_description">Descrição do Checkout</label>
                    <textarea id="checkout_description" name="checkout_description" class="form-control" rows="2"><?= $checkoutDescription ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="checkout_button_text">Texto do Botão</label>
                    <input type="text" id="checkout_button_text" name="checkout_button_text" class="form-control" value="<?= $checkoutButtonText ?>">
                </div>
                
                <div class="form-group">
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="show_product_image" <?= isset($data['show_product_image']) ? 'checked' : '' ?>>
                            Mostrar imagem do produto no checkout
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Payment Methods -->
            <div class="form-section">
                <h2>Métodos de Pagamento</h2>
                
                <div class="form-group">
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="show_pix" <?= $showPix ? 'checked' : '' ?> id="show_pix">
                            Aceitar pagamento via PIX
                        </label>
                    </div>
                </div>
                
                <div class="pix-options" style="padding-left: 20px;">
                    <div class="form-group">
                        <label for="pix_discount">Desconto para PIX (%)</label>
                        <input type="number" id="pix_discount" name="pix_discount" step="0.01" min="0" max="100" class="form-control" value="<?= $pixDiscount ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="pix_discount_text">Texto do Desconto PIX</label>
                        <input type="text" id="pix_discount_text" name="pix_discount_text" class="form-control" value="<?= $data['pix_discount_text'] ?? 'Economize {discount}% pagando com PIX' ?>">
                        <small>Use {discount} para inserir a porcentagem de desconto</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="show_card" <?= $showCard ? 'checked' : '' ?> id="show_card">
                            Aceitar pagamento com Cartão
                        </label>
                    </div>
                </div>
                
                <div class="card-options" style="padding-left: 20px;">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="card_installments">Máximo de Parcelas</label>
                                <input type="number" id="card_installments" name="card_installments" min="1" max="12" class="form-control" value="<?= $cardInstallments ?>">
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="card_interest">Taxa de Juros (% ao mês)</label>
                                <input type="number" id="card_interest" name="card_interest" step="0.01" min="0" class="form-control" value="<?= $cardInterest ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="card_interest_text">Texto de Juros</label>
                        <input type="text" id="card_interest_text" name="card_interest_text" class="form-control" value="<?= $data['card_interest_text'] ?? 'Juros de {interest}% ao mês' ?>">
                        <small>Use {interest} para inserir a taxa de juros</small>
                    </div>
                </div>
            </div>
            
            <!-- Customer Information Requirements -->
            <div class="form-section">
                <h2>Informações do Cliente</h2>
                
                <div class="form-group">
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="require_address" <?= $requireAddress ? 'checked' : '' ?>>
                            Exigir endereço completo
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="require_phone" <?= $requirePhone ? 'checked' : '' ?>>
                            Exigir número de telefone
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Redirect URLs -->
            <div class="form-section">
                <h2>URLs de Redirecionamento</h2>
                
                <div class="form-group">
                    <label for="success_url">URL de Sucesso</label>
                    <input type="url" id="success_url" name="success_url" class="form-control" value="<?= $data['success_url'] ?? '' ?>" placeholder="https://seusite.com/sucesso">
                    <small>Cliente será redirecionado para esta URL após pagamento bem-sucedido</small>
                </div>
                
                <div class="form-group">
                    <label for="cancel_url">URL de Cancelamento</label>
                    <input type="url" id="cancel_url" name="cancel_url" class="form-control" value="<?= $data['cancel_url'] ?? '' ?>" placeholder="https://seusite.com/cancelado">
                </div>
                
                <div class="form-group">
                    <label for="terms_url">URL dos Termos e Condições</label>
                    <input type="url" id="terms_url" name="terms_url" class="form-control" value="<?= $data['terms_url'] ?? '' ?>" placeholder="https://seusite.com/termos">
                </div>
            </div>
            
            <button type="submit" class="btn-primary">Criar Produto</button>
        </form>
    </div>
    
    <script>
        // Toggle PIX options visibility
        document.getElementById('show_pix').addEventListener('change', function() {
            const pixOptions = document.querySelector('.pix-options');
            pixOptions.style.display = this.checked ? 'block' : 'none';
        });
        
        // Toggle card options visibility
        document.getElementById('show_card').addEventListener('change', function() {
            const cardOptions = document.querySelector('.card-options');
            cardOptions.style.display = this.checked ? 'block' : 'none';
        });
        
        // Initialize option visibility
        document.addEventListener('DOMContentLoaded', function() {
            const pixOptions = document.querySelector('.pix-options');
            pixOptions.style.display = document.getElementById('show_pix').checked ? 'block' : 'none';
            
            const cardOptions = document.querySelector('.card-options');
            cardOptions.style.display = document.getElementById('show_card').checked ? 'block' : 'none';
        });
        
        // Switch theme preview
        document.getElementById('checkout_model').addEventListener('change', function() {
            const minimalistPreview = document.querySelector('.theme-minimalist-preview');
            const classicPreview = document.querySelector('.theme-classic-preview');
            const modernPreview = document.querySelector('.theme-modern-preview');
            
            // Hide all previews first
            minimalistPreview.classList.remove('theme-active');
            classicPreview.classList.remove('theme-active');
            modernPreview.classList.remove('theme-active');
            
            // Show the selected preview
            if (this.value === 'minimalist') {
                minimalistPreview.classList.add('theme-active');
            } else if (this.value === 'classic') {
                classicPreview.classList.add('theme-active');
            } else if (this.value === 'modern') {
                modernPreview.classList.add('theme-active');
            }
        });
    </script>
</body>
</html>
