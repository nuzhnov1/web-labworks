<?php
include '../../Lab2/Materials/global.php';
include 'error.php';

global $g_users;

try {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $result = $g_users->find($login);

    if (!$result)
        echo generate_error_page("Ошибка: пользователя с таким логином нет!");
    else {
        $hash = $result['hash'];

        if ($hash == crypt($password, "djsubsbin3912748")) {
            session_start();

            $username = $result['username'];
            $login = $result['login'];
            $user_level = $result['user_level'];

            $_SESSION['username'] = $username;
            $_SESSION['login'] = $login;
            $_SESSION['user_level'] = $user_level;

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
