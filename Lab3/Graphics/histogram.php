<?php
include '../../Lab2/Materials/global.php';
include 'error.php';

global $g_products;
global $g_visits;

try {
    $field = $_GET['field'];
    if ($field == 'manufacturer') {
        $table = $g_products->get_avg_price_for_each_manufacturer();
        $title = "Гистограмма средних цен товаров по производителям";
        $table_head = "
            <caption>Таблица средних цен товаров по производителям</caption>
            <thead>
                <tr>
                    <th>Производитель</th>
                    <th>Средняя цена</th>
                </tr>
            </thead>
        ";
    }
    else {
        $table = $g_products->get_avg_price_for_each_vendor();
        $title = "Гистограмма средних цен товаров по поставщикам";
        $table_head = "
            <caption>Таблица средних цен товаров по поставщикам</caption>
            <thead>
                <tr>
                    <th>Поставщик</th>
                    <th>Средняя цена</th>
                </tr>
            </thead>
        ";
    }

    $table_view = "<tbody>";
    foreach ($table as $row) {
        $name = $row['name'];
        $price = round($row['avg_price'], 2);

        $table_view .= "<tr>";
        $table_view .= "<td>$name</td>";
        $table_view .= "<td>$price</td>";
        $table_view .= "</tr>";
    }
    $table_view .= "</tbody>";

    try { $g_visits->add_visit("histogram.php"); }
    catch (RuntimeException $e) {}

    echo "
        <html lang='ru'>
            <head>
                <meta charset='utf-8' content='text/html'>
                <link rel='stylesheet' href='index.css' type='text/css'>
                <title>Gistogramm</title>
            </head>
            <body>
                <h1>Лабораторная работа №3. Часть 1. Графика в PHP.</h1>
                <div style='margin-bottom: 1%;'>
                    <table class='content' style='width: 500px'>
                        $table_head
                        $table_view
                    </table>
                </div>
                <div style='margin-bottom: 1%; text-align: center;'>
                    <span>$title</span><br>
                    <img src='histogram_draw.php?field=$field' alt='Изображение не загружено' width=100% height=100%
                    style='margin: 0 auto; color: red; border: 3px solid black;'
                    >
                </div>
                <div style='margin: 1% auto; text-align: center;'>
                    <a href='index.php'>Назад</a>
                </div>
            </body>
        </html>
    ";
} catch (RuntimeException $e) {
    echo generate_error_page($e->getMessage());
}
