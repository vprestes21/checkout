<?php
$title = 'Dashboard - CheckoutPro';
ob_start();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-light">Dashboard</h1>
        <a href="<?= BASE_URL ?>/product/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Produto
        </a>
    </div>
    
    <!-- Estatísticas -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card h-100">
                <div class="stat-card-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <p class="text-muted mb-1">Total de Vendas</p>
                <h3 class="text-light"><?= $totalSales ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card h-100">
                <div class="stat-card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <p class="text-muted mb-1">Aprovadas</p>
                <h3 class="text-success"><?= $approvedSales ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card h-100">
                <div class="stat-card-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <p class="text-muted mb-1">Pendentes</p>
                <h3 class="text-warning"><?= $pendingSales ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card h-100">
                <div class="stat-card-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <p class="text-muted mb-1">Rejeitadas</p>
                <h3 class="text-danger"><?= $rejectedSales ?></h3>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Resumo financeiro -->
        <div class="col-md-6">
            <div class="dashboard-card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0 text-light">Resumo Financeiro</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted">Receita Total</h6>
                        <h3 class="text-light">R$ <?= number_format($totalRevenue, 2, ',', '.') ?></h3>
                    </div>
                    <div>
                        <h6 class="text-muted">Saldo na Carteira</h6>
                        <h3 class="text-primary">R$ <?= number_format($wallet['balance'], 2, ',', '.') ?></h3>
                        <a href="<?= BASE_URL ?>/wallet" class="btn btn-outline-primary mt-2">
                            Ver Carteira <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Produtos -->
        <div class="col-md-6">
            <div class="dashboard-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-light">Meus Produtos</h5>
                    <span class="badge bg-primary"><?= count($products) ?></span>
                </div>
                <div class="card-body">
                    <?php if (count($products) > 0): ?>
                    <div class="list-group">
                        <?php foreach(array_slice($products, 0, 4) as $product): ?>
                        <a href="<?= BASE_URL ?>/product/edit/<?= $product['id'] ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?= htmlspecialchars($product['title']) ?></h6>
                                <span>R$ <?= number_format($product['price'], 2, ',', '.') ?></span>
                            </div>
                            <small class="text-muted">
                                <?= isset($productSales[$product['id']]) ? $productSales[$product['id']] : 0 ?> vendas
                            </small>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if (count($products) > 4): ?>
                    <div class="mt-3 text-center">
                        <a href="<?= BASE_URL ?>/product" class="btn btn-outline-secondary">
                            Ver todos os produtos
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <p class="text-light">Você ainda não tem produtos cadastrados.</p>
                        <a href="<?= BASE_URL ?>/product/create" class="btn btn-primary">
                            Criar meu primeiro produto
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Vendas recentes -->
    <div class="dashboard-card mt-4">
        <div class="card-header position-relative">
            <h5 class="card-title mb-0 text-light">Vendas Recentes</h5>
        </div>
        <div class="card-body">
            <?php if (count($recentOrders) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-shopping-bag me-2"></i>Produto</th>
                            <th><i class="fas fa-user me-2"></i>Cliente</th>
                            <th><i class="fas fa-calendar me-2"></i>Data</th>
                            <th><i class="fas fa-money-bill-wave me-2"></i>Valor</th>
                            <th><i class="fas fa-info-circle me-2"></i>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recentOrders as $order): ?>
                        <tr>
                            <td class="text-light"><?= htmlspecialchars($order['product_title']) ?></td>
                            <td class="text-light"><?= htmlspecialchars($order['buyer_name']) ?></td>
                            <td class="text-light"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            <td class="text-light">R$ <?= number_format($order['amount'], 2, ',', '.') ?></td>
                            <td>
                                <?php if ($order['status'] == 'approved'): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Aprovado
                                </span>
                                <?php elseif ($order['status'] == 'pending'): ?>
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Pendente
                                </span>
                                <?php else: ?>
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i>Rejeitado
                                </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-4">
                <div class="icon-glow mb-3">
                    <i class="fas fa-receipt fa-3x text-primary"></i>
                </div>
                <p class="text-light">Nenhuma venda registrada ainda.</p>
                <a href="<?= BASE_URL ?>/product" class="btn btn-primary mt-2">
                    <i class="fas fa-tag me-2"></i>Ver meus produtos
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>
