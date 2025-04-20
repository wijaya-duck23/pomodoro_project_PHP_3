-- Create pomodoro_app database
CREATE DATABASE IF NOT EXISTS pomodoro_app;
USE pomodoro_app;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL,
    last_login DATETIME DEFAULT NULL
);

-- Pomodoro session table
CREATE TABLE IF NOT EXISTS pomodoro_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    session_type VARCHAR(50) NOT NULL COMMENT 'pomodoro, shortBreak, longBreak',
    start_time DATETIME NOT NULL,
    end_time DATETIME NULL,
    duration INT NULL COMMENT 'Duration in seconds',
    completed BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Create indices for better performance
CREATE INDEX idx_sessions_user_id ON pomodoro_sessions(user_id);
CREATE INDEX idx_sessions_session_type ON pomodoro_sessions(session_type);
CREATE INDEX idx_sessions_start_time ON pomodoro_sessions(start_time);
CREATE INDEX idx_sessions_completed ON pomodoro_sessions(completed); 