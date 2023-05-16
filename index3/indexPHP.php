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
 
  exit();
}



$user = 'u52952'; 
$pass = '1472414';
$db = new PDO('mysql:host=u52952.kubsu-dev.ru;dbname=form', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 


try {
  $stmt = $db->prepare("INSERT INTO form (name,email,year,gender,limbs,bio) VALUES (:name,:email,:year,:gender,:limbs,:bio)");
$stmt -> execute(['name'=>$_POST['field-name'], 'email'=>$_POST['field-email'],'year'=>$_POST['field-year'],
'gender'=>$_POST['radio-group-1'],'limbs'=>$_POST['radio-group-2'],'bio'=>$_POST['field-name-2']]);
//$stmt = $db->prepare("SELECT FROM powers WHERE 'power' = $_POST['abilities']");
//$stmt -> execute
//foreach ($_POST['abilities'] as $ability) {
 // $stmt = $db->prepare("INSERT INTO connect (pow_id) VALUES (:pow_id)");
 // $stmt -> execute(['pow_id'=>$_POST['abilities[]']]);
 //  }
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}

//  stmt - это "дескриптор состояния".
 
//  Именованные метки.
//$stmt = $db->prepare("INSERT INTO test (label,color) VALUES (:label,:color)");
//$stmt -> execute(['label'=>'perfect', 'color'=>'green']);
 
//Еще вариант
/*$stmt = $db->prepare("INSERT INTO users (firstname, lastname, email) VALUES (:firstname, :lastname, :email)");
$stmt->bindParam(':firstname', $firstname);
$stmt->bindParam(':lastname', $lastname);
$stmt->bindParam(':email', $email);
$firstname = "John";
$lastname = "Smith";
$email = "john@test.com";
$stmt->execute();
*/


header('Location: ?save=1');
