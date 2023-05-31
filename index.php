<?php 
//ini_set('display_errors', '1'); 
include('db.php');
include('roles.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>autetnification</title>
</head>
<body>
  
<?php



//запуск сессии, генерация токена и его запись в сессию, если еще не записан
if (!isset($_SESSION)) {
session_start();
}



if (isset($_COOKIE['login'])) {
$login = $_COOKIE['login'];
}
//echo $_SESSION['login'];
if (isset($_COOKIE['user_hash_cookie'])) {
$cookie = $_COOKIE['user_hash_cookie'];
}


$req = "SELECT `login`, `role` FROM `users2` WHERE `user_hash_cookie`='$cookie'";
$res = $db->query($req);
$data = $res->Fetch(PDO::FETCH_ASSOC);
    



// формируем токен для проверки формы
if (!isset($_POST["token"])) {
$token = hash('gost-crypto', random_int(0,999999));
}
if (isset($token) && empty($_SESSION['CSRF']) || !isset($_POST["token"])) {
   $_SESSION['CSRF'] = $token;
}


//форма аутентификации если токен еще не передан
if ( !isset($_POST["token"]) && !isset($data['login']) && !isset($_SESSION['vk']) && !isset($_SESSION['login'])) {

?>

<div class="container pt-4">
<div class="container pt-1">
<h4>Авторизация через ВКонтакте</h4>
<form method="post" action="">

<input type="hidden" name="auth_vk" value="auth_vk">

<input type="checkbox" id="cookie" name="cookie_vk" value="remember" autocomplete="off">
<label for="cookie">Запомнить меня</label>

<input type="hidden" name="token" value="<?php echo $token ?>"> 
<input type="submit" value="Войти c VK" class="btn btn-primary">
</form> 
</div>
    <h4>Вход для зарегистрированных пользователей</h4>
<form method="post" action="">
<input type="text" name="login" placeholder="Логин" class="form-control" size= "30%" autocomplete="off"><br/>
<input type="password" name="pass" class="form-control" width = "100px" autocomplete="off"> <br/>
<input type="hidden" name="auth" value="auth">

<input type="checkbox" id="cookie" name="cookie1" value="remember" autocomplete="off">
<label for="cookie">Запомнить меня</label>

<input type="hidden" name="token" value="<?php echo $token ?>"> 
<input type="submit" value="Вход для зарегистрированных пользователей" class="btn btn-primary">
</form>

    <h4>Регистрация</h4>
<form method="post" action="">
<input type="text" name="login_first" placeholder="Логин" class="form-control" size= "30%" autocomplete="off"><br/>
<input type="password" name="pass_first" class="form-control" width = "100px" autocomplete="off"> <br/>
<input type="checkbox" id="cookie" name="cookie2" value="remember" autocomplete="off">
<label for="cookie">Запомнить меня</label>
<input type="hidden" name="token" value="<?php echo $token ?>">
<input type="hidden" name="regg" value="regg>">
<input type="submit" value="Регистрация" class="btn btn-primary">
</form>
<div>
<?php
}


//при РЕГИСТРАЦИИ
//проверка токена в POST из формы и запись логина и пароля если их еще нет в БД

if (isset($_POST["token"]) && $_POST["token"] == $_SESSION["CSRF"] && isset($_POST["regg"])) {
include('registration.php');
} 

if (isset($_POST["token"]) && $_POST["token"] != $_SESSION["CSRF"] && isset($_POST["regg"])) {
    echo 'токен не прошел проверку!';
}

// АВТОРИЗАЦИЯ
//проверка токена в POST из формы  и проверка логина и пароля
if (isset($_POST["token"]) && $_POST["token"] == $_SESSION["CSRF"] && (isset($_POST["auth"]))) {
include('authorization.php');
} 



// АВТОРИЗАЦИЯ VK
//проверка токена в POST из формы и запрос к ВК
if (isset($_POST["token"]) && $_POST["token"] == $_SESSION["CSRF"] && (isset($_POST["auth_vk"]))) {
    if (isset($_POST["cookie_vk"])) {
        
        $cookie_hash = md5(random_int(0,9999999)."solemio");
        if (empty($_COOKIE['user_hash_cookie'])) {
        setcookie('user_hash_cookie', $cookie_hash, time() + 3600*24*30);
        }
        if (empty($_SESSION['cookie']) && empty($_COOKIE['user_hash_cookie'])) {
            $_SESSION['cookie'] = $cookie_hash;
            }
         $cookie = $_SESSION['cookie'];
        }
        else {
            $newcookie = NULL; 
        }

    include('vk.php');
    $clientId     = '51661229'; // ID приложения
    $clientSecret = 'PkoiVEepJN7as6fxU52W'; // Защищённый ключ
    $redirectUri  = 'http://localhost:8888/modul27_autetification/index.php'; // Адрес, на который будет переадресован пользователь после прохождения авторизации
 
// Формируем ссылку для авторизации
$params = array(
	'client_id'     => $clientId,
	'redirect_uri'  => $redirectUri,
	'response_type' => 'code',
	'v'             => '5.126', // (обязательный параметр) версиb API https://vk.com/dev/versions
 
	// Права доступа приложения https://vk.com/dev/permissions
	// Если указать "offline", полученный access_token будет "вечным" (токен умрёт, если пользователь сменит свой пароль или удалит приложение).
	// Если не указать "offline", то полученный токен будет жить 12 часов.
	'scope'         => 'photos,offline',
);
 
$a = 'http://oauth.vk.com/authorize?' . http_build_query( $params );

header('Location: '.$a);

} 

// при получении ответа от ВК
if (isset($_GET['code'])) {
    
    include('vk.php');
}
if ((!isset($_SESSION['token']) && isset($_SESSION['vk']))){
    ?>
    <h3>Вы не прошли авторизацию в ВК, вернитесь для повторной регистрации</h3>
    
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
header('location: index.php');
}
}

