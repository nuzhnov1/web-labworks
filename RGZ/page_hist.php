<?php
include_once '../Lab2/Materials/global.php';
include_once 'error.php';
include_once 'common.php';

global $g_visits;

try {
    if (!isset($_GET['page'])) {
        echo generate_error_page("не задано название страницы");
        exit(-1);
    }

    $page = $_GET['page'];

    if (isset($_GET['all'])) {
        unset($_SESSION['page_hist_begin']);
        unset($_SESSION['page_hist_end']);
    }

    if (isset($_GET['begin']) || isset($_GET['end'])) {
        $show_all = "
            <a href='page_hist.php?page=$page&all='>
                <input type='button' value='Показать всё'>
            </a>
        ";

        if (isset($_GET['begin']))
            $_SESSION['page_hist_begin'] = $_GET['begin'];

        if (isset($_GET['end']))
            $_SESSION['page_hist_end'] = $_GET['end'];
    }
    else
        $show_all = "";

    $begin = $_SESSION['page_hist_begin'];
    $end = $_SESSION['page_hist_end'];

    $user_stat_table = $g_visits->get_page_hist_table($page, $begin, $end);
    $period = get_period($begin, $end);

    echo "
        <html lang='ru'>
            <head>
                <meta charset='utf-8' content='text/html'>
                <link rel='stylesheet' href='index.css' type='text/css'>
                <title>Page history</title>
            </head>
            <body>
                <div style='margin-bottom: 1%;'>
                    <table class='content' style='width: 50%;'>
                        <caption>Хронология посещений страницы '$page' $period</caption>
                        <thead>
                            <tr>
                                <td>Посетитель</td>
                                <td>День посещения</td>
                                <td>Количество посещений за день</td>
                            </tr>
                        </thead>
                        <tbody>
                            $user_stat_table
                        </tbody>
                    </table>
                    <div style='text-align: center; margin-top: 1%;'>
                        Интервал времени посещений:<br>
                        <form method='get' action='page_hist.php' style='margin: 0 auto;'>
                            <label for='page'></label>
                            <label for='begin'></label>
                            <label for='end'></label>
                            <input id='page' type='hidden' name='page' value='$page'>
                            <span>
                                Начало:
                                <input id='begin' type='date' name='begin'>
                            </span>
                            <span>
                                Конец:
                                <input id='end' type='date' name='end'>
                            </span><br>
                            <input type='submit' value='Показать'>
                            <input type='reset' value='Сбросить'>
                            $show_all
                        </form>
                    </div>
                </div>
                <div style='text-align: center;'>
                    <a href='index.php'>Назад</a>
                </div>
            </body>
        </html>
    ";
}
catch (RuntimeException $e) {
    echo generate_error_page($e->getMessage());
}
