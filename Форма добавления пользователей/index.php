<?php
include("config.php");

$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET["search"]);
}

if (!empty($search)) {
    $safe_search = mysqli_real_escape_string($conn, $search);
    $sql = "SELECT * FROM new_users
            WHERE user_name LIKE '%$safe_search%'
            ORDER BY id_user DESC";
} else {
    $sql = "SELECT * FROM new_users ORDER BY id_user DESC";
}

$result = mysqli_query($conn, $sql);

$message = '';
$message_type = '';
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заполнение формы</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="hero">
        <h1>Страница заполнения формы</h1>

        <?php if (!empty($message)): ?>
            <div class="<?php echo $message_type; ?>">
                <?php echo $message ?>
            </div>
        <?php endif; ?>

        <div class="search-container">
            <form action="" method="get">
                <input type="text"
                    name="search"
                    class="search-input"
                    value="<?php echo htmlspecialchars($search); ?>"
                    placeholder="Поиск по имени...">
                <button type="submit" class="search-btn">Найти</button>

                <?php if (!empty($search)): ?>
                    <a href="index.php" class="clear-btn">✖ Сбросить</a>
                <?php endif; ?>
            </form>

            <?php if (!empty($search)): ?>
                <div class="search-info">
                    🔍 Результаты поиска для "<strong><?php echo htmlspecialchars($search); ?></strong>":
                    найдено <?php echo mysqli_num_rows($result); ?> записей
                </div>
            <?php endif; ?>
        </div>

        <div class="form-container">
            <h2>➕ Добавление нового пользователя</h2>

            <form action="add_user.php" method="POST">
                <label for="name">👤 Имя:</label>
                <input type="text"
                    id="name"
                    name="name"
                    placeholder="Введите имя"
                    value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
                    required>

                <br><br>

                <label for="age">📅 Возраст:</label>
                <input type="number"
                    id="age"
                    name="age"
                    placeholder="Введите возраст"
                    min="1"
                    max="100"
                    value="<?php echo isset($age) ? htmlspecialchars($age) : ''; ?>"
                    required>

                <br><br>

                <button type="submit">Добавить</button>
            </form>
        </div>

        <div class="table-container">
            <h2>📋 Список пользователей</h2>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Возраст</th>
                            <th>Удаление</th>
                            <th>Редактирование</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['id_user']; ?></td>
                                <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                <td><?php echo $row['user_age']; ?> лет</td>
                                <td>
                                    <a href="delete_user.php?delete=<?php echo $row['id_user']; ?>" 
                                       class="delete-btn"
                                       onclick="return confirm('Точно удалить пользователя <?php echo htmlspecialchars($row['user_name']); ?>?')">
                                        Удалить
                                    </a>
                                </td>
                                <td>
                                    <a href="edit_user.php?edit=<?php echo $row['id_user']; ?>" 
                                       class="edit-btn">
                                        Редактировать
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <p style="text-align: right; margin-top: 10px; color: #666;">
                    Всего записей: <?php echo mysqli_num_rows($result); ?>
                </p>

            <?php else: ?>
                <div class="no-data">
                    <?php if (!empty($search)): ?>
                        <p>🔍 Ничего не найдено по запросу "<?php echo htmlspecialchars($search); ?>"</p>
                        <p style="font-size: 14px;">
                            Попробуйте другое имя или <a href="index.php">сбросьте поиск</a>
                        </p>
                    <?php else: ?>
                        <p>📭 Пока нет ни одного пользователя</p>
                        <p style="font-size: 14px;">Добавьте первого пользователя через форму выше!</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
