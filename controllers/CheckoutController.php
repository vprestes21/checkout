<?php
class CheckoutController {
    private $productModel;
    private $utmModel;
    
    public function __construct() {
        require_once 'models/ProductModel.php';
        $this->productModel = new ProductModel();
        
        require_once 'models/UtmModel.php';
        $this->utmModel = new UtmModel();
    }
    
    /**
     * Default index method - redirect to homepage
     */
    public function index() {
        redirect('');
    }
    
    /**
     * Display checkout page by product slug
     * 
     * @param string $slug Product slug
     * @return void
     */
    public function show($slug = null) {
        if (!$slug) {
            // No slug provided, show 404
            $this->notFound();
            return;
        }
        
        // Find product by slug
        $product = $this->productModel->findBySlug($slug);
        
        if (!$product) {
            // Product not found, show 404
            $this->notFound();
            return;
        }
        
        // Extract customizations
        $customizations = json_decode($product['customizations'] ?? '{}', true) ?? [];
        $customerSettings = $customizations['customer_info'] ?? [];
        $utmSettings = $customizations['utm'] ?? [];
        
        // Track UTM parameters if enabled
        if (($utmSettings['enabled'] ?? true) === true) {
            $this->trackUtmParameters($product['id']);
        }
        
        // Render checkout page
        view('checkout/show', compact('product', 'customerSettings', 'utmSettings'));
    }
    
    /**
     * Track UTM parameters from the URL
     */
    private function trackUtmParameters($productId) {
        $utmSource = $_GET['utm_source'] ?? null;
        $utmMedium = $_GET['utm_medium'] ?? null;
        $utmCampaign = $_GET['utm_campaign'] ?? null;
        $utmTerm = $_GET['utm_term'] ?? null;
        $utmContent = $_GET['utm_content'] ?? null;
        
        // Only track if we have at least a source
        if ($utmSource) {
            $this->utmModel->trackVisit([
                'product_id' => $productId,
                'utm_source' => $utmSource,
                'utm_medium' => $utmMedium,
                'utm_campaign' => $utmCampaign,
                'utm_term' => $utmTerm,
                'utm_content' => $utmContent,
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
    
    /**
     * Handle product slugs (this method catches all non-method route parameters)
     * 
     * @param string $slug Product slug
     * @return void
     */
    public function __call($name, $arguments) {
        // Treat the method name as a product slug
        $this->show($name);
    }
    
    /**
     * Display 404 page
     */
    private function notFound() {
        http_response_code(404);
        view('errors/404', ['message' => 'Produto não encontrado']);
    }

    /**
     * Processa o POST do checkout e redireciona para o pagamento correto
     */
    public function payment($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            $this->notFound();
            return;
        }

        // Buscar produto
        $product = $this->productModel->findById($id);
        if (!$product) {
            $this->notFound();
            return;
        }

        // Validar campos mínimos
        $buyer_name = trim($_POST['name'] ?? '');
        $buyer_email = trim($_POST['email'] ?? '');
        $payment_method = $_POST['payment_method'] ?? '';

        // Adicione os campos extras se existirem
        $buyer_phone = $_POST['phone'] ?? null;
        $buyer_cpf = $_POST['cpf'] ?? null;
        $buyer_address = $_POST['address'] ?? null;
        $buyer_city = $_POST['city'] ?? null;
        $buyer_state = $_POST['state'] ?? null;
        $buyer_postal_code = $_POST['postal_code'] ?? null;

        if (!$buyer_name || !$buyer_email || !in_array($payment_method, ['pix', 'card'])) {
            setFlash('error', 'Preencha todos os campos obrigatórios.');
            redirect('checkout/' . $product['slug']);
            return;
        }

        // Criar pedido (Order) - adapte conforme seu modelo
        require_once 'models/OrderModel.php';
        $orderModel = new OrderModel();
        $orderId = $orderModel->create([
            'product_id' => $product['id'],
            'buyer_id' => null,
            'buyer_name' => $buyer_name,
            'buyer_email' => $buyer_email,
            'buyer_phone' => $buyer_phone,
            'buyer_cpf' => $buyer_cpf,
            'buyer_address' => $buyer_address,
            'buyer_city' => $buyer_city,
            'buyer_state' => $buyer_state,
            'buyer_postal_code' => $buyer_postal_code,
            'payment_method' => $payment_method,
            'amount' => $product['price'],
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        if (!$orderId) {
            setFlash('error', 'Erro ao criar pedido.');
            redirect('checkout/' . $product['slug']);
            return;
        }

        // Redirecionar para o fluxo de pagamento correto
        if ($payment_method === 'pix') {
            redirect("checkout/pix/$orderId");
        } else {
            redirect("checkout/card/$orderId");
        }
    }

    /**
     * Exibe a tela de pagamento PIX para o pedido
     */
    public function pix($orderId = null) {
        if (!$orderId) {
            $this->notFound();
            return;
        }
        require_once 'models/OrderModel.php';
        $orderModel = new OrderModel();
        $order = $orderModel->findById($orderId);
        if (!$order) {
            $this->notFound();
            return;
        }
        $product = $this->productModel->findById($order['product_id']);
        if (!$product) {
            $this->notFound();
            return;
        }
        $customizations = json_decode($product['customizations'] ?? '{}', true) ?? [];

        // Gere o código PIX real aqui, se necessário
        $pixCode = '00020101021226930014br.gov.bcb.pix2571example-pix-code-for-demonstration-only-not-valid-pix-code-example5204000053039865802BR5925COMPANHIA CHECKOUT DEMO6009SAO PAULO62150511PAGPRODUTO0503***63041234';

        view('checkout/payment/pix', compact('order', 'product', 'customizations', 'pixCode'));
    }

    /**
     * Exibe a tela de pagamento com cartão para o pedido
     */
    public function card($orderId = null) {
        if (!$orderId) {
            $this->notFound();
            return;
        }
        require_once 'models/OrderModel.php';
        $orderModel = new OrderModel();
        $order = $orderModel->findById($orderId);
        if (!$order) {
            $this->notFound();
            return;
        }
        $product = $this->productModel->findById($order['product_id']);
        if (!$product) {
            $this->notFound();
            return;
        }
        $customizations = json_decode($product['customizations'] ?? '{}', true) ?? [];

        view('checkout/payment/card', compact('order', 'product', 'customizations'));
    }

    /**
     * Exibe a tela de status do pagamento do pedido
     */
    public function status($orderId = null) {
        if (!$orderId) {
            $this->notFound();
            return;
        }
        require_once 'models/OrderModel.php';
        $orderModel = new OrderModel();
        $order = $orderModel->findById($orderId);
        if (!$order) {
            $this->notFound();
            return;
        }
        $product = $this->productModel->findById($order['product_id']);
        if (!$product) {
            $this->notFound();
            return;
        }
        // Você pode adicionar lógica para status, entrega, etc.
        view('checkout/status', compact('order', 'product'));
    }
}
