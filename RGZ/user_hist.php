<?php
include_once '../Lab2/Materials/global.php';
include_once 'error.php';
include_once 'common.php';

global $g_visits;

try {
    verification();

    if (!isset($_GET['user'])) {
        echo generate_error_page("не задано имя пользователя");
        exit(-1);
    }

    $user = $_GET['user'];

    if (isset($_GET['all']) || (count($_GET) == 1)) {
        session_unregister("user_hist_begin");
        session_unregister("user_hist_end");
    }

    if (isset($_GET['begin']) || isset($_GET['end'])) {
        $show_all = "
            <a href='user_hist.php?user=$user&all='>
                <input type='button' value='Показать всё'>
            </a>
        ";

        if (isset($_GET['begin'])) {
            $user_hist_begin = $_GET['begin'];
            session_register("user_hist_begin");
        }

        if (isset($_GET['end'])) {
            $user_hist_end = $_GET['end'];
            session_register("user_hist_end");
        }
    }
    else
        $show_all = "";

    $begin = $_SESSION['user_hist_begin'];
    $end = $_SESSION['user_hist_end'];

    $user_stat_table = $g_visits->get_user_hist_table($user, $begin, $end);
    $period = get_period($begin, $end);

    echo "
        <html lang='ru'>
            <head>
                <meta charset='utf-8' content='text/html'>
                <link rel='stylesheet' href='index.css' type='text/css'>
                <title>User history</title>
            </head>
            <body>
                <div style='margin-bottom: 1%;'>
                    <table class='content' style='width: 50%;'>
                        <caption>Хронология посещений пользователя '$user' $period</caption>
                        <thead>
                            <tr>
                                <td>Название страницы</td>
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
                        <form method='get' action='user_hist.php' style='margin: 0 auto;'>
                            <label for='user'></label>
                            <label for='begin'></label>
                            <label for='end'></label>
                            <input id='user' type='hidden' name='user' value='$user'>
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
