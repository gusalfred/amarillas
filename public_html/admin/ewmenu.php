<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(84, "mi_inicio_php", $Language->MenuPhrase("84", "MenuText"), "inicio.php", -1, "", AllowListMenu('{D02329FA-C783-46FA-A3B4-996FD9449799}inicio.php'), FALSE);
$RootMenu->AddMenuItem(4, "mi_empresas", $Language->MenuPhrase("4", "MenuText"), "empresas_list.php", -1, "", AllowListMenu('{D02329FA-C783-46FA-A3B4-996FD9449799}empresas'), FALSE);
$RootMenu->AddMenuItem(24, "mi_categorias_nivel1", $Language->MenuPhrase("24", "MenuText"), "categorias_nivel1_list.php", -1, "", AllowListMenu('{D02329FA-C783-46FA-A3B4-996FD9449799}categorias_nivel1'), FALSE);
$RootMenu->AddMenuItem(51, "mi_contratos", $Language->MenuPhrase("51", "MenuText"), "contratos_list.php", -1, "", AllowListMenu('{D02329FA-C783-46FA-A3B4-996FD9449799}contratos'), FALSE);
$RootMenu->AddMenuItem(55, "mi_recibos", $Language->MenuPhrase("55", "MenuText"), "recibos_list.php", -1, "", AllowListMenu('{D02329FA-C783-46FA-A3B4-996FD9449799}recibos'), FALSE);
$RootMenu->AddMenuItem(85, "mi_avisos", $Language->MenuPhrase("85", "MenuText"), "avisos_list.php", -1, "", AllowListMenu('{D02329FA-C783-46FA-A3B4-996FD9449799}avisos'), FALSE);
$RootMenu->AddMenuItem(17, "mi_users", $Language->MenuPhrase("17", "MenuText"), "users_list.php", -1, "", AllowListMenu('{D02329FA-C783-46FA-A3B4-996FD9449799}users'), FALSE);
$RootMenu->AddMenuItem(82, "mci_3ci_class3d22fa_fa2dpie2dchart_fa2dfw223e3c2fi3e_Reportes", $Language->MenuPhrase("82", "MenuText"), "reportes.php", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(47, "mci_3ci_class3d22fa_fa2dwrench_fa2dfw223e3c2fi3e_Configuracif3n", $Language->MenuPhrase("47", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(83, "mi_configuraciones", $Language->MenuPhrase("83", "MenuText"), "configuraciones_list.php", 47, "", AllowListMenu('{D02329FA-C783-46FA-A3B4-996FD9449799}configuraciones'), FALSE);
$RootMenu->AddMenuItem(20, "mi_planes", $Language->MenuPhrase("20", "MenuText"), "planes_list.php", 47, "", AllowListMenu('{D02329FA-C783-46FA-A3B4-996FD9449799}planes'), FALSE);
$RootMenu->AddMenuItem(49, "mi_avisos_ubicaciones", $Language->MenuPhrase("49", "MenuText"), "avisos_ubicaciones_list.php?cmd=resetall", 47, "", AllowListMenu('{D02329FA-C783-46FA-A3B4-996FD9449799}avisos_ubicaciones'), FALSE);
$RootMenu->AddMenuItem(23, "mi_redes_sociales", $Language->MenuPhrase("23", "MenuText"), "redes_sociales_list.php", 47, "", AllowListMenu('{D02329FA-C783-46FA-A3B4-996FD9449799}redes_sociales'), FALSE);
$RootMenu->AddMenuItem(2, "mi_departamentos", $Language->MenuPhrase("2", "MenuText"), "departamentos_list.php", 47, "", AllowListMenu('{D02329FA-C783-46FA-A3B4-996FD9449799}departamentos'), FALSE);
$RootMenu->AddMenuItem(86, "mi_ubicaciones", $Language->MenuPhrase("86", "MenuText"), "ubicaciones_list.php", 47, "", AllowListMenu('{D02329FA-C783-46FA-A3B4-996FD9449799}ubicaciones'), FALSE);
$RootMenu->AddMenuItem(9, "mi_empleados", $Language->MenuPhrase("9", "MenuText"), "empleados_list.php", -1, "", AllowListMenu('{D02329FA-C783-46FA-A3B4-996FD9449799}empleados'), FALSE);
$RootMenu->AddMenuItem(10, "mi_empleados_niveles", $Language->MenuPhrase("10", "MenuText"), "empleados_niveles_list.php", 9, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(-2, "mi_changepwd", "<span class='glyphicon glyphicon-refresh'></span> ".$Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mi_logout", "<span class='glyphicon glyphicon-off'></span> ".$Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->Render();
?>
<!-- End Main Menu -->
