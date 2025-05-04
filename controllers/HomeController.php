<?php
class HomeController {
    private $userModel;
    private $productModel;
    
    public function __construct() {
        require_once 'models/UserModel.php';
        require_once 'models/ProductModel.php';
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
    }
    
    public function index() {
        view('home/index');
    }
    
    public function create() {
        // Handle form submission for product creation with customizations
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate required fields
            if (empty($_POST['name']) || empty($_POST['price'])) {
                setFlash('error', 'Nome e preço são obrigatórios.');
                view('home/create', ['data' => $_POST]);
                return;
            }
            
            // Process checkout customizations
            $customizations = [
                // Checkout page appearance
                'checkout' => [
                    'model' => $_POST['checkout_model'] ?? 'classic', // Store the checkout model (classic, modern, etc.)
                    'title' => $_POST['checkout_title'] ?? 'Complete sua compra',
                    'description' => $_POST['checkout_description'] ?? '',
                    'button_text' => $_POST['checkout_button_text'] ?? 'Finalizar Compra',
                    'primary_color' => $_POST['primary_color'] ?? '#3498db', // Ensure field name matches form
                    'secondary_color' => $_POST['secondary_color'] ?? '#f1c40f', // Add secondary color
                    'background_color' => $_POST['checkout_background'] ?? '#ffffff',
                    'logo' => $_POST['checkout_logo'] ?? '',
                    'show_product_image' => isset($_POST['show_product_image']),
                ],
                
                // Payment methods configuration
                'payment' => [
                    'methods' => [
                        'pix' => [
                            'enabled' => isset($_POST['show_pix']),
                            'discount_percentage' => floatval($_POST['pix_discount'] ?? 0),
                            'discount_text' => $_POST['pix_discount_text'] ?? 'Economize {discount}% pagando com PIX',
                        ],
                        'card' => [
                            'enabled' => isset($_POST['show_card']),
                            'max_installments' => intval($_POST['card_installments'] ?? 1),
                            'interest_rate' => floatval($_POST['card_interest'] ?? 0),
                            'interest_text' => $_POST['card_interest_text'] ?? 'Juros de {interest}% ao mês',
                        ],
                    ],
                ],
                
                // Customer information requirements
                'customer_info' => [
                    'require_address' => isset($_POST['require_address']),
                    'require_phone' => isset($_POST['require_phone']),
                ],
                
                // URLs for checkout flow
                'urls' => [
                    'success' => $_POST['success_url'] ?? '',
                    'cancel' => $_POST['cancel_url'] ?? '',
                    'terms' => $_POST['terms_url'] ?? '',
                ],
            ];
            
            // Main product data with customizations
            $productData = [
                'name' => $_POST['name'],
                'description' => $_POST['description'] ?? '',
                'price' => floatval($_POST['price']),
                'image' => $_POST['image'] ?? '',
                'customizations' => json_encode($customizations), // Store as JSON in database
            ];
            
            // Save product with customizations
            $productId = $this->productModel->create($productData);
            
            if ($productId) {
                setFlash('success', 'Produto criado com sucesso! A personalização será aplicada durante o checkout.');
                redirect('product/view/' . $productId);
            } else {
                setFlash('error', 'Erro ao criar produto.');
                view('home/create', ['data' => $_POST]);
            }
        } else {
            // Default customization values for the creation form
            $defaultData = [
                'name' => '',
                'description' => '',
                'price' => '',
                'image' => '',
                'checkout_title' => 'Complete sua compra',
                'checkout_description' => '',
                'checkout_button_text' => 'Finalizar Compra',
                'checkout_color' => '#3498db',
                'checkout_background' => '#ffffff',
                'show_pix' => true,
                'show_card' => true,
                'pix_discount' => 0,
                'card_installments' => 1,
                'card_interest' => 0,
                'require_address' => true,
                'require_phone' => true,
            ];
            
            view('home/create', ['data' => $defaultData]);
        }
    }
    
    public function setup() {
        global $conn;
        $db = new Database();
        if($db->setup()) {
            setFlash('success', 'Banco de dados configurado com sucesso!');
        } else {
            setFlash('error', 'Erro ao configurar banco de dados.');
        }
        redirect('');
    }
}
