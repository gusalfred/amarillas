<?php
require_once 'vendor/autoload.php';
require_once 'ewcfg11.php';
require_once 'userfn11.php';
$conn = dbal_conn();

$action = (empty($_POST['action'])) ? '' : $_POST['action'];

switch ($action) {

    case 'categoria_add':
        unset($_POST['action']);
        $conn->insert('empresas_categorias', $_POST);
        echo "ok"; break;

    case 'categoria_delete':
        $conn->delete('empresas_categorias', array( 'id_empresa_categoria' => $_POST['id'] ));
        echo "ok"; break;



}


