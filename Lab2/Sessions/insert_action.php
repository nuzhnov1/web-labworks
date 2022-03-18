<?php
include '../Materials/global.php';
include 'error.php';

global $g_products;

try {
    session_start();

    if ($_SESSION['user_level'] > 2) {
        echo generate_error_page("Ошибка: вам не доступна операция вставки!");
        exit(-1);
    }

    $name = $_POST['name'];
    $price = $_POST['price'];
    $release_date = $_POST['date'];
    $manufacturer_id = $_POST['manufacturer_id'];
    $vendor_id = $_POST['vendor_id'];

    $g_products->add_product($name, $price, $release_date, $manufacturer_id, $vendor_id);
    header("Location: index.php");
}
catch (RuntimeException $e) {
    echo generate_error_page($e->getMessage());
}
