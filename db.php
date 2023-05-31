<?php

        $host = 'localhost'; //имя хоста, на локальном компьютере это localhost
		$user = 'root'; //имя пользователя, по умолчанию это root
		$password = 'root'; //пароль
		$db_name = 'testtable'; //имя базы данных
 
	//готовим переменную для запросов
    $db = new PDO("mysql:host=$host;dbname=$db_name", $user, $password);

    // подготовленный запрос вставка
    $insert = $db->prepare("INSERT INTO users2 (`login`, `password_hash`, `user_hash_cookie`, `register_time`) VALUES (:login, :password, :cookie, :times)");
    $insert->bindParam(':login', $login);
    $insert->bindParam(':password', $password);
    $insert->bindParam(':cookie', $cookie);
    $insert->bindParam(':times', $time);

    $insert2 = $db->prepare("INSERT INTO users2 (`login`, `password_hash`, `register_time`) VALUES (:login, :password, :times)");
    $insert2->bindParam(':login', $login);
    $insert2->bindParam(':password', $password);
    $insert2->bindParam(':times', $time);

    $req_log = $db->prepare("SELECT `id` FROM `users2` WHERE `login`= :login");
    $req_log->bindParam(':login', $login);

    $update_cookie = $db->prepare("UPDATE `users2` SET `user_hash_cookie`= :newcookie WHERE `login`= :login");
    $update_cookie->bindParam(':newcookie', $newcookie);
    $update_cookie->bindParam(':login', $login);

    $update_cookie2 = $db->prepare("UPDATE `users2` SET `user_hash_cookie`= :newcookie WHERE `login`= :login");
    $update_cookie2->bindParam(':newcookie', $cookie);
    $update_cookie2->bindParam(':login', $login);

    $drop_cookie = $db->prepare("UPDATE `users2` SET `user_hash_cookie`= '' WHERE `login`= :login");
    $drop_cookie->bindParam(':login', $login);
     
    // авторизация
    $avtor = $db->prepare("UPDATE `users2` SET `role`= :role WHERE `login`= :login");
    $avtor->bindParam(':login', $login);
    $avtor->bindParam(':role', $role);


    // для ВК
    $insertvk = $db->prepare("INSERT INTO users2 (`login`, `user_hash_cookie`, `register_time`,`role`, `vk_user_id` ) VALUES (:login, :cookie, :times, :role, :vk_user_id)");
    $insertvk->bindParam(':login', $login_vk);
    $insertvk->bindParam(':cookie', $cookie);
    $insertvk->bindParam(':times', $time);
    $insertvk->bindParam(':role', $role);
    $insertvk->bindParam(':vk_user_id', $vk_user_id);

    

    


