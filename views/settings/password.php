<?php
$title = 'Alterar Senha - CheckoutPro';
ob_start();
?>

<div class="container py-4">
    <h1 class="mb-4">Configurações</h1>
    
    <div class="row">
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
                <a href="<?= BASE_URL ?>/settings/password" class="list-group-item list-group-item-action d-flex align-items-center active">
                    <i class="fas fa-key me-3"></i>
                    <span>Alterar Senha</span>
                </a>
                <a href="<?= BASE_URL ?>/settings/transferPassword" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="fas fa-lock me-3"></i>
                    <span>Senha de Transferência</span>
                </a>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Alterar Senha</h5>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>/settings/password" method="post">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Senha Atual</label>
                            <input type="password" class="form-control <?= isset($errors['current_password']) ? 'is-invalid' : '' ?>" 
                                   id="current_password" name="current_password" required>
                            <?php if(isset($errors['current_password'])): ?>
                                <div class="invalid-feedback"><?= $errors['current_password'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control <?= isset($errors['new_password']) ? 'is-invalid' : '' ?>" 
                                   id="new_password" name="new_password" required>
                            <div class="form-text">A senha deve ter pelo menos 6 caracteres</div>
                            <?php if(isset($errors['new_password'])): ?>
                                <div class="invalid-feedback"><?= $errors['new_password'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
                                   id="confirm_password" name="confirm_password" required>
                            <?php if(isset($errors['confirm_password'])): ?>
                                <div class="invalid-feedback"><?= $errors['confirm_password'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <a href="<?= BASE_URL ?>/settings/index" class="btn btn-outline-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Alterar Senha</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>
