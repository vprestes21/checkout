<!DOCTYPE html>
<html lang="pt-BR" class="dark-mode">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#121212">
    <title><?= $title ?? 'Plataforma de Checkout' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/modern-theme.css">
</head>
<body class="fade-in">
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>">
                <i class="fas fa-shopping-cart me-2"></i>
                <strong>CheckoutPro</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if(isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/dashboard/index">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/product') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/product/index">Meus Produtos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/wallet') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>/wallet/index">Carteira</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> <?= $_SESSION['user_name'] ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/settings/index"><i class="fas fa-cog me-2"></i>Configurações</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt me-2"></i>Sair</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/auth/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/auth/register">Cadastro</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
        <?php $flash = getFlash(); ?>
        <?php if($flash): ?>
            <div class="container">
                <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
                    <?= $flash['message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
        
        <?= $content ?>
    </div>

    <footer>
        <div class="container text-center">
            <p>&copy; <?= date('Y') ?> CheckoutPro - Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Força o modo escuro em elementos internos
    document.addEventListener('DOMContentLoaded', function() {
        // Evita alterações de cor indesejadas em tabelas durante hover
        const tables = document.querySelectorAll('.table');
        tables.forEach(table => {
            table.addEventListener('mouseover', function(e) {
                if (e.target.tagName === 'TD' || e.target.tagName === 'TR') {
                    const links = e.target.querySelectorAll('a, span, p');
                    links.forEach(link => {
                        link.dataset.origColor = link.style.color;
                        link.style.color = 'var(--text-light)';
                    });
                }
            });
            
            table.addEventListener('mouseout', function(e) {
                if (e.target.tagName === 'TD' || e.target.tagName === 'TR') {
                    const links = e.target.querySelectorAll('a, span, p');
                    links.forEach(link => {
                        if (link.dataset.origColor) {
                            link.style.color = link.dataset.origColor;
                            delete link.dataset.origColor;
                        }
                    });
                }
            });
        });
    });
    </script>
</body>
</html>
