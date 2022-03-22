<?php
include '../Materials/global.php';
include 'error.php';

global $g_users;

try {
    session_start();

    $user_level = $_SESSION['user_level'];
    $username = $_POST['username'];
    $login = $_POST['login'];
    $password = $_POST['password'];

    if (!isset($_POST['user_type']))
        $user_type = 2;
    elseif ($_POST['user_type'] == '1' && $user_level > 1) {
        echo generate_error_page("Ошибка: не достаточно прав для создания такого пользователя");
        exit(-1);
    }
    else
        $user_type = $_POST['user_type'];

    $g_users->add_user($username, $login, crypt($password, "djsubsbin3912748"), $user_type);

    if ($user_level > 1) {
        $username = $_POST['username'];
        $login = $_POST['login'];
        $user_level = $_POST['user_type'];

        session_register("username");
        session_register("login");
        session_register("user_level");
    }

    if ($user_type == 1) {
        $color = 'yellow';
        $level = 'администратор';
    }
    else {
        $color = 'green';
        $level = 'пользователь';
    }

    echo "
        <html lang='ru'>
            <head>
                <meta charset='utf-8' content='text/html'>
                <title>Success!</title>
            </head>
            <body style='background-color: grey'>
                <h1 style='color: green; font: 24pt \"Times New Roman\"; text-align: center'>
                    Регистрация прошла успешно!
                </h1>
                <div style='text-align: center'>
                    <div style='margin: 10px auto'>
                        <span>Пользователь</span>
                        <span style='color: $color'>$username</span>
                        <span>успешно зарегистрирован!</span><br>
                        <span>Уровень привилегий: $level.</span>
                    </div>
                    <a href='index.php'>На главную</a>
                </div>
            </body>
        </html>
    ";
}
catch (RuntimeException $e) {
    echo generate_error_page($e->getMessage());
}
