<?php
include("config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user_id = (int)$_POST['id'];
    $new_name = trim($_POST['name']);
    $new_age = $_POST['age'];
    
    $errors = [];

    if (empty($new_name)) {
        $errors[] = "Имя не может быть пустым";
    } elseif (strlen($new_name) < 2) {
        $errors[] = "Имя должно содержать минимум 2 символа";
    } elseif (strlen($new_name) > 50) {
        $errors[] = "Имя должно содержать максимум 50 символов";
    }

    if (empty($new_age)) {
        $errors[] = "Возраст не может быть пустым";
    } elseif (!is_numeric($new_age)) {
        $errors[] = "Возраст должен быть числом";
    } elseif ($new_age < 1 || $new_age > 100) {
        $errors[] = "Возраст должен быть от 1 до 100 лет";
    }

    if (!empty($errors)) {
        $_SESSION['message'] = "❌ " . implode("<br>❌ ", $errors);
        $_SESSION['message_type'] = "error";
        header("Location: edit_user.php?edit=" . $user_id);
        exit();
    }

    $safe_name = mysqli_real_escape_string($conn, $new_name);
    $safe_age = (int)$new_age;

    $check_sql = "SELECT id_user FROM new_users WHERE user_name = '$safe_name' AND id_user != $user_id";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['message'] = "❌ Пользователь с именем '$new_name' уже существует!";
        $_SESSION['message_type'] = "error";
        header("Location: edit_user.php?edit=" . $user_id);
        exit();
    }

    $update_sql = "UPDATE new_users 
                   SET user_name = '$safe_name', 
                       user_age = $safe_age 
                   WHERE id_user = $user_id";
    
    if (mysqli_query($conn, $update_sql)) {
        if (mysqli_affected_rows($conn) > 0) {
            $_SESSION['message'] = "✅ Данные пользователя успешно обновлены!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "⚠️ Данные не изменились";
            $_SESSION['message_type'] = "warning";
        }
    } else {
        $_SESSION['message'] = "❌ Ошибка при обновлении: " . mysqli_error($conn);
        $_SESSION['message_type'] = "error";
    }

    header("Location: index.php");
    exit();
    
} else {
    $_SESSION['message'] = "❌ Неверный метод запроса";
    $_SESSION['message_type'] = "error";
    header("Location: index.php");
    exit();
}
?>