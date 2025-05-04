<?php 
$title = 'Cadastro - CheckoutPro';
ob_start(); 
?>

<div class="container">
    <div class="auth-container">
        <div class="auth-logo">
            <i class="fas fa-user-plus fa-3x text-primary"></i>
        </div>
        <h2>Criar Conta</h2>
        
        <form action="<?= BASE_URL ?>/auth/register" method="post">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            
            <div class="mb-3">
                <label for="name" class="form-label">Nome completo</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                        id="name" name="name" value="<?= $old['name'] ?? '' ?>" placeholder="Seu nome completo" required>
                </div>
                <?php if(isset($errors['name'])): ?>
                    <div class="invalid-feedback d-block mt-1"><?= $errors['name'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                        id="email" name="email" value="<?= $old['email'] ?? '' ?>" placeholder="seu@email.com" required>
                </div>
                <?php if(isset($errors['email'])): ?>
                    <div class="invalid-feedback d-block mt-1"><?= $errors['email'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                        id="password" name="password" placeholder="Mínimo 6 caracteres" required>
                </div>
                <?php if(isset($errors['password'])): ?>
                    <div class="invalid-feedback d-block mt-1"><?= $errors['password'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar senha</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
                        id="confirm_password" name="confirm_password" placeholder="Repita sua senha" required>
                </div>
                <?php if(isset($errors['confirm_password'])): ?>
                    <div class="invalid-feedback d-block mt-1"><?= $errors['confirm_password'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="terms" required>
                <label class="form-check-label" for="terms">
                    Concordo com os <a href="#">Termos de Serviço</a> e <a href="#">Política de Privacidade</a>
                </label>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Criar conta</button>
            </div>
            
            <div class="auth-links mt-4">
                <p>Já possui uma conta? <a href="<?= BASE_URL ?>/auth/login" class="fw-bold">Fazer login</a></p>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>
