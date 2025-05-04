<?php
class UtmModel {
    private $conn;
    private $utmTable = 'utm_visits';
    private $conversionTable = 'orders';
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
        
        // Ensure the UTM tracking table exists
        $this->createUtmTableIfNotExists();
    }
    
    /**
     * Create UTM tracking table if it doesn't exist
     */
    private function createUtmTableIfNotExists() {
        $query = "CREATE TABLE IF NOT EXISTS {$this->utmTable} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            utm_source VARCHAR(255),
            utm_medium VARCHAR(255),
            utm_campaign VARCHAR(255),
            utm_term VARCHAR(255),
            utm_content VARCHAR(255),
            ip_address VARCHAR(45),
            user_agent TEXT,
            converted TINYINT(1) DEFAULT 0,
            conversion_id INT DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX (product_id),
            INDEX (utm_source),
            INDEX (utm_campaign)
        )";
        
        try {
            $this->conn->exec($query);
        } catch (PDOException $e) {
            error_log("Error creating UTM table: " . $e->getMessage());
        }
    }
    
    /**
     * Track a UTM visit
     * 
     * @param array $data Visit data including UTM parameters
     * @return int|bool ID of the tracked visit or false on failure
     */
    public function trackVisit($data) {
        $query = "INSERT INTO {$this->utmTable} 
                  (product_id, utm_source, utm_medium, utm_campaign, utm_term, utm_content, ip_address, user_agent)
                  VALUES (:product_id, :utm_source, :utm_medium, :utm_campaign, :utm_term, :utm_content, :ip_address, :user_agent)";
                  
        try {
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':product_id', $data['product_id']);
            $stmt->bindParam(':utm_source', $data['utm_source']);
            $stmt->bindParam(':utm_medium', $data['utm_medium']);
            $stmt->bindParam(':utm_campaign', $data['utm_campaign']);
            $stmt->bindParam(':utm_term', $data['utm_term']);
            $stmt->bindParam(':utm_content', $data['utm_content']);
            $stmt->bindParam(':ip_address', $data['ip_address']);
            $stmt->bindParam(':user_agent', $data['user_agent']);
            
            $stmt->execute();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error tracking UTM visit: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Mark a visit as converted
     * 
     * @param int $utmId UTM visit ID
     * @param int $conversionId Conversion/order ID
     * @return bool Success status
     */
    public function markAsConverted($utmId, $conversionId) {
        $query = "UPDATE {$this->utmTable} SET converted = 1, conversion_id = :conversion_id WHERE id = :id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':conversion_id', $conversionId);
            $stmt->bindParam(':id', $utmId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error marking UTM as converted: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get UTM analytics data for a specific user and time period
     * 
     * @param int $userId User ID
     * @param int $days Number of days to look back (0 for all time)
     * @param int|null $productId Optional product ID to filter by
     * @return array UTM analytics data
     */
    public function getUtmAnalytics($userId, $days = 30, $productId = null) {
        $dateClause = $days > 0 ? "AND u.created_at >= DATE_SUB(NOW(), INTERVAL {$days} DAY)" : "";
        $productClause = $productId ? "AND u.product_id = {$productId}" : "";
        
        $query = "SELECT 
                    u.utm_source,
                    u.utm_medium,
                    u.utm_campaign,
                    COUNT(DISTINCT u.id) as visits,
                    COUNT(DISTINCT CASE WHEN u.converted = 1 THEN u.id END) as conversions,
                    IFNULL(SUM(CASE WHEN u.converted = 1 THEN o.amount ELSE 0 END), 0) as revenue
                  FROM {$this->utmTable} u
                  LEFT JOIN {$this->conversionTable} o ON u.conversion_id = o.id
                  JOIN products p ON u.product_id = p.id
                  WHERE p.user_id = :user_id {$dateClause} {$productClause}
                  GROUP BY u.utm_source, u.utm_medium, u.utm_campaign
                  ORDER BY visits DESC";
                  
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting UTM analytics: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get UTM source data
     * 
     * @param int $userId User ID
     * @param int $days Number of days to look back
     * @param int|null $productId Optional product ID filter
     * @return array Source data
     */
    public function getSourceData($userId, $days = 30, $productId = null) {
        $dateClause = $days > 0 ? "AND u.created_at >= DATE_SUB(NOW(), INTERVAL {$days} DAY)" : "";
        $productClause = $productId ? "AND u.product_id = {$productId}" : "";
        
        $query = "SELECT 
                    IFNULL(u.utm_source, 'direct') as utm_source,
                    COUNT(DISTINCT u.id) as visits,
                    COUNT(DISTINCT CASE WHEN u.converted = 1 THEN u.id END) as conversions,
                    IFNULL(SUM(CASE WHEN u.converted = 1 THEN o.amount ELSE 0 END), 0) as revenue
                  FROM {$this->utmTable} u
                  LEFT JOIN {$this->conversionTable} o ON u.conversion_id = o.id
                  JOIN products p ON u.product_id = p.id
                  WHERE p.user_id = :user_id {$dateClause} {$productClause}
                  GROUP BY IFNULL(u.utm_source, 'direct')
                  ORDER BY visits DESC
                  LIMIT 10";
                  
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting source data: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get campaign data
     * 
     * @param int $userId User ID
     * @param int $days Number of days to look back
     * @param int|null $productId Optional product ID filter
     * @return array Campaign data
     */
    public function getCampaignData($userId, $days = 30, $productId = null) {
        $dateClause = $days > 0 ? "AND u.created_at >= DATE_SUB(NOW(), INTERVAL {$days} DAY)" : "";
        $productClause = $productId ? "AND u.product_id = {$productId}" : "";
        
        $query = "SELECT 
                    u.utm_campaign,
                    COUNT(DISTINCT u.id) as visits,
                    COUNT(DISTINCT CASE WHEN u.converted = 1 THEN u.id END) as conversions
                  FROM {$this->utmTable} u
                  JOIN products p ON u.product_id = p.id
                  WHERE p.user_id = :user_id 
                    AND u.utm_campaign IS NOT NULL 
                    AND u.utm_campaign != '' 
                    {$dateClause} {$productClause}
                  GROUP BY u.utm_campaign
                  ORDER BY visits DESC
                  LIMIT 10";
                  
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting campaign data: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get summary statistics
     * 
     * @param int $userId User ID
     * @param int $days Number of days to look back
     * @param int|null $productId Optional product ID filter
     * @return array Summary statistics
     */
    public function getSummaryStats($userId, $days = 30, $productId = null) {
        $dateClause = $days > 0 ? "AND u.created_at >= DATE_SUB(NOW(), INTERVAL {$days} DAY)" : "";
        $productClause = $productId ? "AND u.product_id = {$productId}" : "";
        
        $query = "SELECT 
                    COUNT(DISTINCT u.id) as total_visits,
                    COUNT(DISTINCT CASE WHEN u.converted = 1 THEN u.id END) as total_conversions,
                    IFNULL(SUM(CASE WHEN u.converted = 1 THEN o.amount ELSE 0 END), 0) as total_revenue
                  FROM {$this->utmTable} u
                  LEFT JOIN {$this->conversionTable} o ON u.conversion_id = o.id
                  JOIN products p ON u.product_id = p.id
                  WHERE p.user_id = :user_id {$dateClause} {$productClause}";
                  
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Calculate conversion rate
            $result['conversion_rate'] = $result['total_visits'] > 0 ? 
                $result['total_conversions'] / $result['total_visits'] : 0;
                
            return $result;
        } catch (PDOException $e) {
            error_log("Error getting summary stats: " . $e->getMessage());
            return [
                'total_visits' => 0,
                'total_conversions' => 0,
                'total_revenue' => 0,
                'conversion_rate' => 0
            ];
        }
    }
}
?>
