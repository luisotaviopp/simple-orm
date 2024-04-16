<?php 
class Model {
    protected $table;
    protected $db;

    public function __construct($table, $db) {
        $this->table = $table;
        $this->db = $db;
    }

    public function select(int $id, array $fields = []): array {
        $columns = "*";
    
        if (!empty($fields)) {
            $columns = implode(", ", $fields);
        }
    
        $stmt = $this->db->prepare("SELECT {$columns} FROM {$this->table} WHERE id = :id");
    
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    
        $result = $stmt->execute();
    
        $row = $result->fetchArray(SQLITE3_ASSOC);
    
        $stmt->close();
    
        // Check if user is not found
        if (!$row) {
            return ["message" => "Id {$id} not found"];
        }
    
        return $row;
    }

    public function list(int $limit, array $fields = []): array {
        $columns = "*";
    
        if (!empty($fields)) {
            $columns = implode(", ", $fields);
        }
    
        $stmt = $this->db->prepare("SELECT {$columns} FROM {$this->table} LIMIT :limit");
        $stmt->bindValue(':limit', $limit, SQLITE3_INTEGER);
    
        $result = $stmt->execute();
    
        $users = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $users[] = $row;
        }
    
        $stmt->close();
    
        if (empty($users)) {
            return ["message" => "No {$this->table} found"];
        }
    
        return $users;
    }    

    public function insert(array $data): bool {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), '?'));
    
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
    
        if (!$stmt) {
            return false;
        }
    
        $i = 1;

        foreach ($data as $value) {
            $stmt->bindValue($i++, $value);
        }
    
        $result = $stmt->execute();
    
        if (!$result) {
            return false;
        }
    
        $stmt->close();
    
        return true;
    }
    

    public function update($id, array $data): bool {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(" = ?, ", array_keys($data)) . " = ?";
    
        $stmt = $this->db->prepare("UPDATE {$this->table} SET $placeholders WHERE id = :id");
    
        if (!$stmt) {
            return false;
        }
    
        $i = 1;
        foreach ($data as $value) {
            $stmt->bindValue($i++, $value);
        }
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    
        $result = $stmt->execute();
    
        if (!$result) {
            return false;
        }
    
        $stmt->close();
    
        return true;
    }
    

    public function delete($id): bool {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET is_active = 0 WHERE id = :id");
    
        if (!$stmt) {
            return false;
        }
    
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    
        $result = $stmt->execute();
    
        if (!$result) {
            return false;
        }
    
        $stmt->close();
    
        return true;
    }

    public function reactivate($id): bool {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET is_active = 1 WHERE id = :id");
    
        if (!$stmt) {
            return false;
        }
    
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    
        $result = $stmt->execute();
    
        if (!$result) {
            return false;
        }
    
        $stmt->close();
    
        return true;
    }
}