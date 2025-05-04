<?php
class UserModel {
    private $conn;
    private $table = 'users';
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  SET name = :name, 
                      email = :email, 
                      password = :password";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize data
        $data['name'] = htmlspecialchars(strip_tags($data['name']));
        $data['email'] = htmlspecialchars(strip_tags($data['email']));
        
        // Bind values
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        
        return false;
    }
    
    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        
        $email = htmlspecialchars(strip_tags($email));
        $stmt->bindParam(':email', $email);
        
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
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
    
    public function update($id, $data) {
        try {
            // Verificar se a tabela users possui as colunas necessárias
            $this->ensureTransferPasswordColumns();
            
            $query = "UPDATE users SET ";
            $params = [];
            
            foreach ($data as $key => $value) {
                $params[] = "$key = :$key";
            }
            
            $query .= implode(", ", $params);
            $query .= " WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao atualizar usuário: ' . $e->getMessage());
            return false;
        }
    }

    // Método para garantir que a tabela users tenha as colunas necessárias para a senha de transferência
    private function ensureTransferPasswordColumns() {
        try {
            // Verificar se a coluna transfer_password_enabled existe
            $columns = $this->conn->query("SHOW COLUMNS FROM users LIKE 'transfer_password_enabled'")->fetchAll();
            if (empty($columns)) {
                $this->conn->exec("ALTER TABLE users ADD COLUMN transfer_password_enabled TINYINT DEFAULT 0");
            }
            
            // Verificar se a coluna transfer_password existe
            $columns = $this->conn->query("SHOW COLUMNS FROM users LIKE 'transfer_password'")->fetchAll();
            if (empty($columns)) {
                $this->conn->exec("ALTER TABLE users ADD COLUMN transfer_password VARCHAR(255) DEFAULT NULL");
            }
            
            return true;
        } catch (PDOException $e) {
            error_log('Erro ao verificar/adicionar colunas: ' . $e->getMessage());
            return false;
        }
    }

    public function updatePassword($id, $hashedPassword) {
        $query = "UPDATE users SET password = :password WHERE id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':password', $hashedPassword);
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao atualizar senha: ' . $e->getMessage());
            return false;
        }
    }
    
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}
