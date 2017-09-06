<?php
if (session_id() == "") session_start();
require_once 'vendor/autoload.php';
require_once 'ewcfg11.php';
require_once 'phpfn11.php';
include_once "userfn11.php";
$conn = dbal_conn();

$id_cat = (empty($_REQUEST['id_cat'])) ? 0 : $_REQUEST['id_cat'];

$cats_1 =  $conn->fetchAll("SELECT * FROM categorias_nivel1 ORDER BY categoria");

?>
<link href="bootstrap3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="css/global.css" rel="stylesheet" type="text/css" />
<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<script language="JavaScript" type="text/javascript">
function selecciona(id){
  parent.categoria_add(id);
  parent.$.fancybox.close();
}
</script>
<?php if ($id_cat != 0 ){ ?>
<?php $cats_2 =  $conn->fetchAll("SELECT * FROM categorias_nivel2 WHERE id_categoria_nivel1 = $id_cat ORDER BY categoria"); ?>

<a class="btn btn-primary" role="button" href="categorias_selecciona.php"><i class="fa fa-chevron-circle-left"></i> Regresar</a>
<table class="table table-bordered table-hover table-condensed">
    <tr class="info">
        <td>Sub Categorias</td>
    </tr>
    <?php foreach ($cats_2 as $row){ ?>
    <tr onclick="selecciona(<?= $row['id_categoria_nivel2'] ?>);">
        <td><?= $row['categoria'] ?></td>
    </tr>
    <?php }?>
</table>

<?php } else {?>

<table class="table table-bordered table-hover table-condensed">
    <tr class="info">
        <td>Categorias Principales </td>
    </tr>
    <?php foreach ($cats_1 as $row){ ?>
    <tr>
        <td><a href="?id_cat=<?= $row['id_categoria_nivel1'] ?>"><?= $row['categoria'] ?></a></td>
    </tr>
    <?php } ?>
</table>

<?php } ?>



