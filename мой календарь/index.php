<?php
require_once 'database.php';
require_once 'control_tasks.php';

$controller = new TaskController($pdo);
$controller->addTask();


if (isset($_GET['delete_id'])) {
    $controller->deleteTask($_GET['delete_id']);
}

$tasks = $controller->getTasksForMain();
$current_filter = isset($_GET['filter']) ? $_GET['filter'] : 'current';
?>

<html>
<head>
    <meta charset="UTF-8">
    <link href="style.css" type="text/css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <h1>Мой календарь</h1>
        
        <div class="form-card">
            <h2>Новая задача</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Тема</label>
                    <input type="text" name="topic" required>
                </div>
                
                <div class="form-group">
                    <label>Тип</label>
                    <select name="type">
                        <option value="встреча">Встреча</option>
                        <option value="звонок">Звонок</option>
                        <option value="совещание">Совещание</option>
                        <option value="дело">Дело</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Место</label>
                    <input type="text" name="place">
                </div>
                
                <div class="form-group">
                    <label>Дата и время</label>
                    <div class="datetime-group">
                        <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>">
                        <input type="time" name="time" required value="12:00">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Длительность</label>
                    <select name="duration">
                        <option value="30">30 минут</option>
                        <option value="1">1 час</option>
                        <option value="2">2 часа</option>
                        <option value="3">3 часа</option>
                        <option value=">3">Более 3 часов</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Комментарий</label>
                    <textarea name="comment" rows="3"></textarea>
                </div>
                
                <button type="submit">Добавить</button>
            </form>
        </div>
        
        <div class="nav-buttons">
            <a href="?filter=current" class="nav-btn <?php echo $current_filter == 'current' ? 'active' : ''; ?>">Текущие</a>
            <a href="?filter=overdue" class="nav-btn <?php echo $current_filter == 'overdue' ? 'active' : ''; ?>">Просроченные</a>
            <a href="?filter=completed" class="nav-btn <?php echo $current_filter == 'completed' ? 'active' : ''; ?>">Выполненные</a>
        </div>
        
        <h2>
            <?php
            switch($current_filter) {
                case 'current': echo "Текущие задачи"; break;
                case 'overdue': echo "Просроченные задачи"; break;
                case 'completed': echo "Выполненные задачи"; break;
                default: echo "Задачи";
            }
            ?>
        </h2>
        
        <?php if(empty($tasks)): ?>
            <div class="empty-message">
                <p>Пока у вас нет задач</p>
            </div>
        <?php else: ?>
            <table class="task-table">
                <thead>
                    <tr>
                        <th>Тип</th>
                        <th>Задача</th>
                        <th>Место</th>
                        <th>Дата и время</th>
                        <th>Длительность</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tasks as $task): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['type']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $task['id']; ?>">
                                <?php echo htmlspecialchars($task['topic']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($task['place'] ?: '-'); ?></td>
                        <td><?php echo date('d.m.Y H:i', strtotime($task['datetime'])); ?></td>
                        <td><?php echo $task['duration']; ?> ч</td>
                        <td>
                            <a href="edit.php?id=<?php echo $task['id']; ?>" class="edit-btn">Изменить</a>
                            <a href="?delete_id=<?php echo $task['id']; ?>" class="delete-btn">Удалить</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>