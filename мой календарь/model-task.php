<?php
class Task {
    private $db;
    
    public function __construct($db) {
        $this->db = $db; 
    }
    
    public function addNewTask($data) {
        $sql = "INSERT INTO tasks (topic, type, place, datetime, duration, comment) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['topic'],
            $data['type'],
            $data['place'],
            $data['datetime'],
            $data['duration'],
            $data['comment']
        ]);
    }
    
    public function getCurrentTasks() {
        $sql = "SELECT * FROM tasks WHERE status = 'текущая' AND datetime >= NOW() 
                ORDER BY datetime;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOverdueTasks() {
        $sql = "SELECT * FROM tasks WHERE status = 'текущая' AND datetime < NOW() 
                ORDER BY datetime;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCompletedTasks() {
        $sql = "SELECT * FROM tasks WHERE status = 'выполнена' 
                ORDER BY datetime DESC;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTasksByDate($date) {
        $sql = "SELECT * FROM tasks WHERE DATE(datetime) = ? ORDER BY datetime;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTaskById($id) {
        $sql = "SELECT * FROM tasks WHERE id = ?;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTodayTask() {
        $sql = "SELECT * FROM tasks WHERE DATE(datetime) = CURDATE() ORDER BY datetime;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTommorowTask() {
        $sql = "SELECT * FROM tasks WHERE DATE(datetime) = DATE_ADD(CURDATE(), INTERVAL 1 DAY) ORDER BY datetime;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTaskOnThisWeek() {
        $sql = "SELECT * FROM tasks WHERE DATE(datetime) 
                BETWEEN 
                DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) 
                AND
                DATE_ADD(CURDATE(), INTERVAL (6 - WEEKDAY(CURDATE())) DAY) 
                ORDER BY datetime;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTaskOnNextWeek() {
        $sql = "SELECT * FROM tasks WHERE DATE(datetime) 
                BETWEEN 
                DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 7 DAY)
                AND
                DATE_ADD(DATE_ADD(CURDATE(), INTERVAL (6 - WEEKDAY(CURDATE())) DAY), INTERVAL 7 DAY)
                ORDER BY datetime;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function update($id, $data) {
        $sql = "UPDATE tasks SET topic = ?, type = ?, place = ?, datetime = ?, 
                duration = ?, comment = ?, status = ? WHERE id = ?;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['topic'],
            $data['type'],
            $data['place'],
            $data['datetime'],
            $data['duration'],
            $data['comment'],
            $data['status'],
            $id
        ]);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM tasks WHERE id = ?;";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>