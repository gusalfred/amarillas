<?php
require_once 'vendor/autoload.php';
require_once 'ewcfg11.php';
require_once 'phpfn11.php';
include_once "userfn11.php";
$conn = dbal_conn();
$id = $_GET['id'];
echo $precio = $conn->fetchColumn('SELECT precio FROM planes WHERE id_plan = ?', array($id));