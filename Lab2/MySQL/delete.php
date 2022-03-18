<?php
include '../Materials/global.php';
include 'error.php';

global $g_products;

try {
    $id = $_GET['id'];
    $g_products->delete_product($id);
    header("Location: index.php?all=");
}
catch (RuntimeException $e) {
    echo generate_error_page($e->getMessage());
}
