<?php
class ProductModel {
    private $conn;
    private $table = 'products';
    private $db;

    public function __construct() {
        global $conn, $db;
        // Ensure connection is established
        if (!$conn) {
            error_log("ProductModel constructor: Global connection not found. Attempting to create new DB connection.");
            try {
                // Assuming Database class exists and is autoloaded or required elsewhere
                if (!class_exists('Database')) {
                    require_once __DIR__ . '/../core/Database.php'; // Adjust path if needed
                }
                $database = new Database(); 
                $conn = $database->getConnection();
            } catch (Exception $e) {
                error_log("Failed to create new DB connection in ProductModel: " . $e->getMessage());
                $conn = null;
            }
            
            if (!$conn) {
                error_log("FATAL: Database connection failed in ProductModel constructor after attempt.");
                // Depending on application structure, might need to exit or throw
                // For now, set conn to null and let methods fail gracefully
            }
        }
        $this->conn = $conn;
        $this->db = $db ?? null; // Initialize $db property

        if (!$this->conn) {
             error_log("ProductModel initialized with NULL connection.");
        }
    }

    // ... [create, findById, findBySlug methods remain the same] ...
    public function create($data) {
        // Check connection first
        if (!$this->conn) {
            error_log("ProductModel::create failed - No database connection.");
            return false;
        }
        $query = "INSERT INTO " . $this->table . " 
                  SET user_id = :user_id, 
                      title = :title, 
                      description = :description, 
                      price = :price,
                      logo_url = :logo_url,
                      logo_width = :logo_width,
                      logo_height = :logo_height,
                      payment_methods = :payment_methods,
                      template = :template,
                      primary_color = :primary_color,
                      secondary_color = :secondary_color,
                      slug = :slug";

        try {
            $stmt = $this->conn->prepare($query);

            // Sanitize data
            $data['title'] = htmlspecialchars(strip_tags($data['title']));
            $data['description'] = isset($data['description']) ? htmlspecialchars(strip_tags($data['description'])) : null;
            
            // Validate logo URL
            if (isset($data['logo_url']) && !empty($data['logo_url'])) {
                if (!filter_var($data['logo_url'], FILTER_VALIDATE_URL)) {
                    $data['logo_url'] = null; // Set to null if invalid
                }
            } else {
                $data['logo_url'] = null;
            }

            // Generate slug
            $data['slug'] = $this->generateSlug($data['title']);

            // Encode payment methods to JSON
            if (isset($data['payment_methods']) && is_array($data['payment_methods'])) {
                $data['payment_methods'] = json_encode($data['payment_methods']);
            } else {
                $data['payment_methods'] = json_encode(["pix", "card"]); // Default if not provided or invalid
            }

            // Default values for customization fields
            $data['template'] = $data['template'] ?? 'modern';
            $data['primary_color'] = $data['primary_color'] ?? '#3490dc';
            $data['secondary_color'] = $data['secondary_color'] ?? '#38c172';
            $data['logo_width'] = isset($data['logo_width']) && $data['logo_width'] > 0 ? (int)$data['logo_width'] : 200;
            $data['logo_height'] = isset($data['logo_height']) && $data['logo_height'] > 0 ? (int)$data['logo_height'] : 200;

            // Bind values
            $stmt->bindParam(':user_id', $data['user_id']);
            $stmt->bindParam(':title', $data['title']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':logo_url', $data['logo_url']);
            $stmt->bindParam(':logo_width', $data['logo_width']);
            $stmt->bindParam(':logo_height', $data['logo_height']);
            $stmt->bindParam(':payment_methods', $data['payment_methods']);
            $stmt->bindParam(':template', $data['template']);
            $stmt->bindParam(':primary_color', $data['primary_color']);
            $stmt->bindParam(':secondary_color', $data['secondary_color']);
            $stmt->bindParam(':slug', $data['slug']);

            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            error_log("ProductModel::create failed execution: " . implode(", ", $stmt->errorInfo()));
            return false;
        } catch (PDOException $e) {
            error_log('Error creating product: ' . $e->getMessage());
            return false;
        }
    }

