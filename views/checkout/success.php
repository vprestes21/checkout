<?php 
// Template para página de sucesso
$primary_color = $product['primary_color'] ?? '#3490dc';
$secondary_color = $product['secondary_color'] ?? '#38c172';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Realizada com Sucesso</title>
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
        
        .success-container {
            max-width: 600px;
            margin: 100px auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .success-icon {
            font-size: 80px;
            color: var(--secondary-color);
            margin-bottom: 30px;
        }
        
        .order-details {
            background-color: #f1f5f9;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            text-align: left;
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
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1>Compra Realizada com Sucesso!</h1>
        <p class="lead">Obrigado por sua compra. Seu pedido foi processado com sucesso.</p>
        
        <div class="order-details">
            <h5>Detalhes do pedido:</h5>
            <p><strong>Produto:</strong> <?= htmlspecialchars($order['product_title']) ?></p>
            <p><strong>Valor:</strong> R$ <?= number_format($order['amount'], 2, ',', '.') ?></p>
            <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
            <p><strong>Método de pagamento:</strong> <?= $order['payment_method'] === 'pix' ? 'PIX' : 'Cartão de Crédito' ?></p>
        </div>
        
        <p>Um e-mail de confirmação foi enviado para <?= htmlspecialchars($order['buyer_email']) ?>.</p>
        
        <div class="mt-4">
            <a href="<?= BASE_URL ?>" class="btn btn-primary">Voltar para a página inicial</a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
