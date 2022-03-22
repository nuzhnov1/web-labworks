<?php
function get_period($begin, $end) {
    if ($begin && $end)
        return "за период от '$begin' до '$end'";
    elseif ($begin)
        return "начиная с '$begin'";
    elseif ($end)
        return "заканчивая '$end'";
    else
        return "за всё время";
}

function verification() {
    session_start();

    if (!isset($_SESSION['login'])) {
        echo "
            <html lang='ru'>
                <head>
                    <meta charset='utf-8'>
                    <link rel='stylesheet' href='index.css' type='text/css'>
                    <title>RGZ</title>
                </head>
                <body>
                    <div style='text-align: center; margin: auto 0;'>
                        <div class='register'>
                            <div style='color: blue; margin: 10px auto;'>
                                Чтобы продолжить войдите в систему
                            </div>
                            <form method='post' action='login.php'>
                                <table style='margin: 0 auto'>
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
                        </div>
                        <a href='../index.html'>Назад</a>
                    </div>
                </body>
            </html>
        ";
        exit(0);
    }
    elseif ($_SESSION['user_level'] > 1) {
        echo "
            <html lang='ru'>
                <head>
                    <meta charset='utf-8'>
                    <link rel='stylesheet' href='index.css' type='text/css'>
                    <title>RGZ</title>
                </head>
                <body>
                    <h1 style='color: red'>Данная возможность вам не доступна!</h1>
                    <div style='text-align: center'>
                        <a href='exit.php'>Выйти из аккаунта</a>
                        <a href='../index.html'>Назад</a>
                    </div>
                </body>
            </html>
        ";
        exit(-1);
    }
}
