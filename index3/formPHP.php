<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <link rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script
      src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/slapform@latest/dist/index.min.js"></script>
  <body style="margin:0;">
    <div style="text-align:center; padding-top:20%">
    <button id="my-button" data-toggle="modal" data-target="#myModal">Форма</button></div>
    <div class="modal fade" id="myModal" >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
<form action="http://u52952.kubsu-dev.ru/index.php"
method="POST">
<label>
    <br />
    <input required name="field-name"
      value="Имя" />
  </label><br />

  <label>
    <input required name="field-email"
      type="email"
      placeholder="Введите вашу почту" />
  </label><br />
  <label>
    <select name="field-year">
      <?php 
    for ($i = 1922; $i <= 2022; $i++) {
      printf('<option value="%d">%d год</option>', $i, $i);
    }
    ?>
    </select>
  </label><br />
  Пол:<br />
  <label><input type="radio" checked="checked"
    name="radio-group-1" value="1" />
    Мужской</label>
  <label><input type="radio"
    name="radio-group-1" value="2" />
    Женский</label><br />

        Количество конечностей:<br />
        <label><input type="radio" checked="checked"
          name="radio-group-2" value="1" />
          1</label>
        <label><input type="radio"
          name="radio-group-2" value="2" />
          2</label>
          <label><input type="radio"
            name="radio-group-2" value="3" />
            3</label>
            <label><input type="radio"
                name="radio-group-2" value="4" />
                4</label><br />

        <label>
            Сверхспособности:
            <br />
            <select name="abilities[]"
              multiple="multiple">
              <option value="1" selected="selected"> бессмертие</option>
              <option value="2" >прохождение сквозь стены</option>
              <option value="3">левитация</option>
            </select>
          </label><br />

          <label>
            Биография:<br />
            <textarea name="field-name-2">Введите биографию</textarea>
          </label><br />

          <label><input type="checkbox" checked="checked"
            name="check-1" />
            с контрактом ознакомлен (а)</label><br />
            <input  type="submit" value="Отправить" />
        </form>
        </div>
        </div>
      </div>
    </div>   
