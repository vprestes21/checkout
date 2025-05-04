<?php
class ProductController {
    private $productModel;
    private $orderModel;
    
    public function __construct() {
        // Verificar se o usuário está logado
        if (!isset($_SESSION['user_id'])) {
            setFlash('error', 'Você precisa estar logado para acessar seus produtos.');
            redirect('auth/login');
        }
        
        require_once 'models/ProductModel.php';
        require_once 'models/OrderModel.php';
        
        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
    }
    
    public function index() {
        $userId = $_SESSION['user_id'];
        $products = $this->productModel->getAllByUserId($userId);
        
        // Para cada produto, pegar estatísticas
        foreach ($products as &$product) {
            $product['total_sales'] = $this->productModel->getTotalSales($product['id']);
            $product['approved_sales'] = $this->productModel->getApprovedSales($product['id']);
            $product['total_revenue'] = $this->productModel->getTotalRevenue($product['id']);
        }
        
        view('product/index', compact('products'));
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar o token CSRF
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Token de segurança inválido!');
                redirect('product/create');
            }
            
            // Validar dados
            $title = sanitize($_POST['title'] ?? '');
            $description = sanitize($_POST['description'] ?? '');
            $price = filter_var($_POST['price'] ?? 0, FILTER_VALIDATE_FLOAT);
            $logo_url = filter_var($_POST['logo_url'] ?? '', FILTER_VALIDATE_URL);
            $logo_width = (int)($_POST['logo_width'] ?? 200);
            $logo_height = (int)($_POST['logo_height'] ?? 200);
            $payment_methods = $_POST['payment_methods'] ?? ['pix', 'card'];
            $template = sanitize($_POST['template'] ?? 'modern');
            $primary_color = $_POST['primary_color'] ?? '#3490dc'; // Default blue color
$secondary_color = $_POST['secondary_color'] ?? '#38c172'; // Default green color
            
