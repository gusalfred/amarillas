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

$contratos_view = NULL; // Initialize page object first

class ccontratos_view extends ccontratos {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'contratos';

	// Page object name
	var $PageObjName = 'contratos_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["id_contrato"] <> "") {
			$this->RecKey["id_contrato"] = $_GET["id_contrato"];
			$KeyUrl .= "&amp;id_contrato=" . urlencode($this->RecKey["id_contrato"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (empleados)
		if (!isset($GLOBALS['empleados'])) $GLOBALS['empleados'] = new cempleados();

		// User table object (empleados)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cempleados();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'contratos', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("contratos_list.php"));
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id_contrato"] <> "") {
				$this->id_contrato->setQueryStringValue($_GET["id_contrato"]);
				$this->RecKey["id_contrato"] = $this->id_contrato->QueryStringValue;
			} elseif (@$_POST["id_contrato"] <> "") {
				$this->id_contrato->setFormValue($_POST["id_contrato"]);
				$this->RecKey["id_contrato"] = $this->id_contrato->FormValue;
			} else {
				$sReturnUrl = "contratos_list.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "contratos_list.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "contratos_list.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "contratos_list.php", "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($contratos_view)) $contratos_view = new ccontratos_view();

// Page init
$contratos_view->Page_Init();

// Page main
$contratos_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contratos_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var contratos_view = new ew_Page("contratos_view");
contratos_view.PageID = "view"; // Page ID
var EW_PAGE_ID = contratos_view.PageID; // For backward compatibility

// Form object
var fcontratosview = new ew_Form("fcontratosview");

// Form_CustomValidate event
fcontratosview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontratosview.ValidateRequired = true;
<?php } else { ?>
fcontratosview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcontratosview.Lists["x_id_empresa"] = {"LinkField":"x_id_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontratosview.Lists["x_id_plan"] = {"LinkField":"x_id_plan","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontratosview.Lists["x_id_empleado"] = {"LinkField":"x_id_empleado","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $contratos_view->ExportOptions->Render("body") ?>
<?php
	foreach ($contratos_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $contratos_view->ShowPageHeader(); ?>
<?php
$contratos_view->ShowMessage();
?>
<form name="fcontratosview" id="fcontratosview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($contratos_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $contratos_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="contratos">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($contratos->id_empresa->Visible) { // id_empresa ?>
	<tr id="r_id_empresa">
		<td><span id="elh_contratos_id_empresa"><?php echo $contratos->id_empresa->FldCaption() ?></span></td>
		<td<?php echo $contratos->id_empresa->CellAttributes() ?>>
<span id="el_contratos_id_empresa">
<span<?php echo $contratos->id_empresa->ViewAttributes() ?>>
<?php echo $contratos->id_empresa->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contratos->tipo->Visible) { // tipo ?>
	<tr id="r_tipo">
		<td><span id="elh_contratos_tipo"><?php echo $contratos->tipo->FldCaption() ?></span></td>
		<td<?php echo $contratos->tipo->CellAttributes() ?>>
<span id="el_contratos_tipo">
<span<?php echo $contratos->tipo->ViewAttributes() ?>>
<?php echo $contratos->tipo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contratos->numero->Visible) { // numero ?>
	<tr id="r_numero">
		<td><span id="elh_contratos_numero"><?php echo $contratos->numero->FldCaption() ?></span></td>
		<td<?php echo $contratos->numero->CellAttributes() ?>>
<span id="el_contratos_numero">
<span<?php echo $contratos->numero->ViewAttributes() ?>>
<?php echo $contratos->numero->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contratos->fecha->Visible) { // fecha ?>
	<tr id="r_fecha">
		<td><span id="elh_contratos_fecha"><?php echo $contratos->fecha->FldCaption() ?></span></td>
		<td<?php echo $contratos->fecha->CellAttributes() ?>>
<span id="el_contratos_fecha">
<span<?php echo $contratos->fecha->ViewAttributes() ?>>
<?php echo $contratos->fecha->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contratos->id_plan->Visible) { // id_plan ?>
	<tr id="r_id_plan">
		<td><span id="elh_contratos_id_plan"><?php echo $contratos->id_plan->FldCaption() ?></span></td>
		<td<?php echo $contratos->id_plan->CellAttributes() ?>>
<span id="el_contratos_id_plan">
<span<?php echo $contratos->id_plan->ViewAttributes() ?>>
<?php echo $contratos->id_plan->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contratos->fecha_desde->Visible) { // fecha_desde ?>
	<tr id="r_fecha_desde">
		<td><span id="elh_contratos_fecha_desde"><?php echo $contratos->fecha_desde->FldCaption() ?></span></td>
		<td<?php echo $contratos->fecha_desde->CellAttributes() ?>>
<span id="el_contratos_fecha_desde">
<span<?php echo $contratos->fecha_desde->ViewAttributes() ?>>
<?php echo $contratos->fecha_desde->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contratos->fecha_hasta->Visible) { // fecha_hasta ?>
	<tr id="r_fecha_hasta">
		<td><span id="elh_contratos_fecha_hasta"><?php echo $contratos->fecha_hasta->FldCaption() ?></span></td>
		<td<?php echo $contratos->fecha_hasta->CellAttributes() ?>>
<span id="el_contratos_fecha_hasta">
<span<?php echo $contratos->fecha_hasta->ViewAttributes() ?>>
<?php echo $contratos->fecha_hasta->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contratos->id_empleado->Visible) { // id_empleado ?>
	<tr id="r_id_empleado">
		<td><span id="elh_contratos_id_empleado"><?php echo $contratos->id_empleado->FldCaption() ?></span></td>
		<td<?php echo $contratos->id_empleado->CellAttributes() ?>>
<span id="el_contratos_id_empleado">
<span<?php echo $contratos->id_empleado->ViewAttributes() ?>>
<?php echo $contratos->id_empleado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contratos->monto->Visible) { // monto ?>
	<tr id="r_monto">
		<td><span id="elh_contratos_monto"><?php echo $contratos->monto->FldCaption() ?></span></td>
		<td<?php echo $contratos->monto->CellAttributes() ?>>
<span id="el_contratos_monto">
<span<?php echo $contratos->monto->ViewAttributes() ?>>
<?php echo $contratos->monto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contratos->descuento->Visible) { // descuento ?>
	<tr id="r_descuento">
		<td><span id="elh_contratos_descuento"><?php echo $contratos->descuento->FldCaption() ?></span></td>
		<td<?php echo $contratos->descuento->CellAttributes() ?>>
<span id="el_contratos_descuento">
<span<?php echo $contratos->descuento->ViewAttributes() ?>>
<?php echo $contratos->descuento->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contratos->sub_total->Visible) { // sub_total ?>
	<tr id="r_sub_total">
		<td><span id="elh_contratos_sub_total"><?php echo $contratos->sub_total->FldCaption() ?></span></td>
		<td<?php echo $contratos->sub_total->CellAttributes() ?>>
<span id="el_contratos_sub_total">
<span<?php echo $contratos->sub_total->ViewAttributes() ?>>
<?php echo $contratos->sub_total->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contratos->impuesto->Visible) { // impuesto ?>
	<tr id="r_impuesto">
		<td><span id="elh_contratos_impuesto"><?php echo $contratos->impuesto->FldCaption() ?></span></td>
		<td<?php echo $contratos->impuesto->CellAttributes() ?>>
<span id="el_contratos_impuesto">
<span<?php echo $contratos->impuesto->ViewAttributes() ?>>
<?php echo $contratos->impuesto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contratos->monto_total->Visible) { // monto_total ?>
	<tr id="r_monto_total">
		<td><span id="elh_contratos_monto_total"><?php echo $contratos->monto_total->FldCaption() ?></span></td>
		<td<?php echo $contratos->monto_total->CellAttributes() ?>>
<span id="el_contratos_monto_total">
<span<?php echo $contratos->monto_total->ViewAttributes() ?>>
<?php echo $contratos->monto_total->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contratos->observaciones->Visible) { // observaciones ?>
	<tr id="r_observaciones">
		<td><span id="elh_contratos_observaciones"><?php echo $contratos->observaciones->FldCaption() ?></span></td>
		<td<?php echo $contratos->observaciones->CellAttributes() ?>>
<span id="el_contratos_observaciones">
<span<?php echo $contratos->observaciones->ViewAttributes() ?>>
<?php echo $contratos->observaciones->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contratos->estatus->Visible) { // estatus ?>
	<tr id="r_estatus">
		<td><span id="elh_contratos_estatus"><?php echo $contratos->estatus->FldCaption() ?></span></td>
		<td<?php echo $contratos->estatus->CellAttributes() ?>>
<span id="el_contratos_estatus">
<span<?php echo $contratos->estatus->ViewAttributes() ?>>
<?php echo $contratos->estatus->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fcontratosview.Init();
</script>
<?php
$contratos_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$contratos_view->Page_Terminate();
?>
