<?php
$title = 'Editar Perfil - CheckoutPro';
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
                    <h5 class="text-center"><?= htmlspecialchars($user['name']) ?></h5>
                    <p class="text-center text-muted mb-0"><?= htmlspecialchars($user['email']) ?></p>
                </div>
            </div>
            
            <div class="list-group mt-4">
                <a href="<?= BASE_URL ?>/settings/profile" class="list-group-item list-group-item-action d-flex align-items-center active">
                    <i class="fas fa-user-edit me-3"></i>
                    <span>Editar Perfil</span>
                </a>
                <a href="<?= BASE_URL ?>/settings/password" class="list-group-item list-group-item-action d-flex align-items-center">
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
                    <h5 class="card-title mb-0">Editar Perfil</h5>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>/settings/profile" method="post">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                   id="name" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
                            <?php if(isset($errors['name'])): ?>
                                <div class="invalid-feedback"><?= $errors['name'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                   id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                            <?php if(isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= $errors['email'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <a href="<?= BASE_URL ?>/settings/index" class="btn btn-outline-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
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
