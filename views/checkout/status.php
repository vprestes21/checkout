<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Status do Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .status-container {
            max-width: 600px;
            margin: 60px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.07);
            padding: 40px 30px;
            text-align: center;
        }
        .status-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
        .status-success { color: #38c172; }
        .status-pending { color: #f1c40f; }
        .status-failed { color: #e74c3c; }
        .order-details { text-align: left; margin: 30px 0 10px 0; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="status-container">
        <?php
        $status = $order['status'] ?? 'pending';
        $icon = 'fa-clock status-pending';
        $msg = 'Aguardando pagamento...';
        if ($status === 'approved' || $status === 'paid') {
            $icon = 'fa-check-circle status-success';
            $msg = 'Pagamento aprovado!';
        } elseif ($status === 'failed' || $status === 'cancelled') {
            $icon = 'fa-times-circle status-failed';
            $msg = 'Pagamento não aprovado ou cancelado.';
        }
        ?>
        <div class="status-icon">
            <i class="fas <?= $icon ?>"></i>
        </div>
        <h2>Status do Pedido</h2>
        <p class="lead"><?= $msg ?></p>
        <div class="order-details">
            <p><strong>Produto:</strong> <?= htmlspecialchars($product['title']) ?></p>
            <p><strong>Valor:</strong> R$ <?= number_format($order['amount'], 2, ',', '.') ?></p>
            <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
            <p><strong>Método de pagamento:</strong> <?= $order['payment_method'] === 'pix' ? 'PIX' : 'Cartão de Crédito' ?></p>
        </div>
        <?php if ($status === 'approved' || $status === 'paid'): ?>
            <div class="mt-4">
                <a href="<?= BASE_URL ?>/checkout/entrega/<?= $order['id'] ?>" class="btn btn-success btn-lg">Acessar Conteúdo</a>
            </div>
        <?php endif; ?>
        <div class="mt-4">
            <a href="<?= BASE_URL ?>/" class="btn btn-outline-primary">Voltar para a página inicial</a>
        </div>
    </div>
</body>
</html>
