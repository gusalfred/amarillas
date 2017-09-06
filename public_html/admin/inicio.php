<?php
if (session_id() == "") session_start();
require_once 'vendor/autoload.php';
require_once 'ewcfg11.php';
require_once 'phpfn11.php';
include_once "userfn11.php";
$conn = dbal_conn();

include("header.php");

$empresas = $conn->fetchColumn("SELECT COUNT(id_empresa) AS total FROM empresas");
// $inmuebles = $conn->fetchColumn("SELECT COUNT(id_inmueble) AS total FROM inmuebles");
// $vehiculos = $conn->fetchColumn("SELECT COUNT(id_vehiculo) AS total FROM vehiculos");
// $licores = $conn->fetchColumn("SELECT COUNT(id_licor) AS total FROM licores");
?>
    <h3><i class="fa fa-fw fa-home"></i> Inicio</h3>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">

                <div class="panel panel-default">
                    <div class="panel-heading"><span class="glyphicon glyphicon-signal"></span> Estadisticas</div>
                    <div class="panel-body" style="padding: 0;">
                        <table class="table table-striped table-condensed" style="margin: 0;">
                            <tr>
                                <td>Empresas</td>
                                <td class="aling-right"><?= numero($empresas) ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="aling-right"></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
            <div class="col-md-5"></div>
        </div>
    </div>

<?php include("footer.php"); ?>