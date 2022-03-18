<?php
    session_start();

    $true_captcha = $_SESSION['captcha_keystring'];
    $captcha = $_POST['captcha'];

    if ($captcha == $true_captcha) {
        do {
            $record = "Дата и время: " . date('d F Y H:i:s') . ".\n" . "Планируемые города для посещения:";
            foreach(array('Moscow', 'Rome', 'Cairo', 'Paris', 'London') as $city)
                if (isset($_POST[$city]))
                    $record .= " \"$city\"";
            if (isset($_POST['time']))
                $record .= ".\n" . "Планируемое время посещения: " . $_POST['time'];
            if (isset($_POST['editor']))
                $record .= ".\n" . "Содержимое редактора: " . $_POST['editor'] . ".\n\n";

            $file = fopen('data.txt', 'a+');
            if (!$file || !flock($file, 2) || !fwrite($file, $record)) {
                $content = "<h1 style='color: red;'>Ошибка: не удалось записать данные на сервер!</h1>";
                $content .= "<div style='text-align: center'><a href='index.php' style='margin: 1%'>Назад</a></div>";
                break;
            }

            if (fseek($file, 0, SEEK_SET) == -1) {
                $content = "<h1 style='color: red;'>Ошибка: не удалось прочитать данные из сервера!</h1>";
                $content .= "<div style='text-align: center'><a href='index.php' style='margin: 1%'>Назад</a></div>";
                break;
            }

            $content = "<h1 style='color: blue;'>Содержимое файла:</h1>";
            $content .= "<div>";
            while ($line = fgets($file))
                $content .= "$line<br>";
            $content .= "</div>";
            $content .= "<div style='text-align: center'><a href='index.php' style='margin: 1%'>Назад</a></div>";

            flock($file, 3);
            fclose($file);
        } while (false);
    }
    else {
        $content = "<h1 style='color: red'>Captcha введена неверно!</h1>";
        $content .= "<div style='text-align: center'><a href='index.php' style='margin: 1%'>Исправить</a></div>";
    }

    echo "
        <html lang='ru'>
            <head>
                <meta charset='utf-8' content='text/html'>
                <link rel='stylesheet' href='index.css' type='text/css'>
                <title>Captcha</title>
            </head>
            <body>
                $content
            </body>
        </html>
    ";
