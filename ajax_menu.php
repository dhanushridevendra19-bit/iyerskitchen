<?php
include("db.php");

$action = $_GET['action'] ?? '';

if($action == 'get_all') {
    $result = mysqli_query($conn, "SELECT * FROM menu_items WHERE is_available = 1 ORDER BY category");
    $items = [];
    while($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
    echo json_encode($items);
}
elseif($action == 'get_special') {
    $result = mysqli_query($conn, "SELECT * FROM menu_items WHERE is_special = 1 AND is_available = 1");
    $items = [];
    while($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }
    echo json_encode($items);
}
?>