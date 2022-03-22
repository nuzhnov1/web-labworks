<?php
session_start();
$user_level = $_SESSION['user_level'];

if ($user_level <= 1) {
    $user_type_form = "
        <div style='font: 12pt \"Times New Roman\"; margin: 10px auto; color: black; text-align: center'>
            <snap>Выберите тип пользователя для регистрации:</snap><br>
            <snap>
                <input id='user_type' type='radio' name='user_type' value='1'>
                Администратор
            </snap>
            <snap>
                <input id='user_type' type='radio' name='user_type' value='2' checked>
                Пользователь
            </snap>
        </div>
    ";
}
else {
    $user_type_form = "";
}

echo "
    <html lang='ru'>
        <head>
            <meta charset='utf-8' content='text/html'>
            <title>Registration</title>
        </head>
        <body style='background-color: #808080'>
            <label for='username'></label>
            <label for='login'></label>
            <label for='password'></label>
            <label for='user_type'></label>
            <form method='post' action='register_action.php' style='width: 400px; margin: 0 auto;'>
                <table style='font: 12pt \"Times New Roman\"; caption-side: top; color: black; margin: 0 auto;'>
                    <caption style='font: 14pt \"Times New Roman\";'>Окно регистрации нового пользователя</caption>
                    <tbody>
                        <tr>
                            <td>Имя пользователя:</td>
                            <td><input id='username' name='username' type='text' required pattern='\w{5,255}'></td>
                        </tr>
                        <tr>
                            <td>Логин:</td>
                            <td><input id='login' name='login' type='text' required pattern='[\w_]{5,255}'></td>
                        </tr>
                        <tr>
                            <td>Пароль:</td>
                            <td><input id='password' name='password' type='password' required pattern='[\w_\-]{5,255}'></td>
                        </tr>
                    </tbody>
                </table>
                $user_type_form
                <div style='text-align: center; margin-top: 10px'>
                    <input type='submit' value='Подтвердить'>
                    <input type='reset' value='Очистить' style='margin-bottom: 10px'><br>
                    <a href='index.php'>Назад</a>
                </div>
            </form>
        </body>
    </html>
";