<?php 
// Template personalizado que não usa o layout principal
$theme = $product['template'] ?? 'modern';

// Extract customization data for easier access
$checkoutCustom = $customizations['checkout'] ?? [];
$primaryColor = $checkoutCustom['primary_color'] ?? '#3498db';
$secondaryColor = $checkoutCustom['secondary_color'] ?? '#f1c40f';
$backgroundColor = $checkoutCustom['background_color'] ?? '#ffffff';
$title = $checkoutCustom['title'] ?? 'Complete sua compra';
$description = $checkoutCustom['description'] ?? '';
$buttonText = $checkoutCustom['button_text'] ?? 'Finalizar Compra';
$showImage = $checkoutCustom['show_product_image'] ?? !empty($logoUrl);

// Function to adjust color brightness (make it lighter for background)
function adjustColorBrightness($hex, $opacity) {
    // Remove # if present
    $hex = ltrim($hex, '#');
    
    // Convert hex to rgb
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    // Mix with white to create a lighter version
    $r = round($r * $opacity + 255 * (1 - $opacity));
    $g = round($g * $opacity + 255 * (1 - $opacity));
    $b = round($b * $opacity + 255 * (1 - $opacity));
    
    // Convert back to hex
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

// Definir variáveis CSS a partir das variáveis PHP
$primaryColorRgb = implode(",", sscanf(htmlspecialchars($primaryColor), "#%02x%02x%02x") ?: [52, 152, 219]);
$dark_bg = "#121212"; 
$dark_card = "#1E1E1E";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    
    <!-- Incluir CSS base dos temas -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/checkout-themes.css"> 
    
    <!-- Customer fields styling -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/customer-fields.css">
    
    <!-- Estilos dinâmicos baseados nas customizações -->
    <style>
        :root {
            --primary-color: <?= htmlspecialchars($primaryColor) ?>;
            --secondary-color: <?= htmlspecialchars($secondaryColor) ?>;
            --background-color: <?= htmlspecialchars($backgroundColor) ?>;
            --primary-color-rgb: <?= $primaryColorRgb ?>;
            
            /* Cores derivadas */
            --primary-light: <?= adjustColorBrightness($primaryColor, 0.8) ?>;
            --primary-dark: <?= adjustColorBrightness($primaryColor, 0.6) ?>;
            
            --text-light: #f8f9fa;
            --text-dark: #212529;
            --text-muted: #6c757d;
            --border-radius: 8px; 
            --dark-bg: <?= $dark_bg ?>; 
            --dark-card: <?= $dark_card ?>; 
        }
        
        body {
            background-color: var(--background-color);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: var(--text-dark); 
            line-height: 1.6;
        }
        
        .checkout-container-wrapper {
            max-width: 1000px;
            margin: 40px auto;
            animation: fadeIn 0.5s ease forwards;
        }

        /* Estilos gerais para botões, formulários etc. que podem ser usados pelos temas */
        .btn {
            display: inline-block;
            font-weight: 500;
            line-height: 1.5;
            color: #fff;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.75rem 1.25rem;
            font-size: 1rem;
            border-radius: var(--border-radius);
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        .btn-primary {
            color: #fff;
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            box-shadow: 0 5px 15px rgba(var(--primary-color-rgb), 0.3);
        }

        .btn-primary:hover {
            opacity: 0.9;
        }
        
        .alert {
            position: relative;
            padding: 1rem 1rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: var(--border-radius);
        }
        .alert-success {
            color: #0f5132;
            background-color: #d1e7dd;
            border-color: #badbcc;
        }
        .alert-error, .alert-danger {
            color: #842029;
            background-color: #f8d7da;
            border-color: #f5c2c7;
        }
        .alert-warning {
             color: #664d03;
            background-color: #fff3cd;
            border-color: #ffecb5;
        }
        .alert-info {
            color: #055160;
            background-color: #cff4fc;
            border-color: #b6effb;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 768px) {
            .checkout-container-wrapper {
                margin: 20px auto;
            }
        }
    </style>
</head>
<body>
    <div class="checkout-container-wrapper">
        <?php 
        // Exibir mensagem flash, se houver
        $flash = getFlash(); // Usar helper function
        if ($flash): 
        ?>
            <div class="alert alert-<?= htmlspecialchars($flash["type"]) ?>" role="alert">
                <?= htmlspecialchars($flash["message"]) ?>
            </div>
        <?php endif; ?>
        
        <?php 
        // Carregar o template do tema apropriado
        $themeFileName = basename($theme) . ".php";
        $themePath = __DIR__ . "/themes/" . $themeFileName;
        
        if (file_exists($themePath)) {
            // Incluir o arquivo do tema. As variáveis definidas acima estarão disponíveis no escopo do tema.
            include $themePath;
        } else {
            // Fallback para um tema padrão (ex: modern) se o selecionado não existir
            echo "<div class=\"alert alert-warning\">Erro: Tema '" . htmlspecialchars($theme) . "' não encontrado. Usando tema padrão.</div>";
            $fallbackThemePath = __DIR__ . "/themes/modern.php";
            if (file_exists($fallbackThemePath)) {
                 include $fallbackThemePath;
            } else {
                 echo "<div class=\"alert alert-danger\">Erro Crítico: Tema padrão 'modern' também não encontrado.</div>";
            }
        }
        ?>
    </div>
    
    <!-- UTM Tracking JS -->
    <script src="<?= BASE_URL ?>/assets/js/utm-tracker.js"></script>
</body>
</html>

<?php
// Limpar dados da sessão
unset($_SESSION['old']);
?>
