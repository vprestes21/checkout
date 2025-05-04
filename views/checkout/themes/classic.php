<?php
// Ensure we have all required variables with fallbacks
$title = $title ?? 'Complete sua compra';
$description = $description ?? '';
$buttonText = $buttonText ?? 'Finalizar Compra';
$showImage = $showImage ?? false;
$product = $product ?? ['title' => '', 'description' => '', 'price' => 0];
$customizations = $customizations ?? [
    'payment' => [
        'methods' => [
            'pix' => ['enabled' => false, 'discount_percentage' => 0],
            'card' => ['enabled' => false, 'max_installments' => 1]
        ]
    ]
];

// Set product-specific customer info field requirements
$requireEmail = isset($product['require_email']) ? (bool)$product['require_email'] : true;
$requirePhone = isset($product['require_phone']) ? (bool)$product['require_phone'] : false;
$requireAddress = isset($product['require_address']) ? (bool)$product['require_address'] : false;
$requireCpf = isset($product['require_cpf']) ? (bool)$product['require_cpf'] : false;

$pixEnabled = $customizations['payment']['methods']['pix']['enabled'] ?? false;
$cardEnabled = $customizations['payment']['methods']['card']['enabled'] ?? false;
?>

<div class="checkout-classic">
    <div class="checkout-container">
        <div class="checkout-card">
            <div class="checkout-header">
                <h2>Finalizar Pedido</h2>
            </div>
            
            <div class="product-info">
                <?php if ($showImage && !empty($logoUrl)): ?>
                <div class="logo-container">
                    <img src="<?= htmlspecialchars($logoUrl) ?>" 
                         alt="<?= htmlspecialchars($product['title']) ?>" 
                         style="max-width: <?= $logoWidth ?>px; max-height: <?= $logoHeight ?>px;">
                </div>
                <?php endif; ?>
                
                <h3 class="product-title"><?= htmlspecialchars($product['title']) ?></h3>
                <?php if (!empty($product['description'])): ?>
                    <div class="product-description"><?= nl2br(htmlspecialchars($product['description'])) ?></div>
                <?php endif; ?>
                <div class="product-price">R$ <?= number_format($product['price'], 2, ',', '.') ?></div>
            </div>

            <form id="checkout-form" method="post" action="<?= BASE_URL ?>/checkout/payment/<?= (int)($product['id'] ?? 0) ?>">
                <fieldset>
                    <legend>Seus Dados</legend>
                    <div class="form-row">
                        <label for="name">Nome completo:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-row">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <?php if ($customerSettings['require_phone'] ?? false): ?>
                    <div class="form-row">
                        <label for="phone">Telefone:</label>
                        <input type="tel" id="phone" name="phone" required placeholder="(00) 00000-0000">
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($customerSettings['require_cpf'] ?? false): ?>
                    <div class="form-row">
                        <label for="cpf">CPF:</label>
                        <input type="text" id="cpf" name="cpf" required placeholder="000.000.000-00">
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($customerSettings['require_address'] ?? false): ?>
                    <div class="form-row">
                        <label for="address">Endereço:</label>
                        <input type="text" id="address" name="address" required placeholder="Rua, número">
                    </div>
                    <div class="form-row">
                        <label for="city">Cidade:</label>
                        <input type="text" id="city" name="city" required>
                    </div>
                    <div class="form-row">
                        <label for="state">Estado:</label>
                        <input type="text" id="state" name="state" required maxlength="2">
                    </div>
                    <div class="form-row">
                        <label for="postal_code">CEP:</label>
                        <input type="text" id="postal_code" name="postal_code" required placeholder="00000-000">
                    </div>
                    <?php endif; ?>
                </fieldset>

                <fieldset>
                    <legend>Método de pagamento</legend>
                    
                    <div class="payment-options">
                        <?php if (is_array($product['payment_methods']) && in_array('pix', $product['payment_methods'])): ?>
                        <div class="payment-option">
                            <input type="radio" id="pix" name="payment_method" value="pix" checked>
                            <label for="pix">
                                <img src="<?= BASE_URL ?>/assets/img/pix-icon.png" alt="PIX">
                                <span>PIX</span>
                            </label>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (is_array($product['payment_methods']) && in_array('card', $product['payment_methods'])): ?>
                        <div class="payment-option">
                            <input type="radio" id="card" name="payment_method" value="card">
                            <label for="card">
                                <img src="<?= BASE_URL ?>/assets/img/card-icon.png" alt="Cartão">
                                <span>Cartão de Crédito</span>
                            </label>
                        </div>
                        <?php endif; ?>
                    </div>
                </fieldset>

                <div class="checkout-actions">
                    <button type="submit" class="checkout-button">
                        <?= htmlspecialchars($buttonText) ?>
                    </button>
                </div>

                <div class="secure-checkout">
                    <img src="<?= BASE_URL ?>/assets/img/secure-icon.png" alt="Seguro" width="16" height="16">
                    Ambiente seguro e protegido
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Classic Theme - Traditional design with soft pastel colors */
.checkout-classic {
    font-family: Georgia, 'Times New Roman', Times, serif;
    color: #444;
    background-color: var(--secondary-ultra-light);
    padding: 20px 0;
}

