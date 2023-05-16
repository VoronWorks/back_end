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
$db = new PDO('mysql:host=localhost;port=8889;dbname=form', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 


try {
  $stmt = $db->prepare("INSERT INTO form (name,email,year,gender,limbs,bio) VALUES (:name,:email,:year,:gender,:limbs,:bio)");
$stmt -> execute(['name'=>$_POST['field-name'], 'email'=>$_POST['field-email'],'year'=>$_POST['field-year'],
'gender'=>$_POST['radio-group-1'],'limbs'=>$_POST['radio-group-2'],'bio'=>$_POST['field-name-2']]);
  $stmt2 = $conn->prepare("SELECT id FROM form where name=$_POST['field-name']");
  $stmt2->execute();
  $row = $stmt2->fetch_assoc();
  $id = $row['id'];
    echo $id;
foreach ($_POST['abilities'] as $ability) {
 $stmt = $db->prepare("INSERT INTO connect (s_id,pow_id) VALUES (:s_id,:pow_id)");
 $stmt -> execute(['s_id'=>$id,'pow_id'=>$ability]);
  }
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}

header('Location: ?save=1');
