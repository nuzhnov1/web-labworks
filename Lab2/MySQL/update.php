<?php
include '../Materials/global.php';
include 'error.php';

global $g_products;
global $g_manufacturers;
global $g_vendors;

try {
    $id = $_GET['id'];
    $row = $g_products->get_product_by_id($id);
    $man_name_list_view = $g_manufacturers->get_html_list($row['manufacturer_id']);
    $ven_name_list_view = $g_vendors->get_html_list($row['vendor_id']);

    $default_name = $row['name'];
    $default_price = $row['price'];
    $default_release_date = $row['release_date'];
    $default_man = $row['manufacturer_id'];
    $default_ven = $row['vendor_id'];

    echo "
        <html lang='ru'>
            <head>
                <meta charset='utf-8' content='text/html'>
                <title>Update row</title>
            </head>
            <body style='background-color: #808080'>
                <label for='id'></labelfor>
                <label for='name'></label>
                <label for='price'></label>
                <label for='date'></label>
                <label for='manufacturer_id'></label>
                <label for='vendor_id'></label>
                <form method='post' action='update_action.php' style='width: 400px;'>
                    <table style='font: 12pt \"Times New Roman\"; caption-side: top; color: black;'>
                        <caption style='font: 14pt \"Times New Roman\";'>Обновление строки</caption>
                        <tbody>
                            <tr>
                                <td>Наименование материала:</td>
                                <td><input id='name' name='name' type='text' required value=$default_name></td>
                            </tr>
                            <tr>
                                <td>Цена товара (₽):</td>
                                <td>
                                    <input id='price' name='price' type='text' 
                                        required pattern='(0|[1-9]\d*)(\.\d{1,2})?'
                                        value=$default_price
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td>Дата выпуска:</td>
                                <td>
                                    <input id='date' name='date' type='date' required value=$default_release_date>
                                </td>
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
                        <input type='hidden' name='id' value='$id'>
                        <input type='submit' value='Обновить строку'>
                        <input type='reset' value='Очистить'><br>
                        <a href='index.php?all='>Назад</a>
                    </div>
                </form>
            </body>
        </html>
    ";
} catch (RuntimeException $e) {
    echo generate_error_page($e->getMessage());
}
