<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Checkout' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
        }
        .checkout-container {
            max-width: 768px;
            margin: 0 auto;
        }
        .checkout-header {
            padding: 1.5rem 0;
            text-align: center;
        }
        .checkout-footer {
            padding: 1.5rem 0;
            text-align: center;
            font-size: 0.875rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <header class="checkout-header py-3">
            <div class="container">
                <a href="<?= BASE_URL ?>">
                    <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="CheckoutPro" height="40" onerror="this.style.display='none'">
                </a>
            </div>
        </header>
        
        <main>
            <?php echo $content; ?>
        </main>
        
        <footer class="checkout-footer">
            <div class="container">
                <p>Pagamento seguro processado pela CheckoutPro</p>
                <p>&copy; <?= date('Y') ?> CheckoutPro. Todos os direitos reservados.</p>
            </div>
        </footer>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
