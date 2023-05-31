<?php
session_start(); 

// получаем код доступа к api vk в $_GET['code']
// Параметры приложения
$clientId     = '51661229'; // ID приложения
$clientSecret = 'PkoiVEepJN7as6fxU52W'; // Защищённый ключ
$redirectUri  = 'http://localhost:8888/modul27_autetification/index.php'; // Адрес, на который будет переадресован пользователь после прохождения авторизации
 
// Формируем ссылку для авторизации
$params = array(
	'client_id'     => $clientId,
	'redirect_uri'  => $redirectUri,
	'response_type' => 'code',
	'v'             => '5.126', // (обязательный параметр) версиb API https://vk.com/dev/versions
	'scope'         => 'photos,offline',
);
 
$_SESSION['vk'] = 'true';


if (isset ($_GET['code']) && !isset($_SESSION['user_id'])) {
// получаем данные пользователя по коду из GET
$params = array(
    'client_id'     => $clientId,
    //'first_name'    => $first_name,
    'client_secret' => $clientSecret,
    'code'          => $_GET['code'],
    'redirect_uri'  => $redirectUri
);

if (!$content = @file_get_contents('https://oauth.vk.com/access_token?' . http_build_query($params))) {
    $error = error_get_last();
    throw new Exception('HTTP request failed. Error: ' . $error['message']);
}

$response = json_decode($content);

// Если при получении токена произошла ошибка
if (isset($response->error)) {
    throw new Exception('При получении токена произошла ошибка. Error: ' . $response->error . '. Error description: ' . $response->error_description);
}
//если все прошло хорошо
$token = $response->access_token; // Токен
$expiresIn = $response->expires_in; // Время жизни токена
$userId = $response->user_id;
//$first_name = $response->first_name;


// Сохраняем токен в сессии
$_SESSION['token'] = $token;
$_SESSION['user_id'] = $userId;
}






