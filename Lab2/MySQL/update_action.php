<?php
include '../Materials/global.php';
include 'error.php';

global $g_products;

try {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $date = $_POST['date'];
    $manufacturer_id = $_POST['manufacturer_id'];
    $vendor_id = $_POST['vendor_id'];

    $g_products->update_product($id, $name, $price, $date, $manufacturer_id, $vendor_id);
    header("Location: index.php?all=");
}
catch (RuntimeException $e) {
    echo generate_error_page($e->getMessage());
}
