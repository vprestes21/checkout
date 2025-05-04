<?php
$title = 'Checkout - ' . htmlspecialchars($product['title']);

// Parse customizations from the product data
$customizations = json_decode($product['customizations'] ?? '{}', true) ?? [];
$checkoutSettings = $customizations['checkout'] ?? [];

// Extract theme settings with fallbacks
$template = $product['template'] ?? 'modern';
$primaryColor = $product['primary_color'] ?? '#3490dc';
$secondaryColor = $product['secondary_color'] ?? '#38c172';

// Convert hex colors to RGB for advanced styling
function hexToRgb($hex) {
    $hex = ltrim($hex, '#');
    if(strlen($hex) == 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return "$r, $g, $b";
}

$primaryRgb = hexToRgb($primaryColor);
$secondaryRgb = hexToRgb($secondaryColor);

// Function to create pastel variations of colors
function createPastelColor($hex) {
    // Convert hex to RGB
    $hex = ltrim($hex, '#');
    if(strlen($hex) == 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    // Mix with white to create pastel (soften the color)
    $r = (int)($r * 0.7 + 255 * 0.3);
    $g = (int)($g * 0.7 + 255 * 0.3);
    $b = (int)($b * 0.7 + 255 * 0.3);
    
    // Convert back to hex
    return '#' . str_pad(dechex($r), 2, '0', STR_PAD_LEFT) .
           str_pad(dechex($g), 2, '0', STR_PAD_LEFT) .
           str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
}

// Create pastel versions and subtle variations
$primaryPastel = createPastelColor($primaryColor);
$secondaryPastel = createPastelColor($secondaryColor);

// Create ultra-light versions (for backgrounds)
function createUltraLightColor($hex) {
    // Convert hex to RGB
    $hex = ltrim($hex, '#');
    if(strlen($hex) == 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    // Mix with white to create ultra-light color (90% white)
    $r = (int)($r * 0.1 + 255 * 0.9);
    $g = (int)($g * 0.1 + 255 * 0.9);
    $b = (int)($b * 0.1 + 255 * 0.9);
    
    // Convert back to hex
    return '#' . str_pad(dechex($r), 2, '0', STR_PAD_LEFT) .
           str_pad(dechex($g), 2, '0', STR_PAD_LEFT) .
           str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
}

$primaryUltraLight = createUltraLightColor($primaryColor);
$secondaryUltraLight = createUltraLightColor($secondaryColor);

// Additional settings
$buttonText = $checkoutSettings['button_text'] ?? 'Finalizar Compra';
$showImage = $checkoutSettings['show_product_image'] ?? true;
$logoUrl = $product['logo_url'];
$logoWidth = $product['logo_width'];
$logoHeight = $product['logo_height'];

// Load the appropriate theme based on template selection
ob_start();
?>

<style>
    :root {
        --primary-color: <?= $primaryColor ?>;
        --secondary-color: <?= $secondaryColor ?>;
        --primary-pastel: <?= $primaryPastel ?>;
        --secondary-pastel: <?= $secondaryPastel ?>;
        --primary-ultra-light: <?= $primaryUltraLight ?>;
        --secondary-ultra-light: <?= $secondaryUltraLight ?>;
        --primary-color-rgb: <?= $primaryRgb ?>;
        --secondary-color-rgb: <?= $secondaryRgb ?>;
        --text-dark: #333;
        --text-muted: #6c757d;
        --text-light: #fff;
        --border-color: #dee2e6;
        
        /* Theme specific variables */
        --border-radius: <?= $template === 'modern' ? '15px' : ($template === 'minimal' ? '4px' : '8px') ?>;
        --box-shadow: <?= $template === 'modern' ? '0 8px 20px rgba(0,0,0,0.05)' : 
                          ($template === 'minimal' ? '0 2px 8px rgba(0,0,0,0.05)' : 
                           '0 3px 10px rgba(0,0,0,0.08)') ?>;
    }
    
    body {
        background-color: #fcfcfc;
    }
</style>

<?php
// Include the selected theme
$themePath = __DIR__ . '/themes/' . $template . '.php';
if (file_exists($themePath)) {
    include $themePath;
} else {
    // Fallback to modern theme if selected theme doesn't exist
    include __DIR__ . '/themes/modern.php';
}

$content = ob_get_clean();

// Use checkout layout
$layoutFile = 'views/layouts/checkout.php';
include $layoutFile;
?>
