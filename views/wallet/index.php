<?php
$title = 'Minha Carteira - CheckoutPro';
ob_start();
?>

<div class="container py-4">
    <h1 class="text-light mb-4">Minha Carteira</h1>
    
    <div class="row">
        <div class="col-md-4">
            <div class="dashboard-card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Saldo Disponível</h5>
                    <div class="wallet-balance">R$ <?= number_format($wallet['balance'], 2, ',', '.') ?></div>
                    
                    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#transferModal">
                        <i class="fas fa-paper-plane"></i> Transferir via PIX
                    </button>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-body">
                    <h5 class="card-title">Informações</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            <span class="text-light">Valores disponíveis após 14 dias da aprovação da venda</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <span class="text-light">Transferências processadas em até 24 horas úteis</span>
                        </li>
                        <li>
                            <i class="fas fa-shield-alt text-primary me-2"></i>
                            <span class="text-light">Todas as transferências são seguras e criptografadas</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="dashboard-card">
                <div class="card-header">
                    <h5 class="card-title mb-0 text-light">Histórico de Transferências</h5>
                </div>
                <div class="card-body">
                    <?php if (count($transfers) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Valor</th>
                                    <th>Chave PIX</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transfers as $transfer): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i', strtotime($transfer['created_at'])) ?></td>
                                    <td>R$ <?= number_format($transfer['amount'], 2, ',', '.') ?></td>
                                    <td><?= htmlspecialchars($transfer['pix_key']) ?></td>
                                    <td>
                                        <?php if ($transfer['status'] == 'completed'): ?>
                                            <span class="badge bg-success">Concluída</span>
                                        <?php elseif ($transfer['status'] == 'pending'): ?>
                                            <span class="badge bg-warning">Pendente</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Falhou</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                        <p class="text-light">Você ainda não realizou nenhuma transferência.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Transferência -->
<div class="modal fade" id="transferModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: var(--dark-card); border: 1px solid rgba(255, 141, 122, 0.2);">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255, 141, 122, 0.2);">
                <h5 class="modal-title text-light">Transferir para PIX</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form action="<?= BASE_URL ?>/wallet/transfer" method="post">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                    
                    <div class="mb-3">
                        <label for="amount" class="form-label">Valor</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" class="form-control" id="amount" name="amount" 
                                   step="0.01" min="1" max="<?= $wallet['balance'] ?>" required>
                        </div>
                        <div class="form-text">Saldo disponível: R$ <?= number_format($wallet['balance'], 2, ',', '.') ?></div>
                        <?php if (isset($_SESSION['errors']['amount'])): ?>
                            <div class="text-danger"><?= $_SESSION['errors']['amount'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="pix_key" class="form-label">Chave PIX</label>
                        <input type="text" class="form-control" id="pix_key" name="pix_key" required>
                        <div class="form-text">CPF, E-mail, Telefone ou Chave Aleatória</div>
                        <?php if (isset($_SESSION['errors']['pix_key'])): ?>
                            <div class="text-danger"><?= $_SESSION['errors']['pix_key'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <?php
                    // Verificar se o usuário tem a senha de transferência habilitada
                    $userModel = new UserModel();
                    $user = $userModel->findById($_SESSION['user_id']);
                    if (isset($user['transfer_password_enabled']) && $user['transfer_password_enabled']):
                    ?>
                    <div class="mb-3">
                        <label for="transfer_password" class="form-label">Senha de Transferência</label>
                        <input type="password" class="form-control" id="transfer_password" name="transfer_password" required>
                        <?php if (isset($_SESSION['errors']['transfer_password'])): ?>
                            <div class="text-danger"><?= $_SESSION['errors']['transfer_password'] ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Transferir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// Limpar os erros da sessão
unset($_SESSION['errors']);

$content = ob_get_clean();
include 'views/layouts/main.php';
?>
