<?php
// Theme: Minimal - Clean design with square corners and subtle color accents
// Variables expected from views/checkout/show.php: 
// $product, $customizations, $primaryColor, $secondaryColor, $buttonText, etc.
?>

<div class="checkout-minimal">
    <div class="checkout-container">
        <div class="checkout-card">
            <div class="product-info">
                <div class="product-content">
                    <?php if ($showImage && !empty($logoUrl)): ?>
                    <div class="logo-container">
                        <img src="<?= htmlspecialchars($logoUrl) ?>" 
                            alt="<?= htmlspecialchars($product['title']) ?>" 
                            style="max-width: <?= $logoWidth ?>px; max-height: <?= $logoHeight ?>px;">
                    </div>
                    <?php endif; ?>
                    
                    <div class="product-details">
                        <h2 class="product-title"><?= htmlspecialchars($product['title']) ?></h2>
                        <?php if (!empty($product['description'])): ?>
                            <div class="product-description"><?= nl2br(htmlspecialchars($product['description'])) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="product-price">R$ <?= number_format($product['price'], 2, ',', '.') ?></div>
            </div>

            <form id="checkout-form" method="post" action="<?= BASE_URL ?>/checkout/payment/<?= (int)($product['id'] ?? 0) ?>">
                <div class="form-section">
                    <span class="section-label">Informações</span>
                    
                    <div class="form-group">
                        <input type="text" id="name" name="name" required placeholder="Nome completo">
                    </div>
                    
                    <div class="form-group">
                        <input type="email" id="email" name="email" required placeholder="Email">
                    </div>
                    
                    <?php if ($customerSettings['require_phone'] ?? false): ?>
                    <div class="form-group">
                        <input type="tel" id="phone" name="phone" required placeholder="Telefone">
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($customerSettings['require_cpf'] ?? false): ?>
                    <div class="form-group">
                        <input type="text" id="cpf" name="cpf" required placeholder="CPF">
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($customerSettings['require_address'] ?? false): ?>
                    <div class="form-group">
                        <input type="text" id="address" name="address" required placeholder="Endereço">
                    </div>
                    <div class="form-group address-group">
                        <input type="text" id="city" name="city" required placeholder="Cidade">
                        <input type="text" id="state" name="state" required placeholder="UF" maxlength="2">
                    </div>
                    <div class="form-group">
                        <input type="text" id="postal_code" name="postal_code" required placeholder="CEP">
                    </div>
                    <?php endif; ?>
                </div>

                <div class="form-section">
                    <span class="section-label">Pagamento</span>
                    
                    <div class="payment-methods">
                        <?php if (is_array($product['payment_methods']) && in_array('pix', $product['payment_methods'])): ?>
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="pix" checked>
                            <span class="method-box">
                                <span class="method-icon">
                                    <img src="<?= BASE_URL ?>/assets/img/pix-icon.png" alt="PIX">
                                </span>
                                <span class="method-name">PIX</span>
                            </span>
                        </label>
                        <?php endif; ?>
                        
                        <?php if (is_array($product['payment_methods']) && in_array('card', $product['payment_methods'])): ?>
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="card">
                            <span class="method-box">
                                <span class="method-icon">
                                    <img src="<?= BASE_URL ?>/assets/img/card-icon.png" alt="Cartão">
                                </span>
                                <span class="method-name">Cartão de Crédito</span>
                            </span>
                        </label>
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit" class="checkout-button">
                    <?= htmlspecialchars($buttonText) ?>
                </button>

                <div class="secure-checkout">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 11H5V21H19V11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17 7V11H7V7C7 5.93913 7.42143 4.92172 8.17157 4.17157C8.92172 3.42143 9.93913 3 11 3H13C14.0609 3 15.0783 3.42143 15.8284 4.17157C16.5786 4.92172 17 5.93913 17 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Pagamento seguro</span>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Minimal Theme - Clean design with square corners and subtle color accents */
body {
    background-color: var(--secondary-lighter);
}

.checkout-minimal {
    font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;
    color: #333;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.checkout-container {
    max-width: 550px;
    margin: 3rem auto;
    padding: 0 20px;
}

.checkout-card {
    background: white;
    border-radius: 4px;
    box-shadow: var(--box-shadow);
    border: 1px solid #eaeaea;
}

.product-info {
    padding: 2rem;
    border-bottom: 1px solid #eaeaea;
    background-color: #fcfcfc;
}

.product-content {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    gap: 1.5rem;
}

.logo-container {
    flex-shrink: 0;
    background-color: white;
    padding: 8px;
    border: 1px solid #f0f0f0;
    border-radius: 2px;
}

.product-details {
    flex-grow: 1;
}

.product-title {
    font-size: 1.2rem;
    font-weight: 500;
    margin-bottom: 0.6rem;
    color: #333;
    letter-spacing: -0.01em;
}

.product-description {
    font-size: 0.9rem;
    color: #666;
    line-height: 1.5;
}

.product-price {
    font-size: 1.3rem;
    font-weight: 500;
    color: var(--primary-color);
    text-align: right;
}

form {
    padding: 2rem;
}

.form-section {
    margin-bottom: 2rem;
    position: relative;
    border: 1px solid #eaeaea;
    padding: 1.5rem;
    padding-top: 2rem;
    border-radius: 2px;
    background-color: #fff;
}

.section-label {
    position: absolute;
    top: -0.7rem;
    left: 0.75rem;
    background: white;
    padding: 0 0.5rem;
    font-size: 0.75rem;
    color: #666;
    font-weight: 500;
    letter-spacing: 0.03em;
}

.form-group {
    margin-bottom: 1rem;
    position: relative;
}

.form-group input {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #e0e0e0;
    border-radius: 2px;
    background: white;
    font-size: 1rem;
    transition: all 0.15s ease;
}

.form-group input:focus {
    border-color: var(--primary-pastel);
    outline: none;
}

.payment-methods {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.payment-method {
    cursor: pointer;
}

.payment-method input {
    position: absolute;
    opacity: 0;
}

.method-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1rem;
    border: 1px solid #e0e0e0;
    border-radius: 2px;
    transition: all 0.15s ease;
    background-color: white;
}

.method-icon {
    width: 32px;
    height: 32px;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.method-icon img {
    max-width: 100%;
    max-height: 100%;
}

.method-name {
    font-size: 0.85rem;
    font-weight: 500;
    color: #333;
}

.payment-method input:checked + .method-box {
    border-color: var(--primary-pastel);
    background-color: var(--primary-ultra-light);
}

.checkout-button {
    width: 100%;
    padding: 0.9rem;
    background: var(--primary-pastel);
    color: #333;
    border: 0;
    border-radius: 2px;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s ease;
    letter-spacing: 0.03em;
}

.checkout-button:hover {
    background: var(--primary-color);
    color: white;
}

.secure-checkout {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 1.5rem;
    font-size: 0.85rem;
    color: #777;
    gap: 0.5rem;
    border-top: 1px solid #f0f0f0;
    padding-top: 1.2rem;
}

.secure-checkout svg {
    color: #777;
}

.address-group {
    display: flex;
    gap: 10px;
}

.address-group input:first-child {
    flex-grow: 3;
}

.address-group input:last-child {
    flex-grow: 1;
    max-width: 60px;
}

@media (max-width: 480px) {
    .product-content {
        flex-direction: column;
        text-align: center;
    }
    .product-price {
        text-align: center;
    }
    .payment-methods {
        grid-template-columns: 1fr;
    }
}
</style>
