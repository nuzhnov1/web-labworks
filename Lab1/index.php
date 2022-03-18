<html lang="ru">
    <head>
        <meta charset="utf-8">
        <title>Lab1</title>
        <style>
            h1 {
                text-align: center;
                font: 32pt "Times New Roman";
                color: darkslateblue;
                margin-bottom: 5%;
            }
            h2 {
                text-align: left;
                font: 26pt "Times New Roman";
                color: darkslateblue;
                margin-top: 0;
                margin-bottom: 2%;
            }
            .Part {
                text-align: left;
                font: 14pt "Times New Roman";
                color: green;
                border: 4px solid silver;
                margin-bottom: 5%;
            }
            body {
                background-color:#808080;
            }
        </style>
    </head>
    <body>
        <h1>Лабораторная работа №1</h1>
        <div class="Part">
            <h2>Часть 1. MySQL.</h2>
            <p>Файл дампа базы данных - <a href="dump.sql">Скрипт MySQL</a>.
        </div>

        <div class="Part">
            <h2>Часть 2. Cookies.</h2>
            <?php
            if (isset($_COOKIE['visited'])) {
                $data = preg_split("/ /", $_COOKIE['visited'], 5);

                $note = ($data[0] == 'unchecked') ? '' : 'checked';
                $warn = ($data[1] == 'unchecked') ? '' : 'checked';
                $error = ($data[2] == 'unchecked') ? '' : 'checked';
                $fatal = ($data[3] == 'unchecked') ? '' : 'checked';
                $result = $data[4];
            } else {
                // Default values:
                $note = '';
                $warn = '';
                $error = 'checked';
                $fatal = 'checked';
                $result = 'Вы здесь впервые. Выбраны флаги: "Ошибка" "Критическая ошибка"';
            }

            echo "
            <form method=\"get\" action=\"cookies.php\">
                <p>Какова степень детализации сообщений об ошибках?</p>
                <p style=\"color: darkgreen\"><input type=\"checkbox\" name=\"Уведомление\" $note>Уведомление</p>
                <p style=\"color: yellow\"><input type=\"checkbox\" name=\"Предупреждение\" $warn>Предупреждение</p>
                <p style=\"color: red\"><input type=\"checkbox\" name=\"Ошибка\" $error>Ошибка</p>
                <p style=\"color: darkred\">
                    <input type=\"checkbox\" name=\"Критическая\" $fatal>Критическая ошибка</p>
                <p style=\"color: sienna\">$result</p>
                <hr>
                <p><input type=\"submit\" value=\"Отправить\"> <input type=\"reset\" value=\"Сбросить\"></p>
            </form>
            ";
            ?>
        </div>

        <div class="Part">
            <h2>Часть 3. Файлы.</h2>
            <form method="get" action="files.php">
                <p>Выберите экскурсии по городам:</p>
                <p style="color: red"><input type="checkbox" name="Москва">Москва</p>
                <p style="color: lightgreen"><input type="checkbox" name="Рим">Рим</p>
                <p style="color: yellow"><input type="checkbox" name="Каир">Каир</p>
                <p style="color: blue"><input type="checkbox" name="Париж">Париж</p>
                <p style="color: white"><input type="checkbox" name="Лондон">Лондон</p>
                <p>Выберите время экскурсии:</p>
                <p>
                    <select name="time_list" size="1">
                        <option value="10:00">10:00</option>
                        <option value="12:00" selected>12:00</option>
                        <option value="14:00">14:00</option>
                        <option value="16:00">16:00</option>
                        <option value="18:00">18:00</option>
                    </select>
                </p>
                <hr>
                <p><input type="submit" value="Отправить"> <input type="reset" value="Сбросить"></p>
            </form>
        </div>

        <div class="Part">
            <h2><a href="../index.html">Назад</a></h2>
        </div>
    </body>
</html>
