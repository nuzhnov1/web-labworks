<html lang="ru">
    <head>
        <meta charset="utf-8">
        <title>Lab1. Files.</title>
        <style>
            h1 {
                text-align: center;
                font: 32pt "Times New Roman";
                color: darkslateblue;
                margin-bottom: 5%;
            }
            body {
                background-color:#808080;
            }
        </style>
    </head>
    <body>
        <h1>Лабораторная работа №1. Часть 3. Работа с файлами в PHP</h1>
        <?php
        $record = 'Дата и время: ' . date('d F Y H:i:s. ') . 'Планируемые города для посещения:';
        foreach(array('Москва', 'Рим', 'Каир', 'Париж', 'Лондон') as $city)
            if (isset($_GET[$city]))
                $record .= " \"$city\"";
        $record .= '. Планируемое время посещения: ' . $_GET['time_list'] . "\n";

        $file = fopen('data.txt', 'a+');
        if (!$file || !flock($file, 2) || !fwrite($file, $record))
            exit("<p style='font-size: 16pt; color: red;'>Ошибка: не удалось записать данные на сервер!</p>");

        if (fseek($file, 0, SEEK_SET) == -1)
            exit("<p style='font-size: 16pt; color: red;'>Ошибка: не удалось прочитать данные из сервера!</p>");

        echo "<p style='font-size: 18pt; color: blue;'>Содержимое файла:</p>";
        while ($line = fgets($file))
            echo "$line<br>";

        flock($file, 3);
        fclose($file);
        ?>
        <p><a href="index.php">Назад</a></p>
    </body>
</html>
