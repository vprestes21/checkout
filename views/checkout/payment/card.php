<?php 
// Extract customization data for easier access
$checkoutCustom = $customizations['checkout'] ?? [];
$paymentCustom = $customizations['payment']['methods']['card'] ?? [];

$primaryColor = $checkoutCustom['primary_color'] ?? '#3498db';
$secondaryColor = $checkoutCustom['secondary_color'] ?? '#f1c40f';
$backgroundColor = $checkoutCustom['background_color'] ?? '#ffffff';

// Get card payment settings
$maxInstallments = $paymentCustom['max_installments'] ?? 1;
$interestRate = $paymentCustom['interest_rate'] ?? 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento com Cartão</title>
    
    <!-- Base CSS -->
    <link rel="stylesheet" href="/checkout/assets/css/checkout.css">
    
    <!-- Dynamic styles based on customization -->
    <style>
        :root {
            --primary-color: <?= $primaryColor ?>;
            --secondary-color: <?= $secondaryColor ?>;
            --background-color: <?= $backgroundColor ?>;
        }
        
        body {
            background-color: var(--background-color);
        }
        
        .card-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        
        .card-form {
            margin-top: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .card-row {
            display: flex;
            gap: 15px;
        }
        
        .card-col {
            flex: 1;
        }
        
        .card-preview {
            background: linear-gradient(135deg, #333, #666);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            height: 200px;
            position: relative;
        }
        
        .card-number {
            font-size: 18px;
            letter-spacing: 2px;
            margin-top: 40px;
        }
        
        .card-name {
            position: absolute;
            bottom: 50px;
            left: 20px;
            font-size: 16px;
            text-transform: uppercase;
        }
        
        .card-expiry {
            position: absolute;
            bottom: 50px;
            right: 20px;
        }
        
        .card-brand {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card-container">
            <h1 style="color: var(--primary-color);">Pagamento com Cartão</h1>
            
            <div class="product-summary">
                <h3><?= htmlspecialchars($product['title'] ?? $product['name'] ?? 'Produto') ?></h3>
                <p class="price">R$ <?= number_format($product['price'], 2, ',', '.') ?></p>
            </div>
            
            <div class="card-preview">
                <div class="card-brand">VISA</div>
                <div class="card-number">•••• •••• •••• ••••</div>
                <div class="card-name">NOME DO TITULAR</div>
                <div class="card-expiry">MM/AA</div>
            </div>
            
            <form class="card-form" id="card-payment-form" method="post" action="/checkout/process-payment/<?= $product['id'] ?>">
                <div class="form-group">
                    <label for="card_number">Número do Cartão</label>
                    <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
                </div>
                
                <div class="form-group">
                    <label for="card_name">Nome do Titular</label>
                    <input type="text" id="card_name" name="card_name" placeholder="Como está no cartão">
                </div>
                
                <div class="card-row">
                    <div class="card-col">
                        <div class="form-group">
                            <label for="card_expiry">Validade (MM/AA)</label>
                            <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/AA" maxlength="5">
                        </div>
                    </div>
                    <div class="card-col">
                        <div class="form-group">
                            <label for="card_cvv">CVV</label>
                            <input type="text" id="card_cvv" name="card_cvv" placeholder="123" maxlength="4">
                        </div>
                    </div>
                </div>
                
                <?php if ($maxInstallments > 1): ?>
                <div class="form-group">
                    <label for="installments">Parcelamento</label>
                    <select id="installments" name="installments">
                        <option value="1">À vista - R$ <?= number_format($product['price'], 2, ',', '.') ?></option>
                        
                        <?php for ($i = 2; $i <= $maxInstallments; $i++): ?>
                            <?php
                                // Calculate installment value
                                $installmentValue = $product['price'] / $i;
                                
                                // Apply interest if needed
                                if ($interestRate > 0) {
                                    // Simple interest calculation
                                    $installmentValue = $product['price'] * (1 + ($interestRate/100) * $i) / $i;
                                    $totalValue = $installmentValue * $i;
                                    
                                    echo "<option value=\"$i\">{$i}x de R$ " . number_format($installmentValue, 2, ',', '.') . 
                                         " (Total: R$ " . number_format($totalValue, 2, ',', '.') . ") com juros</option>";
                                } else {
                                    echo "<option value=\"$i\">{$i}x de R$ " . number_format($installmentValue, 2, ',', '.') . " sem juros</option>";
                                }
                            ?>
                        <?php endfor; ?>
                    </select>
                </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">Pagar Agora</button>
                </div>
            </form>
            
            <div class="checkout-footer">
                <p>Pagamento seguro processado por CheckoutSystem</p>
                <img src="/checkout/assets/img/secure-payment.png" alt="Pagamento Seguro" style="height: 30px;">
            </div>
        </div>
    </div>
    
    <script>
        // Card number formatting
        document.getElementById('card_number').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                value = value.match(/.{1,4}/g).join(' ');
            }
            e.target.value = value;
            
            // Update card preview
            document.querySelector('.card-number').textContent = value || '•••• •••• •••• ••••';
        });
        
        // Card name formatting
        document.getElementById('card_name').addEventListener('input', function (e) {
            document.querySelector('.card-name').textContent = e.target.value.toUpperCase() || 'NOME DO TITULAR';
        });
        
        // Expiry date formatting
        document.getElementById('card_expiry').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            
            e.target.value = value;
            document.querySelector('.card-expiry').textContent = value || 'MM/AA';
        });
        
        // Basic form validation
        document.getElementById('card-payment-form').addEventListener('submit', function(e) {
            const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
            const cardName = document.getElementById('card_name').value;
            const cardExpiry = document.getElementById('card_expiry').value;
            const cardCvv = document.getElementById('card_cvv').value;
            
            if (cardNumber.length < 16 || !cardName || cardExpiry.length < 5 || cardCvv.length < 3) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos do cartão corretamente.');
            }
        });
    </script>
</body>
</html>
