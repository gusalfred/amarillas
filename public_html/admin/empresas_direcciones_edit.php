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

$empresas_direcciones_edit = NULL; // Initialize page object first

class cempresas_direcciones_edit extends cempresas_direcciones {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'empresas_direcciones';

	// Page object name
	var $PageObjName = 'empresas_direcciones_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("empresas_direcciones_list.php"));
		}

		// Update last accessed time
		if ($UserProfile->IsValidUser(CurrentUserName(), session_id())) {
		} else {
			echo $Language->Phrase("UserProfileCorrupted");
		}

		// Create form object
		$objForm = new cFormObj();
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id_empresa_direccion"] <> "") {
			$this->id_empresa_direccion->setQueryStringValue($_GET["id_empresa_direccion"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id_empresa_direccion->CurrentValue == "")
			$this->Page_Terminate("empresas_direcciones_list.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("empresas_direcciones_list.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_empresa->FldIsDetailKey) {
			$this->id_empresa->setFormValue($objForm->GetValue("x_id_empresa"));
		}
		if (!$this->direccion->FldIsDetailKey) {
			$this->direccion->setFormValue($objForm->GetValue("x_direccion"));
		}
		if (!$this->codigo_departamento->FldIsDetailKey) {
			$this->codigo_departamento->setFormValue($objForm->GetValue("x_codigo_departamento"));
		}
		if (!$this->codigo_provincia->FldIsDetailKey) {
			$this->codigo_provincia->setFormValue($objForm->GetValue("x_codigo_provincia"));
		}
		if (!$this->codigo_distrito->FldIsDetailKey) {
			$this->codigo_distrito->setFormValue($objForm->GetValue("x_codigo_distrito"));
		}
		if (!$this->telefonos->FldIsDetailKey) {
			$this->telefonos->setFormValue($objForm->GetValue("x_telefonos"));
		}
		if (!$this->latitud->FldIsDetailKey) {
			$this->latitud->setFormValue($objForm->GetValue("x_latitud"));
		}
		if (!$this->longitud->FldIsDetailKey) {
			$this->longitud->setFormValue($objForm->GetValue("x_longitud"));
		}
		if (!$this->id_empresa_direccion->FldIsDetailKey)
			$this->id_empresa_direccion->setFormValue($objForm->GetValue("x_id_empresa_direccion"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_empresa_direccion->CurrentValue = $this->id_empresa_direccion->FormValue;
		$this->id_empresa->CurrentValue = $this->id_empresa->FormValue;
		$this->direccion->CurrentValue = $this->direccion->FormValue;
		$this->codigo_departamento->CurrentValue = $this->codigo_departamento->FormValue;
		$this->codigo_provincia->CurrentValue = $this->codigo_provincia->FormValue;
		$this->codigo_distrito->CurrentValue = $this->codigo_distrito->FormValue;
		$this->telefonos->CurrentValue = $this->telefonos->FormValue;
		$this->latitud->CurrentValue = $this->latitud->FormValue;
		$this->longitud->CurrentValue = $this->longitud->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_empresa
			$this->id_empresa->EditAttrs["class"] = "form-control";
			$this->id_empresa->EditCustomAttributes = 'disabled';
			$this->id_empresa->EditValue = ew_HtmlEncode($this->id_empresa->CurrentValue);
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
					$this->id_empresa->EditValue = ew_HtmlEncode($rswrk->fields('DispFld'));
					$rswrk->Close();
				} else {
					$this->id_empresa->EditValue = ew_HtmlEncode($this->id_empresa->CurrentValue);
				}
			} else {
				$this->id_empresa->EditValue = NULL;
			}

			// direccion
			$this->direccion->EditAttrs["class"] = "form-control";
			$this->direccion->EditCustomAttributes = "";
			$this->direccion->EditValue = ew_HtmlEncode($this->direccion->CurrentValue);

			// codigo_departamento
			$this->codigo_departamento->EditAttrs["class"] = "form-control";
			$this->codigo_departamento->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `codigo_departamento`, `departamento` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `departamentos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codigo_departamento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `departamento`";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->codigo_departamento->EditValue = $arwrk;

			// codigo_provincia
			$this->codigo_provincia->EditAttrs["class"] = "form-control";
			$this->codigo_provincia->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `codigo_provincia`, `provincia` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `codigo_departamento` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `provincias`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codigo_provincia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `provincia`";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->codigo_provincia->EditValue = $arwrk;

			// codigo_distrito
			$this->codigo_distrito->EditAttrs["class"] = "form-control";
			$this->codigo_distrito->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `codigo_distrito`, `distrito` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `codigo_provincia` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `distritos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codigo_distrito, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `distrito`";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->codigo_distrito->EditValue = $arwrk;

			// telefonos
			$this->telefonos->EditAttrs["class"] = "form-control";
			$this->telefonos->EditCustomAttributes = "";
			$this->telefonos->EditValue = ew_HtmlEncode($this->telefonos->CurrentValue);

			// latitud
			$this->latitud->EditAttrs["class"] = "form-control";
			$this->latitud->EditCustomAttributes = "";
			$this->latitud->EditValue = ew_HtmlEncode($this->latitud->CurrentValue);

			// longitud
			$this->longitud->EditAttrs["class"] = "form-control";
			$this->longitud->EditCustomAttributes = "";
			$this->longitud->EditValue = ew_HtmlEncode($this->longitud->CurrentValue);

			// Edit refer script
			// id_empresa

			$this->id_empresa->HrefValue = "";

			// direccion
			$this->direccion->HrefValue = "";

			// codigo_departamento
			$this->codigo_departamento->HrefValue = "";

			// codigo_provincia
			$this->codigo_provincia->HrefValue = "";

			// codigo_distrito
			$this->codigo_distrito->HrefValue = "";

			// telefonos
			$this->telefonos->HrefValue = "";

			// latitud
			$this->latitud->HrefValue = "";

			// longitud
			$this->longitud->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckInteger($this->id_empresa->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_empresa->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// id_empresa
			$this->id_empresa->SetDbValueDef($rsnew, $this->id_empresa->CurrentValue, NULL, $this->id_empresa->ReadOnly);

			// direccion
			$this->direccion->SetDbValueDef($rsnew, $this->direccion->CurrentValue, NULL, $this->direccion->ReadOnly);

			// codigo_departamento
			$this->codigo_departamento->SetDbValueDef($rsnew, $this->codigo_departamento->CurrentValue, NULL, $this->codigo_departamento->ReadOnly);

			// codigo_provincia
			$this->codigo_provincia->SetDbValueDef($rsnew, $this->codigo_provincia->CurrentValue, NULL, $this->codigo_provincia->ReadOnly);

			// codigo_distrito
			$this->codigo_distrito->SetDbValueDef($rsnew, $this->codigo_distrito->CurrentValue, NULL, $this->codigo_distrito->ReadOnly);

			// telefonos
			$this->telefonos->SetDbValueDef($rsnew, $this->telefonos->CurrentValue, NULL, $this->telefonos->ReadOnly);

			// latitud
			$this->latitud->SetDbValueDef($rsnew, $this->latitud->CurrentValue, NULL, $this->latitud->ReadOnly);

			// longitud
			$this->longitud->SetDbValueDef($rsnew, $this->longitud->CurrentValue, NULL, $this->longitud->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "empresas_direcciones_list.php", "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($empresas_direcciones_edit)) $empresas_direcciones_edit = new cempresas_direcciones_edit();

// Page init
$empresas_direcciones_edit->Page_Init();

// Page main
$empresas_direcciones_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empresas_direcciones_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var empresas_direcciones_edit = new ew_Page("empresas_direcciones_edit");
empresas_direcciones_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = empresas_direcciones_edit.PageID; // For backward compatibility

// Form object
var fempresas_direccionesedit = new ew_Form("fempresas_direccionesedit");

// Validate form
fempresas_direccionesedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_id_empresa");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($empresas_direcciones->id_empresa->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fempresas_direccionesedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempresas_direccionesedit.ValidateRequired = true;
<?php } else { ?>
fempresas_direccionesedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fempresas_direccionesedit.Lists["x_id_empresa"] = {"LinkField":"x_id_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fempresas_direccionesedit.Lists["x_codigo_departamento"] = {"LinkField":"x_codigo_departamento","Ajax":null,"AutoFill":false,"DisplayFields":["x_departamento","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fempresas_direccionesedit.Lists["x_codigo_provincia"] = {"LinkField":"x_codigo_provincia","Ajax":null,"AutoFill":false,"DisplayFields":["x_provincia","","",""],"ParentFields":["x_codigo_departamento"],"FilterFields":["x_codigo_departamento"],"Options":[]};
fempresas_direccionesedit.Lists["x_codigo_distrito"] = {"LinkField":"x_codigo_distrito","Ajax":null,"AutoFill":false,"DisplayFields":["x_distrito","","",""],"ParentFields":["x_codigo_provincia"],"FilterFields":["x_codigo_provincia"],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $empresas_direcciones_edit->ShowPageHeader(); ?>
<?php
$empresas_direcciones_edit->ShowMessage();
?>
<form name="fempresas_direccionesedit" id="fempresas_direccionesedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($empresas_direcciones_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $empresas_direcciones_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="empresas_direcciones">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($empresas_direcciones->id_empresa->Visible) { // id_empresa ?>
	<div id="r_id_empresa" class="form-group">
		<label id="elh_empresas_direcciones_id_empresa" class="col-sm-2 control-label ewLabel"><?php echo $empresas_direcciones->id_empresa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas_direcciones->id_empresa->CellAttributes() ?>>
<span id="el_empresas_direcciones_id_empresa">
<?php
	$wrkonchange = trim(" " . @$empresas_direcciones->id_empresa->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$empresas_direcciones->id_empresa->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_empresa" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_id_empresa" id="sv_x_id_empresa" value="<?php echo $empresas_direcciones->id_empresa->EditValue ?>" size="30"<?php echo $empresas_direcciones->id_empresa->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_id_empresa" name="x_id_empresa" id="x_id_empresa" value="<?php echo ew_HtmlEncode($empresas_direcciones->id_empresa->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id_empresa`, `nombre` AS `DispFld` FROM `empresas`";
$sWhereWrk = "`nombre` LIKE '{query_value}%'";

// Call Lookup selecting
$empresas_direcciones->Lookup_Selecting($empresas_direcciones->id_empresa, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_id_empresa" id="q_x_id_empresa" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fempresas_direccionesedit.CreateAutoSuggest("x_id_empresa", false);
</script>
</span>
<?php echo $empresas_direcciones->id_empresa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas_direcciones->direccion->Visible) { // direccion ?>
	<div id="r_direccion" class="form-group">
		<label id="elh_empresas_direcciones_direccion" for="x_direccion" class="col-sm-2 control-label ewLabel"><?php echo $empresas_direcciones->direccion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas_direcciones->direccion->CellAttributes() ?>>
<span id="el_empresas_direcciones_direccion">
<textarea data-field="x_direccion" name="x_direccion" id="x_direccion" cols="30" rows="2"<?php echo $empresas_direcciones->direccion->EditAttributes() ?>><?php echo $empresas_direcciones->direccion->EditValue ?></textarea>
</span>
<?php echo $empresas_direcciones->direccion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas_direcciones->codigo_departamento->Visible) { // codigo_departamento ?>
	<div id="r_codigo_departamento" class="form-group">
		<label id="elh_empresas_direcciones_codigo_departamento" for="x_codigo_departamento" class="col-sm-2 control-label ewLabel"><?php echo $empresas_direcciones->codigo_departamento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas_direcciones->codigo_departamento->CellAttributes() ?>>
<span id="el_empresas_direcciones_codigo_departamento">
<?php $empresas_direcciones->codigo_departamento->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_codigo_provincia']); " . @$empresas_direcciones->codigo_departamento->EditAttrs["onchange"]; ?>
<select data-field="x_codigo_departamento" id="x_codigo_departamento" name="x_codigo_departamento"<?php echo $empresas_direcciones->codigo_departamento->EditAttributes() ?>>
<?php
if (is_array($empresas_direcciones->codigo_departamento->EditValue)) {
	$arwrk = $empresas_direcciones->codigo_departamento->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($empresas_direcciones->codigo_departamento->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fempresas_direccionesedit.Lists["x_codigo_departamento"].Options = <?php echo (is_array($empresas_direcciones->codigo_departamento->EditValue)) ? ew_ArrayToJson($empresas_direcciones->codigo_departamento->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $empresas_direcciones->codigo_departamento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas_direcciones->codigo_provincia->Visible) { // codigo_provincia ?>
	<div id="r_codigo_provincia" class="form-group">
		<label id="elh_empresas_direcciones_codigo_provincia" for="x_codigo_provincia" class="col-sm-2 control-label ewLabel"><?php echo $empresas_direcciones->codigo_provincia->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas_direcciones->codigo_provincia->CellAttributes() ?>>
<span id="el_empresas_direcciones_codigo_provincia">
<?php $empresas_direcciones->codigo_provincia->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_codigo_distrito']); " . @$empresas_direcciones->codigo_provincia->EditAttrs["onchange"]; ?>
<select data-field="x_codigo_provincia" id="x_codigo_provincia" name="x_codigo_provincia"<?php echo $empresas_direcciones->codigo_provincia->EditAttributes() ?>>
<?php
if (is_array($empresas_direcciones->codigo_provincia->EditValue)) {
	$arwrk = $empresas_direcciones->codigo_provincia->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($empresas_direcciones->codigo_provincia->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fempresas_direccionesedit.Lists["x_codigo_provincia"].Options = <?php echo (is_array($empresas_direcciones->codigo_provincia->EditValue)) ? ew_ArrayToJson($empresas_direcciones->codigo_provincia->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $empresas_direcciones->codigo_provincia->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas_direcciones->codigo_distrito->Visible) { // codigo_distrito ?>
	<div id="r_codigo_distrito" class="form-group">
		<label id="elh_empresas_direcciones_codigo_distrito" for="x_codigo_distrito" class="col-sm-2 control-label ewLabel"><?php echo $empresas_direcciones->codigo_distrito->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas_direcciones->codigo_distrito->CellAttributes() ?>>
<span id="el_empresas_direcciones_codigo_distrito">
<select data-field="x_codigo_distrito" id="x_codigo_distrito" name="x_codigo_distrito"<?php echo $empresas_direcciones->codigo_distrito->EditAttributes() ?>>
<?php
if (is_array($empresas_direcciones->codigo_distrito->EditValue)) {
	$arwrk = $empresas_direcciones->codigo_distrito->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($empresas_direcciones->codigo_distrito->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fempresas_direccionesedit.Lists["x_codigo_distrito"].Options = <?php echo (is_array($empresas_direcciones->codigo_distrito->EditValue)) ? ew_ArrayToJson($empresas_direcciones->codigo_distrito->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $empresas_direcciones->codigo_distrito->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas_direcciones->telefonos->Visible) { // telefonos ?>
	<div id="r_telefonos" class="form-group">
		<label id="elh_empresas_direcciones_telefonos" for="x_telefonos" class="col-sm-2 control-label ewLabel"><?php echo $empresas_direcciones->telefonos->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas_direcciones->telefonos->CellAttributes() ?>>
<span id="el_empresas_direcciones_telefonos">
<input type="text" data-field="x_telefonos" name="x_telefonos" id="x_telefonos" size="30" maxlength="250" value="<?php echo $empresas_direcciones->telefonos->EditValue ?>"<?php echo $empresas_direcciones->telefonos->EditAttributes() ?>>
</span>
<?php echo $empresas_direcciones->telefonos->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas_direcciones->latitud->Visible) { // latitud ?>
	<div id="r_latitud" class="form-group">
		<label id="elh_empresas_direcciones_latitud" for="x_latitud" class="col-sm-2 control-label ewLabel"><?php echo $empresas_direcciones->latitud->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas_direcciones->latitud->CellAttributes() ?>>
<span id="el_empresas_direcciones_latitud">
<input type="text" data-field="x_latitud" name="x_latitud" id="x_latitud" size="30" maxlength="30" value="<?php echo $empresas_direcciones->latitud->EditValue ?>"<?php echo $empresas_direcciones->latitud->EditAttributes() ?>>
</span>
<?php echo $empresas_direcciones->latitud->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas_direcciones->longitud->Visible) { // longitud ?>
	<div id="r_longitud" class="form-group">
		<label id="elh_empresas_direcciones_longitud" for="x_longitud" class="col-sm-2 control-label ewLabel"><?php echo $empresas_direcciones->longitud->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas_direcciones->longitud->CellAttributes() ?>>
<span id="el_empresas_direcciones_longitud">
<input type="text" data-field="x_longitud" name="x_longitud" id="x_longitud" size="30" maxlength="30" value="<?php echo $empresas_direcciones->longitud->EditValue ?>"<?php echo $empresas_direcciones->longitud->EditAttributes() ?>>
</span>
<?php echo $empresas_direcciones->longitud->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-field="x_id_empresa_direccion" name="x_id_empresa_direccion" id="x_id_empresa_direccion" value="<?php echo ew_HtmlEncode($empresas_direcciones->id_empresa_direccion->CurrentValue) ?>">
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fempresas_direccionesedit.Init();
</script>
<?php
$empresas_direcciones_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empresas_direcciones_edit->Page_Terminate();
?>
