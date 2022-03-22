<?php
include '../Materials/global.php';
include 'error.php';

global $g_users;

try {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $result = $g_users->find($login);

    if (!$result)
        echo generate_error_page("Ошибка: пользователя с таким логином нет!");
    else {
        $username = $result['username'];
        $login = $result['login'];
        $hash = $result['hash'];
        $user_level = $result['user_level'];

        if ($hash == crypt($password, "djsubsbin3912748")) {
            session_start();

            session_register('username');
            session_register('login');
            session_register('user_level');

            header("Location: index.php");
        }
        else {
            echo generate_error_page("Ошибка: неправильный пароль!");
        }
    }
}
catch (RuntimeException $e) {
    echo generate_error_page($e->getMessage());
}
