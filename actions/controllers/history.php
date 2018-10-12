<?php

if ('admin' != $_SESSION['userLevel']) {
    header('Location: index.php');
}


$stmt = query('SELECT * FROM `batch` ORDER BY `id` DESC');
$recordset = $stmt->fetchAll();
