<?php
$title = 'Detalhes do Produto - CheckoutPro';
ob_start();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Detalhes do Produto</h1>
        <div>
            <a href="<?= BASE_URL ?>/checkout/<?= $product['slug'] ?>" target="_blank" class="btn btn-primary me-2">
                <i class="fas fa-external-link-alt"></i> Ver Checkout
            </a>
            <a href="<?= BASE_URL ?>/product/edit/<?= $product['id'] ?>" class="btn btn-outline-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-5">
            <div class="card dashboard-card mb-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <?php if (!empty($product['logo_url'])): ?>
                        <img src="<?= htmlspecialchars($product['logo_url']) ?>" alt="<?= htmlspecialchars($product['title']) ?>" 
                             style="max-width: 100%; max-height: 200px;">
                        <?php else: ?>
                        <div class="p-4 bg-light rounded">
                            <i class="fas fa-box fa-4x text-muted"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <h4 class="card-title mb-3"><?= htmlspecialchars($product['title']) ?></h4>
                    <h2 class="text-primary mb-4">R$ <?= number_format($product['price'], 2, ',', '.') ?></h2>
                    
                    <?php if (!empty($product['description'])): ?>
                    <div class="mb-4">
                        <h5 class="text-muted">Descrição</h5>
                        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mb-4">
                        <h5 class="text-muted mb-3">Métodos de Pagamento</h5>
                        <div class="d-flex gap-2">
                            <?php if(in_array('pix', $product['payment_methods'])): ?>
                            <span class="badge rounded-pill bg-success py-2 px-3">
                                <i class="fas fa-qrcode me-1"></i> PIX
                            </span>
                            <?php endif; ?>
                            
                            <?php if(in_array('card', $product['payment_methods'])): ?>
                            <span class="badge rounded-pill bg-primary py-2 px-3">
                                <i class="fas fa-credit-card me-1"></i> Cartão
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="text-muted mb-3">Link do Checkout</h5>
                        <div class="input-group">
                            <input type="text" class="form-control" value="<?= BASE_URL ?>/checkout/<?= $product['slug'] ?>" readonly id="checkout-url">
                            <button class="btn btn-outline-primary" type="button" onclick="copyCheckoutUrl()">Copiar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-7">
            <div class="card dashboard-card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Estatísticas de Vendas</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">Vendas Totais</h6>
                            <h3><?= count($orders) ?></h3>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">Vendas Aprovadas</h6>
                            <h3 class="text-success">
                                <?= count(array_filter($orders, function($order) { return $order['status'] == 'approved'; })) ?>
                            </h3>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted">Receita Total</h6>
                            <h3>R$ 
                                <?= number_format(
                                    array_reduce($orders, function($carry, $order) { 
                                        return $carry + ($order['status'] == 'approved' ? $order['amount'] : 0);
                                    }, 0), 
                                    2, ',', '.'
                                ) ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card dashboard-card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Últimas Vendas</h5>
                </div>
                <div class="card-body">
                    <?php if (count($orders) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Cliente</th>
                                    <th>Valor</th>
                                    <th>Pagamento</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($orders as $order): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                    <td><?= htmlspecialchars($order['buyer_name']) ?></td>
                                    <td>R$ <?= number_format($order['amount'], 2, ',', '.') ?></td>
                                    <td>
                                        <?php if ($order['payment_method'] == 'pix'): ?>
                                            <span class="badge bg-success">PIX</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary">Cartão</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($order['status'] == 'approved'): ?>
                                            <span class="badge bg-success">Aprovado</span>
                                        <?php elseif ($order['status'] == 'pending'): ?>
                                            <span class="badge bg-warning text-dark">Pendente</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Rejeitado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p>Este produto ainda não tem vendas registradas.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyCheckoutUrl() {
        const copyText = document.getElementById("checkout-url");
        copyText.select();
        document.execCommand("copy");
        alert("Link copiado para a área de transferência!");
    }
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>
