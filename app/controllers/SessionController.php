<?php

require_once 'core/Controller.php';
require_once 'app/models/UserModel.php';

class SessionController extends Controller 
{
    private $userModel;
    
    public function __construct() 
    {
        $this->userModel = new UserModel();
    }
    
    public function login() 
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['error' => 'Method not allowed'], 405);
        }
        
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->json(['error' => 'Email and password are required'], 400);
        }
        
        $user = $this->userModel->verifyUser($data['email'], $data['password']);
        
        if (!$user) {
            return $this->json(['error' => 'Invalid email or password'], 401);
        }
        
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Update last login time
        $this->userModel->updateLastLogin($user['id']);
        
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['logged_in'] = true;
        
        // Remove sensitive data before returning
        unset($user['password']);
        
        return $this->json([
            'success' => true,
            'user' => $user
        ]);
    }
    
    public function register() 
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['error' => 'Method not allowed'], 405);
        }
        
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        $requiredFields = ['username', 'email', 'password', 'password_confirm'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return $this->json(['error' => "Field '{$field}' is required"], 400);
            }
        }
        
        // Validate password
        if ($data['password'] !== $data['password_confirm']) {
            return $this->json(['error' => 'Passwords do not match'], 400);
        }
        
        if (strlen($data['password']) < 8) {
            return $this->json(['error' => 'Password must be at least 8 characters'], 400);
        }
        
        try {
            $userId = $this->userModel->createUser([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $data['password']
            ]);
            
            // Start session if not already started
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            // Set session variables for automatic login
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $data['username'];
            $_SESSION['logged_in'] = true;
            
            return $this->json([
                'success' => true,
                'user_id' => $userId
            ]);
            
        } catch (Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
    
    public function logout() 
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Unset all session variables
        $_SESSION = [];
        
        // If a session cookie is used, destroy it
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        // Destroy the session
        session_destroy();
        
        return $this->json(['success' => true]);
    }
    
    public function checkAuth() 
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            return $this->json(['authenticated' => false]);
        }
        
        return $this->json([
            'authenticated' => true,
            'user' => [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username']
            ]
        ]);
    }
} 