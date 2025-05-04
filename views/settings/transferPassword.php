<?php
$title = 'Senha de Transferência - CheckoutPro';
ob_start();
?>

<div class="container py-4">
    <h1 class="mb-4">Configurações</h1>
    
    <div class="row">
        <!-- Sidebar do menu de configurações -->
        <div class="col-md-3 mb-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-center mb-4">
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x text-dark"></i>
                        </div>
                    </div>
                    <h5 class="text-center"><?= htmlspecialchars($_SESSION['user_name']) ?></h5>
                    <p class="text-center text-muted mb-0"><?= htmlspecialchars($_SESSION['user_email']) ?></p>
                </div>
            </div>
            
            <div class="list-group mt-4">
                <a href="<?= BASE_URL ?>/settings/profile" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="fas fa-user-edit me-3"></i>
                    <span>Editar Perfil</span>
                </a>
                <a href="<?= BASE_URL ?>/settings/password" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="fas fa-key me-3"></i>
                    <span>Alterar Senha</span>
                </a>
                <a href="<?= BASE_URL ?>/settings/transferPassword" class="list-group-item list-group-item-action d-flex align-items-center active">
                    <i class="fas fa-lock me-3"></i>
                    <span>Senha de Transferência</span>
                </a>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Senha de Transferência</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <span>Configure uma senha adicional para proteger suas transferências PIX.</span>
                    </div>
                    
                    <form action="<?= BASE_URL ?>/settings/transferPassword" method="post">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" id="enable_password" name="enable_password" 
                                   <?= isset($user['transfer_password_enabled']) && $user['transfer_password_enabled'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="enable_password">
                                Exigir senha adicional para transferências
                            </label>
                        </div>
                        
                        <div id="password-fields" class="<?= isset($user['transfer_password_enabled']) && $user['transfer_password_enabled'] ? '' : 'd-none' ?>">
                            <p class="text-muted mb-3">
                                <?php if(isset($user['transfer_password']) && !empty($user['transfer_password'])): ?>
                                    Você já possui uma senha de transferência configurada. Preencha os campos abaixo apenas se desejar alterar.
                                <?php else: ?>
                                    Configure uma senha para autorizar suas transferências PIX.
                                <?php endif; ?>
                            </p>
                            <div class="mb-3">
                                <label for="transfer_password" class="form-label">Senha de Transferência</label>
                                <input type="password" class="form-control <?= isset($errors['transfer_password']) ? 'is-invalid' : '' ?>" 
                                       id="transfer_password" name="transfer_password">
                                <div class="form-text">A senha deve ter pelo menos 6 caracteres</div>
                                <?php if(isset($errors['transfer_password'])): ?>
                                    <div class="invalid-feedback"><?= $errors['transfer_password'] ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmar Senha</label>
                                <input type="password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
                                       id="confirm_password" name="confirm_password">
                                <?php if(isset($errors['confirm_password'])): ?>
                                    <div class="invalid-feedback"><?= $errors['confirm_password'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <a href="<?= BASE_URL ?>/settings/index" class="btn btn-outline-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Configurações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const enablePasswordSwitch = document.getElementById('enable_password');
        const passwordFields = document.getElementById('password-fields');
        
        enablePasswordSwitch.addEventListener('change', function() {
            if (this.checked) {
                passwordFields.classList.remove('d-none');
            } else {
                passwordFields.classList.add('d-none');
                document.getElementById('transfer_password').value = '';
                document.getElementById('confirm_password').value = '';
            }
        });
    });
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>