    public function findById($id) {
         // Check connection first
        if (!$this->conn) {
            error_log("ProductModel::findById failed - No database connection.");
            return false;
        }
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                // Decode payment_methods
                $product['payment_methods'] = json_decode($product['payment_methods'] ?? '[]', true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $product['payment_methods'] = ["pix", "card"]; // Fallback
                }
                return $product;
            }
            return false;
        } catch (PDOException $e) {
            error_log('Error finding product by ID: ' . $e->getMessage());
            return false;
        }
    }

    public function findBySlug($slug) {
         // Check connection first
        if (!$this->conn) {
            error_log("ProductModel::findBySlug failed - No database connection.");
            return false;
        }
        $query = "SELECT * FROM " . $this->table . " WHERE slug = :slug";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':slug', $slug);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                // Decode payment_methods
                $product['payment_methods'] = json_decode($product['payment_methods'] ?? '[]', true);
                 if (json_last_error() !== JSON_ERROR_NONE) {
                    $product['payment_methods'] = ["pix", "card"]; // Fallback
                }
                return $product;
            }
            return false;
        } catch (PDOException $e) {
            error_log('Error finding product by Slug: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all products for a specific user
     * 
     * @param int $userId User ID
     * @return array Array of products
     */
    public function getAllByUserId($userId) {
        // For now, just return all products
        // In a real application, you would filter by user ID:
        // $sql = "SELECT * FROM products WHERE user_id = $userId ORDER BY id DESC";
        
        return $this->getProducts();
    }

    /**
     * Get all products
     * @return array Array of products
     */
    public function getProducts() {
        // Check connection first
        if (!$this->conn) {
            error_log("ProductModel::getProducts failed - No database connection.");
            return [];
        }
        
        $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            $products = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Decode payment_methods
                $row['payment_methods'] = json_decode($row['payment_methods'] ?? '[]', true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $row['payment_methods'] = ["pix", "card"]; // Fallback
                }
                $products[] = $row;
            }
            
            return $products;
        } catch (PDOException $e) {
            error_log('Error getting products: ' . $e->getMessage());
            return [];
        }
    }

    // --- UNIFIED UPDATE METHOD --- 
    public function update($data, $id)
    {
        if (!is_array($data)) {
            error_log("ProductModel::update - DATA NÃO É ARRAY: " . print_r($data, true));
            return false;
        }
        // Check connection first
        if (!$this->conn) {
            error_log("ProductModel::update failed - No database connection.");
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Erro de conexão com o banco de dados ao atualizar.']; 
            return false;
        }

        // Ensure id is an integer
        $id = (int)$id;
        
        try {
            // Store fields to update
            $fields = [];
            $values = [];
            
            // Manually construct fields list and values array
            foreach ($data as $key => $value) {
                // Skip id field - it should not be updated
                if ($key === 'id') continue;
                
                // Skip user_id, slug - they should not be updatable here
                if ($key === 'user_id' || $key === 'slug') continue;
                
                // Skip created_at/updated_at
                if ($key === 'created_at' || $key === 'updated_at') continue;
                
                // Add to fields and values arrays
                $fields[] = "`$key` = ?";
                $values[] = $value;
            }
            
            // Add updated_at timestamp
            $fields[] = "`updated_at` = NOW()";
            
            // Construct the update query
            $query = "UPDATE products SET " . implode(", ", $fields) . " WHERE id = ?";
            
            // Add ID to values array for the WHERE clause
            $values[] = $id;
            
            // Prepare and execute
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute($values);
            
            return $result;
        } catch (PDOException $e) {
            error_log('Error updating product: ' . $e->getMessage());
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Erro ao atualizar: ' . $e->getMessage()];
            return false;
        }
    }
    // --- END UNIFIED UPDATE METHOD ---

    public function delete($id) {
         // Check connection first
        if (!$this->conn) {
            error_log("ProductModel::delete failed - No database connection.");
            return false;
        }
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error deleting product: ' . $e->getMessage());
            return false;
        }
    }

    private function generateSlug($title) {
         // Check connection first
        if (!$this->conn) {
            error_log("ProductModel::generateSlug failed - No database connection.");
            // Generate a basic slug anyway, might not be unique
            return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title))) . '-' . substr(md5(time() . rand()), 0, 8);
        }
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $originalSlug = $slug;
        $counter = 1;

        // Check if slug exists and append counter if necessary
        while ($this->findBySlug($slug)) {
            $slug = $originalSlug . '-' . $counter++;
        }
        
        // Add unique hash just in case
        if ($counter > 1) {
             $slug .= '-' . substr(md5(time() . rand()), 0, 4);
        } else {
             $slug .= '-' . substr(md5(time() . rand()), 0, 8);
        }

        return $slug;
    }

    // --- STATS METHODS --- 
    public function getTotalSales($productId) {
         // Check connection first
        if (!$this->conn) return 0;
        $query = "SELECT COUNT(*) as total FROM orders WHERE product_id = :product_id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log('Error getting total sales: ' . $e->getMessage());
            return 0;
        }
    }

    public function getApprovedSales($productId) {
         // Check connection first
        if (!$this->conn) return 0;
        $query = "SELECT COUNT(*) as total FROM orders WHERE product_id = :product_id AND status = 'approved'";
         try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log('Error getting approved sales: ' . $e->getMessage());
            return 0;
        }
    }

    public function getTotalRevenue($productId) {
         // Check connection first
        if (!$this->conn) return 0;
        $query = "SELECT SUM(amount) as total FROM orders WHERE product_id = :product_id AND status = 'approved'";
         try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log('Error getting total revenue: ' . $e->getMessage());
            return 0;
        }
    }
    // --- END STATS METHODS ---

    // Helper function to check if a string is valid JSON
    private function isJson($string) {
        if (!is_string($string)) {
            return false;
        }
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    // Removed duplicate/incorrect methods: get($id) and the second update($data)

    // Count all products
    public function countProducts() {
        // Check connection first
        if (!$this->conn) {
            error_log("ProductModel::countProducts failed - No database connection.");
            return 0;
        }
        
        $query = "SELECT COUNT(*) as count FROM " . $this->table;
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($result['count'] ?? 0);
        } catch (PDOException $e) {
            error_log('Error counting products: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Execute direct update query with prepared statement
     * 
     * @param string $query The SQL query with placeholders
     * @param array $params Array of parameters to bind
     * @return bool Success status
     */
    public function executeUpdate($query, $params) {
        // Check connection first
        if (!$this->conn) {
            error_log("ProductModel::executeUpdate failed - No database connection.");
            return false;
        }
        
        try {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            // Log error
            error_log('ProductModel executeUpdate error: ' . $e->getMessage());
            return false;
        }
    }
}

