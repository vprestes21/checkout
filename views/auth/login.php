<?php 
$title = 'Login - CheckoutPro';
ob_start(); 
?>

<div class="container">
    <div class="auth-container">
        <div class="auth-logo">
            <i class="fas fa-shopping-cart fa-3x text-primary"></i>
        </div>
        <h2>Bem-vindo de volta</h2>
        
        <form action="<?= BASE_URL ?>/auth/login" method="post">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" placeholder="seu@email.com" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Lembrar-me</label>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
            </div>
            
            <div class="auth-links mt-4">
                <p>Não tem uma conta? <a href="<?= BASE_URL ?>/auth/register" class="fw-bold">Cadastre-se</a></p>
                <p><a href="<?= BASE_URL ?>/auth/forgot-password">Esqueci minha senha</a></p>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>
