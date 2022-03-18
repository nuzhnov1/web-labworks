<?php
include_once 'Database.php';
include_once 'Products.php';
include_once 'Manufacturers.php';
include_once 'Vendors.php';
include_once 'Users.php';
include_once 'Visits.php';

const HOST = "localhost";
const USER = "sunman";
const PASSWORD = "kenkortmrqwe1";
const DATABASE = "materials";

$g_database = new Database(HOST, USER, PASSWORD, DATABASE);
$g_products = new Products($g_database);
$g_manufacturers = new Manufacturers($g_database);
$g_vendors = new Vendors($g_database);
$g_users = new Users($g_database);
$g_visits = new Visits($g_database);
