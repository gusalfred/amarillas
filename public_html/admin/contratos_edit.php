<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "contratos_info.php" ?>
<?php include_once "empleados_info.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$contratos_edit = NULL; // Initialize page object first

class ccontratos_edit extends ccontratos {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'contratos';

	// Page object name
	var $PageObjName = 'contratos_edit';

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

		// Table object (contratos)
		if (!isset($GLOBALS["contratos"]) || get_class($GLOBALS["contratos"]) == "ccontratos") {
			$GLOBALS["contratos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["contratos"];
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
			define("EW_TABLE_NAME", 'contratos', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("contratos_list.php"));
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
		global $EW_EXPORT, $contratos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($contratos);
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
		if (@$_GET["id_contrato"] <> "") {
			$this->id_contrato->setQueryStringValue($_GET["id_contrato"]);
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
		if ($this->id_contrato->CurrentValue == "")
			$this->Page_Terminate("contratos_list.php"); // Invalid key, return to list

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
					$this->Page_Terminate("contratos_list.php"); // No matching record, return to list
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
		if (!$this->tipo->FldIsDetailKey) {
			$this->tipo->setFormValue($objForm->GetValue("x_tipo"));
		}
		if (!$this->numero->FldIsDetailKey) {
			$this->numero->setFormValue($objForm->GetValue("x_numero"));
		}
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		}
		if (!$this->id_plan->FldIsDetailKey) {
			$this->id_plan->setFormValue($objForm->GetValue("x_id_plan"));
		}
		if (!$this->fecha_desde->FldIsDetailKey) {
			$this->fecha_desde->setFormValue($objForm->GetValue("x_fecha_desde"));
			$this->fecha_desde->CurrentValue = ew_UnFormatDateTime($this->fecha_desde->CurrentValue, 7);
		}
		if (!$this->fecha_hasta->FldIsDetailKey) {
			$this->fecha_hasta->setFormValue($objForm->GetValue("x_fecha_hasta"));
			$this->fecha_hasta->CurrentValue = ew_UnFormatDateTime($this->fecha_hasta->CurrentValue, 7);
		}
		if (!$this->id_empleado->FldIsDetailKey) {
			$this->id_empleado->setFormValue($objForm->GetValue("x_id_empleado"));
		}
		if (!$this->monto->FldIsDetailKey) {
			$this->monto->setFormValue($objForm->GetValue("x_monto"));
		}
		if (!$this->descuento->FldIsDetailKey) {
			$this->descuento->setFormValue($objForm->GetValue("x_descuento"));
		}
		if (!$this->sub_total->FldIsDetailKey) {
			$this->sub_total->setFormValue($objForm->GetValue("x_sub_total"));
		}
		if (!$this->impuesto->FldIsDetailKey) {
			$this->impuesto->setFormValue($objForm->GetValue("x_impuesto"));
		}
		if (!$this->monto_total->FldIsDetailKey) {
			$this->monto_total->setFormValue($objForm->GetValue("x_monto_total"));
		}
		if (!$this->observaciones->FldIsDetailKey) {
			$this->observaciones->setFormValue($objForm->GetValue("x_observaciones"));
		}
		if (!$this->estatus->FldIsDetailKey) {
			$this->estatus->setFormValue($objForm->GetValue("x_estatus"));
		}
		if (!$this->id_contrato->FldIsDetailKey)
			$this->id_contrato->setFormValue($objForm->GetValue("x_id_contrato"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_contrato->CurrentValue = $this->id_contrato->FormValue;
		$this->id_empresa->CurrentValue = $this->id_empresa->FormValue;
		$this->tipo->CurrentValue = $this->tipo->FormValue;
		$this->numero->CurrentValue = $this->numero->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		$this->id_plan->CurrentValue = $this->id_plan->FormValue;
		$this->fecha_desde->CurrentValue = $this->fecha_desde->FormValue;
		$this->fecha_desde->CurrentValue = ew_UnFormatDateTime($this->fecha_desde->CurrentValue, 7);
		$this->fecha_hasta->CurrentValue = $this->fecha_hasta->FormValue;
		$this->fecha_hasta->CurrentValue = ew_UnFormatDateTime($this->fecha_hasta->CurrentValue, 7);
		$this->id_empleado->CurrentValue = $this->id_empleado->FormValue;
		$this->monto->CurrentValue = $this->monto->FormValue;
		$this->descuento->CurrentValue = $this->descuento->FormValue;
		$this->sub_total->CurrentValue = $this->sub_total->FormValue;
		$this->impuesto->CurrentValue = $this->impuesto->FormValue;
		$this->monto_total->CurrentValue = $this->monto_total->FormValue;
		$this->observaciones->CurrentValue = $this->observaciones->FormValue;
		$this->estatus->CurrentValue = $this->estatus->FormValue;
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

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_contrato->DbValue = $row['id_contrato'];
		$this->id_empresa->DbValue = $row['id_empresa'];
		$this->tipo->DbValue = $row['tipo'];
		$this->numero->DbValue = $row['numero'];
		$this->fecha->DbValue = $row['fecha'];
		$this->id_plan->DbValue = $row['id_plan'];
		$this->fecha_desde->DbValue = $row['fecha_desde'];
		$this->fecha_hasta->DbValue = $row['fecha_hasta'];
		$this->id_empleado->DbValue = $row['id_empleado'];
		$this->id_recibo->DbValue = $row['id_recibo'];
		$this->monto->DbValue = $row['monto'];
		$this->descuento->DbValue = $row['descuento'];
		$this->sub_total->DbValue = $row['sub_total'];
		$this->impuesto->DbValue = $row['impuesto'];
		$this->monto_total->DbValue = $row['monto_total'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->estatus->DbValue = $row['estatus'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->monto->FormValue == $this->monto->CurrentValue && is_numeric(ew_StrToFloat($this->monto->CurrentValue)))
			$this->monto->CurrentValue = ew_StrToFloat($this->monto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->descuento->FormValue == $this->descuento->CurrentValue && is_numeric(ew_StrToFloat($this->descuento->CurrentValue)))
			$this->descuento->CurrentValue = ew_StrToFloat($this->descuento->CurrentValue);

		// Convert decimal values if posted back
		if ($this->sub_total->FormValue == $this->sub_total->CurrentValue && is_numeric(ew_StrToFloat($this->sub_total->CurrentValue)))
			$this->sub_total->CurrentValue = ew_StrToFloat($this->sub_total->CurrentValue);

		// Convert decimal values if posted back
		if ($this->impuesto->FormValue == $this->impuesto->CurrentValue && is_numeric(ew_StrToFloat($this->impuesto->CurrentValue)))
			$this->impuesto->CurrentValue = ew_StrToFloat($this->impuesto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->monto_total->FormValue == $this->monto_total->CurrentValue && is_numeric(ew_StrToFloat($this->monto_total->CurrentValue)))
			$this->monto_total->CurrentValue = ew_StrToFloat($this->monto_total->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `id_plan`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `planes`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_plan, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_plan->EditValue = $arwrk;

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
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `id_empleado`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `empleados`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_empleado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_empleado->EditValue = $arwrk;

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

			// Edit refer script
			// id_empresa

			$this->id_empresa->HrefValue = "";

			// tipo
			$this->tipo->HrefValue = "";

			// numero
			$this->numero->HrefValue = "";

			// fecha
			$this->fecha->HrefValue = "";

			// id_plan
			$this->id_plan->HrefValue = "";

			// fecha_desde
			$this->fecha_desde->HrefValue = "";

			// fecha_hasta
			$this->fecha_hasta->HrefValue = "";

			// id_empleado
			$this->id_empleado->HrefValue = "";

			// monto
			$this->monto->HrefValue = "";

			// descuento
			$this->descuento->HrefValue = "";

			// sub_total
			$this->sub_total->HrefValue = "";

			// impuesto
			$this->impuesto->HrefValue = "";

			// monto_total
			$this->monto_total->HrefValue = "";

			// observaciones
			$this->observaciones->HrefValue = "";

			// estatus
			$this->estatus->HrefValue = "";
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
		if (!ew_CheckEuroDate($this->fecha->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fecha_desde->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_desde->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fecha_hasta->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_hasta->FldErrMsg());
		}
		if (!ew_CheckNumber($this->monto->FormValue)) {
			ew_AddMessage($gsFormError, $this->monto->FldErrMsg());
		}
		if (!ew_CheckNumber($this->descuento->FormValue)) {
			ew_AddMessage($gsFormError, $this->descuento->FldErrMsg());
		}
		if (!ew_CheckNumber($this->sub_total->FormValue)) {
			ew_AddMessage($gsFormError, $this->sub_total->FldErrMsg());
		}
		if (!ew_CheckNumber($this->impuesto->FormValue)) {
			ew_AddMessage($gsFormError, $this->impuesto->FldErrMsg());
		}
		if (!ew_CheckNumber($this->monto_total->FormValue)) {
			ew_AddMessage($gsFormError, $this->monto_total->FldErrMsg());
		}
		if (!ew_CheckInteger($this->estatus->FormValue)) {
			ew_AddMessage($gsFormError, $this->estatus->FldErrMsg());
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

			// tipo
			$this->tipo->SetDbValueDef($rsnew, $this->tipo->CurrentValue, NULL, $this->tipo->ReadOnly);

			// numero
			$this->numero->SetDbValueDef($rsnew, $this->numero->CurrentValue, NULL, $this->numero->ReadOnly);

			// fecha
			$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 7), NULL, $this->fecha->ReadOnly);

			// id_plan
			$this->id_plan->SetDbValueDef($rsnew, $this->id_plan->CurrentValue, NULL, $this->id_plan->ReadOnly);

			// fecha_desde
			$this->fecha_desde->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_desde->CurrentValue, 7), NULL, $this->fecha_desde->ReadOnly);

			// fecha_hasta
			$this->fecha_hasta->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_hasta->CurrentValue, 7), NULL, $this->fecha_hasta->ReadOnly);

			// id_empleado
			$this->id_empleado->SetDbValueDef($rsnew, $this->id_empleado->CurrentValue, NULL, $this->id_empleado->ReadOnly);

			// monto
			$this->monto->SetDbValueDef($rsnew, $this->monto->CurrentValue, NULL, $this->monto->ReadOnly);

			// descuento
			$this->descuento->SetDbValueDef($rsnew, $this->descuento->CurrentValue, NULL, $this->descuento->ReadOnly);

			// sub_total
			$this->sub_total->SetDbValueDef($rsnew, $this->sub_total->CurrentValue, NULL, $this->sub_total->ReadOnly);

			// impuesto
			$this->impuesto->SetDbValueDef($rsnew, $this->impuesto->CurrentValue, NULL, $this->impuesto->ReadOnly);

			// monto_total
			$this->monto_total->SetDbValueDef($rsnew, $this->monto_total->CurrentValue, NULL, $this->monto_total->ReadOnly);

			// observaciones
			$this->observaciones->SetDbValueDef($rsnew, $this->observaciones->CurrentValue, NULL, $this->observaciones->ReadOnly);

			// estatus
			$this->estatus->SetDbValueDef($rsnew, $this->estatus->CurrentValue, NULL, $this->estatus->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "contratos_list.php", "", $this->TableVar, TRUE);
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
if (!isset($contratos_edit)) $contratos_edit = new ccontratos_edit();

// Page init
$contratos_edit->Page_Init();

// Page main
$contratos_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contratos_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var contratos_edit = new ew_Page("contratos_edit");
contratos_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = contratos_edit.PageID; // For backward compatibility

// Form object
var fcontratosedit = new ew_Form("fcontratosedit");

// Validate form
fcontratosedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2($contratos->id_empresa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contratos->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_desde");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contratos->fecha_desde->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_hasta");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contratos->fecha_hasta->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_monto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contratos->monto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_descuento");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contratos->descuento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sub_total");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contratos->sub_total->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_impuesto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contratos->impuesto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_monto_total");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contratos->monto_total->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_estatus");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contratos->estatus->FldErrMsg()) ?>");

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
fcontratosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontratosedit.ValidateRequired = true;
<?php } else { ?>
fcontratosedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcontratosedit.Lists["x_id_empresa"] = {"LinkField":"x_id_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontratosedit.Lists["x_id_plan"] = {"LinkField":"x_id_plan","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontratosedit.Lists["x_id_empleado"] = {"LinkField":"x_id_empleado","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $contratos_edit->ShowPageHeader(); ?>
<?php
$contratos_edit->ShowMessage();
?>
<form name="fcontratosedit" id="fcontratosedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($contratos_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $contratos_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="contratos">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($contratos->id_empresa->Visible) { // id_empresa ?>
	<div id="r_id_empresa" class="form-group">
		<label id="elh_contratos_id_empresa" class="col-sm-2 control-label ewLabel"><?php echo $contratos->id_empresa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $contratos->id_empresa->CellAttributes() ?>>
<span id="el_contratos_id_empresa">
<?php
	$wrkonchange = trim(" " . @$contratos->id_empresa->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$contratos->id_empresa->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_empresa" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_id_empresa" id="sv_x_id_empresa" value="<?php echo $contratos->id_empresa->EditValue ?>" size="30"<?php echo $contratos->id_empresa->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_id_empresa" name="x_id_empresa" id="x_id_empresa" value="<?php echo ew_HtmlEncode($contratos->id_empresa->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id_empresa`, `nombre` AS `DispFld` FROM `empresas`";
$sWhereWrk = "`nombre` LIKE '{query_value}%'";

// Call Lookup selecting
$contratos->Lookup_Selecting($contratos->id_empresa, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_id_empresa" id="q_x_id_empresa" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fcontratosedit.CreateAutoSuggest("x_id_empresa", false);
</script>
</span>
<?php echo $contratos->id_empresa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($contratos->tipo->Visible) { // tipo ?>
	<div id="r_tipo" class="form-group">
		<label id="elh_contratos_tipo" for="x_tipo" class="col-sm-2 control-label ewLabel"><?php echo $contratos->tipo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $contratos->tipo->CellAttributes() ?>>
<span id="el_contratos_tipo">
<select data-field="x_tipo" id="x_tipo" name="x_tipo"<?php echo $contratos->tipo->EditAttributes() ?>>
<?php
if (is_array($contratos->tipo->EditValue)) {
	$arwrk = $contratos->tipo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contratos->tipo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</span>
<?php echo $contratos->tipo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($contratos->numero->Visible) { // numero ?>
	<div id="r_numero" class="form-group">
		<label id="elh_contratos_numero" for="x_numero" class="col-sm-2 control-label ewLabel"><?php echo $contratos->numero->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $contratos->numero->CellAttributes() ?>>
<span id="el_contratos_numero">
<input type="text" data-field="x_numero" name="x_numero" id="x_numero" size="10" maxlength="20" value="<?php echo $contratos->numero->EditValue ?>"<?php echo $contratos->numero->EditAttributes() ?>>
</span>
<?php echo $contratos->numero->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($contratos->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label id="elh_contratos_fecha" for="x_fecha" class="col-sm-2 control-label ewLabel"><?php echo $contratos->fecha->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $contratos->fecha->CellAttributes() ?>>
<span id="el_contratos_fecha">
<input type="text" data-field="x_fecha" name="x_fecha" id="x_fecha" size="10" value="<?php echo $contratos->fecha->EditValue ?>"<?php echo $contratos->fecha->EditAttributes() ?>>
<?php if (!$contratos->fecha->ReadOnly && !$contratos->fecha->Disabled && !isset($contratos->fecha->EditAttrs["readonly"]) && !isset($contratos->fecha->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fcontratosedit", "x_fecha", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $contratos->fecha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($contratos->id_plan->Visible) { // id_plan ?>
	<div id="r_id_plan" class="form-group">
		<label id="elh_contratos_id_plan" for="x_id_plan" class="col-sm-2 control-label ewLabel"><?php echo $contratos->id_plan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $contratos->id_plan->CellAttributes() ?>>
<span id="el_contratos_id_plan">
<select data-field="x_id_plan" id="x_id_plan" name="x_id_plan"<?php echo $contratos->id_plan->EditAttributes() ?>>
<?php
if (is_array($contratos->id_plan->EditValue)) {
	$arwrk = $contratos->id_plan->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contratos->id_plan->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontratosedit.Lists["x_id_plan"].Options = <?php echo (is_array($contratos->id_plan->EditValue)) ? ew_ArrayToJson($contratos->id_plan->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $contratos->id_plan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($contratos->fecha_desde->Visible) { // fecha_desde ?>
	<div id="r_fecha_desde" class="form-group">
		<label id="elh_contratos_fecha_desde" for="x_fecha_desde" class="col-sm-2 control-label ewLabel"><?php echo $contratos->fecha_desde->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $contratos->fecha_desde->CellAttributes() ?>>
<span id="el_contratos_fecha_desde">
<input type="text" data-field="x_fecha_desde" name="x_fecha_desde" id="x_fecha_desde" size="10" value="<?php echo $contratos->fecha_desde->EditValue ?>"<?php echo $contratos->fecha_desde->EditAttributes() ?>>
<?php if (!$contratos->fecha_desde->ReadOnly && !$contratos->fecha_desde->Disabled && !isset($contratos->fecha_desde->EditAttrs["readonly"]) && !isset($contratos->fecha_desde->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fcontratosedit", "x_fecha_desde", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $contratos->fecha_desde->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($contratos->fecha_hasta->Visible) { // fecha_hasta ?>
	<div id="r_fecha_hasta" class="form-group">
		<label id="elh_contratos_fecha_hasta" for="x_fecha_hasta" class="col-sm-2 control-label ewLabel"><?php echo $contratos->fecha_hasta->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $contratos->fecha_hasta->CellAttributes() ?>>
<span id="el_contratos_fecha_hasta">
<input type="text" data-field="x_fecha_hasta" name="x_fecha_hasta" id="x_fecha_hasta" size="10" value="<?php echo $contratos->fecha_hasta->EditValue ?>"<?php echo $contratos->fecha_hasta->EditAttributes() ?>>
<?php if (!$contratos->fecha_hasta->ReadOnly && !$contratos->fecha_hasta->Disabled && !isset($contratos->fecha_hasta->EditAttrs["readonly"]) && !isset($contratos->fecha_hasta->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fcontratosedit", "x_fecha_hasta", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $contratos->fecha_hasta->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($contratos->id_empleado->Visible) { // id_empleado ?>
	<div id="r_id_empleado" class="form-group">
		<label id="elh_contratos_id_empleado" for="x_id_empleado" class="col-sm-2 control-label ewLabel"><?php echo $contratos->id_empleado->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $contratos->id_empleado->CellAttributes() ?>>
<span id="el_contratos_id_empleado">
<select data-field="x_id_empleado" id="x_id_empleado" name="x_id_empleado"<?php echo $contratos->id_empleado->EditAttributes() ?>>
<?php
if (is_array($contratos->id_empleado->EditValue)) {
	$arwrk = $contratos->id_empleado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contratos->id_empleado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontratosedit.Lists["x_id_empleado"].Options = <?php echo (is_array($contratos->id_empleado->EditValue)) ? ew_ArrayToJson($contratos->id_empleado->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $contratos->id_empleado->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($contratos->monto->Visible) { // monto ?>
	<div id="r_monto" class="form-group">
		<label id="elh_contratos_monto" for="x_monto" class="col-sm-2 control-label ewLabel"><?php echo $contratos->monto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $contratos->monto->CellAttributes() ?>>
<span id="el_contratos_monto">
<input type="text" data-field="x_monto" name="x_monto" id="x_monto" size="10" value="<?php echo $contratos->monto->EditValue ?>"<?php echo $contratos->monto->EditAttributes() ?>>
</span>
<?php echo $contratos->monto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($contratos->descuento->Visible) { // descuento ?>
	<div id="r_descuento" class="form-group">
		<label id="elh_contratos_descuento" for="x_descuento" class="col-sm-2 control-label ewLabel"><?php echo $contratos->descuento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $contratos->descuento->CellAttributes() ?>>
<span id="el_contratos_descuento">
<input type="text" data-field="x_descuento" name="x_descuento" id="x_descuento" size="10" value="<?php echo $contratos->descuento->EditValue ?>"<?php echo $contratos->descuento->EditAttributes() ?>>
</span>
<?php echo $contratos->descuento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($contratos->sub_total->Visible) { // sub_total ?>
	<div id="r_sub_total" class="form-group">
		<label id="elh_contratos_sub_total" for="x_sub_total" class="col-sm-2 control-label ewLabel"><?php echo $contratos->sub_total->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $contratos->sub_total->CellAttributes() ?>>
<span id="el_contratos_sub_total">
<input type="text" data-field="x_sub_total" name="x_sub_total" id="x_sub_total" size="10" value="<?php echo $contratos->sub_total->EditValue ?>"<?php echo $contratos->sub_total->EditAttributes() ?>>
</span>
<?php echo $contratos->sub_total->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($contratos->impuesto->Visible) { // impuesto ?>
	<div id="r_impuesto" class="form-group">
		<label id="elh_contratos_impuesto" for="x_impuesto" class="col-sm-2 control-label ewLabel"><?php echo $contratos->impuesto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $contratos->impuesto->CellAttributes() ?>>
<span id="el_contratos_impuesto">
<input type="text" data-field="x_impuesto" name="x_impuesto" id="x_impuesto" size="10" value="<?php echo $contratos->impuesto->EditValue ?>"<?php echo $contratos->impuesto->EditAttributes() ?>>
</span>
<?php echo $contratos->impuesto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($contratos->monto_total->Visible) { // monto_total ?>
	<div id="r_monto_total" class="form-group">
		<label id="elh_contratos_monto_total" for="x_monto_total" class="col-sm-2 control-label ewLabel"><?php echo $contratos->monto_total->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $contratos->monto_total->CellAttributes() ?>>
<span id="el_contratos_monto_total">
<input type="text" data-field="x_monto_total" name="x_monto_total" id="x_monto_total" size="10" value="<?php echo $contratos->monto_total->EditValue ?>"<?php echo $contratos->monto_total->EditAttributes() ?>>
</span>
<?php echo $contratos->monto_total->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($contratos->observaciones->Visible) { // observaciones ?>
	<div id="r_observaciones" class="form-group">
		<label id="elh_contratos_observaciones" for="x_observaciones" class="col-sm-2 control-label ewLabel"><?php echo $contratos->observaciones->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $contratos->observaciones->CellAttributes() ?>>
<span id="el_contratos_observaciones">
<textarea data-field="x_observaciones" name="x_observaciones" id="x_observaciones" cols="30" rows="2"<?php echo $contratos->observaciones->EditAttributes() ?>><?php echo $contratos->observaciones->EditValue ?></textarea>
</span>
<?php echo $contratos->observaciones->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($contratos->estatus->Visible) { // estatus ?>
	<div id="r_estatus" class="form-group">
		<label id="elh_contratos_estatus" for="x_estatus" class="col-sm-2 control-label ewLabel"><?php echo $contratos->estatus->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $contratos->estatus->CellAttributes() ?>>
<span id="el_contratos_estatus">
<input type="text" data-field="x_estatus" name="x_estatus" id="x_estatus" size="30" value="<?php echo $contratos->estatus->EditValue ?>"<?php echo $contratos->estatus->EditAttributes() ?>>
</span>
<?php echo $contratos->estatus->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-field="x_id_contrato" name="x_id_contrato" id="x_id_contrato" value="<?php echo ew_HtmlEncode($contratos->id_contrato->CurrentValue) ?>">
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fcontratosedit.Init();
</script>
<?php
$contratos_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$contratos_edit->Page_Terminate();
?>
