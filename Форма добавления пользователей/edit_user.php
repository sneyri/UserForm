<?php
include("config.php");
session_start();

if (!isset($_GET['edit']) || empty($_GET['edit'])) {
    $_SESSION['message'] = "❌ Не указан пользователь для редактирования";
    $_SESSION['message_type'] = "error";
    header("Location: index.php");
    exit();
}

$edit_id = (int)$_GET['edit'];

$sql = "SELECT * FROM new_users WHERE id_user = $edit_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['message'] = "❌ Пользователь не найден";
    $_SESSION['message_type'] = "error";
    header("Location: index.php");
    exit();
}

$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование пользователя</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="edit-container">
        <h1>✏️ Редактирование пользователя</h1>
        
        <form action="update_user.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $user['id_user']; ?>">
            
            <label for="name">👤 Имя:</label>
            <input type="text"
                   id="name"
                   name="name"
                   value="<?php echo htmlspecialchars($user['user_name']); ?>"
                   required>
            
            <label for="age">📅 Возраст:</label>
            <input type="number"
                   id="age"
                   name="age"
                   value="<?php echo $user['user_age']; ?>"
                   min="1"
                   max="100"
                   required>
            
            <button type="submit">💾 Обновить данные</button>
        </form>
        
        <a href="index.php" class="back-link">← Вернуться к списку</a>
    </div>
</body>
</html>