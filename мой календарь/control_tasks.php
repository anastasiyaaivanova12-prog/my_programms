<?php
require_once 'model-task.php';

class TaskController {
    private $taskModel;
    
    public function __construct($db) {
        $this->taskModel = new Task($db);
    }
    
    public function addTask() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datetime = $_POST['date'] . ' ' . $_POST['time'];
            
            $data = [
                'topic' => $_POST['topic'],      
                'type' => $_POST['type'],
                'place' => $_POST['place'],     
                'datetime' => $datetime,
                'duration' => $_POST['duration'],
                'comment' => $_POST['comment']
            ];
            
            $this->taskModel->addNewTask($data);  
            header('Location: index.php');
            exit();
        }
    }
    
    public function getTasksForMain() {
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'current';
        
        switch($filter) {
            case 'overdue':
                return $this->taskModel->getOverdueTasks();
            case 'completed':
                return $this->taskModel->getCompletedTasks();
            default:
                return $this->taskModel->getCurrentTasks();
        }
    }
    
    public function getTaskForEdit($id) {
        return $this->taskModel->getTaskById($id);
    }
    
    public function updateTask($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datetime = $_POST['date'] . ' ' . $_POST['time'];
            
            $data = [
                'topic' => $_POST['topic'],       
                'type' => $_POST['type'],
                'place' => $_POST['place'],     
                'datetime' => $datetime,
                'duration' => $_POST['duration'],
                'comment' => $_POST['comment'],
                'status' => $_POST['status']
            ];
            
            $this->taskModel->update($id, $data);  
            header('Location: index.php');
            exit();
        }
    }
    
    public function deleteTask($id) {
        $this->taskModel->delete($id);
        header('Location: index.php');
        exit();
    }
}
?>