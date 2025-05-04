<?php
class DashboardController extends Controller {
    private $userModel;
    private $productModel;
    private $orderModel;
    private $walletModel;
    
    public function __construct() {
        // Verificar se o usuário está logado
        if (!isset($_SESSION['user_id'])) {
            setFlash('error', 'Você precisa estar logado para acessar o dashboard.');
            redirect('auth/login');
        }
        
        require_once 'models/UserModel.php';
        require_once 'models/ProductModel.php';
        require_once 'models/OrderModel.php';
        require_once 'models/WalletModel.php';
        
        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
        $this->walletModel = new WalletModel();
    }
    
    public function index() {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        $products = $this->productModel->getProducts(); // Usando o método existente
        
        // Estatísticas de vendas
        $totalSales = $this->orderModel->getCountByStatus($userId);
        $approvedSales = $this->orderModel->getCountByStatus($userId, 'approved');
        $pendingSales = $this->orderModel->getCountByStatus($userId, 'pending');
        $rejectedSales = $this->orderModel->getCountByStatus($userId, 'rejected');
        
        // Informação da carteira
        $wallet = $this->walletModel->getByUserId($userId);
        if (!$wallet) {
            $walletId = $this->walletModel->create(['user_id' => $userId, 'balance' => 0]);
            $wallet = $this->walletModel->findById($walletId);
        }
        
        // Recentes vendas
        $recentOrders = $this->orderModel->getBySellerId($userId);
        $recentOrders = array_slice($recentOrders, 0, 5); // Limitar a 5 registros
        
        // Total de receita
        $totalRevenue = 0;
        $productSales = []; // Adicionar contagem de vendas por produto
        
        foreach ($products as $product) {
            $productId = $product['id'];
            $productSales[$productId] = $this->productModel->getApprovedSales($productId);
            $totalRevenue += $this->productModel->getTotalRevenue($productId);
        }
        
        // Dados da UTM - em um aplicativo real, você carregaria isso do seu banco de dados
        $utmData = $this->getSampleUtmData();
        
        // Calcular totais da UTM
        $totalVisits = array_sum(array_column($utmData['sources'], 'visits'));
        $totalConversions = array_sum(array_column($utmData['sources'], 'conversions'));
        $totalRevenueUtm = array_sum(array_column($utmData['sources'], 'revenue'));
        $conversionRate = $totalVisits > 0 ? ($totalConversions / $totalVisits) : 0;
        
        // Make sure to use the correct view path for dashboard index
        $this->render('dashboard/index', [
            'user' => $user,
            'products' => $products,
            'totalSales' => $totalSales,
            'approvedSales' => $approvedSales,
            'pendingSales' => $pendingSales,
            'rejectedSales' => $rejectedSales,
            'wallet' => $wallet,
            'recentOrders' => $recentOrders,
            'totalRevenue' => $totalRevenue,
            'productSales' => $productSales,
            'utmData' => $utmData,
            'totalVisits' => $totalVisits,
            'totalConversions' => $totalConversions,
            'totalRevenueUtm' => $totalRevenueUtm,
            'conversionRate' => $conversionRate
        ]);
    }
    
    /**
     * Display UTM analytics dashboard
     */
    public function utm() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('auth/login');
            return;
        }
        
        // Load necessary models
        require_once 'models/ProductModel.php';
        require_once 'models/UtmModel.php';
        
        $productModel = new ProductModel();
        $utmModel = new UtmModel();
        
        $userId = $_SESSION['user_id'];
        
        // Get filter parameters
        $period = isset($_GET['period']) ? (int)$_GET['period'] : 30;
        $productId = isset($_GET['product']) && $_GET['product'] !== 'all' ? (int)$_GET['product'] : null;
        
        // Get user's products for the dropdown
        $products = $productModel->getAllByUserId($userId);
        
        // Get period text for display
        $periodText = $this->getPeriodText($period);
        
        // Get selected product text for display
        $selectedProduct = 'Todos os produtos';
        if ($productId) {
            foreach ($products as $product) {
                if ($product['id'] == $productId) {
                    $selectedProduct = $product['title'];
                    break;
                }
            }
        }
        
        // Get UTM analytics data
        $utmData = $utmModel->getUtmAnalytics($userId, $period, $productId);
        $sourceData = $utmModel->getSourceData($userId, $period, $productId);
        $campaigns = $utmModel->getCampaignData($userId, $period, $productId);
        $stats = $utmModel->getSummaryStats($userId, $period, $productId);
        
        // Pass data to view
        $this->render('dashboard/utm', compact(
            'products', 
            'utmData', 
            'sourceData', 
            'campaigns', 
            'stats', 
            'period', 
            'periodText', 
            'productId', 
            'selectedProduct'
        ));
    }
    
    /**
     * Get period text for display
     */
    private function getPeriodText($period) {
        switch($period) {
            case 7:
                return 'Últimos 7 dias';
            case 30:
                return 'Últimos 30 dias';
            case 90:
                return 'Últimos 90 dias';
            default:
                return 'Todo o período';
        }
    }
    
    /**
     * Get dashboard statistics
     */
    private function getDashboardStats() {
        // Products count
        $productModel = new ProductModel();
        $productsCount = $productModel->countProducts();
        
        // Sales stats (placeholder - you should implement the actual models)
        // In a real application, you'd get this data from your database
        return [
            'products_count' => $productsCount ?? 0,
            'sales_count' => 125, // Placeholder
            'total_revenue' => 12500.00, // Placeholder
            'conversion_rate' => 0.15, // Placeholder
        ];
    }
    
    /**
     * Get sample UTM data
     * In a real app, you would load this from your database
     * 
     * @return array UTM data
     */
    private function getSampleUtmData() {
        return [
            'sources' => [
                ['label' => 'Google', 'visits' => 423, 'conversions' => 87, 'revenue' => 8745.00],
                ['label' => 'Facebook', 'visits' => 318, 'conversions' => 52, 'revenue' => 5180.00],
                ['label' => 'Instagram', 'visits' => 265, 'conversions' => 43, 'revenue' => 4300.00],
                ['label' => 'Direct', 'visits' => 189, 'conversions' => 35, 'revenue' => 3150.00],
                ['label' => 'Email', 'visits' => 156, 'conversions' => 29, 'revenue' => 2700.00],
            ],
            'campaigns' => [
                ['label' => 'Summer Sale', 'visits' => 286, 'conversions' => 58, 'revenue' => 5800.00],
                ['label' => 'Black Friday', 'visits' => 241, 'conversions' => 47, 'revenue' => 4700.00],
                ['label' => 'Launch Promo', 'visits' => 197, 'conversions' => 37, 'revenue' => 3550.00],
            ],
            'daily' => [
                ['date' => '01/06', 'visits' => 45, 'conversions' => 8],
                ['date' => '02/06', 'visits' => 52, 'conversions' => 11],
                ['date' => '03/06', 'visits' => 49, 'conversions' => 9],
                ['date' => '04/06', 'visits' => 63, 'conversions' => 15],
                ['date' => '05/06', 'visits' => 58, 'conversions' => 12],
                ['date' => '06/06', 'visits' => 47, 'conversions' => 8],
                ['date' => '07/06', 'visits' => 51, 'conversions' => 10],
            ],
        ];
    }
}
