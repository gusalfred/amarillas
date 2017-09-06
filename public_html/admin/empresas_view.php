<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "empresas_info.php" ?>
<?php include_once "empleados_info.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$empresas_view = NULL; // Initialize page object first

class cempresas_view extends cempresas {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'empresas';

	// Page object name
	var $PageObjName = 'empresas_view';

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

		// Table object (empresas)
		if (!isset($GLOBALS["empresas"]) || get_class($GLOBALS["empresas"]) == "cempresas") {
			$GLOBALS["empresas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["empresas"];
		}
		$KeyUrl = "";
		if (@$_GET["id_empresa"] <> "") {
			$this->RecKey["id_empresa"] = $_GET["id_empresa"];
			$KeyUrl .= "&amp;id_empresa=" . urlencode($this->RecKey["id_empresa"]);
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
			define("EW_TABLE_NAME", 'empresas', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("empresas_list.php"));
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
		global $EW_EXPORT, $empresas;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($empresas);
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
			if (@$_GET["id_empresa"] <> "") {
				$this->id_empresa->setQueryStringValue($_GET["id_empresa"]);
				$this->RecKey["id_empresa"] = $this->id_empresa->QueryStringValue;
			} elseif (@$_POST["id_empresa"] <> "") {
				$this->id_empresa->setFormValue($_POST["id_empresa"]);
				$this->RecKey["id_empresa"] = $this->id_empresa->FormValue;
			} else {
				$sReturnUrl = "empresas_list.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "empresas_list.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "empresas_list.php"; // Not page request, return to list
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
		$this->id_empresa->setDbValue($rs->fields('id_empresa'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->documento_tipo->setDbValue($rs->fields('documento_tipo'));
		$this->documento_numero->setDbValue($rs->fields('documento_numero'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->web->setDbValue($rs->fields('web'));
		$this->logo->Upload->DbValue = $rs->fields('logo');
		$this->logo->CurrentValue = $this->logo->Upload->DbValue;
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->meta_descripcion->setDbValue($rs->fields('meta_descripcion'));
		$this->meta_palabras_clave->setDbValue($rs->fields('meta_palabras_clave'));
		$this->slug->setDbValue($rs->fields('slug'));
		$this->comentarios->setDbValue($rs->fields('comentarios'));
		$this->estrellas->setDbValue($rs->fields('estrellas'));
		$this->contrato->setDbValue($rs->fields('contrato'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_empresa->DbValue = $row['id_empresa'];
		$this->nombre->DbValue = $row['nombre'];
		$this->documento_tipo->DbValue = $row['documento_tipo'];
		$this->documento_numero->DbValue = $row['documento_numero'];
		$this->_email->DbValue = $row['email'];
		$this->web->DbValue = $row['web'];
		$this->logo->Upload->DbValue = $row['logo'];
		$this->descripcion->DbValue = $row['descripcion'];
		$this->meta_descripcion->DbValue = $row['meta_descripcion'];
		$this->meta_palabras_clave->DbValue = $row['meta_palabras_clave'];
		$this->slug->DbValue = $row['slug'];
		$this->comentarios->DbValue = $row['comentarios'];
		$this->estrellas->DbValue = $row['estrellas'];
		$this->contrato->DbValue = $row['contrato'];
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
		if ($this->estrellas->FormValue == $this->estrellas->CurrentValue && is_numeric(ew_StrToFloat($this->estrellas->CurrentValue)))
			$this->estrellas->CurrentValue = ew_StrToFloat($this->estrellas->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

			// estrellas
			$this->estrellas->LinkCustomAttributes = "";
			$this->estrellas->HrefValue = "";
			$this->estrellas->TooltipValue = "";

			// contrato
			$this->contrato->LinkCustomAttributes = "";
			$this->contrato->HrefValue = "";
			$this->contrato->TooltipValue = "";
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
		$Breadcrumb->Add("list", $this->TableVar, "empresas_list.php", "", $this->TableVar, TRUE);
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
if (!isset($empresas_view)) $empresas_view = new cempresas_view();

// Page init
$empresas_view->Page_Init();

// Page main
$empresas_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empresas_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var empresas_view = new ew_Page("empresas_view");
empresas_view.PageID = "view"; // Page ID
var EW_PAGE_ID = empresas_view.PageID; // For backward compatibility

// Form object
var fempresasview = new ew_Form("fempresasview");

// Form_CustomValidate event
fempresasview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempresasview.ValidateRequired = true;
<?php } else { ?>
fempresasview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">
$(document).ready(function() {
$('.ewMessageDialog').after('<table width="100%"><tr><td width="50%" valign="top"><div id="col1"></div></td><td width="50%" valign="top"><div id="col2"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></div></td></tr></table>');
$('#col1').append( $('#fempresasview') );
$('#col2').load("empresas_detalles.php?idm=<?= $_GET['id_empresa'] ?>&tab=<?= @$_GET['tab'] ?>");
$('#r_nombre td').first().css( "width", "30%" );
});

function reload_detalles(tab=1){
	$('#col2').html('<i class="fa fa-cog fa-spin fa-3x fa-fw"></i>');
	$('#col2').load("empresas_detalles.php?idm=<?= $_GET['id_empresa'] ?>&tab="+tab);
}
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $empresas_view->ExportOptions->Render("body") ?>
<?php
	foreach ($empresas_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $empresas_view->ShowPageHeader(); ?>
<?php
$empresas_view->ShowMessage();
?>
<form name="fempresasview" id="fempresasview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($empresas_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $empresas_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="empresas">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($empresas->nombre->Visible) { // nombre ?>
	<tr id="r_nombre">
		<td><span id="elh_empresas_nombre"><?php echo $empresas->nombre->FldCaption() ?></span></td>
		<td<?php echo $empresas->nombre->CellAttributes() ?>>
<span id="el_empresas_nombre">
<span<?php echo $empresas->nombre->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($empresas->nombre->ViewValue)) && $empresas->nombre->LinkAttributes() <> "") { ?>
<a<?php echo $empresas->nombre->LinkAttributes() ?>><?php echo $empresas->nombre->ViewValue ?></a>
<?php } else { ?>
<?php echo $empresas->nombre->ViewValue ?>
<?php } ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empresas->documento_tipo->Visible) { // documento_tipo ?>
	<tr id="r_documento_tipo">
		<td><span id="elh_empresas_documento_tipo"><?php echo $empresas->documento_tipo->FldCaption() ?></span></td>
		<td<?php echo $empresas->documento_tipo->CellAttributes() ?>>
<span id="el_empresas_documento_tipo">
<span<?php echo $empresas->documento_tipo->ViewAttributes() ?>>
<?php echo $empresas->documento_tipo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empresas->documento_numero->Visible) { // documento_numero ?>
	<tr id="r_documento_numero">
		<td><span id="elh_empresas_documento_numero"><?php echo $empresas->documento_numero->FldCaption() ?></span></td>
		<td<?php echo $empresas->documento_numero->CellAttributes() ?>>
<span id="el_empresas_documento_numero">
<span<?php echo $empresas->documento_numero->ViewAttributes() ?>>
<?php echo $empresas->documento_numero->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empresas->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_empresas__email"><?php echo $empresas->_email->FldCaption() ?></span></td>
		<td<?php echo $empresas->_email->CellAttributes() ?>>
<span id="el_empresas__email">
<span<?php echo $empresas->_email->ViewAttributes() ?>>
<?php echo $empresas->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empresas->web->Visible) { // web ?>
	<tr id="r_web">
		<td><span id="elh_empresas_web"><?php echo $empresas->web->FldCaption() ?></span></td>
		<td<?php echo $empresas->web->CellAttributes() ?>>
<span id="el_empresas_web">
<span<?php echo $empresas->web->ViewAttributes() ?>>
<?php echo $empresas->web->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empresas->logo->Visible) { // logo ?>
	<tr id="r_logo">
		<td><span id="elh_empresas_logo"><?php echo $empresas->logo->FldCaption() ?></span></td>
		<td<?php echo $empresas->logo->CellAttributes() ?>>
<span id="el_empresas_logo">
<span>
<?php echo ew_GetFileViewTag($empresas->logo, $empresas->logo->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empresas->descripcion->Visible) { // descripcion ?>
	<tr id="r_descripcion">
		<td><span id="elh_empresas_descripcion"><?php echo $empresas->descripcion->FldCaption() ?></span></td>
		<td<?php echo $empresas->descripcion->CellAttributes() ?>>
<span id="el_empresas_descripcion">
<span<?php echo $empresas->descripcion->ViewAttributes() ?>>
<?php echo $empresas->descripcion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empresas->meta_descripcion->Visible) { // meta_descripcion ?>
	<tr id="r_meta_descripcion">
		<td><span id="elh_empresas_meta_descripcion"><?php echo $empresas->meta_descripcion->FldCaption() ?></span></td>
		<td<?php echo $empresas->meta_descripcion->CellAttributes() ?>>
<span id="el_empresas_meta_descripcion">
<span<?php echo $empresas->meta_descripcion->ViewAttributes() ?>>
<?php echo $empresas->meta_descripcion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empresas->meta_palabras_clave->Visible) { // meta_palabras_clave ?>
	<tr id="r_meta_palabras_clave">
		<td><span id="elh_empresas_meta_palabras_clave"><?php echo $empresas->meta_palabras_clave->FldCaption() ?></span></td>
		<td<?php echo $empresas->meta_palabras_clave->CellAttributes() ?>>
<span id="el_empresas_meta_palabras_clave">
<span<?php echo $empresas->meta_palabras_clave->ViewAttributes() ?>>
<?php echo $empresas->meta_palabras_clave->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empresas->estrellas->Visible) { // estrellas ?>
	<tr id="r_estrellas">
		<td><span id="elh_empresas_estrellas"><?php echo $empresas->estrellas->FldCaption() ?></span></td>
		<td<?php echo $empresas->estrellas->CellAttributes() ?>>
<span id="el_empresas_estrellas">
<span<?php echo $empresas->estrellas->ViewAttributes() ?>>
<?php echo $empresas->estrellas->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($empresas->contrato->Visible) { // contrato ?>
	<tr id="r_contrato">
		<td><span id="elh_empresas_contrato"><?php echo $empresas->contrato->FldCaption() ?></span></td>
		<td<?php echo $empresas->contrato->CellAttributes() ?>>
<span id="el_empresas_contrato">
<span<?php echo $empresas->contrato->ViewAttributes() ?>>
<?php echo $empresas->contrato->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fempresasview.Init();
</script>
<?php
$empresas_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empresas_view->Page_Terminate();
?>