            // Conteúdo de entrega
            $delivery_type = $_POST['delivery_type'] ?? '';
            $delivery_link = $_POST['delivery_link'] ?? '';
            $delivery_text = $_POST['delivery_text'] ?? '';
            $delivery_file_path = null;
            if ($delivery_type === 'upload' && isset($_FILES['delivery_file']) && $_FILES['delivery_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/delivery/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $filename = uniqid('delivery_') . '_' . basename($_FILES['delivery_file']['name']);
                $targetPath = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['delivery_file']['tmp_name'], $targetPath)) {
                    $delivery_file_path = $targetPath;
                }
            }
            
            $errors = [];
            
            if (empty($title)) {
                $errors['title'] = 'O título do produto é obrigatório';
            }
            
            if ($price <= 0) {
                $errors['price'] = 'O preço deve ser maior que zero';
            }
            
            // Garantir que a URL da logo seja válida ou definir como NULL
            if (empty($logo_url)) {
                $logo_url = null;
            } else if (!filter_var($logo_url, FILTER_VALIDATE_URL)) {
                $errors['logo_url'] = 'A URL da logo é inválida';
            }
            
            if (empty($payment_methods)) {
                $errors['payment_methods'] = 'Escolha pelo menos um método de pagamento';
            }
            
            if (empty($errors)) {
                $productData = [
                    'user_id' => $_SESSION['user_id'],
                    'title' => $title,
                    'description' => $description,
                    'price' => $price,
                    'logo_url' => $logo_url, // Usar a URL validada ou null
                    'logo_width' => $logo_width,
                    'logo_height' => $logo_height,
                    'payment_methods' => json_encode($payment_methods), // Salvar como JSON string
                    'template' => $template,
                    'primary_color' => $primary_color,
                    'secondary_color' => $secondary_color,
                    'delivery_type' => $delivery_type,
                    'delivery_link' => $delivery_link,
                    'delivery_text' => $delivery_text,
                    'delivery_file' => $delivery_file_path,
                ];
                
                $productId = $this->productModel->create($productData);
                
                if ($productId) {
                    setFlash('success', 'Produto criado com sucesso!');
                    redirect('product/index');
                } else {
                    setFlash('error', 'Erro ao criar o produto.');
                    redirect('product/create');
                }
            } else {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = [
                    'title' => $title,
                    'description' => $description,
                    'price' => $price,
                    'logo_url' => $logo_url,
                    'logo_width' => $logo_width,
                    'logo_height' => $logo_height,
                    'payment_methods' => $payment_methods,
                    'template' => $template,
                    'primary_color' => $primary_color,
                    'secondary_color' => $secondary_color,
                    'delivery_type' => $delivery_type,
                    'delivery_link' => $delivery_link,
                    'delivery_text' => $delivery_text,
                    // Não armazene arquivo em $_SESSION
                ];
                redirect('product/create');
            }
        } else {
            $csrf_token = generateCsrfToken();
            $errors = $_SESSION['errors'] ?? [];
            $old = $_SESSION['old'] ?? [];
            unset($_SESSION['errors'], $_SESSION['old']);
            
            view('product/create', compact('csrf_token', 'errors', 'old'));
        }
    }
    
    public function edit($id = null) {
        if (!$id) {
            setFlash('error', 'Produto não encontrado');
            redirect('product/index');
        }
        
        $product = $this->productModel->findById($id);
        
        if (!$product || $product['user_id'] != $_SESSION['user_id']) {
            setFlash('error', 'Você não tem permissão para editar este produto');
            redirect('product/index');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Coletar debug
            $debug = [];
            $debug['POST'] = $_POST;

            // Validar o token CSRF
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Token de segurança inválido!');
                redirect("product/edit/$id");
            }
            
            // Validar dados (similar ao create)
            $title = sanitize($_POST['title'] ?? '');
            $description = sanitize($_POST['description'] ?? '');
            $price = filter_var($_POST['price'] ?? 0, FILTER_VALIDATE_FLOAT);
            $logo_url = filter_var($_POST['logo_url'] ?? '', FILTER_VALIDATE_URL);
            $logo_width = (int)($_POST['logo_width'] ?? 200);
            $logo_height = (int)($_POST['logo_height'] ?? 200);
            
            // Garantir que payment_methods é um array simples de métodos ativos
            $payment_methods = [];
            if (isset($_POST['payment_methods']) && is_array($_POST['payment_methods'])) {
                foreach ($_POST['payment_methods'] as $method) {
                    if (in_array($method, ['pix', 'card'])) {
                        $payment_methods[] = $method;
                    }
                }
            } else {
                $payment_methods = ['pix'];
            }
            $debug['payment_methods'] = $payment_methods;

            $template = sanitize($_POST['template'] ?? 'modern');
            $primary_color = $_POST['primary_color'] ?? '#3490dc'; // Default blue color
$secondary_color = $_POST['secondary_color'] ?? '#38c172'; // Default green color
            
            // Conteúdo de entrega
            $delivery_type = $_POST['delivery_type'] ?? '';
            $delivery_link = $_POST['delivery_link'] ?? '';
            $delivery_text = $_POST['delivery_text'] ?? '';
            $delivery_file_path = $product['delivery_file'] ?? null;

            // Remover arquivo se solicitado
            if (!empty($_POST['remove_delivery_file']) && $delivery_file_path && file_exists($delivery_file_path)) {
                unlink($delivery_file_path);
                $delivery_file_path = null;
            }

            // Upload de novo arquivo
            if ($delivery_type === 'upload' && isset($_FILES['delivery_file']) && $_FILES['delivery_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/delivery/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $filename = uniqid('delivery_') . '_' . basename($_FILES['delivery_file']['name']);
                $targetPath = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['delivery_file']['tmp_name'], $targetPath)) {
                    // Remove arquivo antigo se existir
                    if ($delivery_file_path && file_exists($delivery_file_path)) {
                        unlink($delivery_file_path);
                    }
                    $delivery_file_path = $targetPath;
                }
            }

            $errors = [];
            
            if (empty($title)) {
                $errors['title'] = 'O título do produto é obrigatório';
            }
            
            if ($price <= 0) {
                $errors['price'] = 'O preço deve ser maior que zero';
            }
            
            // Garantir que a URL da logo seja válida ou definir como NULL
            if (empty($logo_url)) {
                $logo_url = null;
            } else if (!filter_var($logo_url, FILTER_VALIDATE_URL)) {
                $errors['logo_url'] = 'A URL da logo é inválida';
            }
            
            if (empty($payment_methods)) {
                $errors['payment_methods'] = 'Escolha pelo menos um método de pagamento';
            }
            
            if (empty($errors)) {
                // Informações do Cliente
                $require_email = true; // Email sempre obrigatório
                $require_phone = isset($_POST['require_phone']);
                $require_cpf = isset($_POST['require_cpf']);
                $require_address = isset($_POST['require_address']);

                // UTM
                $enable_utm = isset($_POST['enable_utm']);
                $default_utm_source = $_POST['default_utm_source'] ?? '';
                $utm_api_key = $_POST['utm_api_key'] ?? '';

                // Build customizations array with all the new settings
                $customizations = [
                    'checkout' => [
                        'model' => $_POST['template'] ?? 'classic',
                        'title' => 'Complete sua compra',
                        'description' => '',
                        'button_text' => 'Finalizar Compra',
                        'primary_color' => $_POST['primary_color'] ?? '#3498db',
                        'secondary_color' => $_POST['secondary_color'] ?? '#f1c40f',
                        'show_product_image' => true,
                    ],
                    'payment' => [
                        'methods' => [
                            'pix' => [
                                'enabled' => in_array('pix', $payment_methods),
                                'discount_percentage' => 0,
                            ],
                            'card' => [
                                'enabled' => in_array('card', $payment_methods),
                                'max_installments' => 1,
                            ],
                        ],
                    ],
                    // Add customer information settings
                    'customer_info' => [
                        'require_email' => $require_email,
                        'require_phone' => $require_phone,
                        'require_cpf' => $require_cpf,
                        'require_address' => $require_address,
                    ],
                    // Add UTM settings
                    'utm' => [
                        'enabled' => $enable_utm,
                        'default_source' => $default_utm_source,
                        'api_key' => $utm_api_key,
                    ],
                ];
                
                // Prepare data for update
                try {
                    // Simplified update data with only essential fields
                    $updateData = [
                        'title' => trim((string)$_POST['title']),
                        'description' => trim((string)($_POST['description'] ?? '')),
                        'price' => floatval($_POST['price']),
                        'logo_url' => trim((string)($_POST['logo_url'] ?? '')),
                        'logo_width' => (int)($_POST['logo_width'] ?? 0),
                        'logo_height' => (int)($_POST['logo_height'] ?? 0),
                        'template' => trim((string)($_POST['template'] ?? 'classic')),
                        'primary_color' => trim((string)($_POST['primary_color'] ?? '#3498db')),
                        'secondary_color' => trim((string)($_POST['secondary_color'] ?? '#f1c40f')),
                        'payment_methods' => json_encode($payment_methods), // Salvar como JSON string
                        'customizations' => json_encode($customizations),
                        'delivery_type' => $delivery_type,
                        'delivery_link' => $delivery_link,
                        'delivery_text' => $delivery_text,
                        'delivery_file' => $delivery_file_path,
                    ];
                    
                    // Log update attempt for debugging
                    error_log("ProductController: Attempting to update product ID $id with: " . json_encode($updateData));
                    
                    // Update the product directly with the model
                    $success = $this->productModel->update($updateData, (int)$id);
                    
                    if ($success) {
                        setFlash('success', 'Produto atualizado com sucesso!');
                        redirect('product/index');
                    } else {
                        // Get error info from session if available or use generic message
                        $errorMessage = $_SESSION['flash']['message'] ?? 'Erro desconhecido ao atualizar o produto.';
                        setFlash('error', $errorMessage);
                        redirect("product/edit/$id");
                    }
                } catch (Exception $e) {
                    error_log("ProductController: Exception in edit method: " . $e->getMessage());
                    setFlash('error', 'Erro ao processar a atualização: ' . $e->getMessage());
                    redirect("product/edit/$id");
                }
            } else {
                $debug['validation_errors'] = $errors;
                $_SESSION['debug'] = $debug;
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = [
                    'title' => $title,
                    'description' => $description,
                    'price' => $price,
                    'logo_url' => $logo_url,
                    'logo_width' => $logo_width,
                    'logo_height' => $logo_height,
                    'payment_methods' => $payment_methods,
                    'template' => $template,
                    'primary_color' => $primary_color,
                    'secondary_color' => $secondary_color,
                    // Add customer info fields
                    'require_email' => $require_email,
                    'require_phone' => $require_phone,
                    'require_cpf' => $require_cpf,
                    'require_address' => $require_address,
                    // Add UTM fields
                    'enable_utm' => $enable_utm,
                    'default_utm_source' => $default_utm_source,
                    'utm_api_key' => $utm_api_key,
                    'delivery_type' => $delivery_type,
                    'delivery_link' => $delivery_link,
                    'delivery_text' => $delivery_text,
                ];
                redirect("product/edit/$id");
            }
        } else {
            $csrf_token = generateCsrfToken();
            $errors = $_SESSION['errors'] ?? [];
            $old = $_SESSION['old'] ?? $product;
            // Pega debug da sessão, se houver
            $debug = $_SESSION['debug'] ?? null;
            unset($_SESSION['errors'], $_SESSION['old'], $_SESSION['debug']);
            
            // Preparar dados para o formulário de edição - carregar personalizações existentes
            $customizations = json_decode($product['customizations'] ?? '{}', true) ?? [];
            $checkoutSettings = $customizations['checkout'] ?? [];
            $paymentSettings = $customizations['payment']['methods'] ?? ['pix' => [], 'card' => []];
            $customerSettings = $customizations['customer_info'] ?? [];
            $utmSettings = $customizations['utm'] ?? [];
            $urlSettings = $customizations['urls'] ?? [];
            
            // Extract color values from product or set defaults
            $primary_color = $product['primary_color'] ?? $checkoutSettings['primary_color'] ?? '#3498db';
            $secondary_color = $product['secondary_color'] ?? $checkoutSettings['secondary_color'] ?? '#f1c40f';
            
            // Corrigir: gerar array simples de métodos ativos
            $active_payment_methods = [];
            if (!empty($paymentSettings['pix']['enabled'])) $active_payment_methods[] = 'pix';
            if (!empty($paymentSettings['card']['enabled'])) $active_payment_methods[] = 'card';
            
            // Mesclar dados do produto com personalizações para o formulário
            $formData = [
                'title' => $product['title'],
                'description' => $product['description'],
                'price' => $product['price'],
                'logo_url' => $product['logo_url'],
                'logo_width' => $product['logo_width'],
                'logo_height' => $product['logo_height'],
                'payment_methods' => $active_payment_methods,
                'template' => $product['template'],
                'primary_color' => $primary_color,
                'secondary_color' => $secondary_color,
                'checkout_model' => $checkoutSettings['model'] ?? 'classic',
                'checkout_title' => $checkoutSettings['title'] ?? 'Complete sua compra',
                'checkout_description' => $checkoutSettings['description'] ?? '',
                'checkout_button_text' => $checkoutSettings['button_text'] ?? 'Finalizar Compra',
                'checkout_background' => $checkoutSettings['background_color'] ?? '#ffffff',
                'checkout_logo' => $checkoutSettings['logo'] ?? '',
                'show_product_image' => $checkoutSettings['show_product_image'] ?? false,
                'show_pix' => ($paymentSettings['pix']['enabled'] ?? false),
                'pix_discount' => $paymentSettings['pix']['discount_percentage'] ?? 0,
                'pix_discount_text' => $paymentSettings['pix']['discount_text'] ?? 'Economize {discount}% pagando com PIX',
                'show_card' => ($paymentSettings['card']['enabled'] ?? false),
                'card_installments' => $paymentSettings['card']['max_installments'] ?? 1,
                'card_interest' => $paymentSettings['card']['interest_rate'] ?? 0,
                'card_interest_text' => $paymentSettings['card']['interest_text'] ?? 'Juros de {interest}% ao mês',
                'require_address' => $customerSettings['require_address'] ?? false,
                'require_phone' => $customerSettings['require_phone'] ?? false,
                'require_email' => $customerSettings['require_email'] ?? true,
                'require_cpf' => $customerSettings['require_cpf'] ?? false,
                // Add UTM settings
                'enable_utm' => $utmSettings['enabled'] ?? true,
                'default_utm_source' => $utmSettings['default_source'] ?? '',
                'utm_api_key' => $utmSettings['api_key'] ?? '',
                'success_url' => $urlSettings['success'] ?? '',
                'cancel_url' => $urlSettings['cancel'] ?? '',
                'terms_url' => $urlSettings['terms'] ?? '',
                // Adicione os campos de entrega
                'delivery_type' => $product['delivery_type'] ?? '',
                'delivery_link' => $product['delivery_link'] ?? '',
                'delivery_text' => $product['delivery_text'] ?? '',
                'delivery_file' => $product['delivery_file'] ?? '',
            ];
            
            view('product/edit', compact('csrf_token', 'errors', 'old', 'product', 'formData', 'debug'));
        }
    }
    
    public function delete($id = null) {
        if (!$id) {
            setFlash('error', 'Produto não encontrado');
            redirect('product/index');
        }
        
        $product = $this->productModel->findById($id);
        
        if (!$product || $product['user_id'] != $_SESSION['user_id']) {
            setFlash('error', 'Você não tem permissão para excluir este produto');
            redirect('product/index');
        }
        
        if ($this->productModel->delete($id)) {
            setFlash('success', 'Produto excluído com sucesso!');
        } else {
            setFlash('error', 'Erro ao excluir o produto.');
        }
        
        redirect('product/index');
    }
    
    public function view($id = null) {
        if (!$id) {
            setFlash('error', 'Produto não encontrado');
            redirect('product/index');
        }
        
        $product = $this->productModel->findById($id);
        
        if (!$product || $product['user_id'] != $_SESSION['user_id']) {
            setFlash('error', 'Você não tem permissão para visualizar este produto');
            redirect('product/index');
        }
        
        $orders = $this->orderModel->getByProductId($id);
        
        view('product/view', compact('product', 'orders'));
    }
}
