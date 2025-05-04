<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'checkout_db';
    private $username = 'root';
    private $password = '';
    private $conn;
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $e) {
            echo 'Erro de conexão: ' . $e->getMessage();
        }
        
        return $this->conn;
    }
    
    public function setup() {
        // Criar tabelas necessárias se não existirem
        $tables = [
            "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
            "CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                price DECIMAL(10,2) NOT NULL,
                logo_url VARCHAR(255),
                logo_width INT DEFAULT 200,
                logo_height INT DEFAULT 200,
                payment_methods TEXT NOT NULL, /* Alterado de JSON para TEXT */
                template VARCHAR(50) DEFAULT 'modern',
                primary_color VARCHAR(7) DEFAULT '#3490dc',
                secondary_color VARCHAR(7) DEFAULT '#38c172',
                slug VARCHAR(255) UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )",
            "CREATE TABLE IF NOT EXISTS orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                buyer_id INT NOT NULL,
                status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                payment_method VARCHAR(20) NOT NULL,
                transaction_id VARCHAR(255),
                amount DECIMAL(10,2) NOT NULL,
                buyer_email VARCHAR(255) NOT NULL,
                buyer_name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE
            )",
            "CREATE TABLE IF NOT EXISTS wallets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                balance DECIMAL(12,2) DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )",
            "CREATE TABLE IF NOT EXISTS transfers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                wallet_id INT NOT NULL,
                amount DECIMAL(12,2) NOT NULL,
                pix_key VARCHAR(255) NOT NULL,
                status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (wallet_id) REFERENCES wallets(id) ON DELETE CASCADE
            )"
        ];
        
        try {
            foreach ($tables as $sql) {
                $this->conn->exec($sql);
            }
            return true;
        } catch(PDOException $e) {
            echo 'Erro ao criar tabelas: ' . $e->getMessage();
            return false;
        }
    }
    
    /* Adicionar método para verificar e modificar a estrutura da tabela */
    public function fixProductsTable() {
        try {
            // Verificar se a coluna payment_methods é do tipo JSON
            $query = "SHOW COLUMNS FROM products WHERE Field = 'payment_methods'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $column = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Se for JSON, alterar para TEXT
            if ($column && strpos(strtoupper($column['Type']), 'JSON') !== false) {
                $alterQuery = "ALTER TABLE products MODIFY COLUMN payment_methods TEXT NOT NULL";
                $this->conn->exec($alterQuery);
                return true;
            }
            
            return false;
        } catch(PDOException $e) {
            error_log('Erro ao modificar tabela products: ' . $e->getMessage());
            return false;
        }
    }
}
