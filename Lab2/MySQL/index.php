<?php
include '../Materials/global.php';
include 'error.php';

global $g_products;

try {
    if (isset($_POST['name'])) {
        $table = $g_products->get_html_view(true, true, null, $_POST['name']);
        $caption = "Результаты поиска";
        $ref = "<a href='index.php?all='>Показать всё</a>";
    } elseif (isset($_GET['all'])) {
        $table = $g_products->get_html_view(true, true);
        $caption = "Строительные товары";
        $ref = "<a href='index.php'>Свернуть</a>";
    }
    else {
        $table = $g_products->get_html_view(true, true, 5);
        $caption = "Строительные товары";
        $ref = "<a href='index.php?all='>Показать всё</a>";
    }

    echo "
        <html lang='ru'>
            <head>
                <meta charset='utf-8' content='text/html'>
                <link rel='stylesheet' href='index.css' type='text/css'>
                <title>MySQL</title>
            </head>
            <body>
                <h1>Лабораторная работа №2. Часть 1. Работа с базой данных MySQL.</h1>
                <table class='content'>
                    <caption>$caption</caption>
                    <thead>
                        <tr>
                            <th>Наименование материала</th> 
                            <th>Стоимость товара (₽)</th>
                            <th>Дата выпуска</th>
                            <th>Производитель</th>
                            <th>Поставщик</th>
                            <th>Обновить</th>
                            <th>Удалить</th>
                        </tr>
                    </thead>
                    <tbody>
                        $table
                    </tbody>
                </table>
                <div style='margin: 1% auto; text-align: center;'>
                    <form method='post' action='index.php' style='margin-bottom: 1%;'>
                        <label for='name'></label>
                        <span>
                            Поиск по имени:
                            <input type='text' id='name' name='name' required placeholder='Пример: Bricks'>
                        </span><br>
                        <input type='submit' value='Поиск'>
                        <input type='reset' value='Очистить'>
                    </form>
                    $ref<br>
                    <a href='insert.php'>Добавить строку</a><br>
                    <a href='../index.html'>Назад</a>
                </div>
            </body>
        </html>
    ";
} catch (RuntimeException $e) {
    echo generate_error_page($e->getMessage());
}
