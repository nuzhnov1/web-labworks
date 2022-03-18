<?php
/**
 * @param $msg - Сообщение об ошибке
 * @return string - HTML-страница с сообщением об ошибке
 */
function generate_error_page($msg) {
    return "
        <html lang='ru'>
            <head>
                <meta charset='utf-8' content='text/html'>
                <title>Internal server error</title>
            </head>
            <body>
                <h1 style='color: red; text-align: center; font-size: 18pt'>Internal server error: $msg</h1>
            </body>
        </html>
    ";
}
