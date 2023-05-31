<?php
// проверка на существующий логин
$login = $_POST['login_first'];
$req = "SELECT `id` FROM `users2` WHERE `login`= '$login'";
$res = $db->query($req);
if (!isset($_SESSION["status"])) {
$data = $res->Fetch(PDO::FETCH_ASSOC);
}
if (!$data) {

$hashed = password_hash($_POST['pass_first'], PASSWORD_BCRYPT);

if (isset($_POST["cookie2"])) {
$cookie_hash = md5(random_int(0,9999999)."solemio");
if (empty($_COOKIE['user_hash_cookie'])) {
setcookie('user_hash_cookie', $cookie_hash, time() + 3600*24*30);
}

$password = $hashed;
$login = $_POST['login_first'];
$time = date('Y-m-d H:i:s',time());

if (empty($_SESSION['cookie']) && empty($_COOKIE['user_hash_cookie'])) {
$_SESSION['cookie'] = $cookie_hash;
}

$cookie = $_SESSION['cookie'];
$insert->execute();
}
else {
    $password = $hashed;
    $login = $_POST['login_first'];
    $time = date('Y-m-d H:i:s',time());
    $insert2->execute();
}

$req = "SELECT `login`, `user_hash_cookie` FROM `users2` WHERE `login`='$login'";
$res = $db->query($req);
$data = $res->Fetch(PDO::FETCH_ASSOC);

if (empty($_COOKIE['login'])) {
    setcookie('login', $data['login'], time() + 3600*24*30);
    };

$_SESSION["status"] = 'run';
echo "<br>";echo "<br>";
echo "Регистрация пройдена";
echo "<br>";echo "<br>";
echo 'WELLCOME, '.$login;
$_SESSION['login'] = $login;

//include('lk.php');

    ?>
<div class="container pt-4">
    
<form method="post" action="">
<input type="hidden" name="exit" value="exit"> <br/>
<input type="submit" value="Выйти из акаунта" class="btn btn-primary">
</form>
<div>
<?php

}
else if (isset($_POST["regg"]) && !isset($_SESSION['login'])){
    ?>
    <h3>Этот Логин уже зарегистрирован! Вернитесь назад</h3>
    
<div class="container pt-4">
<form method="post" action="">

<input type="hidden" name="session_exit" value="session_exit"> <br/>
<input type="submit" value="Вернуться назад" class="btn btn-primary">
</form>
<div>
    <?php
if (isset($_POST["session_exit"])) {
session_destroy();
setcookie('user_hash_cookie', '', time());
setcookie('login', '', time());
$newcookie = NULL;
    $login = $login;
    $update_cookie->execute();
header('location: index.php');
}
}
