<?php
include '../../Lab2/Materials/global.php';
include 'error.php';

global $g_products;
global $g_visits;

try {
    session_start();

    if ($_SESSION['user_level'] > 1) {
        echo generate_error_page("Ошибка: вам не доступна операция удаления!");
        exit(-1);
    }

    $id = $_GET['id'];
    $g_products->delete_product($id);

    try { $g_visits->add_visit("delete.php"); }
    catch (RuntimeException $e) {}

    header("Location: index.php?all=");
}
catch (RuntimeException $e) {
    echo generate_error_page($e->getMessage());
}