// Получение Имеми пользователя в ВК

if (isset($_SESSION['token']) ) {
$token = $_SESSION['token']; // Извлекаем токен из сессии
$userId = $_SESSION['user_id'];
// Формируем запрос на получение Имени и Фимилии из ВК
$params = array(
    'v' => '5.126', // Версия API
    'access_token' => $token, // Токен
    'user_ids' => $userId, // ID пользователей
    'fields' => 'photo_100,about' // Список опциональных полей https://vk.com/dev/objects/user
);
if (!$content = @file_get_contents('https://api.vk.com/method/users.get?' . http_build_query($params))) {
    $error = error_get_last();
    throw new Exception('HTTP request failed. Error: ' . $error['message']);
}
$response = json_decode($content);
// Если возникла ошибка
if (isset($response->error)) {
    throw new Exception('При отправке запроса к API VK возникла ошибка. Error: ' . $response->error . '. Error description: ' . $response->error_description);
}
$response = $response->response;
foreach ($response as $userItem) {
    $userItem->id; // ID пользователя
    $userItem->first_name; // Имя
    $userItem->last_name; // Фамилия  
}
$first_name = $userItem->first_name;
$last_name = $userItem->last_name;
$vk_user_id = $userItem->id;
$_SESSION['login'] = $first_name;

if (isset($first_name)) {
$options = ['watch', 'edit'];
}

$_SESSION["status"] = 'run';

// обновление данных в БД
$req = "SELECT `login` FROM `users2` WHERE `vk_user_id`='$vk_user_id'";
$res_vk = $db->query($req);
$data_vk = $res_vk->Fetch(PDO::FETCH_ASSOC);
if (isset($data_vk['login'])) {
$login = $data_vk['login'];

$update_cookie2->execute();

if (!$data_vk) {

$login_vk = $first_name;
    $time = date('Y-m-d H:i:s',time());
    $role = 'vk_user';
    $cookie = $_SESSION['cookie'];
    $vk_user_id = $vk_user_id;

    $insertvk->execute();
}
}

    if (isset($newcoockie)) {
    //$login = $first_name;
    $update_cookie->execute();
    }
  
    if (empty($_COOKIE['login'])) {
        //setcookie('login', $data['login'], time() + 3600*24*30);
        };
    }







// приветствие при повторном входе по COOKIE
if (isset($data['login']) && !isset($_SESSION['login'])) {

    if (!isset($_POST['auth']) && !isset($_POST['regg']) && !isset($first_name) ) {
    echo 'WELLCOME BACK, '.$data['login'] .' !';
    include('lk.php');
    
    
    $_SESSION["status"] = 'run';
    ?>
    <div class="container pt-4">
        выход 
    <form method="post" action="">
    <input type="hidden" name="exit" value="exit"> <br/>
    <input type="submit" value="Выйти из акаунта" class="btn btn-primary">
    </form>
    <div>
    <?php

}}


// Приветсвие зарегестрированному пользователю
if (isset($_SESSION['login']) ) {
    ?>
    <div class="container pt-4">

    <h5><?php  echo 'Добро пожаловать на сайт , '.$_SESSION['login'] .' !';?> Вы можете перейти на страницу для зарегистрированных пользователей:</h5>
    <p><a href = "lk.php">Страница для зарегистрированных пользователей</a></p>
    <?php
    ?>
    <p><img src="images/2929594473.jpg" width = 25%></p>
    <div>
    <div class="container pt-4">
        
    <form method="post" action="">
    <input type="hidden" name="exit" value="exit"> <br/>
    <input type="submit" value="Выйти из акаунта" class="btn btn-primary">
    </form>
    <div>
    <?php
}


//Код для кнопки Выйти из аккаунта
if (isset($_POST["exit"])) {
    session_destroy();
    setcookie('user_hash_cookie', '', time());
    setcookie('login', '', time());
    unset($login);
    $newcookie = NULL;
        $login = $login;
    header('location: index.php');
}





// Кнопка сбросс сессии и Кук (аварийная)
/*
?>
<div class="container pt-4">
    <form method="post" action="">

    <input type="hidden" name="session_exit" value="session_exit"> <br/>
    <input type="submit" value="сбросить сессию и куки" class="btn btn-primary">
    </form>
    <div>
        <?php
if (isset($_POST["session_exit"])) {
    session_destroy();
    setcookie('user_hash_cookie', '', time());
    setcookie('login', '', time());
    unset($login);
    $newcookie = NULL;
        $login = $login;
        $update_cookie->execute();
    header('location: index.php');
}
*/
?>



<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input@1.3.4/dist/bs-custom-file-input.min.js"></script>

</body>
</html>




