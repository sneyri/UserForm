<?php 
include("config.php");

$search = '';
if (isset($_GET['search'])){
    $search = trim($_GET["search"]);
}

if (!empty($search)) {
    $safe_search = mysqli_real_escape_string($conn, $search);
    $sql = "SELECT * FROM new_users
        WHERE user_name LIKE '%$safe_search%'
        ORDER BY id_user DESC";

    $result = mysqli_query($conn, $sql);

    header("Location: index.php?search=" . urlencode($search));
} else {
    $sql = "SELECT * FROM new_users ORDER BY id_user DESC";
}

header("Location: index.php");
exit();

?>