<?php

if(empty($_POST)) {
  return;
}

if (empty($_POST['user_type'])) {
  $errorMessage = 'Please pick the user type';
  return;
}

if (empty($_POST['password'])) {
  $errorMessage = 'Please enter the password';
  return;
}

$userType = strtolower(trim($_POST['user_type']));
$password = strtolower(trim($_POST['password']));

$userLevel = null;
$passwordFromSettings = null;
if ('regular' == $userType) {
  $passwordFromSettings = query('SELECT `value` FROM `settings` WHERE `name`="regular_password"')->fetchColumn();
}
elseif ('admin' == $userType) {
  $passwordFromSettings = query('SELECT `value` FROM `settings` WHERE `name`="admin_password"')->fetchColumn();
}

if ($passwordFromSettings == $password) {
  $userLevel = $userType;
}

if (empty($userLevel)) {
  $errorMessage = 'The password is not correct. Please check and try again.';
  return;
}

$_SESSION['authenticated'] = true;
$_SESSION['userLevel'] = $userLevel;
header('Location: index.php');