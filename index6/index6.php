<?php
header('Content-Type: text/html; charset=UTF-8');
include('megamodule.php');
$user = 'u52952';
$pass = '1472414';
$db = new PDO('mysql:host=localhost;dbname=u52952', $user, $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $messages = array();


  if (!empty($_COOKIE['save'])) {
    

    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);

    $messages[] = 'Спасибо, результаты сохранены.';

    if (!empty($_COOKIE['pass'])) {
      $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass']));
    }
 
  }


  $errors = array();
  $errors['field-name'] = !empty($_COOKIE['field-name_error']);
  $errors['field-email'] = !empty($_COOKIE['field-email_error']);
  $errors['field-year'] = !empty($_COOKIE['field-year_error']);
  $errors['radio-group-1'] = !empty($_COOKIE['gender_error']);
  $errors['radio-group-2'] = !empty($_COOKIE['limbs_error']);
  $errors['abilities'] = !empty($_COOKIE['abilities_error']);
  $errors['field-name-2'] = !empty($_COOKIE['bio_error']);




  if ($errors['field-name']) {
    setcookie('field-name_error', '', 100000);
    $messages[] = '<div class="error">Заполните имя корректно.</div>';
  }
  
  if ($errors['field-email']) {
      setcookie('field-email_error', '', 100000);
      $messages[] = '<div class="error">Заполните email корректно. <br> Email должен содержать символ "@" </div>';
  }
  if ($errors['field-year']) {
      setcookie('field-year_error', '', 100000);
      $messages[] = '<div class="error">Заполните дату рождения корректно.  </div>';
  }
  if ($errors['radio-group-1']) {
      setcookie('gender_error', '', 100000);
      $messages[] = '<div class="error">Укажите пол. </div>';
  }
  if ($errors['radio-group-2']) {
      setcookie('limbs_error', '', 100000);
      $messages[] = '<div class="error">Укажите количество конечностей. </div>';
  }
  if ($errors['abilities']) {
      setcookie('abilities_error', '', 100000);
      $messages[] = '<div class="error">Укажите хотя бы одну способность. </div>';
  }
  
  if ($errors['field-name-2']) {
      setcookie('bio_error', '', 100000);
      $messages[] = '<div class="error">Заполните биографию корректно. </div>';
  }
  


  $values = array();
  $values['field-name'] = empty($_COOKIE['field-name_value']) ? '' : strip_tags($_COOKIE['field-name_value']);
  $values['field-email'] = empty($_COOKIE['field-email_value']) ? '' : strip_tags($_COOKIE['field-email_value']);
  $values['field-year'] = empty($_COOKIE['field-year_value']) ? '' :strip_tags($_COOKIE['field-year_value']);
  $values['radio-group-1'] = empty($_COOKIE['radio-group-1_value']) ? '' : strip_tags($_COOKIE['radio-group-1_value']);
  $values['radio-group-2'] = empty($_COOKIE['radio-group-2_value']) ? '' : strip_tags($_COOKIE['radio-group-2_value']);
  $values['abilities'] = empty($_COOKIE['abilities_value']) ? array() : unserialize($_COOKIE['abilities_value']);
  $values['field-name-2'] = empty($_COOKIE['bio_value']) ? '' : strip_tags($_COOKIE['bio_value']);

  if (count(array_filter($errors)) === 0 && !empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {


        $stmt = $db->prepare("SELECT * FROM form where user_id=?");
        $stmt -> execute([$_SESSION['uid']]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $values['field-name'] = empty($result[0]['name']) ? '' : strip_tags($result[0]['name']);
        $values['field-email'] = empty($result[0]['email']) ? '' : strip_tags($result[0]['email']);
  $values['field-year'] = empty($result[0]['year']) ? '' :strip_tags($result[0]['year']);
  $values['radio-group-1'] = empty($result[0]['gender']) ? '' : strip_tags($result[0]['gender']);
  $values['radio-group-2'] = empty($result[0]['limbs']) ? '' : strip_tags($result[0]['limbs']);
 
  $values['field-name-2'] = empty($result[0]['bio']) ? '' : strip_tags($result[0]['bio']);
  $values['radio-group-2'] = empty($result[0]['limbs']) ? '' : strip_tags($result[0]['limbs']);

  $stmt = $db->prepare("SELECT * FROM connect where s_id=(SELECT id FROM form where user_id=?) ");
 

  $stmt -> execute([$_SESSION['uid']]);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($result as $res) {
    $values['abilities'][$res["pow_id"]-1] = empty($res) ? '' : strip_tags($res["pow_id"]);
}
  




  printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
  }


  include('form6.php');
}

else {
    
    if (isset($_POST['logout']) && $_POST['logout'] == 'true') {
        session_destroy();
        setcookie(session_name(), '', time() - 3600);
        setcookie('PHPSESSID', '', time() - 3600, '/');
       
        header('Location: ./');
        exit();
    }
    
  $errors = FALSE;
 if (empty($_POST['field-name']) ) {
    setcookie('field-name_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {

    if(!preg_match('/^([а-яА-ЯЁёa-zA-Z0-9_,.\s-]+)$/u', $_POST['field-name'])){
        setcookie('field-name_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    setcookie('field-name_value', $_POST['field-name'], time() + 365 * 24 * 60 * 60);
  }

  
  
  if (empty($_POST['field-email'])) {
      setcookie('field-email_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
  }
  else {
    if(!filter_var($_POST['field-email'], FILTER_VALIDATE_EMAIL)){
        setcookie('field-email_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
    }
      setcookie('field-email_value', $_POST['field-email'], time() + 365 * 24 * 60 * 60);
  }
  
  if (empty($_POST['field-year'])) {
      $errors = TRUE;
      setcookie('field-year_error', '1', time() + 24 * 60 * 60);
  }
  else {
   
      setcookie('field-year_value', $_POST['field-year'], time() + 365 * 24 * 60 * 60);
  }
  
  if (empty($_POST['radio-group-1'])) {
      $errors = TRUE;
      setcookie('gender_error', '1', time() + 24 * 60 * 60);
  }
  else {
    if( !in_array($_POST['radio-group-1'], ['1','2'])){
        $errors = TRUE;
        setcookie('gender_error', '1', time() + 24 * 60 * 60);
    }
      setcookie('radio-group-1_value', $_POST['radio-group-1'], time() + 365 * 24 * 60 * 60);
  }
  
  if (empty($_POST['radio-group-2'])) {
      setcookie('limbs_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
  }
  else {
    if(!in_array($_POST['radio-group-2'], [1,2,3,4])){
        setcookie('limbs_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
      setcookie('radio-group-2_value', $_POST['radio-group-2'], time() + 365 * 24 * 60 * 60);
  }
  
  if (empty($_POST['abilities'])) {
      setcookie('abilities_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
  }
  else {
      foreach ($_POST['abilities'] as $ability) {
          if (!in_array($ability, [1,2,3])){
              setcookie('abilities_error', '1', time() + 24 * 60 * 60);
              $errors = TRUE;
              break;
          }
      }
      $abs=array();
      
      foreach ($_POST['abilities'] as $res) {
          $abs[$res-1] = $res;
      }
      
      setcookie('abilities_value', serialize($abs), time() + 365 * 24 * 60 * 60);
  }

  
      if (empty($_POST['field-name-2']) ) {
      setcookie('bio_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
  }
  else {
    if(!preg_match('/^([а-яА-ЯЁёa-zA-Z0-9_,.\s-]+)$/u', $_POST['field-name-2'])){
    setcookie('bio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
}
      setcookie('field-name-2_value', $_POST['field-name-2'], time() + 365 * 24 * 60 * 60);
  }
  
  
  if ($errors) {
    header('Location: index6.php');
    exit();
  }
  else {

    setcookie('field-name_error', '', 100000);
    setcookie('field-email_error', '', 100000);
    setcookie('field-year_error', '', 100000);
    setcookie('gender_error', '', 100000);
    setcookie('limbs_error', '', 100000);
    setcookie('abilities_error', '', 100000);
    setcookie('bio_error', '', 100000);

  }


  if (!empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {


          $stmt = $db->prepare("UPDATE form SET name = ?, email = ?, field-year = ?,gender = ?, limbs=?, bio = ? WHERE user_id = ?");
          
          $stmt -> execute([$_POST['field-name'], $_POST['field-email'], $_POST['field-year'], $_POST['radio-group-1'], $_POST['radio-group-2'], $_POST['field-name-2'], $_SESSION['uid']]);

          

          $stmt = $db->prepare("SELECT * FROM connect where s_id=(SELECT id FROM form where user_id=?) ");
          $stmt -> execute([$_SESSION['uid']]);
          $result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);


          $c=0;
          $flag=false;
          foreach ($_POST['abilities'] as $ability) {
            if ($result2[$ability]!=$ability){
                $flag=true;
                break;
            }
        }

 


          if($flag){
            $stmt = $db->prepare("DELETE FROM connect WHERE s_id=(SELECT id FROM form where user_id=?) ");
            $stmt -> execute([$_SESSION['uid']]);

            $stmt = $db->prepare("SELECT id FROM form where user_id=? ");
            $stmt -> execute([$_SESSION['uid']]);
            $result3 = $stmt->fetchAll(PDO::FETCH_ASSOC);


            $stmt = $db->prepare("INSERT INTO connect (s_id, pow_id) VALUES (?,?)");
            foreach ($_POST['abilities'] as $ability) {
                $stmt->execute([$result3[0]["id"], $ability]);
            }
          }
          



  }
  else {

    $login = substr(uniqid('', true), -8, 8);
    $pass = uniqid();

    setcookie('login', $login);
    setcookie('pass', $pass);

 
        try {
          $stmt = $db->prepare("INSERT INTO user (user, pass) VALUES (?,?)");
          $stmt -> execute([$login, password_hash($pass, PASSWORD_DEFAULT)]);
          $id = $db->lastInsertId();
       //   $stmt = $db->prepare("INSERT INTO form (name,email,year,gender,limbs,bio, user_id) VALUES 
       //   (?,?,?,?,?,?)");
       //   $stmt -> execute([$_POST['field-name'], $_POST['field-email'], $_POST['field-year'], $_POST['radio-group-1'],
       //    $_POST['radio-group-2'], $_POST['field-name-2'], $id]);
            $id = $db->lastInsertId();
         //   $stmt = $db->prepare("SELECT id FROM powers");
           // $stmt->execute();
          //  $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
            $stmt = $db->prepare("INSERT INTO connect (s_id, pow_id) VALUES (?,?)");
            foreach($res as $r){
                if(isset($_POST['abilities'][$r["id"]-1])){
                    $stmt->execute([$id, $r["id"]]);
                }
            }
            
            
            
        }
        catch(PDOException $e){
            print('Error : ' . $e->getMessage());
            exit();
        }

  }

  setcookie('save', '1');
  header('Location: /index6.php');
}