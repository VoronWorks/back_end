<?php

header('Content-Type: text/html; charset=UTF-8');


session_start();

if (!empty($_SESSION['login'])) {

  header('Location: ./');
}


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

<form action="" method="post">
  <input name="login" />Логин
  <input name="pass" />Пароль
  <input type="submit" value="Войти" />
</form>

<?php
}
  else {
    try {
    $stmt = $db->prepare("SELECT * FROM user 
    where user=?");
    $stmt -> execute([$_POST['login']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $flag=false;
    if(password_verify($_POST['pass'],$result["pass"]))
    {
        $_SESSION['login'] = $_POST['login'];
        
        $_SESSION['uid'] =$result["id"];
        header('Location: ./');
    }
   
        
    }
    catch(PDOException $e){
      print('Error : ' . $e->getMessage());
      exit();

  }
}
