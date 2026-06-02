<?php
require_once 'database.php';
require_once 'control_tasks.php';

$controller = new TaskController($pdo);

// получаем ID задачи, которую редактируем
$id = isset($_GET['id']) ? $_GET['id'] : 0;

$controller->updateTask($id);

// получаем данные для отображения
$task = $controller->getTaskForEdit($id);

//переводим датуи время в форматы даты и времени
$date = date('Y-m-d', strtotime($task['datetime']));
$time = date('H:i', strtotime($task['datetime']));
$types = ['встреча', 'звонок', 'совещание', 'дело'];
$statuses = ['текущая', 'выполнена', 'просрочена'];
?>

<html>
<head>
    <meta charset="UTF-8">
    <link href="style.css" type="text/css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <div class="form-card">
            <h1>Редактирование задачи</h1>
            <form method="POST">
                <div class="form-group">
                    <label>Тема задачи</label>
                    <input type="text" name="topic" value="<?php echo htmlspecialchars($task['topic']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Тип</label>
                    <select name="type">
                        <?php foreach($types as $type): ?>
                            <option value="<?php echo $type; ?>" <?php echo $task['type'] == $type ? 'selected' : ''; ?>>
                                <?php echo ucfirst($type); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Место</label>
                    <input type="text" name="place" value="<?php echo htmlspecialchars($task['place']); ?>">
                </div>
                
                <div class="form-group">
                    <label>Дата и время</label>
                    <div class="datetime-group">
                        <input type="date" name="date" value="<?php echo $date; ?>" required>
                        <input type="time" name="time" value="<?php echo $time; ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Длительность</label>
                    <input type="number" name="duration" value="<?php echo $task['duration']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Комментарий</label>
                    <textarea name="comment" rows="3"><?php echo htmlspecialchars($task['comment']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Статус</label>
                    <select name="status">
                        <?php foreach($statuses as $status): ?>
                            <option value="<?php echo $status; ?>" <?php echo $task['status'] == $status ? 'selected' : ''; ?>>
                                <?php echo ucfirst($status); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <button type="submit">Сохранить</button>
                    <a href="index.php" class="cancel-btn">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>