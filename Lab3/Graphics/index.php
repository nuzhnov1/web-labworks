<?php
include '../../Lab2/Materials/global.php';
include 'error.php';

global $g_products;
global $g_visits;

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
        $show_panel = "<a href='index.php'>Показать всё</a><br>";
    } else {
        $table = $g_products->get_html_view($update_grant, $delete_grant);
        $caption = "Строительные товары";
        $show_panel = "
            <form method='post' action='index.php' style='margin: 1% 0;'>
                <label for='name'></label>
                <span>
                    Поиск по имени:
                    <input type='text' id='name' name='name' required placeholder='Пример: Bricks'>
                </span><br>
                <input type='submit' value='Поиск'>
                <input type='reset' value='Очистить'>
            </form>
        ";
    }

    try { $g_visits->add_visit("index.php"); }
    catch (RuntimeException $e) {}

    echo "
        <html lang='ru'>
            <head>
                <meta charset='utf-8' content='text/html'>
                <link rel='stylesheet' href='index.css' type='text/css'>
                <title>Graphics</title>
            </head>
            <body>
                <h1>Лабораторная работа №3. Часть 1. Графика в PHP.</h1>
                <div class='register_form'>
                    $register_form
                    <a href='register.php'>Зарегистрироваться</a>
                </div>
                <div style='margin-bottom: 1%; text-align: center;'>
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
                    $insert_form
                </div>
                <div style='margin: 1% auto; text-align: center;'>
                    $show_panel
                    <form method='get' action='histogram.php'>
                        <label for='field'></label>
                        <span>Сгруппировать и вывести гистограмму средних цен:</span>
                        <select id='field' name='field'>
                            <option value='manufacturer'>по производителям</option>
                            <option value='vendor'>по поставщикам</option>
                        </select>
                        <input type='submit' value='Вывести'>
                    </form>
                    <a href='../index.html'>Назад</a>
                </div>
            </body>
        </html>
    ";
} catch (RuntimeException $e) {
    echo generate_error_page($e->getMessage());
}
