<?php

// Global variable for table object
$contratos = NULL;

//
// Table class for contratos
//
class ccontratos extends cTable {
	var $id_contrato;
	var $id_empresa;
	var $tipo;
	var $numero;
	var $fecha;
	var $id_plan;
	var $fecha_desde;
	var $fecha_hasta;
	var $id_empleado;
	var $id_recibo;
	var $monto;
	var $descuento;
	var $sub_total;
	var $impuesto;
	var $monto_total;
	var $observaciones;
	var $estatus;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'contratos';
		$this->TableName = 'contratos';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id_contrato
		$this->id_contrato = new cField('contratos', 'contratos', 'x_id_contrato', 'id_contrato', '`id_contrato`', '`id_contrato`', 19, -1, FALSE, '`id_contrato`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_contrato->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_contrato'] = &$this->id_contrato;

		// id_empresa
		$this->id_empresa = new cField('contratos', 'contratos', 'x_id_empresa', 'id_empresa', '`id_empresa`', '`id_empresa`', 19, -1, FALSE, '`id_empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_empresa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_empresa'] = &$this->id_empresa;

		// tipo
		$this->tipo = new cField('contratos', 'contratos', 'x_tipo', 'tipo', '`tipo`', '`tipo`', 3, -1, FALSE, '`tipo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tipo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tipo'] = &$this->tipo;

