<?php

require_once 'core/Model.php';

class SessionModel extends Model 
{
    protected $table = 'pomodoro_sessions';
    
    public function __construct() 
    {
        parent::__construct();
    }
    
    public function createSession($data) 
    {
        // Sanitize data
        $sanitizedData = [
            'user_id' => isset($data['user_id']) ? filter_var($data['user_id'], FILTER_SANITIZE_NUMBER_INT) : null,
            'session_type' => filter_var($data['session_type'], FILTER_SANITIZE_STRING),
            'start_time' => $data['start_time'],
            'end_time' => isset($data['end_time']) ? $data['end_time'] : null,
            'duration' => isset($data['duration']) ? filter_var($data['duration'], FILTER_SANITIZE_NUMBER_INT) : null,
            'completed' => isset($data['completed']) ? filter_var($data['completed'], FILTER_VALIDATE_BOOLEAN) : false
        ];
        
        return $this->create($sanitizedData);
    }
    
    public function updateSession($id, $data) 
    {
        // Sanitize data
        $sanitizedData = [];
        if (isset($data['end_time'])) {
            $sanitizedData['end_time'] = $data['end_time'];
        }
        if (isset($data['completed'])) {
            $sanitizedData['completed'] = filter_var($data['completed'], FILTER_VALIDATE_BOOLEAN);
        }
        
        return $this->update($id, $sanitizedData);
    }
    
    public function getSessionsByUserId($userId, $limit = 10) 
    {
        $userId = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
        $statement = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = :user_id 
            ORDER BY start_time DESC 
            LIMIT :limit
        ");
        $statement->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCompletedSessionsCount($userId = null) 
    {
        $sql = "SELECT COUNT(*) AS count FROM {$this->table} WHERE completed = 1";
        $params = [];
        
        if ($userId) {
            $sql .= " AND user_id = :user_id";
            $params[':user_id'] = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
        }
        
        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        
        return $statement->fetch(PDO::FETCH_ASSOC)['count'];
    }
    
    public function getSessionStats($userId = null) 
    {
        $sql = "
            SELECT 
                session_type, 
                COUNT(*) as count, 
                SUM(TIMESTAMPDIFF(SECOND, start_time, end_time)) as total_seconds
            FROM {$this->table} 
            WHERE completed = 1
        ";
        
        $params = [];
        if ($userId) {
            $sql .= " AND user_id = :user_id";
            $params[':user_id'] = filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
        }
        
        $sql .= " GROUP BY session_type";
        
        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
} 