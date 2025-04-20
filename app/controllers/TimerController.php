<?php

require_once 'core/Controller.php';
require_once 'app/models/SessionModel.php';

class TimerController extends Controller 
{
    private $sessionModel;
    private $config;
    
    public function __construct() 
    {
        $this->sessionModel = new SessionModel();
        $this->config = require 'config/config.php';
    }
    
    public function index() 
    {
        return $this->view('index', [
            'timerSettings' => [
                'pomodoro' => $this->config['pomodoro_duration'],
                'shortBreak' => $this->config['short_break_duration'],
                'longBreak' => $this->config['long_break_duration'],
                'longBreakInterval' => $this->config['long_break_interval']
            ]
        ]);
    }
    
    public function startSession() 
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['error' => 'Method not allowed'], 405);
        }
        
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (!isset($data['session_type'])) {
            return $this->json(['error' => 'Session type is required'], 400);
        }
        
        $sessionData = [
            'session_type' => $data['session_type'],
            'start_time' => date('Y-m-d H:i:s'),
            'completed' => false
        ];
        
        // Add user_id if logged in
        if (isset($_SESSION['user_id'])) {
            $sessionData['user_id'] = $_SESSION['user_id'];
        }
        
        $sessionId = $this->sessionModel->createSession($sessionData);
        
        return $this->json([
            'success' => true,
            'session_id' => $sessionId,
            'start_time' => $sessionData['start_time']
        ]);
    }
    
    public function endSession() 
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['error' => 'Method not allowed'], 405);
        }
        
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (!isset($data['session_id'])) {
            return $this->json(['error' => 'Session ID is required'], 400);
        }
        
        $completed = isset($data['completed']) ? (bool)$data['completed'] : true;
        
        $updateData = [
            'end_time' => date('Y-m-d H:i:s'),
            'completed' => $completed
        ];
        
        $success = $this->sessionModel->updateSession($data['session_id'], $updateData);
        
        return $this->json([
            'success' => $success,
            'end_time' => $updateData['end_time'],
            'completed' => $completed
        ]);
    }
    
    public function getStats() 
    {
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        
        $stats = [
            'completed_sessions' => $this->sessionModel->getCompletedSessionsCount($userId),
            'session_stats' => $this->sessionModel->getSessionStats($userId)
        ];
        
        // Calculate total focus time in minutes
        $totalFocusTime = 0;
        foreach ($stats['session_stats'] as $stat) {
            if ($stat['session_type'] === 'pomodoro') {
                $totalFocusTime += $stat['total_seconds'] / 60;
            }
        }
        $stats['total_focus_minutes'] = round($totalFocusTime);
        
        return $this->json($stats);
    }
} 