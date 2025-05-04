<?php
// Theme: Modern - Ultra modern with rounded corners and pastel colors
?>

<div class="checkout-modern">
    <div class="checkout-container">
        <div class="checkout-card">
            <div class="product-info">                
                <h2 class="product-title"><?= htmlspecialchars($product['title']) ?></h2>
                <?php if (!empty($product['description'])): ?>
                    <div class="product-description"><?= nl2br(htmlspecialchars($product['description'])) ?></div>
                <?php endif; ?>
                <div class="product-price">R$ <?= number_format($product['price'], 2, ',', '.') ?></div>
            </div>

            <form id="checkout-form" method="post" action="<?= BASE_URL ?>/checkout/payment/<?= (int)($product['id'] ?? 0) ?>">
                <div class="form-section">
                    <h3 class="section-title">Suas informações</h3>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="name">Nome completo</label>
                            <input type="text" id="name" name="name" required class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required class="form-control">
                        </div>
                    </div>
                    
                    <?php if ($customerSettings['require_phone'] ?? false): ?>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="phone">Telefone</label>
                            <input type="tel" id="phone" name="phone" required class="form-control" 
                                   placeholder="(00) 00000-0000">
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($customerSettings['require_cpf'] ?? false): ?>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="cpf">CPF</label>
                            <input type="text" id="cpf" name="cpf" required class="form-control" 
                                   placeholder="000.000.000-00">
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($customerSettings['require_address'] ?? false): ?>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="address">Endereço</label>
                            <input type="text" id="address" name="address" required class="form-control" 
                                   placeholder="Rua, número">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="width: 60%">
                            <label for="city">Cidade</label>
                            <input type="text" id="city" name="city" required class="form-control">
                        </div>
                        <div class="form-group" style="width: 40%">
                            <label for="state">Estado</label>
                            <input type="text" id="state" name="state" required class="form-control" maxlength="2">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="width: 40%">
                            <label for="postal_code">CEP</label>
                            <input type="text" id="postal_code" name="postal_code" required class="form-control" 
                                   placeholder="00000-000">
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="form-section">
                    <h3 class="section-title">Método de pagamento</h3>
                    <?php if (is_array($product['payment_methods']) && in_array('pix', $product['payment_methods'])): ?>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="pix" required>
                        <div class="payment-option-inner">
                            <span class="payment-icon">
                                <img src="<?= BASE_URL ?>/assets/img/pix-icon.png" alt="PIX">
                            </span>
                            <span class="payment-label">PIX</span>
                            <span class="payment-check"></span>
                        </div>
                    </label>
                    <?php endif; ?>
                    <?php if (is_array($product['payment_methods']) && in_array('card', $product['payment_methods'])): ?>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="card" required>
                        <div class="payment-option-inner">
                            <span class="payment-icon">
                                <img src="<?= BASE_URL ?>/assets/img/card-icon.png" alt="Cartão">
                            </span>
                            <span class="payment-label">Cartão de Crédito</span>
                            <span class="payment-check"></span>
                        </div>
                    </label>
                    <?php endif; ?>
                </div>

                <button type="submit" class="checkout-button">
                    <span><?= htmlspecialchars($buttonText) ?></span>
                </button>

                <div class="secure-checkout">
                    <i class="fas fa-lock"></i> Ambiente seguro e protegido
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modern Theme - Soft pastel colors with rounded corners */
.checkout-modern {
    font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--text-dark);
}

.checkout-container {
    max-width: 900px;
    margin: 2rem auto;
    padding: 0 20px;
}

.checkout-card {
    background: white;
    border-radius: 20px;
    box-shadow: var(--box-shadow);
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.04);
}

.product-info {
    padding: 3rem;
    background: linear-gradient(135deg, white, var(--primary-ultra-light));
    border-bottom: 1px solid var(--primary-ultra-light);
    text-align: center;
}

.logo-container {
    margin-bottom: 1.5rem;
}

.product-title {
    font-weight: 600;
    font-size: 2rem;
    margin-bottom: 1rem;
    color: var(--text-dark);
}

.product-description {
    font-size: 1.1rem;
    color: var(--text-muted);
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.product-price {
    font-size: 2rem;
    font-weight: 600;
    color: var(--primary-color);
    display: inline-block;
    padding: 0.3rem 2rem;
    background-color: var(--primary-ultra-light);
    border-radius: 15px;
}

form {
    padding: 2.5rem 3rem 3rem;
}

.form-section {
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: var(--text-dark);
    position: relative;
    padding-bottom: 0.75rem;
    display: inline-block;
}

.section-title:after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    height: 2px;
    width: 100%;
    background: var(--secondary-pastel);
    border-radius: 2px;
}

.form-row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -10px 1rem;
}

.form-group {
    padding: 0 10px;
    margin-bottom: 1.25rem;
}

.form-group.full-width {
    width: 100%;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    font-size: 0.95rem;
    color: var(--text-dark);
}

.form-control {
    width: 100%;
    padding: 0.9rem 1.2rem;
    font-size: 1rem;
    border-radius: 12px;
    border: 1px solid rgba(0, 0, 0, 0.1);
    background: white;
    transition: all 0.25s ease;
}

