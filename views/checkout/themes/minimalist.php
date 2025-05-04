<?php
// Set product-specific customer info field requirements
$requireEmail = isset($product['require_email']) ? (bool)$product['require_email'] : true;
$requirePhone = isset($product['require_phone']) ? (bool)$product['require_phone'] : false;
$requireAddress = isset($product['require_address']) ? (bool)$product['require_address'] : false;
$requireCpf = isset($product['require_cpf']) ? (bool)$product['require_cpf'] : false;

$pixEnabled = $customizations['payment']['methods']['pix']['enabled'] ?? false;
$cardEnabled = $customizations['payment']['methods']['card']['enabled'] ?? false;
?>

<div class="theme-minimalist" style="background-color: #fff; border-radius: 8px; padding: 30px; max-width: 600px; margin: 0 auto; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
    <h1 style="margin-top: 0; font-size: 1.6rem; font-weight: 500; margin-bottom: 1.5rem; color: var(--text-dark);"><?= htmlspecialchars($title) ?></h1>
    
    <form id="minimalist-checkout-form" method="post" action="<?= BASE_URL ?>/checkout/payment/<?= (int)($product['id'] ?? 0) ?>">
        <!-- Customer information form -->
        <?php 
        $formClass = 'minimalist-form-control';
        $theme = 'minimalist';
        include __DIR__ . '/../components/customer_info.php'; 
        ?>
    
        <div class="product-info" style="margin: 2rem 0; padding: 1.5rem; background-color: #fafafa; border-radius: 6px;">
            <?php if ($showImage && !empty($logoUrl)): ?>
                <img src="<?= htmlspecialchars($logoUrl) ?>" alt="<?= htmlspecialchars($product['title'] ?? 'Produto') ?>" 
                     style="display: block; max-width: 100%; height: auto; max-height: 120px; margin: 0 auto 1.5rem; object-fit: contain;">
            <?php endif; ?>
            
            <div class="product-details" style="border-bottom: 1px solid #eee; padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                <h2 style="margin: 0 0 0.5rem; font-size: 1.2rem; font-weight: 500;"><?= htmlspecialchars($product['title'] ?? 'Produto') ?></h2>
                <?php if (!empty($product['description'])): ?>
                    <p style="margin: 0 0 1rem; color: #666; font-size: 0.95rem;"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                <?php endif; ?>
                <div class="price" style="font-size: 1.3rem; font-weight: 600; color: var(--primary-color);">
                    R$ <?= number_format($product['price'] ?? 0, 2, ',', '.') ?>
                </div>
            </div>
        
            <h3 style="font-size: 1.1rem; font-weight: 500; margin: 1.5rem 0 1rem;">Forma de pagamento</h3>
            
            <div class="payment-options" style="margin-bottom: 2rem;">
                <?php if (!$pixEnabled && !$cardEnabled): ?>
                    <div style="padding: 1rem; background-color: #fff3cd; color: #856404; border-radius: 6px;">
                        Nenhuma forma de pagamento disponível.
                    </div>
                <?php else: ?>
                    <?php if ($pixEnabled): ?>
                        <div class="payment-option" style="margin-bottom: 1rem; border: 1px solid #eee; border-radius: 6px; transition: all 0.2s;">
                            <label for="payment-pix" style="display: flex; align-items: center; padding: 1rem; cursor: pointer;">
                                <input type="radio" name="payment_method" id="payment-pix" value="pix" checked 
                                       style="margin-right: 1rem; accent-color: var(--primary-color);">
                                <div style="flex: 1;">
                                    <span style="display: block; font-weight: 500;">PIX</span>
                                    <?php if (($customizations['payment']['methods']['pix']['discount_percentage'] ?? 0) > 0): ?>
                                        <span style="display: block; font-size: 0.85rem; color: var(--secondary-color); margin-top: 0.2rem;">
                                            <?= ($customizations['payment']['methods']['pix']['discount_percentage'] ?? 0) ?>% de desconto
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <img src="<?= BASE_URL ?>/assets/img/pix-icon.png" alt="PIX" style="width: 28px; height: 28px; object-fit: contain;">
                            </label>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($cardEnabled): ?>
                        <div class="payment-option" style="border: 1px solid #eee; border-radius: 6px; transition: all 0.2s;">
                            <label for="payment-card" style="display: flex; align-items: center; padding: 1rem; cursor: pointer;">
                                <input type="radio" name="payment_method" id="payment-card" value="card" <?= !$pixEnabled ? 'checked' : '' ?> 
                                       style="margin-right: 1rem; accent-color: var(--primary-color);">
                                <div style="flex: 1;">
                                    <span style="display: block; font-weight: 500;">Cartão de Crédito</span>
                                    <?php if (($customizations['payment']['methods']['card']['max_installments'] ?? 1) > 1): ?>
                                        <span style="display: block; font-size: 0.85rem; color: #666; margin-top: 0.2rem;">
                                            em até <?= $customizations['payment']['methods']['card']['max_installments'] ?? 1 ?>x
                                            <?php echo ($customizations['payment']['methods']['card']['interest_rate'] ?? 0) > 0 ? 'com juros' : 'sem juros'; ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <img src="<?= BASE_URL ?>/assets/img/card-icon.png" alt="Cartão" style="width: 28px; height: 28px; object-fit: contain;">
                            </label>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn-primary" style="width: 100%; border: none; display: block; padding: 0.8rem; text-align: center; font-weight: 500; font-size: 1rem; cursor: pointer; border-radius: 6px; background-color: var(--primary-color); color: white; transition: opacity 0.2s; margin-top: 1.5rem;">
                <?= htmlspecialchars($buttonText) ?>
            </button>
        </div>
    </form>
