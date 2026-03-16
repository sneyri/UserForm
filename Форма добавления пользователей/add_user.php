<?php
include("config.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $f_name = $_POST["name"];
    $f_age = $_POST["age"];

    $errors = [];

    if (empty(trim($f_name))) {
        $errors[] = "Имя не может быть пустым";
    } elseif (strlen($f_name) < 2) {
        $errors[] = "Имя должно содержать минимум 2 символа";
    } elseif (strlen($f_name) > 50) {
        $errors[] = "Имя должно содержать максимум 50 символов";
    }

    if (empty($f_age)) {
        $errors[] = "Возраст не может быть пустым";
    } elseif (!is_numeric($f_age)) {
        $errors[] = "Возраст должен быть числом";
    }

    if (empty($errors)){
        $safe_name = mysqli_real_escape_string($conn, $f_name);

        $check_sql = "SELECT id_user FROM new_users WHERE user_name = '$safe_name'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result)){
            $_SESSION['message'] = "❌ Пользователь с именем '$f_name' уже существует!";
            $_SESSION['message_type'] = "error";
        } else {
            $safe_age = (int)$f_age;
            $sql = "INSERT INTO new_users(user_name, user_age) VALUES ('$safe_name', '$safe_age')";

            if (mysqli_query($conn, $sql)){
                $last_id = mysqli_insert_id($conn);

                $message = "✅ Пользователь успешно добавлен в базу! ID: " . $last_id;
                $message_type = "success";
            } else {
                $message = "❌ Ошибка при сохранении в базу: " . mysqli_error($conn);
                $message_type = "error";
            }
        }
    } else {
        $message = "❌ " . implode("<br>❌ ", $errors);
        $message_type = "error";
    }

    header("Location: index.php");
    exit();
}
?>