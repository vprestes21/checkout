<?php
class SettingsController {
    private $userModel;
    
    public function __construct() {
        // Verificar se o usuário está logado
        if (!isset($_SESSION['user_id'])) {
            setFlash('error', 'Você precisa estar logado para acessar as configurações.');
            redirect('auth/login');
        }
        
        require_once 'models/UserModel.php';
        $this->userModel = new UserModel();
    }
    
    public function index() {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        
        if (!$user) {
            setFlash('error', 'Usuário não encontrado.');
            redirect('dashboard/index');
        }
        
        view('settings/index', compact('user'));
    }
    
    public function profile() {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar CSRF
            if(!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Token de segurança inválido!');
                redirect('settings/profile');
            }
            
            $name = sanitize($_POST['name'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            
            $errors = [];
            
            if (empty($name)) {
                $errors['name'] = 'O nome é obrigatório';
            }
            
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email inválido';
            } elseif ($email !== $user['email']) {
                // Verificar se o email já está em uso
                $existingUser = $this->userModel->findByEmail($email);
                if ($existingUser && $existingUser['id'] != $userId) {
                    $errors['email'] = 'Este email já está em uso';
                }
            }
            
            if (empty($errors)) {
                $userData = [
                    'name' => $name,
                    'email' => $email
                ];
                
                if ($this->userModel->update($userId, $userData)) {
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_email'] = $email;
                    setFlash('success', 'Perfil atualizado com sucesso!');
                    redirect('settings/index');
                } else {
                    setFlash('error', 'Erro ao atualizar o perfil.');
                }
            } else {
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = [
                    'name' => $name,
                    'email' => $email
                ];
            }
            
            redirect('settings/profile');
        }
        
        $errors = $_SESSION['errors'] ?? [];
        $old = $_SESSION['old'] ?? $user;
        unset($_SESSION['errors'], $_SESSION['old']);
        
        $csrf_token = generateCsrfToken();
        view('settings/profile', compact('user', 'csrf_token', 'errors', 'old'));
    }
    
    public function password() {
        $userId = $_SESSION['user_id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar CSRF
            if(!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Token de segurança inválido!');
                redirect('settings/password');
            }
            
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            $errors = [];
            
            // Verificar a senha atual
            $user = $this->userModel->findById($userId);
            if (!$user || !password_verify($currentPassword, $user['password'])) {
                $errors['current_password'] = 'Senha atual incorreta';
            }
            
            // Validar nova senha
            if (empty($newPassword) || strlen($newPassword) < 6) {
                $errors['new_password'] = 'A nova senha deve ter pelo menos 6 caracteres';
            }
            
            // Confirmar nova senha
            if ($newPassword !== $confirmPassword) {
                $errors['confirm_password'] = 'As senhas não coincidem';
            }
            
            if (empty($errors)) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                if ($this->userModel->updatePassword($userId, $hashedPassword)) {
                    setFlash('success', 'Senha atualizada com sucesso!');
                    redirect('settings/index');
                } else {
                    setFlash('error', 'Erro ao atualizar a senha.');
                }
            } else {
                $_SESSION['errors'] = $errors;
            }
            
            redirect('settings/password');
        }
        
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        
        $csrf_token = generateCsrfToken();
        view('settings/password', compact('csrf_token', 'errors'));
    }
    
    public function transferPassword() {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar CSRF
            if(!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Token de segurança inválido!');
                redirect('settings/transferPassword');
            }
            
            $enablePassword = isset($_POST['enable_password']) ? 1 : 0;
            $transferPassword = $_POST['transfer_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            $errors = [];
            
            // Só validamos a senha se estiver habilitando a funcionalidade
            if ($enablePassword) {
                if (empty($transferPassword) || strlen($transferPassword) < 6) {
                    $errors['transfer_password'] = 'A senha deve ter pelo menos 6 caracteres';
                }
                
                if ($transferPassword !== $confirmPassword) {
                    $errors['confirm_password'] = 'As senhas não coincidem';
                }
            }
            
            if (empty($errors)) {
                $userData = [
                    'transfer_password_enabled' => $enablePassword
                ];
                
                // Só atualizamos a senha se estiver habilitando e tiver preenchido uma nova senha
                if ($enablePassword && !empty($transferPassword)) {
                    $userData['transfer_password'] = password_hash($transferPassword, PASSWORD_DEFAULT);
                }
                
                try {
                    if ($this->userModel->update($userId, $userData)) {
                        setFlash('success', 'Configurações de transferência atualizadas com sucesso!');
                        redirect('settings/index');
                    } else {
                        setFlash('error', 'Erro ao atualizar as configurações de transferência.');
                    }
                } catch (Exception $e) {
                    error_log($e->getMessage());
                    setFlash('error', 'Erro ao atualizar as configurações: ' . $e->getMessage());
                }
            } else {
                $_SESSION['errors'] = $errors;
            }
            
            redirect('settings/transferPassword');
        }
        
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        
        $csrf_token = generateCsrfToken();
        view('settings/transferPassword', compact('user', 'csrf_token', 'errors'));
    }
}
