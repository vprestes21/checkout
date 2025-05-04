<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento via PIX - {{ $product->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .pix-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .qr-container {
            display: flex;
            justify-content: center;
            margin: 30px 0;
        }
        
        .pix-code {
            background: #f1f5f9;
            padding: 15px;
            border-radius: 5px;
            word-break: break-all;
            font-family: monospace;
            margin-bottom: 20px;
        }
        
        .timer {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="pix-container">
        <h2 class="text-center mb-4">Pagamento via PIX</h2>
        <p class="text-center">Escaneie o QR Code abaixo ou copie o código PIX para realizar o pagamento.</p>
        
        <div class="qr-container">
            <img src="https://chart.googleapis.com/chart?cht=qr&chl={{ urlencode($pixCode) }}&chs=250x250&choe=UTF-8&chld=L|2" alt="QR Code PIX">
        </div>
        
        <div class="mt-4">
            <label class="form-label">Código PIX:</label>
            <div class="pix-code">{{ $pixCode }}</div>
            <button id="copy-btn" class="btn btn-outline-primary w-100" onclick="copyPixCode()">Copiar código</button>
        </div>
        
        <div class="timer mt-4">
            <p>Este código expira em: <span id="timer">10:00</span></p>
        </div>
        
        <div class="text-center mt-4">
            <p>Assim que o pagamento for confirmado, você será redirecionado automaticamente.</p>
            <p>Valor: <strong>R$ {{ number_format($product->price, 2, ',', '.') }}</strong></p>
        </div>
        
        <!-- Em produção, isso seria feito via webhook, mas aqui simulamos com um botão -->
        <form action="{{ url('checkout/confirm-pix/' . $order->id) }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-success w-100">Já paguei o PIX</button>
        </form>
    </div>
    
    <script>
        function copyPixCode() {
            const pixCode = "{{ $pixCode }}";
            navigator.clipboard.writeText(pixCode).then(() => {
                const btn = document.getElementById('copy-btn');
                btn.innerText = 'Código copiado!';
                setTimeout(() => {
                    btn.innerText = 'Copiar código';
                }, 3000);
            });
        }
        
        // Timer simulado
        function startTimer(duration, display) {
            let timer = duration, minutes, seconds;
            let interval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);
                
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
                
                display.textContent = minutes + ":" + seconds;
                
                if (--timer < 0) {
                    clearInterval(interval);
                    display.textContent = "Expirado";
                }
            }, 1000);
        }
        
        window.onload = function () {
            let tenMinutes = 60 * 10;
            let display = document.querySelector('#timer');
            startTimer(tenMinutes, display);
        };
    </script>
</body>
</html>
