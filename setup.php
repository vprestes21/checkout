<?php
// Script de configuração inicial

// Verificar se PHP está instalado e rodando
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    die('Você precisa do PHP 7.4 ou superior para executar esta aplicação.');
}

// Verificar extensões necessárias
$requiredExtensions = ['pdo', 'pdo_mysql', 'json', 'mbstring'];
$missingExtensions = [];

foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $missingExtensions[] = $ext;
    }
}

if (!empty($missingExtensions)) {
    die('Por favor, habilite as seguintes extensões PHP: ' . implode(', ', $missingExtensions));
}

// Verificar permissões de diretório
$directories = [
    __DIR__ . '/assets/uploads',
    __DIR__ . '/cache'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0755, true)) {
            die("Não foi possível criar o diretório: $dir");
        }
    }

    if (!is_writable($dir)) {
        die("O diretório $dir não tem permissão de escrita.");
    }
}

// Configurar banco de dados
require_once 'config/database.php';
$db = new Database();
$conn = $db->getConnection();

if ($conn) {
    // Primeiro, tentar aplicar a correção na tabela products
    $fixApplied = $db->fixProductsTable();
    
    // Em seguida, configurar o banco de dados
    if ($db->setup()) {
        echo "<h1>Configuração concluída com sucesso!</h1>";
        if ($fixApplied) {
            echo "<p>A estrutura da tabela products foi atualizada para corrigir problemas com o campo payment_methods.</p>";
        }
        echo "<p>Seu sistema CheckoutPro está pronto para uso.</p>";
        echo "<p>Acesse <a href='index.php'>a página inicial</a> para começar.</p>";
    } else {
        echo "<h1>Erro na criação das tabelas</h1>";
        echo "<p>Não foi possível criar as tabelas no banco de dados.</p>";
    }

    // Verificar e adicionar colunas para senha de transferência
    try {
        // Verificar se a coluna transfer_password_enabled existe
        $columns = $conn->query("SHOW COLUMNS FROM users LIKE 'transfer_password_enabled'")->fetchAll();
        if (empty($columns)) {
            $conn->exec("ALTER TABLE users ADD COLUMN transfer_password_enabled TINYINT DEFAULT 0");
            echo "<p>Coluna transfer_password_enabled adicionada com sucesso.</p>";
        }
        
        // Verificar se a coluna transfer_password existe
        $columns = $conn->query("SHOW COLUMNS FROM users LIKE 'transfer_password'")->fetchAll();
        if (empty($columns)) {
            $conn->exec("ALTER TABLE users ADD COLUMN transfer_password VARCHAR(255) DEFAULT NULL");
            echo "<p>Coluna transfer_password adicionada com sucesso.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Erro ao adicionar colunas para senha de transferência: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<h1>Erro de conexão</h1>";
    echo "<p>Não foi possível conectar ao banco de dados.</p>";
    echo "<p>Verifique as configurações em config/database.php</p>";
}
?>
