<?php

// Global variable for table object
$recibos = NULL;

//
// Table class for recibos
//
class crecibos extends cTable {
	var $id_recibo;
	var $id_empresa;
	var $fecha_emision;
	var $fecha_vencimiento;
	var $numero;
	var $monto_base;
	var $monto_impuesto;
	var $monto_total;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'recibos';
		$this->TableName = 'recibos';
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

		// id_recibo
		$this->id_recibo = new cField('recibos', 'recibos', 'x_id_recibo', 'id_recibo', '`id_recibo`', '`id_recibo`', 19, -1, FALSE, '`id_recibo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_recibo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_recibo'] = &$this->id_recibo;

		// id_empresa
		$this->id_empresa = new cField('recibos', 'recibos', 'x_id_empresa', 'id_empresa', '`id_empresa`', '`id_empresa`', 19, -1, FALSE, '`id_empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_empresa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_empresa'] = &$this->id_empresa;

		// fecha_emision
		$this->fecha_emision = new cField('recibos', 'recibos', 'x_fecha_emision', 'fecha_emision', '`fecha_emision`', 'DATE_FORMAT(`fecha_emision`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_emision`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_emision->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_emision'] = &$this->fecha_emision;

		// fecha_vencimiento
		$this->fecha_vencimiento = new cField('recibos', 'recibos', 'x_fecha_vencimiento', 'fecha_vencimiento', '`fecha_vencimiento`', 'DATE_FORMAT(`fecha_vencimiento`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha_vencimiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fecha_vencimiento->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha_vencimiento'] = &$this->fecha_vencimiento;

