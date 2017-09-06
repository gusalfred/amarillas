<?php

// Global variable for table object
$empresas = NULL;

//
// Table class for empresas
//
class cempresas extends cTable {
	var $id_empresa;
	var $nombre;
	var $documento_tipo;
	var $documento_numero;
	var $_email;
	var $web;
	var $logo;
	var $descripcion;
	var $meta_descripcion;
	var $meta_palabras_clave;
	var $slug;
	var $comentarios;
	var $estrellas;
	var $contrato;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'empresas';
		$this->TableName = 'empresas';
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

		// id_empresa
		$this->id_empresa = new cField('empresas', 'empresas', 'x_id_empresa', 'id_empresa', '`id_empresa`', '`id_empresa`', 19, -1, FALSE, '`id_empresa`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_empresa->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_empresa'] = &$this->id_empresa;

		// nombre
		$this->nombre = new cField('empresas', 'empresas', 'x_nombre', 'nombre', '`nombre`', '`nombre`', 200, -1, FALSE, '`nombre`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nombre'] = &$this->nombre;

		// documento_tipo
		$this->documento_tipo = new cField('empresas', 'empresas', 'x_documento_tipo', 'documento_tipo', '`documento_tipo`', '`documento_tipo`', 3, -1, FALSE, '`documento_tipo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->documento_tipo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['documento_tipo'] = &$this->documento_tipo;

		// documento_numero
		$this->documento_numero = new cField('empresas', 'empresas', 'x_documento_numero', 'documento_numero', '`documento_numero`', '`documento_numero`', 200, -1, FALSE, '`documento_numero`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['documento_numero'] = &$this->documento_numero;

		// email
		$this->_email = new cField('empresas', 'empresas', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['email'] = &$this->_email;

		// web
		$this->web = new cField('empresas', 'empresas', 'x_web', 'web', '`web`', '`web`', 200, -1, FALSE, '`web`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['web'] = &$this->web;

		// logo
		$this->logo = new cField('empresas', 'empresas', 'x_logo', 'logo', '`logo`', '`logo`', 200, -1, TRUE, '`logo`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['logo'] = &$this->logo;

		// descripcion
		$this->descripcion = new cField('empresas', 'empresas', 'x_descripcion', 'descripcion', '`descripcion`', '`descripcion`', 201, -1, FALSE, '`descripcion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['descripcion'] = &$this->descripcion;

		// meta_descripcion
		$this->meta_descripcion = new cField('empresas', 'empresas', 'x_meta_descripcion', 'meta_descripcion', '`meta_descripcion`', '`meta_descripcion`', 200, -1, FALSE, '`meta_descripcion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['meta_descripcion'] = &$this->meta_descripcion;

		// meta_palabras_clave
		$this->meta_palabras_clave = new cField('empresas', 'empresas', 'x_meta_palabras_clave', 'meta_palabras_clave', '`meta_palabras_clave`', '`meta_palabras_clave`', 200, -1, FALSE, '`meta_palabras_clave`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['meta_palabras_clave'] = &$this->meta_palabras_clave;

		// slug
		$this->slug = new cField('empresas', 'empresas', 'x_slug', 'slug', '`slug`', '`slug`', 200, -1, FALSE, '`slug`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['slug'] = &$this->slug;

		// comentarios
		$this->comentarios = new cField('empresas', 'empresas', 'x_comentarios', 'comentarios', '`comentarios`', '`comentarios`', 19, -1, FALSE, '`comentarios`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->comentarios->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['comentarios'] = &$this->comentarios;

		// estrellas
		$this->estrellas = new cField('empresas', 'empresas', 'x_estrellas', 'estrellas', '`estrellas`', '`estrellas`', 131, -1, FALSE, '`estrellas`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->estrellas->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['estrellas'] = &$this->estrellas;

		// contrato
		$this->contrato = new cField('empresas', 'empresas', 'x_contrato', 'contrato', '`contrato`', '`contrato`', 16, -1, FALSE, '`contrato`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->contrato->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['contrato'] = &$this->contrato;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`empresas`";
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
	var $UpdateTable = "`empresas`";

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
			if (array_key_exists('id_empresa', $rs))
				ew_AddFilter($where, ew_QuotedName('id_empresa') . '=' . ew_QuotedValue($rs['id_empresa'], $this->id_empresa->FldDataType));
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
		return "`id_empresa` = @id_empresa@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_empresa->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_empresa@", ew_AdjustSql($this->id_empresa->CurrentValue), $sKeyFilter); // Replace key value
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
			return "empresas_list.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "empresas_list.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("empresas_view.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("empresas_view.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "empresas_add.php?" . $this->UrlParm($parm);
		else
			return "empresas_add.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("empresas_edit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("empresas_add.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("empresas_delete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_empresa->CurrentValue)) {
			$sUrl .= "id_empresa=" . urlencode($this->id_empresa->CurrentValue);
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
			$arKeys[] = @$_GET["id_empresa"]; // id_empresa

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
			$this->id_empresa->CurrentValue = $key;
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
		$this->id_empresa->setDbValue($rs->fields('id_empresa'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->documento_tipo->setDbValue($rs->fields('documento_tipo'));
		$this->documento_numero->setDbValue($rs->fields('documento_numero'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->web->setDbValue($rs->fields('web'));
		$this->logo->Upload->DbValue = $rs->fields('logo');
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->meta_descripcion->setDbValue($rs->fields('meta_descripcion'));
		$this->meta_palabras_clave->setDbValue($rs->fields('meta_palabras_clave'));
		$this->slug->setDbValue($rs->fields('slug'));
		$this->comentarios->setDbValue($rs->fields('comentarios'));
		$this->estrellas->setDbValue($rs->fields('estrellas'));
		$this->contrato->setDbValue($rs->fields('contrato'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_empresa
		// nombre
		// documento_tipo
		// documento_numero
		// email
		// web
		// logo
		// descripcion
		// meta_descripcion
		// meta_palabras_clave
		// slug
		// comentarios
		// estrellas
		// contrato
		// id_empresa

		$this->id_empresa->ViewValue = $this->id_empresa->CurrentValue;
		$this->id_empresa->ViewCustomAttributes = "";

		// nombre
		$this->nombre->ViewValue = $this->nombre->CurrentValue;
		$this->nombre->ViewCustomAttributes = "";

		// documento_tipo
		if (strval($this->documento_tipo->CurrentValue) <> "") {
			switch ($this->documento_tipo->CurrentValue) {
				case $this->documento_tipo->FldTagValue(1):
					$this->documento_tipo->ViewValue = $this->documento_tipo->FldTagCaption(1) <> "" ? $this->documento_tipo->FldTagCaption(1) : $this->documento_tipo->CurrentValue;
					break;
				case $this->documento_tipo->FldTagValue(2):
					$this->documento_tipo->ViewValue = $this->documento_tipo->FldTagCaption(2) <> "" ? $this->documento_tipo->FldTagCaption(2) : $this->documento_tipo->CurrentValue;
					break;
				case $this->documento_tipo->FldTagValue(3):
					$this->documento_tipo->ViewValue = $this->documento_tipo->FldTagCaption(3) <> "" ? $this->documento_tipo->FldTagCaption(3) : $this->documento_tipo->CurrentValue;
					break;
				default:
					$this->documento_tipo->ViewValue = $this->documento_tipo->CurrentValue;
			}
		} else {
			$this->documento_tipo->ViewValue = NULL;
		}
		$this->documento_tipo->ViewCustomAttributes = "";

		// documento_numero
		$this->documento_numero->ViewValue = $this->documento_numero->CurrentValue;
		$this->documento_numero->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// web
		$this->web->ViewValue = $this->web->CurrentValue;
		$this->web->ViewCustomAttributes = "";

		// logo
		$this->logo->UploadPath = '../uploads/logos/';
		if (!ew_Empty($this->logo->Upload->DbValue)) {
			$this->logo->ImageAlt = $this->logo->FldAlt();
			$this->logo->ViewValue = ew_UploadPathEx(FALSE, $this->logo->UploadPath) . $this->logo->Upload->DbValue;
			if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
				$this->logo->ViewValue = ew_UploadPathEx(TRUE, $this->logo->UploadPath) . $this->logo->Upload->DbValue;
			}
		} else {
			$this->logo->ViewValue = "";
		}
		$this->logo->ViewCustomAttributes = "";

		// descripcion
		$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
		$this->descripcion->ViewCustomAttributes = "";

		// meta_descripcion
		$this->meta_descripcion->ViewValue = $this->meta_descripcion->CurrentValue;
		$this->meta_descripcion->ViewCustomAttributes = "";

		// meta_palabras_clave
		$this->meta_palabras_clave->ViewValue = $this->meta_palabras_clave->CurrentValue;
		$this->meta_palabras_clave->ViewCustomAttributes = "";

		// slug
		$this->slug->ViewValue = $this->slug->CurrentValue;
		$this->slug->ViewCustomAttributes = "";

		// comentarios
		$this->comentarios->ViewValue = $this->comentarios->CurrentValue;
		$this->comentarios->ViewCustomAttributes = "";

		// estrellas
		$this->estrellas->ViewValue = $this->estrellas->CurrentValue;
		$this->estrellas->ViewCustomAttributes = "";

		// contrato
		$this->contrato->ViewValue = $this->contrato->CurrentValue;
		$this->contrato->ViewCustomAttributes = "";

		// id_empresa
		$this->id_empresa->LinkCustomAttributes = "";
		$this->id_empresa->HrefValue = "";
		$this->id_empresa->TooltipValue = "";

		// nombre
		$this->nombre->LinkCustomAttributes = "";
		if (!ew_Empty($this->id_empresa->CurrentValue)) {
			$this->nombre->HrefValue = "empresas_view.php?id_empresa=" . ((!empty($this->id_empresa->ViewValue)) ? $this->id_empresa->ViewValue : $this->id_empresa->CurrentValue); // Add prefix/suffix
			$this->nombre->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->nombre->HrefValue = ew_ConvertFullUrl($this->nombre->HrefValue);
		} else {
			$this->nombre->HrefValue = "";
		}
		$this->nombre->TooltipValue = "";

		// documento_tipo
		$this->documento_tipo->LinkCustomAttributes = "";
		$this->documento_tipo->HrefValue = "";
		$this->documento_tipo->TooltipValue = "";

		// documento_numero
		$this->documento_numero->LinkCustomAttributes = "";
		$this->documento_numero->HrefValue = "";
		$this->documento_numero->TooltipValue = "";

		// email
		$this->_email->LinkCustomAttributes = "";
		$this->_email->HrefValue = "";
		$this->_email->TooltipValue = "";

		// web
		$this->web->LinkCustomAttributes = "";
		$this->web->HrefValue = "";
		$this->web->TooltipValue = "";

		// logo
		$this->logo->LinkCustomAttributes = "";
		$this->logo->UploadPath = '../uploads/logos/';
		if (!ew_Empty($this->logo->Upload->DbValue)) {
			$this->logo->HrefValue = ew_UploadPathEx(FALSE, $this->logo->UploadPath) . $this->logo->Upload->DbValue; // Add prefix/suffix
			$this->logo->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->logo->HrefValue = ew_ConvertFullUrl($this->logo->HrefValue);
		} else {
			$this->logo->HrefValue = "";
		}
		$this->logo->HrefValue2 = $this->logo->UploadPath . $this->logo->Upload->DbValue;
		$this->logo->TooltipValue = "";
		if ($this->logo->UseColorbox) {
			$this->logo->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->logo->LinkAttrs["data-rel"] = "empresas_x_logo";
			$this->logo->LinkAttrs["class"] = "ewLightbox";
		}

		// descripcion
		$this->descripcion->LinkCustomAttributes = "";
		$this->descripcion->HrefValue = "";
		$this->descripcion->TooltipValue = "";

		// meta_descripcion
		$this->meta_descripcion->LinkCustomAttributes = "";
		$this->meta_descripcion->HrefValue = "";
		$this->meta_descripcion->TooltipValue = "";

		// meta_palabras_clave
		$this->meta_palabras_clave->LinkCustomAttributes = "";
		$this->meta_palabras_clave->HrefValue = "";
		$this->meta_palabras_clave->TooltipValue = "";

		// slug
		$this->slug->LinkCustomAttributes = "";
		$this->slug->HrefValue = "";
		$this->slug->TooltipValue = "";

		// comentarios
		$this->comentarios->LinkCustomAttributes = "";
		$this->comentarios->HrefValue = "";
		$this->comentarios->TooltipValue = "";

		// estrellas
		$this->estrellas->LinkCustomAttributes = "";
		$this->estrellas->HrefValue = "";
		$this->estrellas->TooltipValue = "";

		// contrato
		$this->contrato->LinkCustomAttributes = "";
		$this->contrato->HrefValue = "";
		$this->contrato->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id_empresa
		$this->id_empresa->EditAttrs["class"] = "form-control";
		$this->id_empresa->EditCustomAttributes = "";
		$this->id_empresa->EditValue = $this->id_empresa->CurrentValue;
		$this->id_empresa->ViewCustomAttributes = "";

		// nombre
		$this->nombre->EditAttrs["class"] = "form-control";
		$this->nombre->EditCustomAttributes = "";
		$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);

		// documento_tipo
		$this->documento_tipo->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->documento_tipo->FldTagValue(1), $this->documento_tipo->FldTagCaption(1) <> "" ? $this->documento_tipo->FldTagCaption(1) : $this->documento_tipo->FldTagValue(1));
		$arwrk[] = array($this->documento_tipo->FldTagValue(2), $this->documento_tipo->FldTagCaption(2) <> "" ? $this->documento_tipo->FldTagCaption(2) : $this->documento_tipo->FldTagValue(2));
		$arwrk[] = array($this->documento_tipo->FldTagValue(3), $this->documento_tipo->FldTagCaption(3) <> "" ? $this->documento_tipo->FldTagCaption(3) : $this->documento_tipo->FldTagValue(3));
		$this->documento_tipo->EditValue = $arwrk;

		// documento_numero
		$this->documento_numero->EditAttrs["class"] = "form-control";
		$this->documento_numero->EditCustomAttributes = "";
		$this->documento_numero->EditValue = ew_HtmlEncode($this->documento_numero->CurrentValue);

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);

		// web
		$this->web->EditAttrs["class"] = "form-control";
		$this->web->EditCustomAttributes = "";
		$this->web->EditValue = ew_HtmlEncode($this->web->CurrentValue);

		// logo
		$this->logo->EditAttrs["class"] = "form-control";
		$this->logo->EditCustomAttributes = "";
		$this->logo->UploadPath = '../uploads/logos/';
		if (!ew_Empty($this->logo->Upload->DbValue)) {
			$this->logo->ImageAlt = $this->logo->FldAlt();
			$this->logo->EditValue = ew_UploadPathEx(FALSE, $this->logo->UploadPath) . $this->logo->Upload->DbValue;
			if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
				$this->logo->EditValue = ew_UploadPathEx(TRUE, $this->logo->UploadPath) . $this->logo->Upload->DbValue;
			}
		} else {
			$this->logo->EditValue = "";
		}
		if (!ew_Empty($this->logo->CurrentValue))
			$this->logo->Upload->FileName = $this->logo->CurrentValue;

		// descripcion
		$this->descripcion->EditAttrs["class"] = "form-control";
		$this->descripcion->EditCustomAttributes = "";
		$this->descripcion->EditValue = ew_HtmlEncode($this->descripcion->CurrentValue);

		// meta_descripcion
		$this->meta_descripcion->EditAttrs["class"] = "form-control";
		$this->meta_descripcion->EditCustomAttributes = "";
		$this->meta_descripcion->EditValue = ew_HtmlEncode($this->meta_descripcion->CurrentValue);

		// meta_palabras_clave
		$this->meta_palabras_clave->EditAttrs["class"] = "form-control";
		$this->meta_palabras_clave->EditCustomAttributes = "";
		$this->meta_palabras_clave->EditValue = ew_HtmlEncode($this->meta_palabras_clave->CurrentValue);

		// slug
		$this->slug->EditAttrs["class"] = "form-control";
		$this->slug->EditCustomAttributes = "";
		$this->slug->EditValue = ew_HtmlEncode($this->slug->CurrentValue);

		// comentarios
		$this->comentarios->EditAttrs["class"] = "form-control";
		$this->comentarios->EditCustomAttributes = "";
		$this->comentarios->EditValue = ew_HtmlEncode($this->comentarios->CurrentValue);

		// estrellas
		$this->estrellas->EditAttrs["class"] = "form-control";
		$this->estrellas->EditCustomAttributes = "";
		$this->estrellas->EditValue = ew_HtmlEncode($this->estrellas->CurrentValue);
		if (strval($this->estrellas->EditValue) <> "" && is_numeric($this->estrellas->EditValue)) $this->estrellas->EditValue = ew_FormatNumber($this->estrellas->EditValue, -2, -1, -2, 0);

		// contrato
		$this->contrato->EditAttrs["class"] = "form-control";
		$this->contrato->EditCustomAttributes = "";
		$this->contrato->EditValue = ew_HtmlEncode($this->contrato->CurrentValue);

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
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->documento_tipo->Exportable) $Doc->ExportCaption($this->documento_tipo);
					if ($this->documento_numero->Exportable) $Doc->ExportCaption($this->documento_numero);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->web->Exportable) $Doc->ExportCaption($this->web);
					if ($this->logo->Exportable) $Doc->ExportCaption($this->logo);
					if ($this->descripcion->Exportable) $Doc->ExportCaption($this->descripcion);
					if ($this->meta_descripcion->Exportable) $Doc->ExportCaption($this->meta_descripcion);
					if ($this->meta_palabras_clave->Exportable) $Doc->ExportCaption($this->meta_palabras_clave);
					if ($this->estrellas->Exportable) $Doc->ExportCaption($this->estrellas);
					if ($this->contrato->Exportable) $Doc->ExportCaption($this->contrato);
				} else {
					if ($this->id_empresa->Exportable) $Doc->ExportCaption($this->id_empresa);
					if ($this->nombre->Exportable) $Doc->ExportCaption($this->nombre);
					if ($this->documento_tipo->Exportable) $Doc->ExportCaption($this->documento_tipo);
					if ($this->documento_numero->Exportable) $Doc->ExportCaption($this->documento_numero);
					if ($this->logo->Exportable) $Doc->ExportCaption($this->logo);
					if ($this->meta_descripcion->Exportable) $Doc->ExportCaption($this->meta_descripcion);
					if ($this->meta_palabras_clave->Exportable) $Doc->ExportCaption($this->meta_palabras_clave);
					if ($this->slug->Exportable) $Doc->ExportCaption($this->slug);
					if ($this->comentarios->Exportable) $Doc->ExportCaption($this->comentarios);
					if ($this->estrellas->Exportable) $Doc->ExportCaption($this->estrellas);
					if ($this->contrato->Exportable) $Doc->ExportCaption($this->contrato);
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
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->documento_tipo->Exportable) $Doc->ExportField($this->documento_tipo);
						if ($this->documento_numero->Exportable) $Doc->ExportField($this->documento_numero);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->web->Exportable) $Doc->ExportField($this->web);
						if ($this->logo->Exportable) $Doc->ExportField($this->logo);
						if ($this->descripcion->Exportable) $Doc->ExportField($this->descripcion);
						if ($this->meta_descripcion->Exportable) $Doc->ExportField($this->meta_descripcion);
						if ($this->meta_palabras_clave->Exportable) $Doc->ExportField($this->meta_palabras_clave);
						if ($this->estrellas->Exportable) $Doc->ExportField($this->estrellas);
						if ($this->contrato->Exportable) $Doc->ExportField($this->contrato);
					} else {
						if ($this->id_empresa->Exportable) $Doc->ExportField($this->id_empresa);
						if ($this->nombre->Exportable) $Doc->ExportField($this->nombre);
						if ($this->documento_tipo->Exportable) $Doc->ExportField($this->documento_tipo);
						if ($this->documento_numero->Exportable) $Doc->ExportField($this->documento_numero);
						if ($this->logo->Exportable) $Doc->ExportField($this->logo);
						if ($this->meta_descripcion->Exportable) $Doc->ExportField($this->meta_descripcion);
						if ($this->meta_palabras_clave->Exportable) $Doc->ExportField($this->meta_palabras_clave);
						if ($this->slug->Exportable) $Doc->ExportField($this->slug);
						if ($this->comentarios->Exportable) $Doc->ExportField($this->comentarios);
						if ($this->estrellas->Exportable) $Doc->ExportField($this->estrellas);
						if ($this->contrato->Exportable) $Doc->ExportField($this->contrato);
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
