<?php
include_once '../Lab2/Materials/global.php';
include_once 'error.php';
include_once 'common.php';

global $g_visits;

try {
    verification();

    if (isset($_GET['all']) || (count($_GET) == 0)) {
        unset($_SESSION['visits_begin']);
        unset($_SESSION['visits_end']);
    }

    if (isset($_GET['visits_begin']) || isset($_GET['visits_end'])) {
        $show_all = "
            <a href='index.php?all='>
                <input type='button' value='Показать всё'>
            </a>
        ";

        if (isset($_GET['visits_begin']))
            $_SESSION['visits_begin'] = $_GET['visits_begin'];

        if (isset($_GET['visits_end']))
            $_SESSION['visits_end'] = $_GET['visits_end'];
    }
    else
        $show_all = "";

    if (isset($_GET['truncate']))
        $g_visits->truncate();

    $begin = $_SESSION['visits_begin'];
    $end = $_SESSION['visits_end'];
    $visits_table = $g_visits->get_visits_table($begin, $end);
    $visits_diagram = $g_visits->get_visits_diagram($begin, $end);
    $period = get_period($begin, $end);
    imagepng($visits_diagram, "visits_diagram.png");

    $visitors_table = $g_visits->get_visitors_table();

    echo "
        <html lang='ru'>
            <head>
                <meta charset='utf-8'>
                <link rel='stylesheet' href='index.css' type='text/css'>
                <title>RGZ</title>
            </head>
            <body>
                <h1>Расчётно-графическое задание. Модуль учёта посещаемости сайта.</h1>
                <div style='text-align: center; margin-bottom: 1%;'>
                    <a href='index.php?truncate='>Очистить историю посещений</a><br>
                    <a href='exit.php'>Выйти из текущего сеанса</a>
                </div>
                <div style='display: flex; margin-bottom: 1%;'>
                    <div style='text-align: center; width: 47%; margin: 0 6% 0 0;'>
                        <table class='content'>
                            <caption>Посетители сайта</caption>
                            <thead>
                                <tr>
                                    <td>Пользователь</td>
                                    <td>Статистика посещений пользователя</td>
                                    <td>Хронология посещений пользователя</td>
                                </tr>
                            </thead>
                            <tbody>
                                $visitors_table
                            </tbody>
                        </table>
                    </div>
                    <div style='text-align: center; width: 47%;'>
                        <table class='content'>
                            <caption>Количество посещений каждой из страниц $period</caption>
                            <thead>
                                <tr>
                                    <td>Название страницы</td>
                                    <td>Количество посещений</td>
                                    <td>Хронология посещений</td>
                                </tr>
                            </thead>
                            <tbody>
                                $visits_table
                            </tbody>
                        </table>
                        <div style='text-align: center; margin: 1% auto 0 auto;'>
                            Интервал времени посещений:<br>
                            <form method='get' action='index.php' style='margin: 0 auto;'>
                                <label for='visits_begin'></label>
                                <label for='visits_end'></label>
                                <span>
                                    Начало:
                                    <input id='visits_begin' type='date' name='visits_begin'>
                                </span>
                                <span>
                                    Конец:
                                    <input id='visits_end' type='date' name='visits_end'>
                                </span><br>
                                <input type='submit' value='Показать'>
                                <input type='reset' value='Сбросить'>
                                $show_all
                            </form>
                        </div>
                    </div>
                </div>
                <div style='margin: 1% auto; text-align: center;'>
                    <span>Диаграмма посещений страниц сайта</span><br>
                    <img src='visits_diagram.png' alt='Не удалось загрузить изображение'
                     style='color: red; width: 90%; height: 90%;'
                    >
                </div>
                <div style='text-align: center'>
                    <a href='../index.html'>Назад</a>
                </div>
            </body>
        </html>
    ";
}
catch (RuntimeException $e) {
    echo generate_error_page($e->getMessage());
}
