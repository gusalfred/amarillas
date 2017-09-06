<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "userfn11.php" ?>

<?php include("header.php"); ?>

<h3><i class="fa fa-fw fa-pie-chart"></i> Reportes</h3>


<?php include("footer.php"); ?>