		// numero
		$this->numero = new cField('contratos', 'contratos', 'x_numero', 'numero', '`numero`', '`numero`', 200, -1, FALSE, '`numero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['numero'] = &$this->numero;

		// fecha
		$this->fecha = new cField('contratos', 'contratos', 'x_fecha', 'fecha', '`fecha`', 'DATE_FORMAT(`fecha`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha'] = &$this->fecha;

		// id_plan
		$this->id_plan = new cField('contratos', 'contratos', 'x_id_plan', 'id_plan', '`id_plan`', '`id_plan`', 19, -1, FALSE, '`id_plan`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_plan->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_plan'] = &$this->id_plan;

		// fecha_desde
		$this->fecha_desde = new cField('contratos', 'contratos', 'x_fecha_desde', 'fecha_desde', '`fecha_desde`', 'DATE_FORMAT(`fecha_desde`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_desde`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_desde->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_desde'] = &$this->fecha_desde;

		// fecha_hasta
		$this->fecha_hasta = new cField('contratos', 'contratos', 'x_fecha_hasta', 'fecha_hasta', '`fecha_hasta`', 'DATE_FORMAT(`fecha_hasta`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_hasta`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_hasta->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_hasta'] = &$this->fecha_hasta;

		// id_empleado
		$this->id_empleado = new cField('contratos', 'contratos', 'x_id_empleado', 'id_empleado', '`id_empleado`', '`id_empleado`', 19, -1, FALSE, '`id_empleado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_empleado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_empleado'] = &$this->id_empleado;

		// id_recibo
		$this->id_recibo = new cField('contratos', 'contratos', 'x_id_recibo', 'id_recibo', '`id_recibo`', '`id_recibo`', 19, -1, FALSE, '`id_recibo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_recibo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_recibo'] = &$this->id_recibo;

		// monto
		$this->monto = new cField('contratos', 'contratos', 'x_monto', 'monto', '`monto`', '`monto`', 131, -1, FALSE, '`monto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->monto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['monto'] = &$this->monto;

		// descuento
		$this->descuento = new cField('contratos', 'contratos', 'x_descuento', 'descuento', '`descuento`', '`descuento`', 131, -1, FALSE, '`descuento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->descuento->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['descuento'] = &$this->descuento;

		// sub_total
		$this->sub_total = new cField('contratos', 'contratos', 'x_sub_total', 'sub_total', '`sub_total`', '`sub_total`', 131, -1, FALSE, '`sub_total`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->sub_total->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['sub_total'] = &$this->sub_total;

		// impuesto
		$this->impuesto = new cField('contratos', 'contratos', 'x_impuesto', 'impuesto', '`impuesto`', '`impuesto`', 131, -1, FALSE, '`impuesto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->impuesto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['impuesto'] = &$this->impuesto;

		// monto_total
		$this->monto_total = new cField('contratos', 'contratos', 'x_monto_total', 'monto_total', '`monto_total`', '`monto_total`', 131, -1, FALSE, '`monto_total`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->monto_total->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['monto_total'] = &$this->monto_total;

		// observaciones
		$this->observaciones = new cField('contratos', 'contratos', 'x_observaciones', 'observaciones', '`observaciones`', '`observaciones`', 200, -1, FALSE, '`observaciones`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['observaciones'] = &$this->observaciones;

		// estatus
		$this->estatus = new cField('contratos', 'contratos', 'x_estatus', 'estatus', '`estatus`', '`estatus`', 3, -1, FALSE, '`estatus`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->estatus->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['estatus'] = &$this->estatus;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`contratos`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`contratos`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('id_contrato', $rs))
				ew_AddFilter($where, ew_QuotedName('id_contrato') . '=' . ew_QuotedValue($rs['id_contrato'], $this->id_contrato->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id_contrato` = @id_contrato@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_contrato->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_contrato@", ew_AdjustSql($this->id_contrato->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "contratos_list.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "contratos_list.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("contratos_view.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("contratos_view.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "contratos_add.php?" . $this->UrlParm($parm);
		else
			return "contratos_add.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("contratos_edit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("contratos_add.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("contratos_delete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_contrato->CurrentValue)) {
			$sUrl .= "id_contrato=" . urlencode($this->id_contrato->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["id_contrato"]; // id_contrato

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id_contrato->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id_contrato->setDbValue($rs->fields('id_contrato'));
		$this->id_empresa->setDbValue($rs->fields('id_empresa'));
		$this->tipo->setDbValue($rs->fields('tipo'));
		$this->numero->setDbValue($rs->fields('numero'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->id_plan->setDbValue($rs->fields('id_plan'));
		$this->fecha_desde->setDbValue($rs->fields('fecha_desde'));
		$this->fecha_hasta->setDbValue($rs->fields('fecha_hasta'));
		$this->id_empleado->setDbValue($rs->fields('id_empleado'));
		$this->id_recibo->setDbValue($rs->fields('id_recibo'));
		$this->monto->setDbValue($rs->fields('monto'));
		$this->descuento->setDbValue($rs->fields('descuento'));
		$this->sub_total->setDbValue($rs->fields('sub_total'));
		$this->impuesto->setDbValue($rs->fields('impuesto'));
		$this->monto_total->setDbValue($rs->fields('monto_total'));
		$this->observaciones->setDbValue($rs->fields('observaciones'));
		$this->estatus->setDbValue($rs->fields('estatus'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_contrato
		// id_empresa
		// tipo
		// numero
		// fecha
		// id_plan
		// fecha_desde
		// fecha_hasta
		// id_empleado
		// id_recibo
		// monto
		// descuento
		// sub_total
		// impuesto
		// monto_total
		// observaciones
		// estatus
		// id_contrato

		$this->id_contrato->ViewValue = $this->id_contrato->CurrentValue;
		$this->id_contrato->ViewCustomAttributes = "";

		// id_empresa
		$this->id_empresa->ViewValue = $this->id_empresa->CurrentValue;
		if (strval($this->id_empresa->CurrentValue) <> "") {
			$sFilterWrk = "`id_empresa`" . ew_SearchString("=", $this->id_empresa->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id_empresa`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_empresa, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_empresa->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->id_empresa->ViewValue = $this->id_empresa->CurrentValue;
			}
		} else {
			$this->id_empresa->ViewValue = NULL;
		}
		$this->id_empresa->ViewCustomAttributes = "";

		// tipo
		if (strval($this->tipo->CurrentValue) <> "") {
			switch ($this->tipo->CurrentValue) {
				case $this->tipo->FldTagValue(1):
					$this->tipo->ViewValue = $this->tipo->FldTagCaption(1) <> "" ? $this->tipo->FldTagCaption(1) : $this->tipo->CurrentValue;
					break;
				case $this->tipo->FldTagValue(2):
					$this->tipo->ViewValue = $this->tipo->FldTagCaption(2) <> "" ? $this->tipo->FldTagCaption(2) : $this->tipo->CurrentValue;
					break;
				default:
					$this->tipo->ViewValue = $this->tipo->CurrentValue;
			}
		} else {
			$this->tipo->ViewValue = NULL;
		}
		$this->tipo->ViewCustomAttributes = "";

		// numero
		$this->numero->ViewValue = $this->numero->CurrentValue;
		$this->numero->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
		$this->fecha->ViewCustomAttributes = "";

		// id_plan
		if (strval($this->id_plan->CurrentValue) <> "") {
			$sFilterWrk = "`id_plan`" . ew_SearchString("=", $this->id_plan->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id_plan`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `planes`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_plan, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_plan->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->id_plan->ViewValue = $this->id_plan->CurrentValue;
			}
		} else {
			$this->id_plan->ViewValue = NULL;
		}
		$this->id_plan->ViewCustomAttributes = "";

		// fecha_desde
		$this->fecha_desde->ViewValue = $this->fecha_desde->CurrentValue;
		$this->fecha_desde->ViewValue = ew_FormatDateTime($this->fecha_desde->ViewValue, 7);
		$this->fecha_desde->ViewCustomAttributes = "";

		// fecha_hasta
		$this->fecha_hasta->ViewValue = $this->fecha_hasta->CurrentValue;
		$this->fecha_hasta->ViewValue = ew_FormatDateTime($this->fecha_hasta->ViewValue, 7);
		$this->fecha_hasta->ViewCustomAttributes = "";

		// id_empleado
		if (strval($this->id_empleado->CurrentValue) <> "") {
			$sFilterWrk = "`id_empleado`" . ew_SearchString("=", $this->id_empleado->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id_empleado`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empleados`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_empleado, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_empleado->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->id_empleado->ViewValue = $this->id_empleado->CurrentValue;
			}
		} else {
			$this->id_empleado->ViewValue = NULL;
		}
		$this->id_empleado->ViewCustomAttributes = "";

		// id_recibo
		$this->id_recibo->ViewValue = $this->id_recibo->CurrentValue;
		$this->id_recibo->ViewCustomAttributes = "";

		// monto
		$this->monto->ViewValue = $this->monto->CurrentValue;
		$this->monto->ViewCustomAttributes = "";

		// descuento
		$this->descuento->ViewValue = $this->descuento->CurrentValue;
		$this->descuento->ViewCustomAttributes = "";

		// sub_total
		$this->sub_total->ViewValue = $this->sub_total->CurrentValue;
		$this->sub_total->ViewCustomAttributes = "";

		// impuesto
		$this->impuesto->ViewValue = $this->impuesto->CurrentValue;
		$this->impuesto->ViewCustomAttributes = "";

		// monto_total
		$this->monto_total->ViewValue = $this->monto_total->CurrentValue;
		$this->monto_total->ViewCustomAttributes = "";

		// observaciones
		$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
		$this->observaciones->ViewCustomAttributes = "";

		// estatus
		$this->estatus->ViewValue = $this->estatus->CurrentValue;
		$this->estatus->ViewCustomAttributes = "";

		// id_contrato
		$this->id_contrato->LinkCustomAttributes = "";
		$this->id_contrato->HrefValue = "";
		$this->id_contrato->TooltipValue = "";

		// id_empresa
		$this->id_empresa->LinkCustomAttributes = "";
		$this->id_empresa->HrefValue = "";
		$this->id_empresa->TooltipValue = "";

		// tipo
		$this->tipo->LinkCustomAttributes = "";
		$this->tipo->HrefValue = "";
		$this->tipo->TooltipValue = "";

		// numero
		$this->numero->LinkCustomAttributes = "";
		$this->numero->HrefValue = "";
		$this->numero->TooltipValue = "";

		// fecha
		$this->fecha->LinkCustomAttributes = "";
		$this->fecha->HrefValue = "";
		$this->fecha->TooltipValue = "";

		// id_plan
		$this->id_plan->LinkCustomAttributes = "";
		$this->id_plan->HrefValue = "";
		$this->id_plan->TooltipValue = "";

		// fecha_desde
		$this->fecha_desde->LinkCustomAttributes = "";
		$this->fecha_desde->HrefValue = "";
		$this->fecha_desde->TooltipValue = "";

		// fecha_hasta
		$this->fecha_hasta->LinkCustomAttributes = "";
		$this->fecha_hasta->HrefValue = "";
		$this->fecha_hasta->TooltipValue = "";

		// id_empleado
		$this->id_empleado->LinkCustomAttributes = "";
		$this->id_empleado->HrefValue = "";
		$this->id_empleado->TooltipValue = "";

		// id_recibo
		$this->id_recibo->LinkCustomAttributes = "";
		$this->id_recibo->HrefValue = "";
		$this->id_recibo->TooltipValue = "";

		// monto
		$this->monto->LinkCustomAttributes = "";
		$this->monto->HrefValue = "";
		$this->monto->TooltipValue = "";

		// descuento
		$this->descuento->LinkCustomAttributes = "";
		$this->descuento->HrefValue = "";
		$this->descuento->TooltipValue = "";

		// sub_total
		$this->sub_total->LinkCustomAttributes = "";
		$this->sub_total->HrefValue = "";
		$this->sub_total->TooltipValue = "";

		// impuesto
		$this->impuesto->LinkCustomAttributes = "";
		$this->impuesto->HrefValue = "";
		$this->impuesto->TooltipValue = "";

		// monto_total
		$this->monto_total->LinkCustomAttributes = "";
		$this->monto_total->HrefValue = "";
		$this->monto_total->TooltipValue = "";

		// observaciones
		$this->observaciones->LinkCustomAttributes = "";
		$this->observaciones->HrefValue = "";
		$this->observaciones->TooltipValue = "";

		// estatus
		$this->estatus->LinkCustomAttributes = "";
		$this->estatus->HrefValue = "";
		$this->estatus->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id_contrato
		$this->id_contrato->EditAttrs["class"] = "form-control";
		$this->id_contrato->EditCustomAttributes = "";
		$this->id_contrato->EditValue = $this->id_contrato->CurrentValue;
		$this->id_contrato->ViewCustomAttributes = "";

		// id_empresa
		$this->id_empresa->EditAttrs["class"] = "form-control";
		$this->id_empresa->EditCustomAttributes = 'disabled';
		$this->id_empresa->EditValue = ew_HtmlEncode($this->id_empresa->CurrentValue);

		// tipo
		$this->tipo->EditAttrs["class"] = "form-control";
		$this->tipo->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->tipo->FldTagValue(1), $this->tipo->FldTagCaption(1) <> "" ? $this->tipo->FldTagCaption(1) : $this->tipo->FldTagValue(1));
		$arwrk[] = array($this->tipo->FldTagValue(2), $this->tipo->FldTagCaption(2) <> "" ? $this->tipo->FldTagCaption(2) : $this->tipo->FldTagValue(2));
		array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
		$this->tipo->EditValue = $arwrk;

		// numero
		$this->numero->EditAttrs["class"] = "form-control";
		$this->numero->EditCustomAttributes = "";
		$this->numero->EditValue = ew_HtmlEncode($this->numero->CurrentValue);

		// fecha
		$this->fecha->EditAttrs["class"] = "form-control";
		$this->fecha->EditCustomAttributes = "";
		$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha->CurrentValue, 7));

		// id_plan
		$this->id_plan->EditAttrs["class"] = "form-control";
		$this->id_plan->EditCustomAttributes = "";

		// fecha_desde
		$this->fecha_desde->EditAttrs["class"] = "form-control";
		$this->fecha_desde->EditCustomAttributes = "";
		$this->fecha_desde->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_desde->CurrentValue, 7));

		// fecha_hasta
		$this->fecha_hasta->EditAttrs["class"] = "form-control";
		$this->fecha_hasta->EditCustomAttributes = "";
		$this->fecha_hasta->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_hasta->CurrentValue, 7));

		// id_empleado
		$this->id_empleado->EditAttrs["class"] = "form-control";
		$this->id_empleado->EditCustomAttributes = "";

		// id_recibo
		$this->id_recibo->EditAttrs["class"] = "form-control";
		$this->id_recibo->EditCustomAttributes = "";
		$this->id_recibo->EditValue = ew_HtmlEncode($this->id_recibo->CurrentValue);

		// monto
		$this->monto->EditAttrs["class"] = "form-control";
		$this->monto->EditCustomAttributes = ' v-model="monto" ';
		$this->monto->EditValue = ew_HtmlEncode($this->monto->CurrentValue);
		if (strval($this->monto->EditValue) <> "" && is_numeric($this->monto->EditValue)) $this->monto->EditValue = ew_FormatNumber($this->monto->EditValue, -2, -1, -2, 0);

		// descuento
		$this->descuento->EditAttrs["class"] = "form-control";
		$this->descuento->EditCustomAttributes = ' v-model="descuento" ';
		$this->descuento->EditValue = ew_HtmlEncode($this->descuento->CurrentValue);
		if (strval($this->descuento->EditValue) <> "" && is_numeric($this->descuento->EditValue)) $this->descuento->EditValue = ew_FormatNumber($this->descuento->EditValue, -2, -1, -2, 0);

		// sub_total
		$this->sub_total->EditAttrs["class"] = "form-control";
		$this->sub_total->EditCustomAttributes = ' v-model="subtotal" readonly ';
		$this->sub_total->EditValue = ew_HtmlEncode($this->sub_total->CurrentValue);
		if (strval($this->sub_total->EditValue) <> "" && is_numeric($this->sub_total->EditValue)) $this->sub_total->EditValue = ew_FormatNumber($this->sub_total->EditValue, -2, -1, -2, 0);

		// impuesto
		$this->impuesto->EditAttrs["class"] = "form-control";
		$this->impuesto->EditCustomAttributes = ' v-model="impuesto" readonly ';
		$this->impuesto->EditValue = ew_HtmlEncode($this->impuesto->CurrentValue);
		if (strval($this->impuesto->EditValue) <> "" && is_numeric($this->impuesto->EditValue)) $this->impuesto->EditValue = ew_FormatNumber($this->impuesto->EditValue, -2, -1, -2, 0);

		// monto_total
		$this->monto_total->EditAttrs["class"] = "form-control";
		$this->monto_total->EditCustomAttributes = ' v-model="total" readonly ';
		$this->monto_total->EditValue = ew_HtmlEncode($this->monto_total->CurrentValue);
		if (strval($this->monto_total->EditValue) <> "" && is_numeric($this->monto_total->EditValue)) $this->monto_total->EditValue = ew_FormatNumber($this->monto_total->EditValue, -2, -1, -2, 0);

		// observaciones
		$this->observaciones->EditAttrs["class"] = "form-control";
		$this->observaciones->EditCustomAttributes = "";
		$this->observaciones->EditValue = ew_HtmlEncode($this->observaciones->CurrentValue);

		// estatus
		$this->estatus->EditAttrs["class"] = "form-control";
		$this->estatus->EditCustomAttributes = "";
		$this->estatus->EditValue = ew_HtmlEncode($this->estatus->CurrentValue);

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->id_empresa->Exportable) $Doc->ExportCaption($this->id_empresa);
					if ($this->tipo->Exportable) $Doc->ExportCaption($this->tipo);
					if ($this->numero->Exportable) $Doc->ExportCaption($this->numero);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
					if ($this->id_plan->Exportable) $Doc->ExportCaption($this->id_plan);
					if ($this->fecha_desde->Exportable) $Doc->ExportCaption($this->fecha_desde);
					if ($this->fecha_hasta->Exportable) $Doc->ExportCaption($this->fecha_hasta);
					if ($this->id_empleado->Exportable) $Doc->ExportCaption($this->id_empleado);
					if ($this->monto->Exportable) $Doc->ExportCaption($this->monto);
					if ($this->descuento->Exportable) $Doc->ExportCaption($this->descuento);
					if ($this->sub_total->Exportable) $Doc->ExportCaption($this->sub_total);
					if ($this->impuesto->Exportable) $Doc->ExportCaption($this->impuesto);
					if ($this->monto_total->Exportable) $Doc->ExportCaption($this->monto_total);
					if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
					if ($this->estatus->Exportable) $Doc->ExportCaption($this->estatus);
				} else {
					if ($this->id_contrato->Exportable) $Doc->ExportCaption($this->id_contrato);
					if ($this->id_empresa->Exportable) $Doc->ExportCaption($this->id_empresa);
					if ($this->tipo->Exportable) $Doc->ExportCaption($this->tipo);
					if ($this->numero->Exportable) $Doc->ExportCaption($this->numero);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
					if ($this->id_plan->Exportable) $Doc->ExportCaption($this->id_plan);
					if ($this->fecha_desde->Exportable) $Doc->ExportCaption($this->fecha_desde);
					if ($this->fecha_hasta->Exportable) $Doc->ExportCaption($this->fecha_hasta);
					if ($this->id_empleado->Exportable) $Doc->ExportCaption($this->id_empleado);
					if ($this->id_recibo->Exportable) $Doc->ExportCaption($this->id_recibo);
					if ($this->monto->Exportable) $Doc->ExportCaption($this->monto);
					if ($this->descuento->Exportable) $Doc->ExportCaption($this->descuento);
					if ($this->sub_total->Exportable) $Doc->ExportCaption($this->sub_total);
					if ($this->impuesto->Exportable) $Doc->ExportCaption($this->impuesto);
					if ($this->monto_total->Exportable) $Doc->ExportCaption($this->monto_total);
					if ($this->observaciones->Exportable) $Doc->ExportCaption($this->observaciones);
					if ($this->estatus->Exportable) $Doc->ExportCaption($this->estatus);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->id_empresa->Exportable) $Doc->ExportField($this->id_empresa);
						if ($this->tipo->Exportable) $Doc->ExportField($this->tipo);
						if ($this->numero->Exportable) $Doc->ExportField($this->numero);
						if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
						if ($this->id_plan->Exportable) $Doc->ExportField($this->id_plan);
						if ($this->fecha_desde->Exportable) $Doc->ExportField($this->fecha_desde);
						if ($this->fecha_hasta->Exportable) $Doc->ExportField($this->fecha_hasta);
						if ($this->id_empleado->Exportable) $Doc->ExportField($this->id_empleado);
						if ($this->monto->Exportable) $Doc->ExportField($this->monto);
						if ($this->descuento->Exportable) $Doc->ExportField($this->descuento);
						if ($this->sub_total->Exportable) $Doc->ExportField($this->sub_total);
						if ($this->impuesto->Exportable) $Doc->ExportField($this->impuesto);
						if ($this->monto_total->Exportable) $Doc->ExportField($this->monto_total);
						if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
						if ($this->estatus->Exportable) $Doc->ExportField($this->estatus);
					} else {
						if ($this->id_contrato->Exportable) $Doc->ExportField($this->id_contrato);
						if ($this->id_empresa->Exportable) $Doc->ExportField($this->id_empresa);
						if ($this->tipo->Exportable) $Doc->ExportField($this->tipo);
						if ($this->numero->Exportable) $Doc->ExportField($this->numero);
						if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
						if ($this->id_plan->Exportable) $Doc->ExportField($this->id_plan);
						if ($this->fecha_desde->Exportable) $Doc->ExportField($this->fecha_desde);
						if ($this->fecha_hasta->Exportable) $Doc->ExportField($this->fecha_hasta);
						if ($this->id_empleado->Exportable) $Doc->ExportField($this->id_empleado);
						if ($this->id_recibo->Exportable) $Doc->ExportField($this->id_recibo);
						if ($this->monto->Exportable) $Doc->ExportField($this->monto);
						if ($this->descuento->Exportable) $Doc->ExportField($this->descuento);
						if ($this->sub_total->Exportable) $Doc->ExportField($this->sub_total);
						if ($this->impuesto->Exportable) $Doc->ExportField($this->impuesto);
						if ($this->monto_total->Exportable) $Doc->ExportField($this->monto_total);
						if ($this->observaciones->Exportable) $Doc->ExportField($this->observaciones);
						if ($this->estatus->Exportable) $Doc->ExportField($this->estatus);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
		header('Location: empresas_view.php?tab=6&id_empresa='.$rsnew['id_empresa']);
		exit;
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
		header("Location: empresas_view.php?tab=6&id_empresa=".$rsold['id_empresa'] );
		die();
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
		header("Location: empresas_view.php?tab=6&id_empresa=".$rs['id_empresa'] );
		die();
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
