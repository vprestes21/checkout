<?php
class AuthController {
    private $userModel;
    
    public function __construct() {
        require_once 'models/UserModel.php';
        $this->userModel = new UserModel();
    }
    
    public function register() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar CSRF
            if(!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Token de segurança inválido!');
                redirect('auth/register');
            }
            
            // Validar dados
            $name = sanitize($_POST['name'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            $errors = [];
            
            if(empty($name)) {
                $errors['name'] = 'Nome é obrigatório';
            }
            
            if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'E-mail inválido';
            }
            
            if(strlen($password) < 6) {
                $errors['password'] = 'A senha deve ter pelo menos 6 caracteres';
            }
            
            if($password !== $confirm_password) {
                $errors['confirm_password'] = 'As senhas não correspondem';
            }
            
            if(empty($errors)) {
                // Verificar se e-mail já existe
                if($this->userModel->findByEmail($email)) {
                    setFlash('error', 'Este e-mail já está em uso.');
                    redirect('auth/register');
                }
                
                // Criar usuário
                $userId = $this->userModel->create([
                    'name' => $name,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ]);
                
                if($userId) {
                    // Criar carteira para o usuário
                    require_once 'models/WalletModel.php';
                    $walletModel = new WalletModel();
                    $walletModel->create([
                        'user_id' => $userId,
                        'balance' => 0
                    ]);
                    
                    setFlash('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
                    redirect('auth/login');
                } else {
                    setFlash('error', 'Erro ao criar conta.');
                    redirect('auth/register');
                }
            } else {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = [
                    'name' => $name,
                    'email' => $email
                ];
                redirect('auth/register');
            }
        } else {
            $csrf_token = generateCsrfToken();
            $errors = $_SESSION['errors'] ?? [];
            $old = $_SESSION['old'] ?? [];
            unset($_SESSION['errors'], $_SESSION['old']);
            
            view('auth/register', compact('csrf_token', 'errors', 'old'));
        }
    }
    
    public function login() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar CSRF
            if(!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Token de segurança inválido!');
                redirect('auth/login');
            }
            
            $email = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if(empty($email) || empty($password)) {
                setFlash('error', 'E-mail e senha são obrigatórios.');
                redirect('auth/login');
            }
            
            $user = $this->userModel->findByEmail($email);
            
            if($user && password_verify($password, $user['password'])) {
                // Autenticar usuário
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                
                setFlash('success', 'Login realizado com sucesso!');
                redirect('dashboard/index'); // Ajustado para chamar o controlador corretamente
            } else {
                setFlash('error', 'E-mail ou senha incorretos.');
                redirect('auth/login');
            }
        } else {
            $csrf_token = generateCsrfToken();
            view('auth/login', compact('csrf_token'));
        }
    }
    
    public function logout() {
        session_destroy();
        redirect('');
    }
}
