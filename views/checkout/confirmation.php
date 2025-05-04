<?php
$title = 'Pedido Confirmado - CheckoutPro';
ob_start();

// Supondo que $product já está disponível na view
?>
<div class="container py-4">
    <h1 class="mb-4">Pedido Confirmado!</h1>
    <p class="lead">Obrigado pela sua compra.</p>

    <?php if (!empty($product['delivery_type'])): ?>
        <div class="card my-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Acesse seu conteúdo</h5>
            </div>
            <div class="card-body text-center">
                <?php if ($product['delivery_type'] === 'link' && !empty($product['delivery_link'])): ?>
                    <a href="<?= htmlspecialchars($product['delivery_link']) ?>" target="_blank" class="btn btn-success btn-lg">ACESSAR</a>
                <?php elseif ($product['delivery_type'] === 'upload' && !empty($product['delivery_file'])): ?>
                    <a href="<?= BASE_URL . '/' . htmlspecialchars($product['delivery_file']) ?>" class="btn btn-success btn-lg" download>ACESSAR</a>
                <?php elseif ($product['delivery_type'] === 'text' && !empty($product['delivery_text'])): ?>
                    <button class="btn btn-success btn-lg" type="button" onclick="document.getElementById('delivery-text-content').style.display='block'">ACESSAR</button>
                    <div id="delivery-text-content" class="alert alert-info mt-4" style="display:none;">
                        <?= nl2br(htmlspecialchars($product['delivery_text'])) ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">Nenhum conteúdo de entrega cadastrado para este produto.</div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between">
        <a href="<?= BASE_URL ?>/product/index" class="btn btn-outline-secondary">Voltar para Produtos</a>
        <a href="<?= BASE_URL ?>/order/print/<?= $orderId ?>" class="btn btn-primary" target="_blank">Imprimir Pedido</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>