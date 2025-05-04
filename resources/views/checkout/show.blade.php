<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->title }} - Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: {{ $product->primary_color ?? '#3490dc' }};
            --secondary-color: {{ $product->secondary_color ?? '#38c172' }};
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .checkout-container {
            max-width: 1000px;
            margin: 40px auto;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
        }
        
        .product-info {
            padding: 40px;
            background-color: white;
        }
        
        .payment-form {
            padding: 40px;
            background-color: #f1f5f9;
            border-left: 1px solid #e2e8f0;
        }
        
        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        
        .logo-container img {
            max-width: 100%;
            height: auto;
            width: {{ $product->logo_width }}px;
            max-height: {{ $product->logo_height }}px;
            object-fit: contain;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 12px 20px;
            font-weight: 500;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .price {
            font-size: 32px;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .description {
            margin: 25px 0;
            line-height: 1.7;
            color: #4b5563;
        }
        
        .payment-method {
            margin: 25px 0;
        }
        
        .form-control {
            padding: 12px;
            border-radius: 8px;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .secure-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
        }
        
        .secure-badge i {
            color: var(--primary-color);
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="checkout-container">
        <div class="row g-0">
            <div class="col-lg-6 product-info">
                @if($product->logo_url)
                <div class="logo-container">
                    <img src="{{ $product->logo_url }}" alt="{{ $product->title }}">
                </div>
                @endif
                <h1 class="mt-4">{{ $product->title }}</h1>
                <div class="price mt-4">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
                <div class="description">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
            <div class="col-lg-6 payment-form">
                <h3 class="mb-4">Complete sua compra</h3>
                <form action="{{ url('checkout/' . $product->slug) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="buyer_name" class="form-label">Nome completo</label>
                        <input type="text" class="form-control" id="buyer_name" name="buyer_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="buyer_email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="buyer_email" name="buyer_email" required>
                    </div>
                    
                    <div class="payment-method">
                        <h5 class="mb-3">Forma de pagamento</h5>
                        @if(in_array('pix', $product->payment_methods))
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="pix" value="pix" checked>
                            <label class="form-check-label" for="pix">
                                <i class="fas fa-qrcode me-2"></i> PIX (pagamento instantâneo)
                            </label>
                        </div>
                        @endif
                        @if(in_array('card', $product->payment_methods))
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="card" value="card"
                                {{ !in_array('pix', $product->payment_methods) ? 'checked' : '' }}>
                            <label class="form-check-label" for="card">
                                <i class="far fa-credit-card me-2"></i> Cartão de Crédito
                            </label>
                        </div>
                        @endif
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">Finalizar Compra</button>
                    
                    <div class="mt-4 text-center secure-badge">
                        <i class="fas fa-lock"></i> <span>Pagamento 100% seguro. Seus dados estão protegidos.</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
