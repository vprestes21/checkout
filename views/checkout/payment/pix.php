<?php 
// Extract customization data for easier access
$checkoutCustom = $customizations['checkout'] ?? [];
$paymentCustom = $customizations['payment']['methods']['pix'] ?? [];

$primaryColor = $checkoutCustom['primary_color'] ?? '#3498db';
$secondaryColor = $checkoutCustom['secondary_color'] ?? '#f1c40f';
$backgroundColor = $checkoutCustom['background_color'] ?? '#ffffff';

// Calculate discounted price if applicable
$originalPrice = $product['price'];
$discountPercentage = $paymentCustom['discount_percentage'] ?? 0;
$finalPrice = $originalPrice;

if ($discountPercentage > 0) {
    $finalPrice = $originalPrice * (1 - ($discountPercentage / 100));
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento via PIX</title>
    
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
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .accent {
            color: var(--secondary-color);
        }
        
        .pix-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            text-align: center;
        }
        
        .pix-qrcode {
            display: block;
            width: 250px;
            height: 250px;
            margin: 20px auto;
            border: 1px solid #ccc;
            padding: 10px;
            background: white;
        }
        
        .pix-code {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            margin: 20px 0;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="pix-container">
            <h1 style="color: var(--primary-color);">Pagamento via PIX</h1>
            
            <div class="product-summary">
                <h3><?= htmlspecialchars($product['title'] ?? $product['name'] ?? 'Produto') ?></h3>
                
                <?php if ($discountPercentage > 0): ?>
                    <p class="price-discount">
                        <span class="original-price">R$ <?= number_format($originalPrice, 2, ',', '.') ?></span>
                        <span class="discount-tag">-<?= $discountPercentage ?>%</span>
                    </p>
                    <p class="final-price">R$ <?= number_format($finalPrice, 2, ',', '.') ?></p>
                <?php else: ?>
                    <p class="price">R$ <?= number_format($finalPrice, 2, ',', '.') ?></p>
                <?php endif; ?>
            </div>
            
            <div class="pix-instructions">
                <h2>Escaneie o QR Code</h2>
                <p>Use o aplicativo do seu banco para escanear o QR Code abaixo:</p>
                
                <img src="/checkout/assets/img/sample-qrcode.png" alt="QR Code PIX" class="pix-qrcode">
                
                <h2>Ou copie o código PIX</h2>
                <p>Cole o código em seu aplicativo bancário:</p>
                
                <div class="pix-code">
                    00020101021226930014br.gov.bcb.pix2571example-pix-code-for-demonstration-only-not-valid-pix-code-example5204000053039865802BR5925COMPANHIA CHECKOUT DEMO6009SAO PAULO62150511PAGPRODUTO0503***63041234
                </div>
                
                <button id="copy-pix-code" class="btn btn-primary">Copiar código PIX</button>
                
                <div class="pix-timer">
                    <p>Este QR Code expira em: <span id="pix-timer">15:00</span></p>
                </div>
            </div>
            
            <div class="checkout-footer">
                <p>Após o pagamento, você receberá a confirmação por email.</p>
                <a href="/checkout/status/<?= $order['id'] ?>" class="btn">Verificar status do pagamento</a>
            </div>
        </div>
    </div>
    
    <script>
        // Simple copy to clipboard functionality
        document.getElementById('copy-pix-code').addEventListener('click', function() {
            const pixCode = document.querySelector('.pix-code').innerText;
            navigator.clipboard.writeText(pixCode).then(() => {
                alert('Código PIX copiado para a área de transferência!');
            }).catch(err => {
                console.error('Erro ao copiar: ', err);
            });
        });
        
        // Simple countdown timer
        let timeLeft = 15 * 60; // 15 minutes in seconds
        const timerEl = document.getElementById('pix-timer');
        
        const timerInterval = setInterval(function() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            timerEl.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                timerEl.textContent = '00:00';
                alert('O tempo para pagamento expirou. Por favor, gere um novo código.');
            }
            
            timeLeft -= 1;
        }, 1000);
    </script>
</body>
</html>
