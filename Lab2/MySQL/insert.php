<?php
include '../Materials/global.php';
include 'error.php';

global $g_manufacturers;
global $g_vendors;

try {
    $man_name_list_view = $g_manufacturers->get_html_list();
    $ven_name_list_view = $g_vendors->get_html_list();

    echo "
        <html lang='ru'>
            <head>
                <meta charset='utf-8' content='text/html'>
                <title>Insert new row</title>
            </head>
            <body style='background-color: #808080'>
                <label for='name'></label>
                <label for='price'></label>
                <label for='date'></label>
                <label for='manufacturer_id'></label>
                <label for='vendor_id'></label>
                <form method='post' action='insert_action.php' style='width: 400px;'>
                    <table style='font: 12pt \"Times New Roman\"; caption-side: top; color: black;'>
                        <caption style='font: 14pt \"Times New Roman\";'>Вставка новой строки</caption>
                        <tbody>
                            <tr>
                                <td>Наименование материала:</td>
                                <td><input id='name' name='name' type='text' required placeholder='Пример: Bricks'></td>
                            </tr>
                            <tr>
                                <td>Цена товара (₽):</td>
                                <td>
                                    <input id='price' name='price' type='text' 
                                        required pattern='(0|[1-9]\d*)(\.\d{1,2})?'
                                        placeholder='Пример: 1.23, 0.23 или 1'
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td>Дата выпуска:</td>
                                <td><input id='date' name='date' type='date' required></td>
                            </tr>
                            <tr>
                                <td>Производитель:</td>
                                <td><select name='manufacturer_id' id='manufacturer_id' required>$man_name_list_view</select></td>
                            </tr>
                            <tr>
                                <td>Поставщик:</td>
                                <td><select name='vendor_id' id='vendor_id' required>$ven_name_list_view</select></td>
                            </tr>
                        </tbody>
                    </table>
                    <div style='text-align: center; margin-top: 10px'>
                        <input type='submit' value='Добавить строку'>
                        <input type='reset' value='Очистить'><br>
                        <a href='index.php'>Назад</a>
                    </div>
                </form>
            </body>
        </html>
    ";
} catch (RuntimeException $e) {
    echo generate_error_page($e->getMessage());
}
