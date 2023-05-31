<?php
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();

 
  if (!empty($_COOKIE['save'])) {

    setcookie('save', '', 100000);
    $messages[] = 'Спасибо, результаты сохранены.';
  }

  $errors = array();
  $errors['field-name'] = !empty($_COOKIE['field-name_error']);
  $errors['field-email'] = !empty($_COOKIE['field-email_error']);
 $errors['gender'] = !empty($_COOKIE['gender_error']);
  $errors['limbs'] = !empty($_COOKIE['limbs_error']);
  $errors['abilities'] = !empty($_COOKIE['abilities_error']);

  if ($errors['field-name']) {

    setcookie('field-name_error', '', 100000);

    $messages[] = '<div class="error">Заполните имя.</div>';
  }
  if ($errors['field-email']) {

    setcookie('field-email_error', '', 100000);

    $messages[] = '<div class="error">Заполните почту.</div>';
  }



  $values = array();
  $values['field-name'] = empty($_COOKIE['field-name_value']) ? '' : $_COOKIE['field-name_value'];
  $values['field-email'] = empty($_COOKIE['field-email_value']) ? '' : $_COOKIE['field-email_value'];
  $values['field-year'] = empty($_COOKIE['field-year_value']) ? '' : $_COOKIE['field-year_value'];

  $values['field-name-2'] = empty($_COOKIE['field-name-2_value']) ? '' : $_COOKIE['field-name-2_value'];
  include('form4.php');
  exit();
}

else {

  $errors = FALSE;
  if (empty($_POST['field-name']) || is_numeric($_POST['field-name'])||!preg_match('/^([а-яА-ЯЁёa-zA-Z0-9_,.\s-]+)$/u', $_POST['field-name'])) {
    setcookie('field-name_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {

    setcookie('field-name_value', $_POST['field-name'], time() + 30 * 24 * 60 * 60);
  }


  if (empty($_POST['field-email']) ) {
    setcookie('field-email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {

    setcookie('field-email_value', $_POST['field-email'], time() + 30 * 24 * 60 * 60);
  }
  if(isset($_POST['radio-group-1']) && $_POST['radio-group-1']=='1') {
    setcookie('radio1', true, 600, '/');
    $radio1=' checked ';
    $radio2='';
    setcookie('radio2', false, 600, '/');
  } else if(isset($_POST['radio'])&& $_POST['radio']=='2') {
    setcookie('radio2', true, 600, '/');
    $radio1='';
    $radio2=' checked ';
    setcookie('radio1', false, 600, '/');
  }
if(isset($_POST['radio-group-2']) && $_POST['radio-group-1']=='1') {
    setcookie('radio1', true, 600, '/');
    $radio1=' checked ';
    $radio2='';
    setcookie('radio2', false, 600, '/');
  } else if(isset($_POST['radio'])&& $_POST['radio']=='2') {
    setcookie('radio2', true, 600, '/');
    $radio1='';
    $radio2=' checked ';
    setcookie('radio1', false, 600, '/');
  }
  if (empty($_POST['field-name-2'])) {
    setcookie('field-name-2_value'," ", time() + 30 * 24 * 60 * 60);
  }
  else {

    setcookie('field-name-2_value', $_POST['field-name-2'], time() + 30 * 24 * 60 * 60);
  }


  if ($errors) {

    header('Location: index4.php');
    exit();
  }
  else {

    setcookie('field-name_error', '', 100000);
    setcookie('field-email_error', '', 100000);
    setcookie('gender_error', '', 100000);
    setcookie('limbs_error', '', 100000);
  
  }
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
setcookie('save', '1');
header('Location: index4.php');
