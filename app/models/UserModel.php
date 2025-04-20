<?php

require_once 'core/Model.php';

class UserModel extends Model 
{
    protected $table = 'users';
    
    public function __construct() 
    {
        parent::__construct();
    }
    
    public function createUser($data) 
    {
        // Sanitize and validate user data
        $sanitizedData = [
            'username' => filter_var($data['username'], FILTER_SANITIZE_STRING),
            'email' => filter_var($data['email'], FILTER_SANITIZE_EMAIL),
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Validate email
        if (!filter_var($sanitizedData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }
        
        // Check if user already exists
        if ($this->findByEmail($sanitizedData['email'])) {
            throw new Exception('Email already in use');
        }
        
        return $this->create($sanitizedData);
    }
    
    public function findByEmail($email) 
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $statement = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $statement->execute(['email' => $email]);
        
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function verifyUser($email, $password) 
    {
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return false;
        }
        
        return password_verify($password, $user['password']) ? $user : false;
    }
    
    public function updateLastLogin($userId) 
    {
        $statement = $this->db->prepare("
            UPDATE {$this->table} 
            SET last_login = :last_login 
            WHERE id = :id
        ");
        
        return $statement->execute([
            'id' => $userId,
            'last_login' => date('Y-m-d H:i:s')
        ]);
    }
} 