.checkout-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.checkout-card {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: var(--box-shadow);
    overflow: hidden;
}

.checkout-header {
    background-color: var(--primary-pastel);
    color: var(--text-dark);
    padding: 1rem 2rem;
    text-align: center;
    border-bottom: 1px solid #e0e0e0;
}

.checkout-header h2 {
    margin: 0;
    font-size: 1.6rem;
    font-weight: normal;
}

.product-info {
    padding: 2rem;
    border-bottom: 1px solid #e0e0e0;
    text-align: center;
    background-color: #fcfcfc;
}

.logo-container {
    margin-bottom: 1.5rem;
    padding: 5px;
    display: inline-block;
}

.product-title {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #333;
}

.product-description {
    font-size: 1rem;
    color: #666;
    margin-bottom: 1.5rem;
    line-height: 1.5;
}

.product-price {
    font-size: 1.4rem;
    font-weight: bold;
    color: var(--primary-color);
    display: inline-block;
    padding: 5px 15px;
    background: var(--primary-ultra-light);
    border: 1px solid var(--primary-ultra-light);
    border-radius: 4px;
}

form {
    padding: 2rem;
    background: white;
}

fieldset {
    border: 1px solid #e0e0e0;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border-radius: 4px;
    background-color: #fcfcfc;
}

legend {
    font-weight: normal;
    padding: 0 10px;
    color: var(--primary-color);
    background-color: white;
    font-size: 1.1rem;
}

.form-row {
    margin-bottom: 1.2rem;
}

.form-row label {
    display: block;
    margin-bottom: 0.5rem;
    color: #444;
}

.form-row input {
    width: 100%;
    padding: 0.7rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-family: inherit;
    font-size: 1rem;
}

.form-row input:focus {
    border-color: var(--primary-pastel);
    box-shadow: 0 0 3px rgba(var(--primary-color-rgb), 0.15);
    outline: none;
}

.payment-options {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.payment-option {
    flex: 1;
    min-width: 120px;
}

.payment-option input[type=radio] {
    position: absolute;
    opacity: 0;
}

.payment-option label {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    background-color: white;
}

.payment-option label:hover {
    background-color: #f9f9f9;
}

.payment-option input[type=radio]:checked + label {
    border-color: var(--primary-pastel);
    background-color: var(--primary-ultra-light);
}

.payment-option label img {
    width: 32px;
    height: 32px;
    margin-bottom: 0.8rem;
}

.checkout-actions {
    margin-top: 2rem;
    text-align: center;
}

.checkout-button {
    background-color: var(--primary-pastel);
    color: var(--text-dark);
    border: none;
    padding: 0.8rem 2rem;
    font-size: 1.1rem;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-family: inherit;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.checkout-button:hover {
    background-color: var(--primary-color);
    color: white;
}

.secure-checkout {
    text-align: center;
    margin-top: 1.5rem;
    color: #666;
    font-size: 0.9rem;
}

.secure-checkout img {
    vertical-align: middle;
    margin-right: 0.5rem;
}

@media (max-width: 768px) {
    fieldset {
        padding: 1rem;
    }
    .payment-options {
        flex-direction: column;
    }
    .payment-option {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Remova o submit JS manual, deixe o form padrão
});
</script>
