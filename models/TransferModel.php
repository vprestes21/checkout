<?php
class TransferModel {
    private $conn;
    private $table = 'transfers';
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  SET wallet_id = :wallet_id, 
                      amount = :amount,
                      pix_key = :pix_key,
                      status = :status";
        
        $stmt = $this->conn->prepare($query);
        
        // Set default values
        $data['status'] = $data['status'] ?? 'pending';
        
        // Sanitize data
        $data['pix_key'] = htmlspecialchars(strip_tags($data['pix_key']));
        
        // Bind values
        $stmt->bindParam(':wallet_id', $data['wallet_id']);
        $stmt->bindParam(':amount', $data['amount']);
        $stmt->bindParam(':pix_key', $data['pix_key']);
        $stmt->bindParam(':status', $data['status']);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        
        return false;
    }
    
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        return false;
    }
    
    public function getByWalletId($walletId) {
        $query = "SELECT * FROM " . $this->table . " WHERE wallet_id = :wallet_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':wallet_id', $walletId);
        $stmt->execute();
        
        $transfers = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $transfers[] = $row;
        }
        
        return $transfers;
    }
    
    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    public function processTransfer($transferId) {
        // Get transfer details
        $transfer = $this->findById($transferId);
        
        if (!$transfer || $transfer['status'] !== 'pending') {
            return false;
        }
        
        // In a real system, here you would call the PIX API to initiate the transfer
        // For example: $pixApiResponse = $pixApi->transfer($transfer['amount'], $transfer['pix_key']);
        
        // For this example, we'll simulate a successful transfer
        $success = true; // In production, check real API response
        
        if ($success) {
            $this->updateStatus($transferId, 'completed');
            return true;
        } else {
            $this->updateStatus($transferId, 'failed');
            // You would also need to refund the amount to the wallet
            require_once 'WalletModel.php';
            $walletModel = new WalletModel();
            $walletModel->addFunds($transfer['wallet_id'], $transfer['amount']);
            return false;
        }
    }
}
