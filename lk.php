<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>main</title>
</head>
<body>
<?php
session_start();
ini_set('display_errors', '1'); 
include('db.php');
include('roles.php');

$a = $_SESSION['login'];


$req = "SELECT `login`, `role` FROM `users2` WHERE `login`='$a'";
$res = $db->query($req);
$data = $res->Fetch(PDO::FETCH_ASSOC);


if ($data['role'] == 'admin' || $data['role'] == 'vk_user') {
    $options = ['watch', 'edit'];
}

if ($data['role'] == 'vk_user') {
    $photo = '';
}
else {
    $photo = 'hidden';
}


if (in_array("edit", $options)) {
$perm = '';
} else {
    $perm = 'disabled'; 
}

?>
<div class="container pt-4">
<h4> WELLCOME, <?php echo $_SESSION['login']   ?></h4>
<p><a href = "index.php">Вернуться на главную</a></p>

<h5> Эта статься видна всем зарегистрированым</h5>
<p>
Книга профессора Юваля Ноя Харари, впервые опубликованная на иврите в Израиле в 2011 году, а на английском языке в 2014 году. Харари называет одним из основных источников вдохновения книгу «Ружья, микробы и сталь» Джареда Даймонда, показавшую, что можно «задавать очень большие вопросы и отвечать на них научно». 
</p>
<p>
</div>
<div class="container pt-4" <?php echo $photo ?>>
<h5>А это фото видно только пользователям VK</h5>
    <img src="images/Rusalochka-min.jpg" width = 35%>
    <button id = "button1" class="btn btn-primary" <?php echo $perm ?> >Удалить</button>
</p> 
</div>

    <div class="container pt-4">
        
    <form method="post" action="">
    <input type="hidden" name="exit" value="exit"> <br/>
    <input type="submit" value="Выйти из акаунта" class="btn btn-primary">
    </form>
    <div>
    
    <?php
    //для кнопки Выйти из аккаунта
if (isset($_POST["exit"])) {
    session_destroy();
    setcookie('user_hash_cookie', '', time());
    setcookie('login', '', time());
    unset($login);
    $newcookie = NULL;
        $login = $login;
    header('location: index.php');
}
?>
<script>
    let a = `<?php echo ($_SESSION['login']) ?>`;
    document.getElementById('button1').addEventListener('click', function (event) {
  alert(`Добро пожаловать, `+ a + `!`)
})

</script>


  

</div>

</body>
</html>
     
     
