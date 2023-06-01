<?php

global $db;
$user = 'u52952';
$pass = '1472414';
    $db = new PDO('mysql:host=localhost;dbname=u52952', $user, $pass, [
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    




function update_application($db, $id, $data, $abilities) {
    $stmt = $db->prepare("UPDATE form SET name = ?, email = ?, year = ?, gender = ?, limbs = ?, bio = ? WHERE id = ?");
    $stmt -> execute([$data['name'], $data['email'], $data['year'], $data['gender'], $data['limbs'], $data['bio'], $id]);
    
    $stmt = $db->prepare("SELECT * FROM connect WHERE s_id = ?");
    $stmt -> execute([$id]);
    $result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $flag = false;
    foreach ($abilities as $ability) {
        $found = false;
        foreach ($result2 as $row) {
            if ($row['pow_id'] == $ability) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $flag = true;
            break;
        }
    }
 
    if(count($result2)==3&&count($abilities)!=3)
        $flag=true;
    if ($flag) {
        $stmt = $db->prepare("DELETE FROM connect WHERE s_id = ?");
        $stmt -> execute([$id]);
        $stmt = $db->prepare("INSERT INTO connect (s_id, pow_id) VALUES (?,?)");
        foreach ($abilities as $ability) {
            $stmt->execute([$id, $ability]);
        }
    }

}
function validateFormData($data, $abilities ,$row=null) {
    $errors = false;
    
    if (empty($data['name'])) {
        setcookie('field-name_error'.$row, '1', time() + 24 * 60 * 60);
        $errors = true;
    } else {
        if (!preg_match('/^([а-яА-ЯЁёa-zA-Z0-9_,.\s-]+)$/u', $data['name'])) {
            setcookie('field-name_error'.$row, '1', time() + 24 * 60 * 60);
            $errors = true;
        }
        setcookie('field-name_value', $data['name'], time() + 365 * 24 * 60 * 60);
    }
    
    if (empty($data['email'])) {
        setcookie('field-email_error'.$row, '1', time() + 24 * 60 * 60);
        $errors = true;
    } else {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            setcookie('field-email_error'.$row, '1', time() + 24 * 60 * 60);
            $errors = true;
        }
        setcookie('field-email_value', $data['email'], time() + 365 * 24 * 60 * 60);
    }
    
    if (empty($data['year'])) {
        $errors = true;
        setcookie('field-year_error'.$row, '1', time() + 24 * 60 * 60);
    } else {
        setcookie('field-year_value', $data['year'], time() + 365 * 24 * 60 * 60);
    }
    
    if (empty($data['gender'])) {
        $errors = true;
        setcookie('radio-group-1_error'.$row, '1', time() + 24 * 60 * 60);
    } else {
        if (!in_array($data['gender'], ['1', '2'])) {
            $errors = true;
            setcookie('radio-group-1_error'.$row, '1', time() + 24 * 60 * 60);
        }
        setcookie('radio-group-1_value', $data['gender'].$row, time() + 365 * 24 * 60 * 60);
    }
    
    if (empty($data['limbs'])) {
        setcookie('radio-group-2_error'.$row, '1', time() + 24 * 60 * 60);
        $errors = true;
    } else {
        if (!in_array($data['limbs'], [1,2,3, 4])) {
            setcookie('radio-group-2_error'.$row, '1', time() + 24 * 60 * 60);
            $errors = true;
        }
        setcookie('radio-group-2_value', $data['limbs'], time() + 365 * 24 * 60 * 60);
    }
    
    if (empty($abilities)) {
        setcookie('abilities_error'.$row, '1', time() + 24 * 60 * 60);
        $errors = true;
    } else {
        foreach ($abilities as $ability) {
            if (!in_array($ability, [1, 2, 3])) {
                setcookie('abilities_error'.$row, '1', time() + 24 * 60 * 60);
                $errors = true;
                break;
            }
        }
        $abs = array();
        foreach ($abilities as $res) {
            $abs[$res - 1] = $res;
        }
        setcookie('abilities_value', serialize($abs), time() + 365 * 24 * 60 * 60);
    }
    
    if (empty($data['bio'])) {
        setcookie('bio_error'.$row, '1', time() + 24 * 60 * 60);
        $errors = true;
    } else {
        if (!preg_match('/^([а-яА-ЯЁёa-zA-Z0-9,.\s-]+)$/u', $data['bio'])) {
            setcookie('bio_error'.$row, '1', time() + 24 * 60 * 60);
            $errors = true;
        }
        setcookie('bio_value', $data['bio'], time() + 365 * 24 * 60 * 60);
    }
    

    
    return $errors;
}
function err_declare($counter=null){
    $errors = array();
    $errors['field-name'] = !empty($_COOKIE['field-name_error'.$counter]);
    $errors['field-email'] = !empty($_COOKIE['field-email_error'.$counter]);
    $errors['field-year'] = !empty($_COOKIE['field-year_error'.$counter]);
    $errors['radio-group-1'] = !empty($_COOKIE['radio-group-1_error'.$counter]);
    $errors['radio-group-2'] = !empty($_COOKIE['radio-group-2_error'.$counter]);
    $errors['abilities'] = !empty($_COOKIE['abilities_error'.$counter]);
    $errors['bio'] = !empty($_COOKIE['bio_error'.$counter]);
    return $errors;
}

function msg_declare($messages,$errors, $counter=null){
   
    if ($errors['field-name']) {
        setcookie('field-name_error'.$counter, '', 100000);
        $messages[] = '<div class="error">Заполните имя корректно. </div>';
    }
    
    if ($errors['field-email']) {
        setcookie('field-email_error'.$counter, '', 100000);
        $messages[] = '<div class="error">Заполните email корректно. <br> Email должен содержать символ "@" </div>';
    }
    if ($errors['field-year']) {
        setcookie('field-year_error'.$counter, '', 100000);
        $messages[] = '<div class="error">Заполните дату рождения корректно. <br> Дата рождения должна быть записана в формате день/месяц/год. </div>';
    }
    if ($errors['radio-group-1']) {
        setcookie('radio-group-1_error'.$counter, '', 100000);
        $messages[] = '<div class="error">Укажите пол. </div>';
    }
    if ($errors['radio-group-2']) {
        setcookie('radio-group-2_error'.$counter, '', 100000);
        $messages[] = '<div class="error">Укажите количество конечностей. </div>';
    }
    if ($errors['abilities']) {
        setcookie('abilities_error'.$counter, '', 100000);
        $messages[] = '<div class="error">Укажите хотя бы одну способность. </div>';
    }
    
    if ($errors['bio']) {
        setcookie('bio_error'.$counter, '', 100000);
        $messages[] = '<div class="error">Заполните биографию корректно. <br> Допустимые символы: А-Я, а-я, A-Z, a-z, 0-9, -, ., запятая, пробел</div>';
    }
    
    return $messages;
}



