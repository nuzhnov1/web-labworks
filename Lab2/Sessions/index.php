<?php
include '../Materials/global.php';
include 'error.php';

global $g_products;

try {
    session_start();

    if (isset($_SESSION['login'])) {
        $update_grant = true;

        $update_form = "<th>Обновить</th>";
        $insert_form = "<a href='insert.php'>Добавить строку</a><br>";

        $username = $_SESSION['username'];
        if ($_SESSION['user_level'] == 1) {
            $register_form = "
                <span style='color: yellow'>Добро пожаловать $username! (администратор)</span><br>
                <a href='exit.php'>Выйти из текущего сеанса</a><br>
            ";
            $delete_grant = true;
            $delete_form = "<th>Удалить</th>";
        }
        else {  // user_level == 2
            $register_form = "
                <span style='color: green'>Добро пожаловать $username! (пользователь)</span><br>
                <a href='exit.php'>Выйти из текущего сеанса</a><br>
            ";
            $delete_grant = false;
            $delete_form = "";
        }
    }
    else {
        $register_form = "
            <span style='color: blue'>Добро пожаловать гость!</span>
            <form method='post' action='login.php' style='margin: 0 auto;'>
                <table style='margin: 0 auto'>
                    <caption style='font: 14pt \"Times New Roman\"'>Войти в систему</caption>
                    <tr>
                        <td>Логин:</td>
                        <td><input type='text' name='login' required></td>
                    </tr>
                    <tr>
                        <td>Пароль:</td>
                        <td><input type='password' name='password' required></td>
                    </tr>
                </table>
                <input type='submit' value='Войти'>
                <input type='reset' value='Очистить'>
            </form>
        ";
        $update_grant = false;
        $delete_grant = false;

        $update_form = "";
        $insert_form = "";
        $delete_form = "";

        $username = 'guest';
        $user_level = 3;
        session_register("username");
        session_register("user_level");
    }

    if (isset($_POST['name'])) {
        $table = $g_products->get_html_view($update_grant, $delete_grant, null, $_POST['name']);
        $caption = "Результаты поиска";
        $ref = "<a href='index.php?all='>Показать всё</a>";
    } elseif (isset($_GET['all'])) {
        $table = $g_products->get_html_view($update_grant, $delete_grant);
        $caption = "Строительные товары";
        $ref = "<a href='index.php'>Свернуть</a>";
    }
    else {
        $table = $g_products->get_html_view($update_grant, $delete_grant, 5);
        $caption = "Строительные товары";
        $ref = "<a href='index.php?all='>Показать всё</a>";
    }

    echo "
        <html lang='ru'>
            <head>
                <meta charset='utf-8' content='text/html'>
                <link rel='stylesheet' href='index.css' type='text/css'>
                <title>Sessions</title>
            </head>
            <body>
                <h1>Лабораторная работа №2. Часть 2. Сессии.</h1>
                <div class='register_form'>
                    $register_form
                    <a href='register.php'>Зарегистрироваться</a>
                </div>
                <table class='content'>
                    <caption>$caption</caption>
                    <thead>
                        <tr>
                            <th>Наименование материала</th> 
                            <th>Стоимость товара (₽)</th>
                            <th>Дата выпуска</th>
                            <th>Производитель</th>
                            <th>Поставщик</th>
                            $update_form
                            $delete_form
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
                    $insert_form
                    <a href='../index.html'>Назад</a>
                </div>
            </body>
        </html>
    ";
} catch (RuntimeException $e) {
    echo generate_error_page($e->getMessage());
}
