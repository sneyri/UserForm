<?php
include("config.php");

if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    
    $name_sql = "SELECT user_name FROM new_users WHERE id_user = $delete_id";
    $name_result = mysqli_query($conn, $name_sql);
    $user_name = '';
    
    if (mysqli_num_rows($name_result) > 0) {
        $user = mysqli_fetch_assoc($name_result);
        $user_name = $user['user_name'];
    }
    
    $delete_sql = "DELETE FROM new_users WHERE id_user = $delete_id";
    
    if (mysqli_query($conn, $delete_sql)) {
        if (mysqli_affected_rows($conn) > 0) {
            $message = "✅ Пользователь \"" . htmlspecialchars($user_name) . "\" успешно удален!";
            $message_type = "success";
        } else {
            $message = "❌ Пользователь не найден";
            $message_type = "error";
        }
    } else {
        $message = "❌ Ошибка при удалении: " . mysqli_error($conn);
        $message_type = "error";
    }

    header("Location: index.php");
    exit();
}

?>