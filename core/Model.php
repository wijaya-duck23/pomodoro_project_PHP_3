<?php

class Model 
{
    protected $db;
    protected $table;

    public function __construct() 
    {
        $config = require 'config/database.php';
        try {
            $this->db = new PDO(
                "mysql:host={$config['host']};dbname={$config['database']};charset=utf8",
                $config['username'],
                $config['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function findAll() 
    {
        $statement = $this->db->prepare("SELECT * FROM {$this->table}");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) 
    {
        $statement = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $statement->execute(['id' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) 
    {
        $keys = array_keys($data);
        $columns = implode(', ', $keys);
        $placeholders = ':' . implode(', :', $keys);
        
        $statement = $this->db->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        $statement->execute($data);
        
        return $this->db->lastInsertId();
    }

    public function update($id, $data) 
    {
        $updates = [];
        foreach (array_keys($data) as $key) {
            $updates[] = "{$key} = :{$key}";
        }
        $updates = implode(', ', $updates);
        
        $data['id'] = $id;
        $statement = $this->db->prepare("UPDATE {$this->table} SET {$updates} WHERE id = :id");
        return $statement->execute($data);
    }

    public function delete($id) 
    {
        $statement = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $statement->execute(['id' => $id]);
    }
} 