		// numero
		$this->numero = new cField('recibos', 'recibos', 'x_numero', 'numero', '`numero`', '`numero`', 200, -1, FALSE, '`numero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['numero'] = &$this->numero;

		// monto_base
		$this->monto_base = new cField('recibos', 'recibos', 'x_monto_base', 'monto_base', '`monto_base`', '`monto_base`', 131, -1, FALSE, '`monto_base`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->monto_base->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['monto_base'] = &$this->monto_base;

		// monto_impuesto
		$this->monto_impuesto = new cField('recibos', 'recibos', 'x_monto_impuesto', 'monto_impuesto', '`monto_impuesto`', '`monto_impuesto`', 131, -1, FALSE, '`monto_impuesto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->monto_impuesto->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['monto_impuesto'] = &$this->monto_impuesto;

		// monto_total
		$this->monto_total = new cField('recibos', 'recibos', 'x_monto_total', 'monto_total', '`monto_total`', '`monto_total`', 131, -1, FALSE, '`monto_total`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->monto_total->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['monto_total'] = &$this->monto_total;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`recibos`";
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
	var $UpdateTable = "`recibos`";

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
			if (array_key_exists('id_recibo', $rs))
				ew_AddFilter($where, ew_QuotedName('id_recibo') . '=' . ew_QuotedValue($rs['id_recibo'], $this->id_recibo->FldDataType));
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
		return "`id_recibo` = @id_recibo@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_recibo->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_recibo@", ew_AdjustSql($this->id_recibo->CurrentValue), $sKeyFilter); // Replace key value
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
			return "recibos_list.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "recibos_list.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("recibos_view.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("recibos_view.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "recibos_add.php?" . $this->UrlParm($parm);
		else
			return "recibos_add.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("recibos_edit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("recibos_add.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("recibos_delete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_recibo->CurrentValue)) {
			$sUrl .= "id_recibo=" . urlencode($this->id_recibo->CurrentValue);
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
			$arKeys[] = @$_GET["id_recibo"]; // id_recibo

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
			$this->id_recibo->CurrentValue = $key;
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
		$this->id_recibo->setDbValue($rs->fields('id_recibo'));
		$this->id_empresa->setDbValue($rs->fields('id_empresa'));
		$this->fecha_emision->setDbValue($rs->fields('fecha_emision'));
		$this->fecha_vencimiento->setDbValue($rs->fields('fecha_vencimiento'));
		$this->numero->setDbValue($rs->fields('numero'));
		$this->monto_base->setDbValue($rs->fields('monto_base'));
		$this->monto_impuesto->setDbValue($rs->fields('monto_impuesto'));
		$this->monto_total->setDbValue($rs->fields('monto_total'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_recibo
		// id_empresa
		// fecha_emision
		// fecha_vencimiento
		// numero
		// monto_base
		// monto_impuesto
		// monto_total
		// id_recibo

		$this->id_recibo->ViewValue = $this->id_recibo->CurrentValue;
		$this->id_recibo->ViewCustomAttributes = "";

		// id_empresa
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
		$sSqlWrk .= " ORDER BY `nombre`";
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

		// fecha_emision
		$this->fecha_emision->ViewValue = $this->fecha_emision->CurrentValue;
		$this->fecha_emision->ViewValue = ew_FormatDateTime($this->fecha_emision->ViewValue, 7);
		$this->fecha_emision->ViewCustomAttributes = "";

		// fecha_vencimiento
		$this->fecha_vencimiento->ViewValue = $this->fecha_vencimiento->CurrentValue;
		$this->fecha_vencimiento->ViewValue = ew_FormatDateTime($this->fecha_vencimiento->ViewValue, 7);
		$this->fecha_vencimiento->ViewCustomAttributes = "";

		// numero
		$this->numero->ViewValue = $this->numero->CurrentValue;
		$this->numero->ViewCustomAttributes = "";

		// monto_base
		$this->monto_base->ViewValue = $this->monto_base->CurrentValue;
		$this->monto_base->ViewCustomAttributes = "";

		// monto_impuesto
		$this->monto_impuesto->ViewValue = $this->monto_impuesto->CurrentValue;
		$this->monto_impuesto->ViewCustomAttributes = "";

		// monto_total
		$this->monto_total->ViewValue = $this->monto_total->CurrentValue;
		$this->monto_total->ViewCustomAttributes = "";

		// id_recibo
		$this->id_recibo->LinkCustomAttributes = "";
		$this->id_recibo->HrefValue = "";
		$this->id_recibo->TooltipValue = "";

		// id_empresa
		$this->id_empresa->LinkCustomAttributes = "";
		$this->id_empresa->HrefValue = "";
		$this->id_empresa->TooltipValue = "";

		// fecha_emision
		$this->fecha_emision->LinkCustomAttributes = "";
		$this->fecha_emision->HrefValue = "";
		$this->fecha_emision->TooltipValue = "";

		// fecha_vencimiento
		$this->fecha_vencimiento->LinkCustomAttributes = "";
		$this->fecha_vencimiento->HrefValue = "";
		$this->fecha_vencimiento->TooltipValue = "";

		// numero
		$this->numero->LinkCustomAttributes = "";
		$this->numero->HrefValue = "";
		$this->numero->TooltipValue = "";

		// monto_base
		$this->monto_base->LinkCustomAttributes = "";
		$this->monto_base->HrefValue = "";
		$this->monto_base->TooltipValue = "";

		// monto_impuesto
		$this->monto_impuesto->LinkCustomAttributes = "";
		$this->monto_impuesto->HrefValue = "";
		$this->monto_impuesto->TooltipValue = "";

		// monto_total
		$this->monto_total->LinkCustomAttributes = "";
		$this->monto_total->HrefValue = "";
		$this->monto_total->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id_recibo
		$this->id_recibo->EditAttrs["class"] = "form-control";
		$this->id_recibo->EditCustomAttributes = "";
		$this->id_recibo->EditValue = $this->id_recibo->CurrentValue;
		$this->id_recibo->ViewCustomAttributes = "";

		// id_empresa
		$this->id_empresa->EditAttrs["class"] = "form-control";
		$this->id_empresa->EditCustomAttributes = "";

		// fecha_emision
		$this->fecha_emision->EditAttrs["class"] = "form-control";
		$this->fecha_emision->EditCustomAttributes = "";
		$this->fecha_emision->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_emision->CurrentValue, 7));

		// fecha_vencimiento
		$this->fecha_vencimiento->EditAttrs["class"] = "form-control";
		$this->fecha_vencimiento->EditCustomAttributes = "";
		$this->fecha_vencimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_vencimiento->CurrentValue, 7));

		// numero
		$this->numero->EditAttrs["class"] = "form-control";
		$this->numero->EditCustomAttributes = "";
		$this->numero->EditValue = ew_HtmlEncode($this->numero->CurrentValue);

		// monto_base
		$this->monto_base->EditAttrs["class"] = "form-control";
		$this->monto_base->EditCustomAttributes = "";
		$this->monto_base->EditValue = ew_HtmlEncode($this->monto_base->CurrentValue);
		if (strval($this->monto_base->EditValue) <> "" && is_numeric($this->monto_base->EditValue)) $this->monto_base->EditValue = ew_FormatNumber($this->monto_base->EditValue, -2, -1, -2, 0);

		// monto_impuesto
		$this->monto_impuesto->EditAttrs["class"] = "form-control";
		$this->monto_impuesto->EditCustomAttributes = "";
		$this->monto_impuesto->EditValue = ew_HtmlEncode($this->monto_impuesto->CurrentValue);
		if (strval($this->monto_impuesto->EditValue) <> "" && is_numeric($this->monto_impuesto->EditValue)) $this->monto_impuesto->EditValue = ew_FormatNumber($this->monto_impuesto->EditValue, -2, -1, -2, 0);

		// monto_total
		$this->monto_total->EditAttrs["class"] = "form-control";
		$this->monto_total->EditCustomAttributes = "";
		$this->monto_total->EditValue = ew_HtmlEncode($this->monto_total->CurrentValue);
		if (strval($this->monto_total->EditValue) <> "" && is_numeric($this->monto_total->EditValue)) $this->monto_total->EditValue = ew_FormatNumber($this->monto_total->EditValue, -2, -1, -2, 0);

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
					if ($this->fecha_emision->Exportable) $Doc->ExportCaption($this->fecha_emision);
					if ($this->fecha_vencimiento->Exportable) $Doc->ExportCaption($this->fecha_vencimiento);
					if ($this->numero->Exportable) $Doc->ExportCaption($this->numero);
					if ($this->monto_base->Exportable) $Doc->ExportCaption($this->monto_base);
					if ($this->monto_impuesto->Exportable) $Doc->ExportCaption($this->monto_impuesto);
					if ($this->monto_total->Exportable) $Doc->ExportCaption($this->monto_total);
				} else {
					if ($this->id_recibo->Exportable) $Doc->ExportCaption($this->id_recibo);
					if ($this->id_empresa->Exportable) $Doc->ExportCaption($this->id_empresa);
					if ($this->fecha_emision->Exportable) $Doc->ExportCaption($this->fecha_emision);
					if ($this->fecha_vencimiento->Exportable) $Doc->ExportCaption($this->fecha_vencimiento);
					if ($this->numero->Exportable) $Doc->ExportCaption($this->numero);
					if ($this->monto_base->Exportable) $Doc->ExportCaption($this->monto_base);
					if ($this->monto_impuesto->Exportable) $Doc->ExportCaption($this->monto_impuesto);
					if ($this->monto_total->Exportable) $Doc->ExportCaption($this->monto_total);
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
						if ($this->fecha_emision->Exportable) $Doc->ExportField($this->fecha_emision);
						if ($this->fecha_vencimiento->Exportable) $Doc->ExportField($this->fecha_vencimiento);
						if ($this->numero->Exportable) $Doc->ExportField($this->numero);
						if ($this->monto_base->Exportable) $Doc->ExportField($this->monto_base);
						if ($this->monto_impuesto->Exportable) $Doc->ExportField($this->monto_impuesto);
						if ($this->monto_total->Exportable) $Doc->ExportField($this->monto_total);
					} else {
						if ($this->id_recibo->Exportable) $Doc->ExportField($this->id_recibo);
						if ($this->id_empresa->Exportable) $Doc->ExportField($this->id_empresa);
						if ($this->fecha_emision->Exportable) $Doc->ExportField($this->fecha_emision);
						if ($this->fecha_vencimiento->Exportable) $Doc->ExportField($this->fecha_vencimiento);
						if ($this->numero->Exportable) $Doc->ExportField($this->numero);
						if ($this->monto_base->Exportable) $Doc->ExportField($this->monto_base);
						if ($this->monto_impuesto->Exportable) $Doc->ExportField($this->monto_impuesto);
						if ($this->monto_total->Exportable) $Doc->ExportField($this->monto_total);
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
