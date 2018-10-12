<?php

if ('admin' != $_SESSION['userLevel']) {
    header('Location: index.php');
}

$numbersCount = query('SELECT COUNT(*) FROM phones')->fetchColumn();

if (empty($_POST)) {
    return;
}
    
if (!empty($_POST['erase_database'])) {
    query('TRUNCATE TABLE phones');
    query('TRUNCATE TABLE batch');
    $numbersCount = 0;
    exec('rm -rf ' . ARCHIVE_DIR . '*');
    $message = 'Database has been successfully erased';
}

if (!empty($_POST['regular_user_submit'])) {
    $newPassword = trim($_POST['regular_user_password']);
    if (empty($newPassword)) {
        $errorMessage = 'Regular user password should not be empty';
        return;
    }
    query('UPDATE `settings` SET `value`="' . $newPassword . '" WHERE `name`="regular_password"');
    $message = 'Regular user password has been successfully updated';
}

if (!empty($_POST['admin_user_submit'])) {
    $newPassword = trim($_POST['admin_user_password']);
    if (empty($newPassword)) {
        $errorMessage = 'Admin password should not be empty';
        return;
    }
    query('UPDATE `settings` SET `value`="' . $newPassword . '" WHERE `name`="admin_password"');
    $message = 'Admin password has been successfully updated';
}
