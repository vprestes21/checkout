<?php 
// Template personalizado para pagamento com cartão
$primary_color = $product['primary_color'] ?? '#3490dc';
$secondary_color = $product['secondary_color'] ?? '#38c172';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento com Cartão - <?= htmlspecialchars($product['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        :root {
            --primary-color: <?= $primary_color ?>;
            --secondary-color: <?= $secondary_color ?>;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
        }
        
        .credit-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 15px;
            color: white;
            padding: 20px;
            margin-bottom: 30px;
            position: relative;
            height: 200px;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
        }
        
        .card-number {
            font-size: 22px;
            letter-spacing: 2px;
            margin-top: 40px;
            font-family: monospace;
        }
        
        .card-holder, .card-expiry {
            font-size: 14px;
            margin-top: 20px;
        }
        
        .card-brand {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 24px;
        }
        
        .card-input {
            margin-bottom: 20px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <div class="card-container">
        <h2 class="text-center mb-4">Pagamento com Cartão</h2>
        
        <div class="credit-card">
            <div class="card-brand">
                <i class="fab fa-cc-visa"></i>
            </div>
            <div class="card-number" id="card-number-display">•••• •••• •••• ••••</div>
            <div class="d-flex justify-content-between">
                <div class="card-holder" id="card-holder-display">NOME DO TITULAR</div>
                <div class="card-expiry" id="card-expiry-display">MM/AA</div>
            </div>
        </div>
        
        <form action="<?= BASE_URL ?>/checkout/processCard/<?= $order['id'] ?>" method="POST">
            <?php if(isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach($_SESSION['errors'] as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>
            
            <div class="card-input">
                <label class="form-label">Número do cartão</label>
                <input type="text" class="form-control" id="card_number" name="card_number" maxlength="16" required>
            </div>
            
            <div class="card-input">
                <label class="form-label">Nome no cartão</label>
                <input type="text" class="form-control" id="card_name" name="card_name" required>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card-input">
                        <label class="form-label">Validade (MM/AA)</label>
                        <input type="text" class="form-control" id="card_expiry" name="card_expiry" maxlength="5" placeholder="MM/AA" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-input">
                        <label class="form-label">Código de segurança (CVV)</label>
                        <input type="text" class="form-control" id="card_cvv" name="card_cvv" maxlength="3" required>
                    </div>
                </div>
            </div>
            
            <p class="mt-3 text-center">
                <strong>Total: R$ <?= number_format($product['price'], 2, ',', '.') ?></strong>
            </p>
            
            <button type="submit" class="btn btn-primary w-100 mt-4">Pagar agora</button>
            
            <div class="mt-3 text-center">
                <small class="text-muted">Pagamento 100% seguro. Seus dados são criptografados.</small>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('card_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            let display = value.padEnd(16, '•').match(/.{1,4}/g).join(' ');
            document.getElementById('card-number-display').innerText = display;
        });
        
        document.getElementById('card_name').addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();
            document.getElementById('card-holder-display').innerText = value || 'NOME DO TITULAR';
        });
        
        document.getElementById('card_expiry').addEventListener('input', function(e) {
            let value = e.target.value;
            if (value.length === 2 && !value.includes('/')) {
                e.target.value = value + '/';
            }
            document.getElementById('card-expiry-display').innerText = value || 'MM/AA';
        });
    </script>
</body>
</html>