</div>

<style>
/* Minimalist theme form styles */
.minimalist-form-control {
    width: 100%;
    padding: 12px;
    margin-bottom: 10px;
    border: 1px solid #eee;
    border-radius: 4px;
    font-size: 16px;
    transition: border-color 0.2s;
    box-sizing: border-box;
}

.minimalist-form-control:focus {
    border-color: var(--primary-color);
    outline: none;
    box-shadow: 0 0 0 2px rgba(var(--primary-color-rgb), 0.1);
}

/* Grid system for minimalist theme */
.theme-minimalist .row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -5px;
    margin-left: -5px;
}

.theme-minimalist .col-md-4,
.theme-minimalist .col-md-6,
.theme-minimalist .col-md-8 {
    position: relative;
    width: 100%;
    padding-right: 5px;
    padding-left: 5px;
    margin-bottom: 10px;
}

@media (min-width: 768px) {
    .theme-minimalist .col-md-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }
    .theme-minimalist .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }
    .theme-minimalist .col-md-8 {
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
    }
}

.theme-minimalist .customer-section-title {
    font-size: 1.2rem;
    font-weight: 500;
    margin-bottom: 1.5rem;
    color: var(--text-dark);
}

.theme-minimalist .customer-section-subtitle {
    font-size: 1rem;
    font-weight: 500;
    margin: 1rem 0;
    color: var(--text-dark);
}

.theme-minimalist .form-label {
    display: block;
    margin-bottom: 6px;
    font-size: 0.9rem;
    color: #666;
}

/* Form select styling */
.theme-minimalist .form-select {
    appearance: none;
    background-color: #fff;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    width: 100%;
    padding: 12px;
    margin-bottom: 10px;
    border: 1px solid #eee;
    border-radius: 4px;
    font-size: 16px;
}

/* Add spacing between sections */
.theme-minimalist .address-fields {
    border-top: 1px solid #f0f0f0;
    padding-top: 1rem;
    margin-top: 1rem;
}

/* Payment option hover and selected styles */
.theme-minimalist .payment-option:hover {
    border-color: #ddd;
    background-color: #fafafa;
}

.theme-minimalist .payment-option input[type="radio"]:checked + div {
    font-weight: 600;
}

/* Add specific styles for minimalist buttons */
.theme-minimalist .btn-primary:hover {
    opacity: 0.9;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .theme-minimalist .row {
        display: block;
    }
    
    .theme-minimalist [class*="col-"] {
        width: 100%;
        max-width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects for payment options
    const paymentOptions = document.querySelectorAll('.payment-option');
    paymentOptions.forEach(option => {
        const input = option.querySelector('input[type="radio"]');
        
        // Apply styles based on selection
        function updateStyles() {
            if (input.checked) {
                option.style.borderColor = 'var(--primary-color)';
                option.style.backgroundColor = 'rgba(var(--primary-color-rgb), 0.03)';
            } else {
                option.style.borderColor = '#eee';
                option.style.backgroundColor = 'transparent';
            }
        }
        
        // Set initial state
        updateStyles();
        
        // Add event listeners
        input.addEventListener('change', updateStyles);
        option.addEventListener('mouseenter', function() {
            if (!input.checked) {
                this.style.backgroundColor = '#fafafa';
            }
        });
        option.addEventListener('mouseleave', function() {
            if (!input.checked) {
                this.style.backgroundColor = 'transparent';
            }
        });
    });
    
    // Initialize UTM tracker
    if (window.utmTracker) {
        window.utmTracker.init();
    } else {
        console.warn('UTM tracker not available');
    }
});
</script>
