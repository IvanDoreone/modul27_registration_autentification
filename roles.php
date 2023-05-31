<?php

include('db.php');

//include('index.php');


$role = 'admin';
$login = 'admin';
$avtor->execute();

$role = 'admin';
$login = 'ivan';
$avtor->execute();

$role = 'user';
$login = 'ozzy';
$avtor->execute();


 // выбор прав для текущего пользователя
 if (isset($data['role'])) {
    $rules = $data['role'];
} else if (isset($_POST['login'])) {
    $rules = $_POST['login'];
}
$options = [];

if (isset($rules)) {
if ($rules == 'admin' || $rules == 'vk_user') {
$options = ['watch', 'edit'];
}
if ($rules =='user')  {
$options = ['watch'];
}
}
