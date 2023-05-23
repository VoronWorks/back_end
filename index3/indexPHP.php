<?php
header('Content-Type: text/html; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if (!empty($_GET['save'])) {
    print('Результаты сохранены.');
  }
  include('form.php');
  exit();
}

$errors = FALSE;
if (empty($_POST['field-name'])) {
  print('Заполните имя.<br/>');
  $errors = TRUE;
}

if (empty($_POST['field-year']) || !is_numeric($_POST['field-year']) || !preg_match('/^\d+$/', $_POST['field-year'])) {
  print('Заполните год.<br/>');
  $errors = TRUE;
}

   if (empty($_POST['field-email'])) {
    print('Заполните почту.<br/>');
    $errors = TRUE;
  }

if ($errors) {
  include('form.php');
  exit();
}



$user = 'u52952'; 
$pass = '1472414';
$db = new PDO('mysql:host=localhost;port=8889;dbname=u52952', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 


try {
  $stmt = $db->prepare("INSERT INTO form (name,email,year,gender,limbs,bio) VALUES 
  (?,?,?,?,?,?)");
  $stmt -> execute([$_POST['field-name'], $_POST['field-email'], $_POST['field-year'], $_POST['radio-group-1'],
   $_POST['radio-group-2'], $_POST['field-name-2']]);
  $id = $db->lastInsertId();
  $stmt = $db->prepare("INSERT INTO connect (s_id, pow_id) VALUES (?,?)");
    foreach ($_POST['abilities'] as $ability) {
          $stmt->execute([$id, $ability]);
        }
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}

header('Location: ?save=1');
