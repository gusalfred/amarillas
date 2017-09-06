<?php
if (session_id() == "") session_start();
require_once 'vendor/autoload.php';
require_once 'ewcfg11.php';
require_once 'phpfn11.php';
include_once "userfn11.php";
$conn = dbal_conn();

$idm = $_GET['idm'];
$tab = (empty($_GET['tab'])) ? 1 : $_GET['tab'];

$tabs = array_fill(1, 6, '');
$tabs[$tab] = 'active';

$empresa = $conn->fetchAssoc('SELECT * FROM empresas WHERE id_empresa = ?', array($idm));

?>
<div id="tabs">
    <ul class="nav nav-tabs" role="tablist" style="margin-bottom: 0;">
        <li role="presentation" class="<?= $tabs[1] ?>"><a href="#t1" aria-controls="t1" role="tab" data-toggle="tab" title="Categorias"><i class="fa fa-2x fa-tags"></i></a></li>
        <li role="presentation" class="<?= $tabs[2] ?>"><a href="#t2" aria-controls="t2" role="tab" data-toggle="tab" title="Direcciones"><i class="fa fa-2x fa-map-marker"></i> </a></li>
        <li role="presentation" class="<?= $tabs[3] ?>"><a href="#t3" aria-controls="t3" role="tab" data-toggle="tab" title="Redes sociales"><i class="fa fa-2x fa-th-large"></i> </a></li>
        <li role="presentation" class="<?= $tabs[4] ?>"><a href="#t4" aria-controls="t4" role="tab" data-toggle="tab" title="Imagenes"><i class="fa fa-2x fa-photo"></i> </a></li>
        <li role="presentation" class="<?= $tabs[5] ?>"><a href="#t5" aria-controls="t5" role="tab" data-toggle="tab" title="Avisos"><i class="fa fa-2x fa-television"></i> </a></li>
        <li role="presentation" class="<?= $tabs[6] ?>"><a href="#t6" aria-controls="t6" role="tab" data-toggle="tab" title="Contratos"><i class="fa fa-2x fa-file-text-o"></i> </a></li>
        <li role="presentation" class="<?= $tabs[7] ?>"><a href="#t7" aria-controls="t7" role="tab" data-toggle="tab" title="Comentarios"><i class="fa fa-2x fa-comments"></i> </a></li>
    </ul>
    <div class="tab-content" style="padding: 15px; border: solid 1px #c9c9c9; border-top: none;">

        <!-- Categorias -->
        <div role="tabpanel" class="tab-pane <?= $tabs[1] ?>" id="t1">
            <a class="btn btn-primary fancybox fancybox.iframe" href="categorias_selecciona.php" role="button">Agregar</a>
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <td>Categoria Principal</td>
                    <td>Sub Categoria</td>
                    <td></td>
                </tr>
                </thead>
                <?php $rs = $conn->fetchAll("SELECT empresas_categorias.id_empresa_categoria, categorias_nivel2.categoria AS cat2, categorias_nivel1.categoria AS cat1
                FROM empresas_categorias
                INNER JOIN categorias_nivel2 ON (empresas_categorias.id_categoria_nivel2 = categorias_nivel2.id_categoria_nivel2)
                INNER JOIN categorias_nivel1 ON (categorias_nivel2.id_categoria_nivel1 = categorias_nivel1.id_categoria_nivel1)
                WHERE empresas_categorias.id_empresa = $idm");
                foreach($rs as $row) { ?>
                    <tr>
                        <td><?= $row['cat1'] ?></td>
                        <td><?= $row['cat2'] ?></td>
                        <td><a href="#" onclick="categoria_delete(<?= $row['id_empresa_categoria'] ?>)"><i class="glyphicon glyphicon-trash"></i></a></td>
                    </tr>
                <?php } ?>
            </table>
            <script>
                function categoria_add(id){
                    $.post( "ajax.php", { action: 'categoria_add', id_categoria_nivel2: id, id_empresa: <?= $idm ?> } )
                        .done(function( data ) { reload_detalles(1); });
                }
                function categoria_delete(id){
                    if (confirm("Seguro de borrar?")) {
                        $.post( "ajax.php", { action: 'categoria_delete', id: id} )
                            .done(function( data ) { reload_detalles(1); });
                    }
                    return false;
                }
            </script>
        </div>

        <!-- Direcciones -->
        <div role="tabpanel" class="tab-pane <?= $tabs[2] ?>" id="t2">
            <a class="btn btn-primary" href="empresas_direcciones_add.php?idm=<?= $idm ?>" role="button">Agregar</a>
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <td>Direccion</td>
                    <td style="width: 25px;"></td>
                    <td style="width: 25px;"></td>
                </tr>
                </thead>
                <?php $rs = $conn->fetchAll("SELECT * FROM empresas_direcciones WHERE id_empresa = $idm");
                foreach($rs as $row) { ?>
                    <tr>
                        <td><?= $row['direccion'] ?></td>
                        <td><a href="empresas_direcciones_edit.php?id_empresa_direccion=<?= $row['id_empresa_direccion'] ?>"><i class="glyphicon glyphicon-pencil"></i></a></td>
                        <td><a href="empresas_direcciones_delete.php?id_empresa_direccion=<?= $row['id_empresa_direccion'] ?>"><i class="glyphicon glyphicon-trash"></i></a></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <!-- Redes -->
        <div role="tabpanel" class="tab-pane <?= $tabs[3] ?>" id="t3">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Agregar <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <?php $rs = $conn->fetchAll("SELECT * FROM redes_sociales");
                foreach($rs as $row) { ?>
                    <li><a href="#" onclick="com_add(<?= $row['id_red_social'] ?>)"><i class="<?= $row['icon_class'] ?> fa-fw" style="color: <?= $row['color'] ?>;" ></i> <?= $row['red_social'] ?></a></li>
                <?php } ?>
                </ul>
            </div>
            <div id="redes"></div>
            <script>
                $(document).ready(function() {
                    $('#redes').load('empresas_redes.php?idm=<?= $idm ?>');
                });
            </script>
        </div>

        <!-- Imagenes -->
        <div role="tabpanel" class="tab-pane <?= $tabs[4] ?>" id="t4">
            <a class="btn btn-primary" href="empresas_media_add.php?idm=<?= $idm ?>" role="button">Agregar</a>
            <table class="table table-striped">
                <thead>
                <tr>
                    <td>Imagenes</td>
                    <td style="width: 25px;"></td>
                    <td style="width: 25px;"></td>
                </tr>
                </thead>
                <?php
                $rs = $conn->fetchAll("SELECT * FROM empresas_media WHERE id_empresa = $idm");
                foreach($rs as $row) { ?>
                    <tr>
                        <td><img class="img-responsive" src="../uploads/media/<?= $row['archivo'] ?>"></td>
                        <td><a href="empresas_media_edit.php?id_empresa_media=<?= $row['id_empresa_media'] ?>"><i class="glyphicon glyphicon-pencil"></i></a></td>
                        <td><a href="empresas_media_delete.php?id_empresa_media=<?= $row['id_empresa_media'] ?>"><i class="glyphicon glyphicon-trash"></i></a></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <!-- Avisos -->
        <div role="tabpanel" class="tab-pane <?= $tabs[5] ?>" id="t5">
            <a class="btn btn-primary" href="avisos_add.php?idm=<?= $idm ?>" role="button">Agregar</a>
            <table class="table table-striped">
                <thead>
                <tr>
                    <td>Avisos</td>
                    <td style="width: 25px;"></td>
                    <td style="width: 25px;"></td>
                </tr>
                </thead>
                <?php
                $rs = $conn->fetchAll("SELECT * FROM avisos WHERE id_empresa = $idm");
                foreach($rs as $row) { ?>
                    <tr>
                        <td><img src="../uploads/avisos/<?= $row['url_aviso'] ?>"></td>
                        <td><a href="avisos_edit.php?id_aviso=<?= $row['id_aviso'] ?>"><i class="glyphicon glyphicon-pencil"></i></a></td>
                        <td><a href="avisos_delete.php?id_aviso=<?= $row['id_aviso'] ?>"><i class="glyphicon glyphicon-trash"></i></a></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <!-- Contratos -->
        <div role="tabpanel" class="tab-pane <?= $tabs[6] ?>" id="t6">
            <a class="btn btn-primary" href="contratos_add.php?idm=<?= $idm ?>" role="button">Agregar</a>
            <table class="table table-striped">
                <thead>
                <tr>
                    <td># Contrato</td>
                    <td>Emision</td>
                    <td>Desde</td>
                    <td>Hasta</td>
                    <td style="width: 25px;"></td>
                    <td style="width: 25px;"></td>
                </tr>
                </thead>
                <?php
                $rs = $conn->fetchAll("SELECT * FROM contratos WHERE id_empresa = $idm");
                foreach($rs as $row) { ?>
                    <tr>
                        <td><?= $row['numero'] ?></td>
                        <td><?= fecha($row['fecha']) ?></td>
                        <td><?= fecha($row['fecha_desde']) ?></td>
                        <td><?= fecha($row['fecha_hasta']) ?></td>
                        <td><a href="contratos_edit.php?id_contrato=<?= $row['id_contrato'] ?>"><i class="glyphicon glyphicon-pencil"></i></a></td>
                        <td><a href="contratos_delete.php?id_contrato=<?= $row['id_contrato'] ?>"><i class="glyphicon glyphicon-trash"></i></a></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <!-- Comentarios -->
        <div role="tabpanel" class="tab-pane <?= $tabs[7] ?>" id="t7">
            <a class="btn btn-primary" href="empresas_valoraciones_add.php?idm=<?= $idm ?>" role="button">Agregar</a>
            <table class="table table-striped">
                <thead>
                <tr>
                    <td>Comentario</td>
                    <td>Valoración</td>
                    <td style="width: 25px;"></td>
                    <td style="width: 25px;"></td>
                </tr>
                </thead>
                <?php
                $rs = $conn->fetchAll("SELECT * FROM empresas_valoraciones WHERE id_empresa = $idm");
                foreach($rs as $row) { ?>
                    <tr>
                        <td><?= $row['comentario'] ?></td>
                        <td><div class="rateit" data-rateit-value="<?= $row['valor'] ?>" data-rateit-ispreset="true" data-rateit-readonly="true"></div></td>
                        <td><a href="empresas_valoraciones_edit.php?id_empresa_valoracion=<?= $row['id_empresa_valoracion'] ?>"><i class="glyphicon glyphicon-pencil"></i></a></td>
                        <td><a href="empresas_valoraciones_delete.php?id_empresa_valoracion=<?= $row['id_empresa_valoracion'] ?>"><i class="glyphicon glyphicon-trash"></i></a></td>
                    </tr>
                <?php } ?>
            </table>
            <script type="text/javascript">
                $( document ).ready(function() {
                    $('.rateit').rateit();
                });
            </script>
        </div>

    </div>
</div>
