<?php
class OrderModel {
    private $conn;
    private $table = 'orders';
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  SET product_id = :product_id, 
                      buyer_id = :buyer_id, 
                      status = :status, 
                      payment_method = :payment_method,
                      transaction_id = :transaction_id,
                      amount = :amount,
                      buyer_email = :buyer_email,
                      buyer_name = :buyer_name";
        
        $stmt = $this->conn->prepare($query);
        
        // Set default values if not provided
        $data['status'] = $data['status'] ?? 'pending';
        $data['transaction_id'] = $data['transaction_id'] ?? null;
        
        // Sanitize data
        $data['buyer_email'] = htmlspecialchars(strip_tags($data['buyer_email']));
        $data['buyer_name'] = htmlspecialchars(strip_tags($data['buyer_name']));
        $data['payment_method'] = htmlspecialchars(strip_tags($data['payment_method']));
        
        // Bind values
        $stmt->bindParam(':product_id', $data['product_id']);
        $stmt->bindParam(':buyer_id', $data['buyer_id']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':payment_method', $data['payment_method']);
        $stmt->bindParam(':transaction_id', $data['transaction_id']);
        $stmt->bindParam(':amount', $data['amount']);
        $stmt->bindParam(':buyer_email', $data['buyer_email']);
        $stmt->bindParam(':buyer_name', $data['buyer_name']);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        
        return false;
    }
    
    public function findById($id) {
        $query = "SELECT o.*, p.title as product_title, p.price as product_price, p.slug as product_slug, 
                         u.name as seller_name, u.email as seller_email
                  FROM " . $this->table . " o
                  LEFT JOIN products p ON o.product_id = p.id
                  LEFT JOIN users u ON p.user_id = u.id
                  WHERE o.id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        return false;
    }
    
    public function getByProductId($productId) {
        $query = "SELECT * FROM " . $this->table . " WHERE product_id = :product_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
        
        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orders[] = $row;
        }
        
        return $orders;
    }
    
    public function getBySellerId($userId) {
        $query = "SELECT o.*, p.title as product_title, u.name as buyer_name 
                  FROM " . $this->table . " o
                  LEFT JOIN products p ON o.product_id = p.id
                  LEFT JOIN users u ON o.buyer_id = u.id
                  WHERE p.user_id = :user_id
                  ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        
        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orders[] = $row;
        }
        
        return $orders;
    }
    
    public function getByBuyerId($userId) {
        $query = "SELECT o.*, p.title as product_title, p.price as product_price, u.name as seller_name 
                  FROM " . $this->table . " o
                  LEFT JOIN products p ON o.product_id = p.id
                  LEFT JOIN users u ON p.user_id = u.id
                  WHERE o.buyer_id = :user_id
                  ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        
        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orders[] = $row;
        }
        
        return $orders;
    }
    
    public function updateStatus($id, $status, $transactionId = null) {
        $query = "UPDATE " . $this->table . " SET status = :status";
        
        if ($transactionId !== null) {
            $query .= ", transaction_id = :transaction_id";
        }
        
        $query .= " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        
        if ($transactionId !== null) {
            $stmt->bindParam(':transaction_id', $transactionId);
        }
        
        if ($stmt->execute()) {
            // Se for aprovado, adicionar valor à carteira do vendedor
            if ($status === 'approved') {
                $order = $this->findById($id);
                if ($order) {
                    require_once 'models/ProductModel.php';
                    $productModel = new ProductModel();
                    $product = $productModel->findById($order['product_id']);
                    
                    if ($product) {
                        require_once 'models/WalletModel.php';
                        $walletModel = new WalletModel();
                        $wallet = $walletModel->getByUserId($product['user_id']);
                        
                        if ($wallet) {
                            $walletModel->addFunds($wallet['id'], $order['amount']);
                        } else {
                            $walletId = $walletModel->create([
                                'user_id' => $product['user_id'],
                                'balance' => 0
                            ]);
                            if ($walletId) {
                                $walletModel->addFunds($walletId, $order['amount']);
                            }
                        }
                    }
                }
            }
            
            return true;
        }
        
        return false;
    }
    
    public function getCountByStatus($userId, $status = null) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " o
                  LEFT JOIN products p ON o.product_id = p.id
                  WHERE p.user_id = :user_id";
        
        if ($status !== null) {
            $query .= " AND o.status = :status";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        
        if ($status !== null) {
            $stmt->bindParam(':status', $status);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['count'] ?? 0;
    }
}