.form-control:focus {
    border-color: var(--secondary-pastel);
    box-shadow: 0 0 0 3px rgba(var(--secondary-color-rgb), 0.1);
    outline: none;
}

.payment-option {
    display: block;
    margin-bottom: 1rem;
    cursor: pointer;
}

.payment-option input {
    position: absolute;
    opacity: 0;
}

.payment-option-inner {
    display: flex;
    align-items: center;
    padding: 1.2rem 1.5rem;
    border-radius: 12px;
    border: 1px solid rgba(0, 0, 0, 0.1);
    background: white;
    transition: all 0.25s ease;
}

.payment-option input:checked + .payment-option-inner {
    border-color: var(--primary-pastel);
    background: var(--primary-ultra-light);
    box-shadow: 0 0 0 1px var(--primary-pastel);
}

.payment-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    background: var(--secondary-ultra-light);
    padding: 5px;
    border-radius: 50%;
}

.payment-icon img {
    max-width: 100%;
    max-height: 100%;
}

.payment-label {
    font-weight: 500;
    font-size: 1.1rem;
    flex-grow: 1;
}

.payment-check {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid rgba(0, 0, 0, 0.1);
    position: relative;
}

.payment-option input:checked + .payment-option-inner .payment-check {
    border-color: var(--primary-pastel);
    background: var(--primary-pastel);
}

.payment-option input:checked + .payment-option-inner .payment-check:after {
    content: '';
    position: absolute;
    left: 7px;
    top: 3px;
    width: 6px;
    height: 12px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.checkout-button {
    display: block;
    width: 100%;
    padding: 1.2rem;
    font-size: 1.1rem;
    font-weight: 600;
    text-align: center;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    cursor: pointer;
    margin-top: 2rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(var(--primary-color-rgb), 0.2);
    transition: all 0.3s ease;
}

.checkout-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(var(--primary-color-rgb), 0.25);
}

.secure-checkout {
    text-align: center;
    margin-top: 1.5rem;
    color: var(--text-muted);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.secure-checkout i {
    margin-right: 0.5rem;
    color: #28a745;
}

@media (max-width: 768px) {
    .checkout-card {
        border-radius: 15px;
    }
    .product-info {
        padding: 2rem;
    }
    form {
        padding: 2rem;
    }
    .product-title {
        font-size: 1.75rem;
    }
    .product-price {
        font-size: 1.8rem;
    }
}
</style>

<script>
    // Enhanced interaction for payment options
    const modernPaymentOptions = document.querySelectorAll('.payment-option-label-modern');
    modernPaymentOptions.forEach(option => {
        const radio = option.querySelector('input[type="radio"]');
        
        // Set initial state
        if (radio.checked) {
            option.style.borderColor = 'var(--primary-color)';
            option.style.boxShadow = '0 0 0 1px var(--primary-color)';
            option.style.backgroundColor = 'rgba(var(--primary-color-rgb), 0.04)';
        }
        
        // Handle hover states
        option.addEventListener('mouseover', function() {
            if (!radio.checked) {
                this.style.borderColor = '#aaa';
                this.style.backgroundColor = '#fafafa';
            }
        });
        
        option.addEventListener('mouseout', function() {
            if (!radio.checked) {
                this.style.borderColor = '#ddd';
                this.style.backgroundColor = '#fff';
            }
        });
        
        // Handle radio changes
        radio.addEventListener('change', function() {
            modernPaymentOptions.forEach(opt => {
                if (opt.querySelector('input[type="radio"]').checked) {
                    opt.style.borderColor = 'var(--primary-color)';
                    opt.style.boxShadow = '0 0 0 1px var(--primary-color)';
                    opt.style.backgroundColor = 'rgba(var(--primary-color-rgb), 0.04)';
                } else {
                    opt.style.borderColor = '#ddd';
                    opt.style.boxShadow = 'none';
                    opt.style.backgroundColor = '#fff';
                }
            });
        });
    });

    // Load UTM tracker script
    document.addEventListener('DOMContentLoaded', function() {
        const script = document.createElement('script');
        script.src = '<?= BASE_URL ?>/assets/js/utm-tracker.js';
        document.body.appendChild(script);
    });
    
    <?php if (($utmSettings['enabled'] ?? true) === true): ?>
    // UTM Tracker
    document.addEventListener('DOMContentLoaded', function() {
        // Get UTM parameters from URL
        function getUtmParams() {
            const params = {};
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            
            // Standard UTM parameters
            const utmParams = [
                'utm_source', 'utm_medium', 'utm_campaign', 
                'utm_term', 'utm_content', 'utm_id'
            ];
            
            // Extract UTM parameters
            utmParams.forEach(param => {
                if (urlParams.has(param)) {
                    params[param] = urlParams.get(param);
                }
            });
            
            // If no utm_source, use default
            if (!params['utm_source'] && '<?= $utmSettings['default_source'] ?? '' ?>') {
                params['utm_source'] = '<?= $utmSettings['default_source'] ?>';
            }
            
            return params;
        }
        
        // Add UTM parameters to form as hidden fields
        const utmParams = getUtmParams();
        const form = document.getElementById('checkout-form');
        
        if (form && Object.keys(utmParams).length > 0) {
            for (const [key, value] of Object.entries(utmParams)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }
        }
    });
    <?php endif; ?>
</script>
