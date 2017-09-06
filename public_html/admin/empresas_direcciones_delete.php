<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "empresas_direcciones_info.php" ?>
<?php include_once "empleados_info.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$empresas_direcciones_delete = NULL; // Initialize page object first

class cempresas_direcciones_delete extends cempresas_direcciones {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'empresas_direcciones';

	// Page object name
	var $PageObjName = 'empresas_direcciones_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (empresas_direcciones)
		if (!isset($GLOBALS["empresas_direcciones"]) || get_class($GLOBALS["empresas_direcciones"]) == "cempresas_direcciones") {
			$GLOBALS["empresas_direcciones"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["empresas_direcciones"];
		}

		// Table object (empleados)
		if (!isset($GLOBALS['empleados'])) $GLOBALS['empleados'] = new cempleados();

		// User table object (empleados)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cempleados();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'empresas_direcciones', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (IsPasswordExpired())
			$this->Page_Terminate(ew_GetUrl("changepwd.php"));
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("empresas_direcciones_list.php"));
		}

		// Update last accessed time
		if ($UserProfile->IsValidUser(CurrentUserName(), session_id())) {
		} else {
			echo $Language->Phrase("UserProfileCorrupted");
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn, $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $empresas_direcciones;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($empresas_direcciones);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("empresas_direcciones_list.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in empresas_direcciones class, empresas_direccionesinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
		$conn->raiseErrorFn = '';

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id_empresa_direccion->setDbValue($rs->fields('id_empresa_direccion'));
		$this->id_empresa->setDbValue($rs->fields('id_empresa'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->codigo_departamento->setDbValue($rs->fields('codigo_departamento'));
		$this->codigo_provincia->setDbValue($rs->fields('codigo_provincia'));
		$this->codigo_distrito->setDbValue($rs->fields('codigo_distrito'));
		$this->telefonos->setDbValue($rs->fields('telefonos'));
		$this->latitud->setDbValue($rs->fields('latitud'));
		$this->longitud->setDbValue($rs->fields('longitud'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_empresa_direccion->DbValue = $row['id_empresa_direccion'];
		$this->id_empresa->DbValue = $row['id_empresa'];
		$this->direccion->DbValue = $row['direccion'];
		$this->codigo_departamento->DbValue = $row['codigo_departamento'];
		$this->codigo_provincia->DbValue = $row['codigo_provincia'];
		$this->codigo_distrito->DbValue = $row['codigo_distrito'];
		$this->telefonos->DbValue = $row['telefonos'];
		$this->latitud->DbValue = $row['latitud'];
		$this->longitud->DbValue = $row['longitud'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_empresa_direccion
		// id_empresa
		// direccion
		// codigo_departamento
		// codigo_provincia
		// codigo_distrito
		// telefonos
		// latitud
		// longitud

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_empresa_direccion
			$this->id_empresa_direccion->ViewValue = $this->id_empresa_direccion->CurrentValue;
			$this->id_empresa_direccion->ViewCustomAttributes = "";

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

			// direccion
			$this->direccion->ViewValue = $this->direccion->CurrentValue;
			$this->direccion->ViewCustomAttributes = "";

			// codigo_departamento
			if (strval($this->codigo_departamento->CurrentValue) <> "") {
				$sFilterWrk = "`codigo_departamento`" . ew_SearchString("=", $this->codigo_departamento->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT `codigo_departamento`, `departamento` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamentos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codigo_departamento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `departamento`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->codigo_departamento->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->codigo_departamento->ViewValue = $this->codigo_departamento->CurrentValue;
				}
			} else {
				$this->codigo_departamento->ViewValue = NULL;
			}
			$this->codigo_departamento->ViewCustomAttributes = "";

			// codigo_provincia
			if (strval($this->codigo_provincia->CurrentValue) <> "") {
				$sFilterWrk = "`codigo_provincia`" . ew_SearchString("=", $this->codigo_provincia->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT `codigo_provincia`, `provincia` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `provincias`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codigo_provincia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `provincia`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->codigo_provincia->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->codigo_provincia->ViewValue = $this->codigo_provincia->CurrentValue;
				}
			} else {
				$this->codigo_provincia->ViewValue = NULL;
			}
			$this->codigo_provincia->ViewCustomAttributes = "";

			// codigo_distrito
			if (strval($this->codigo_distrito->CurrentValue) <> "") {
				$sFilterWrk = "`codigo_distrito`" . ew_SearchString("=", $this->codigo_distrito->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT `codigo_distrito`, `distrito` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `distritos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codigo_distrito, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `distrito`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->codigo_distrito->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->codigo_distrito->ViewValue = $this->codigo_distrito->CurrentValue;
				}
			} else {
				$this->codigo_distrito->ViewValue = NULL;
			}
			$this->codigo_distrito->ViewCustomAttributes = "";

			// telefonos
			$this->telefonos->ViewValue = $this->telefonos->CurrentValue;
			$this->telefonos->ViewCustomAttributes = "";

			// latitud
			$this->latitud->ViewValue = $this->latitud->CurrentValue;
			$this->latitud->ViewCustomAttributes = "";

			// longitud
			$this->longitud->ViewValue = $this->longitud->CurrentValue;
			$this->longitud->ViewCustomAttributes = "";

			// id_empresa
			$this->id_empresa->LinkCustomAttributes = "";
			$this->id_empresa->HrefValue = "";
			$this->id_empresa->TooltipValue = "";

			// direccion
			$this->direccion->LinkCustomAttributes = "";
			$this->direccion->HrefValue = "";
			$this->direccion->TooltipValue = "";

			// codigo_departamento
			$this->codigo_departamento->LinkCustomAttributes = "";
			$this->codigo_departamento->HrefValue = "";
			$this->codigo_departamento->TooltipValue = "";

			// codigo_provincia
			$this->codigo_provincia->LinkCustomAttributes = "";
			$this->codigo_provincia->HrefValue = "";
			$this->codigo_provincia->TooltipValue = "";

			// codigo_distrito
			$this->codigo_distrito->LinkCustomAttributes = "";
			$this->codigo_distrito->HrefValue = "";
			$this->codigo_distrito->TooltipValue = "";

			// telefonos
			$this->telefonos->LinkCustomAttributes = "";
			$this->telefonos->HrefValue = "";
			$this->telefonos->TooltipValue = "";

			// latitud
			$this->latitud->LinkCustomAttributes = "";
			$this->latitud->HrefValue = "";
			$this->latitud->TooltipValue = "";

			// longitud
			$this->longitud->LinkCustomAttributes = "";
			$this->longitud->HrefValue = "";
			$this->longitud->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id_empresa_direccion'];
				$this->LoadDbValues($row);
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "empresas_direcciones_list.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($empresas_direcciones_delete)) $empresas_direcciones_delete = new cempresas_direcciones_delete();

// Page init
$empresas_direcciones_delete->Page_Init();

// Page main
$empresas_direcciones_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empresas_direcciones_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var empresas_direcciones_delete = new ew_Page("empresas_direcciones_delete");
empresas_direcciones_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = empresas_direcciones_delete.PageID; // For backward compatibility

// Form object
var fempresas_direccionesdelete = new ew_Form("fempresas_direccionesdelete");

// Form_CustomValidate event
fempresas_direccionesdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempresas_direccionesdelete.ValidateRequired = true;
<?php } else { ?>
fempresas_direccionesdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fempresas_direccionesdelete.Lists["x_id_empresa"] = {"LinkField":"x_id_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fempresas_direccionesdelete.Lists["x_codigo_departamento"] = {"LinkField":"x_codigo_departamento","Ajax":null,"AutoFill":false,"DisplayFields":["x_departamento","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fempresas_direccionesdelete.Lists["x_codigo_provincia"] = {"LinkField":"x_codigo_provincia","Ajax":null,"AutoFill":false,"DisplayFields":["x_provincia","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fempresas_direccionesdelete.Lists["x_codigo_distrito"] = {"LinkField":"x_codigo_distrito","Ajax":null,"AutoFill":false,"DisplayFields":["x_distrito","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($empresas_direcciones_delete->Recordset = $empresas_direcciones_delete->LoadRecordset())
	$empresas_direcciones_deleteTotalRecs = $empresas_direcciones_delete->Recordset->RecordCount(); // Get record count
if ($empresas_direcciones_deleteTotalRecs <= 0) { // No record found, exit
	if ($empresas_direcciones_delete->Recordset)
		$empresas_direcciones_delete->Recordset->Close();
	$empresas_direcciones_delete->Page_Terminate("empresas_direcciones_list.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $empresas_direcciones_delete->ShowPageHeader(); ?>
<?php
$empresas_direcciones_delete->ShowMessage();
?>
<form name="fempresas_direccionesdelete" id="fempresas_direccionesdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($empresas_direcciones_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $empresas_direcciones_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="empresas_direcciones">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($empresas_direcciones_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $empresas_direcciones->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($empresas_direcciones->id_empresa->Visible) { // id_empresa ?>
		<th><span id="elh_empresas_direcciones_id_empresa" class="empresas_direcciones_id_empresa"><?php echo $empresas_direcciones->id_empresa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas_direcciones->direccion->Visible) { // direccion ?>
		<th><span id="elh_empresas_direcciones_direccion" class="empresas_direcciones_direccion"><?php echo $empresas_direcciones->direccion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas_direcciones->codigo_departamento->Visible) { // codigo_departamento ?>
		<th><span id="elh_empresas_direcciones_codigo_departamento" class="empresas_direcciones_codigo_departamento"><?php echo $empresas_direcciones->codigo_departamento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas_direcciones->codigo_provincia->Visible) { // codigo_provincia ?>
		<th><span id="elh_empresas_direcciones_codigo_provincia" class="empresas_direcciones_codigo_provincia"><?php echo $empresas_direcciones->codigo_provincia->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas_direcciones->codigo_distrito->Visible) { // codigo_distrito ?>
		<th><span id="elh_empresas_direcciones_codigo_distrito" class="empresas_direcciones_codigo_distrito"><?php echo $empresas_direcciones->codigo_distrito->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas_direcciones->telefonos->Visible) { // telefonos ?>
		<th><span id="elh_empresas_direcciones_telefonos" class="empresas_direcciones_telefonos"><?php echo $empresas_direcciones->telefonos->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas_direcciones->latitud->Visible) { // latitud ?>
		<th><span id="elh_empresas_direcciones_latitud" class="empresas_direcciones_latitud"><?php echo $empresas_direcciones->latitud->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas_direcciones->longitud->Visible) { // longitud ?>
		<th><span id="elh_empresas_direcciones_longitud" class="empresas_direcciones_longitud"><?php echo $empresas_direcciones->longitud->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$empresas_direcciones_delete->RecCnt = 0;
$i = 0;
while (!$empresas_direcciones_delete->Recordset->EOF) {
	$empresas_direcciones_delete->RecCnt++;
	$empresas_direcciones_delete->RowCnt++;

	// Set row properties
	$empresas_direcciones->ResetAttrs();
	$empresas_direcciones->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$empresas_direcciones_delete->LoadRowValues($empresas_direcciones_delete->Recordset);

	// Render row
	$empresas_direcciones_delete->RenderRow();
?>
	<tr<?php echo $empresas_direcciones->RowAttributes() ?>>
<?php if ($empresas_direcciones->id_empresa->Visible) { // id_empresa ?>
		<td<?php echo $empresas_direcciones->id_empresa->CellAttributes() ?>>
<span id="el<?php echo $empresas_direcciones_delete->RowCnt ?>_empresas_direcciones_id_empresa" class="empresas_direcciones_id_empresa">
<span<?php echo $empresas_direcciones->id_empresa->ViewAttributes() ?>>
<?php echo $empresas_direcciones->id_empresa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas_direcciones->direccion->Visible) { // direccion ?>
		<td<?php echo $empresas_direcciones->direccion->CellAttributes() ?>>
<span id="el<?php echo $empresas_direcciones_delete->RowCnt ?>_empresas_direcciones_direccion" class="empresas_direcciones_direccion">
<span<?php echo $empresas_direcciones->direccion->ViewAttributes() ?>>
<?php echo $empresas_direcciones->direccion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas_direcciones->codigo_departamento->Visible) { // codigo_departamento ?>
		<td<?php echo $empresas_direcciones->codigo_departamento->CellAttributes() ?>>
<span id="el<?php echo $empresas_direcciones_delete->RowCnt ?>_empresas_direcciones_codigo_departamento" class="empresas_direcciones_codigo_departamento">
<span<?php echo $empresas_direcciones->codigo_departamento->ViewAttributes() ?>>
<?php echo $empresas_direcciones->codigo_departamento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas_direcciones->codigo_provincia->Visible) { // codigo_provincia ?>
		<td<?php echo $empresas_direcciones->codigo_provincia->CellAttributes() ?>>
<span id="el<?php echo $empresas_direcciones_delete->RowCnt ?>_empresas_direcciones_codigo_provincia" class="empresas_direcciones_codigo_provincia">
<span<?php echo $empresas_direcciones->codigo_provincia->ViewAttributes() ?>>
<?php echo $empresas_direcciones->codigo_provincia->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas_direcciones->codigo_distrito->Visible) { // codigo_distrito ?>
		<td<?php echo $empresas_direcciones->codigo_distrito->CellAttributes() ?>>
<span id="el<?php echo $empresas_direcciones_delete->RowCnt ?>_empresas_direcciones_codigo_distrito" class="empresas_direcciones_codigo_distrito">
<span<?php echo $empresas_direcciones->codigo_distrito->ViewAttributes() ?>>
<?php echo $empresas_direcciones->codigo_distrito->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas_direcciones->telefonos->Visible) { // telefonos ?>
		<td<?php echo $empresas_direcciones->telefonos->CellAttributes() ?>>
<span id="el<?php echo $empresas_direcciones_delete->RowCnt ?>_empresas_direcciones_telefonos" class="empresas_direcciones_telefonos">
<span<?php echo $empresas_direcciones->telefonos->ViewAttributes() ?>>
<?php echo $empresas_direcciones->telefonos->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas_direcciones->latitud->Visible) { // latitud ?>
		<td<?php echo $empresas_direcciones->latitud->CellAttributes() ?>>
<span id="el<?php echo $empresas_direcciones_delete->RowCnt ?>_empresas_direcciones_latitud" class="empresas_direcciones_latitud">
<span<?php echo $empresas_direcciones->latitud->ViewAttributes() ?>>
<?php echo $empresas_direcciones->latitud->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas_direcciones->longitud->Visible) { // longitud ?>
		<td<?php echo $empresas_direcciones->longitud->CellAttributes() ?>>
<span id="el<?php echo $empresas_direcciones_delete->RowCnt ?>_empresas_direcciones_longitud" class="empresas_direcciones_longitud">
<span<?php echo $empresas_direcciones->longitud->ViewAttributes() ?>>
<?php echo $empresas_direcciones->longitud->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$empresas_direcciones_delete->Recordset->MoveNext();
}
$empresas_direcciones_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fempresas_direccionesdelete.Init();
</script>
<?php
$empresas_direcciones_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empresas_direcciones_delete->Page_Terminate();
?>
