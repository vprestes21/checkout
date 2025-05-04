<?php 
$title = 'CheckoutPro - Plataforma de Checkout';
ob_start(); 
?>

<div class="hero bg-primary text-white text-center py-5">
    <div class="container py-5">
        <h1 class="display-4 fw-bold mb-4">Crie checkouts profissionais em minutos</h1>
        <p class="lead mb-4">Venda seus produtos online com uma plataforma completa e personalizada</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="<?= BASE_URL ?>/auth/register" class="btn btn-light btn-lg px-4 gap-3">Começar agora</a>
            <a href="#features" class="btn btn-outline-light btn-lg px-4">Saiba mais</a>
        </div>
    </div>
</div>

<section id="features" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Recursos Poderosos</h2>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-primary bg-gradient text-white mb-3">
                            <i class="fas fa-palette"></i>
                        </div>
                        <h5>Checkouts Personalizados</h5>
                        <p>Crie checkouts únicos com sua própria logo, cores e estilo para combinar com sua marca.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-primary bg-gradient text-white mb-3">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <h5>Múltiplas Formas de Pagamento</h5>
                        <p>Aceite pagamentos via PIX e cartão de crédito, maximizando suas chances de venda.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-primary bg-gradient text-white mb-3">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h5>Análise Completa</h5>
                        <p>Acompanhe suas vendas em tempo real com um dashboard completo e detalhado.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-primary bg-gradient text-white mb-3">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <h5>Carteira Digital</h5>
                        <p>Gerencie seu saldo e realize transferências via PIX diretamente da plataforma.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-primary bg-gradient text-white mb-3">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h5>Design Responsivo</h5>
                        <p>Seus checkouts funcionam perfeitamente em qualquer dispositivo, de celulares a desktops.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-primary bg-gradient text-white mb-3">
                            <i class="fas fa-link"></i>
                        </div>
                        <h5>Link de Checkout Único</h5>
                        <p>Compartilhe seu link de checkout em qualquer lugar e começe a vender imediatamente.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2>Comece a vender agora mesmo!</h2>
                <p class="lead">Cadastre-se gratuitamente e crie seu primeiro checkout em menos de 5 minutos.</p>
                <a href="<?= BASE_URL ?>/auth/register" class="btn btn-primary btn-lg">Criar minha conta</a>
            </div>
            <div class="col-lg-6">
                <img src="<?= BASE_URL ?>/assets/images/checkout-demo.png" class="img-fluid rounded shadow" alt="Checkout Demo">
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include 'views/layouts/main.php';
?>
