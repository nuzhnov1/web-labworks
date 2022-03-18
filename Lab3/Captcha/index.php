<html lang="ru">
    <head>
        <meta charset="utf-8" content="text/html">
        <link rel="stylesheet" href="index.css" type="text/css">
        <title>Captcha</title>
    </head>
    <body>
        <h1>Лабораторная работа №3. Часть 2. Captcha и CKEditor.</h1>
        <form method="post" action="check_captcha.php">
            <div class="wrapper">
                <div class="form">
                    <span>Выберите экскурсии по городам:</span><br>
                    <div style="margin: 3px auto; padding: 2px">
                        <label for="Moscow"></label>
                        <label for="Rome"></label>
                        <label for="Cairo"></label>
                        <label for="Paris"></label>
                        <label for="London"></label>
                        <span>
                            <input id="Moscow" type="checkbox" name="Moscow">
                            <span style="color: red">Москва</span>
                        </span><br>
                        <span>
                            <input id="Rome" type="checkbox" name="Rome">
                            <span style="color: lightgreen">Рим</span>
                        </span><br>
                        <span>
                            <input id="Cairo" type="checkbox" name="Cairo">
                            <span style="color: yellow">Каир</span>
                        </span><br>
                        <span>
                            <input id="Paris" type="checkbox" name="Paris">
                            <span style="color: blue">Париж</span>
                        </span><br>
                        <span>
                            <input id="London" type="checkbox" name="London">
                            <span style="color: white">Лондон</span>
                        </span><br>
                    </div>
                    <div>
                        <span>Выберите время экскурсии:</span>
                        <label for="time"></label>
                        <select id="time" name="time" size="1">
                            <option value="10:00">10:00</option>
                            <option value="12:00" selected>12:00</option>
                            <option value="14:00">14:00</option>
                            <option value="16:00">16:00</option>
                            <option value="18:00">18:00</option>
                        </select>
                    </div>
                </div>
                <div class="editor">
                    <?php
                        include_once("fckeditor/fckeditor.php");

                        session_start();

                        $FCKEditor = new FCKeditor("editor");
                        $FCKEditor->BasePath = "fckeditor/";
                        $FCKEditor->Value = "Редактор FCKEditor";
                        $FCKEditor->Create();
                    ?><br>
                </div>
            </div>
            <div class="captcha">
                <div>
                    <label for="captcha"></label>
                    <img src="kcaptcha/index.php?<?php echo session_name()?>=<?php echo session_id()?>"
                         alt="Captcha" style="margin: 0 auto;"
                    ><br>
                    <span>Введите текст с картинки:</span>
                    <input id="captcha" type="text" name="captcha" required style="margin: 0 auto;">
                </div>
                <span style="text-align: center">
                    <input type="submit" value="Отправить">
                    <input type="reset" value="Сбросить">
                </span>
            </div>
            <div style="text-align: center"><a href="../index.html">Назад</a></div>
        </form>
    </body>
</html>
