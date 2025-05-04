<?php
class WalletModel {
    private $conn;
    private $table = 'wallets';
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  SET user_id = :user_id, 
                      balance = :balance";
        
        $stmt = $this->conn->prepare($query);
        
        // Set default values
        $data['balance'] = $data['balance'] ?? 0;
        
        // Bind values
        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':balance', $data['balance']);
        
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
    
    public function getByUserId($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        return false;
    }
    
    public function addFunds($walletId, $amount) {
        $query = "UPDATE " . $this->table . " SET balance = balance + :amount WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':id', $walletId);
        
        return $stmt->execute();
    }
    
    public function withdraw($walletId, $amount) {
        // First check if there are sufficient funds
        $wallet = $this->findById($walletId);
        
        if (!$wallet || $wallet['balance'] < $amount) {
            return false;
        }
        
        // Begin transaction
        $this->conn->beginTransaction();
        
        try {
            // Update wallet balance
            $queryWallet = "UPDATE " . $this->table . " SET balance = balance - :amount WHERE id = :id";
            $stmtWallet = $this->conn->prepare($queryWallet);
            
            $stmtWallet->bindParam(':amount', $amount);
            $stmtWallet->bindParam(':id', $walletId);
            
            $stmtWallet->execute();
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
