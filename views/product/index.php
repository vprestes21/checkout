<?php
$title = 'Meus Produtos - CheckoutPro';
ob_start();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-light">Meus Produtos</h1>
        <a href="<?= BASE_URL ?>/product/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Produto
        </a>
    </div>
    
    <?php if (count($products) > 0): ?>
    <div class="row g-4">
        <?php foreach($products as $product): ?>
        <div class="col-md-6 col-lg-4">
            <div class="dashboard-card h-100">
                <div class="card-body">
                    <?php if (!empty($product['logo_url'])): ?>
                    <div class="text-center mb-3">
                        <img src="<?= htmlspecialchars($product['logo_url']) ?>" alt="<?= htmlspecialchars($product['title']) ?>" 
                             style="max-width: 100px; max-height: 80px;">
                    </div>
                    <?php endif; ?>
                    
                    <h5 class="card-title"><?= htmlspecialchars($product['title']) ?></h5>
                    <p class="card-text text-primary fw-bold">R$ <?= number_format($product['price'], 2, ',', '.') ?></p>
                    <p class="card-text small text-muted">
                        <?= strlen($product['description']) > 100 ? substr($product['description'], 0, 100) . '...' : $product['description'] ?>
                    </p>
                    
                    <div class="mt-3">
                        <div class="row g-2 text-center">
                            <div class="col-4">
                                <div class="small text-muted">Vendas</div>
                                <div class="fw-bold text-light"><?= $product['total_sales'] ?? 0 ?></div>
                            </div>
                            <div class="col-4">
                                <div class="small text-muted">Aprovadas</div>
                                <div class="fw-bold text-success"><?= $product['approved_sales'] ?? 0 ?></div>
                            </div>
                            <div class="col-4">
                                <div class="small text-muted">Receita</div>
                                <div class="fw-bold text-light">R$ <?= number_format($product['total_revenue'] ?? 0, 2, ',', '.') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <div class="d-flex justify-content-between">
                        <a href="<?= BASE_URL ?>/product/edit/<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="<?= BASE_URL ?>/product/view/<?= $product['id'] ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-eye"></i> Detalhes
                        </a>
                        <a href="<?= BASE_URL ?>/checkout/<?= $product['slug'] ?>" target="_blank" class="btn btn-sm btn-primary">
                            <i class="fas fa-external-link-alt"></i> Ver Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="card dashboard-card p-5 text-center">
        <i class="fas fa-box fa-4x text-muted mb-4"></i>
        <h3 class="text-light">Você ainda não tem produtos cadastrados</h3>
        <p class="text-muted mb-4">Comece criando seu primeiro produto agora mesmo</p>
        <div>
            <a href="<?= BASE_URL ?>/product/create" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i> Criar meu primeiro produto
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>
