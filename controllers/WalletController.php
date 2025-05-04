<?php
class WalletController {
    private $walletModel;
    private $transferModel;
    private $userModel;
    
    public function __construct() {
        // Verificar se o usuário está logado
        if (!isset($_SESSION['user_id'])) {
            setFlash('error', 'Você precisa estar logado para acessar sua carteira.');
            redirect('auth/login');
        }
        
        require_once 'models/WalletModel.php';
        require_once 'models/TransferModel.php';
        require_once 'models/UserModel.php';
        
        $this->walletModel = new WalletModel();
        $this->transferModel = new TransferModel();
        $this->userModel = new UserModel();
    }
    
    public function index() {
        $userId = $_SESSION['user_id'];
        
        // Obter carteira do usuário
        $wallet = $this->walletModel->getByUserId($userId);
        
        // Se não tiver carteira, criar uma
        if (!$wallet) {
            $walletId = $this->walletModel->create([
                'user_id' => $userId,
                'balance' => 0
            ]);
            $wallet = $this->walletModel->findById($walletId);
        }
        
        // Obter transferências
        $transfers = [];
        if ($wallet) {
            $transfers = $this->transferModel->getByWalletId($wallet['id']);
        }
        
        view('wallet/index', compact('wallet', 'transfers'));
    }
    
    public function transfer() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('wallet/index');
        }
        
        // Validar o token CSRF
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token de segurança inválido!');
            redirect('wallet/index');
        }
        
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        $amount = filter_var($_POST['amount'] ?? 0, FILTER_VALIDATE_FLOAT);
        $pix_key = sanitize($_POST['pix_key'] ?? '');
        
        $errors = [];
        
        // Verificar senha de transferência se estiver habilitada
        if (isset($user['transfer_password_enabled']) && $user['transfer_password_enabled']) {
            $transferPassword = $_POST['transfer_password'] ?? '';
            
            if (empty($transferPassword)) {
                $errors['transfer_password'] = 'A senha de transferência é obrigatória';
            } elseif (!password_verify($transferPassword, $user['transfer_password'])) {
                $errors['transfer_password'] = 'Senha de transferência incorreta';
            }
        }
        
        if ($amount <= 0) {
            $errors['amount'] = 'O valor deve ser maior que zero';
        }
        
        if (empty($pix_key)) {
            $errors['pix_key'] = 'A chave PIX é obrigatória';
        }
        
        if (empty($errors)) {
            $wallet = $this->walletModel->getByUserId($userId);
            
            if (!$wallet || $wallet['balance'] < $amount) {
                setFlash('error', 'Saldo insuficiente para realizar a transferência.');
                redirect('wallet/index');
            }
            
            // Começar o processo de transferência
            // 1. Reduzir o saldo da carteira
            if ($this->walletModel->withdraw($wallet['id'], $amount)) {
                // 2. Criar o registro de transferência
                $transferId = $this->transferModel->create([
                    'wallet_id' => $wallet['id'],
                    'amount' => $amount,
                    'pix_key' => $pix_key,
                    'status' => 'pending'
                ]);
                
                if ($transferId) {
                    // 3. Processar a transferência (simulado)
                    $this->transferModel->processTransfer($transferId);
                    
                    setFlash('success', 'Transferência realizada com sucesso!');
                } else {
                    // Se não conseguir criar a transferência, devolver o dinheiro
                    $this->walletModel->addFunds($wallet['id'], $amount);
                    setFlash('error', 'Erro ao processar a transferência.');
                }
            } else {
                setFlash('error', 'Erro ao processar a transferência.');
            }
        } else {
            $_SESSION['errors'] = $errors;
        }
        
        redirect('wallet/index');
    }
}
