<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WalletController;

Route::get('/', function () {
    return view('welcome');
});

// Autenticação (usando Fortify ou Breeze em produção)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [ProductController::class, 'dashboard'])->name('dashboard');
    
    // Produtos
    Route::resource('products', ProductController::class);
    
    // Carteira
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/transfer', [WalletController::class, 'transfer'])->name('wallet.transfer');
});

// Rotas públicas de checkout
Route::get('/checkout/{slug}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/{slug}', [CheckoutController::class, 'purchase'])->name('checkout.purchase');
Route::post('/checkout/process-card/{order}', [CheckoutController::class, 'processCard'])->name('checkout.process-card');
Route::post('/checkout/confirm-pix/{order}', [CheckoutController::class, 'confirmPix'])->name('checkout.confirm-pix');
Route::get('/checkout/status/{orderId}', [CheckoutController::class, 'status'])->name('checkout.status'); // Rota adicionada para a página de status

// Webhook para notificações de pagamento
Route::post('/webhook/payment', [CheckoutController::class, 'webhook'])->name('webhook.payment');
