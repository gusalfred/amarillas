<?php
if (session_id() == "") session_start();
require_once 'vendor/autoload.php';
require_once 'ewcfg11.php';
require_once 'phpfn11.php';
include_once "userfn11.php";
$conn = dbal_conn();

$idm = $_GET['idm'];
@$action = $_GET['action'];

switch ($action) {
    case 'added':
        $conn->insert('empresas_redes', $_POST); print_r($_POST); exit;
        break;
    case 'edited':
        $conn->update('empresas_redes', $_POST, array('id_empresa_red_social' => @$_GET['idcc'] ));
        break;
    case 'deleted':
        $conn->delete('empresas_redes', array('id_empresa_red_social' => @$_POST['idcc'] ));
        break;
}

?>

<table class="table table-bordered table-hover table-condensed">

    <tr id="com_add" style="display: none;">
        <td>
            <form id="form-add" class="form-inline">
                <div class="form-group">
                    <?php $rs = $conn->fetchAll("SELECT * FROM redes_sociales");
                    foreach($rs as $row) { ?>
                        <div id="icon<?= $row['id_red_social'] ?>" style="float: left; padding-right: 5px;" class="iconos_redes" >
                            <i class="<?= $row['icon_class'] ?> fa-2x" style="color: <?= $row['color'] ?>;"> </i>
                        </div>
                    <?php } ?>
                    <input type="text" name="url" class="form-control input-sm" size="50">
                    <input type="hidden" name="id_red_social" id="id_red_social">
                </div>
            </form>
        </td>
        <td style="width: 25px"><a href="#" onclick="com_added()"><span class="glyphicon glyphicon-ok"></span></a></td>
        <td style="width: 25px"><a href="#" onclick="$('#com_add').hide();"><span class="glyphicon glyphicon-remove"></span></a></td>
    </tr>

    <?php $rs = $conn->fetchAll("SELECT empresas_redes.*, id_empresa_red_social AS idcc, redes_sociales.*
    FROM empresas_redes 
    INNER JOIN redes_sociales ON (empresas_redes.id_red_social = redes_sociales.id_red_social)
    WHERE id_empresa = $idm");
    foreach($rs as $row) { ?>

        <?php if ($action == 'edit' and $row['idcc'] == @$_GET['idcc'] ){ ?>
            <tr>
                <td>
                    <form id="form-edit" class="form-inline">
                        <div class="form-group">
                            <input type="text" name="url" value="<?= $row['url'] ?>" class="form-control input-sm" size="50">
                        </div>
                    </form>
                </td>
                <td></td>
                <!--<td style="width: 25px"><a href="#" onclick="com_edited(<?//= $row['idcc'] ?>//)"><span class="glyphicon glyphicon-ok"></span></a></td>-->
                <td style="width: 25px"><a href="#" onclick="com_list()"><span class="glyphicon glyphicon-remove"></span></a></td>
            </tr>
        <?php } else { ?>
            <tr>
                <td><i class="<?= $row['icon_class'] ?> fa-2x" style="color: <?= $row['color'] ?>;"></i>
                    <a href="<?= $row['url'] ?>" target="_blank"><?= $row['url'] ?></a></td>
                <td style="width: 25px"></td>
                <td style="width: 25px"><a href="#" onclick="com_deleted(<?= $row['idcc'] ?>)"><span class="glyphicon glyphicon-trash"></span></a></td>
            </tr>
        <?php } ?>

    <?php } ?>
</table>

<script>
    function com_list(){
        $('#redes').html('<i class="fa fa-cog fa-spin fa-3x fa-fw"></i>');
        $('#redes').load( "empresas_redes.php?idm=<?= $idm ?>" );
    }

    function com_add(tipo){
        $('.iconos_redes').hide();
        $('#com_add').show();
        $('#icon'+tipo).show();
        $('#id_red_social').val(tipo);
    }

    function com_added(){
        jQuery.ajax({
            type: "POST",
            url: 'empresas_redes.php?idm=<?= $idm ?>&action=added',
            data: 'id_empresa=<?= $idm ?>&' + $('#form-add').serialize(),
            success: function(data) {
                com_list();
            }
        });
    }

    function com_edit(idcc){
        $('#com').load('empresas_redes.php?action=edit&idm=<?= $idm ?>&idcc='+idcc );
    }

    function com_edited(idcc){
        jQuery.ajax({
            type: "POST",
            url: 'empresas_redes.php?idm=<?= $idm ?>&action=edited&idcc='+idcc,
            data: $('#form-edit').serialize(),
            success: function(data) {
                com_list();
            }
        });
    }

    function com_deleted(idcc){
        if (confirm("Seguro de borrar?")) {
            jQuery.ajax({
                type: "POST",
                url: 'empresas_redes.php?action=deleted',
                data: 'idcc='+idcc,
                success: function(data) {
                    com_list();
                }
            });
        }
    }
</script>