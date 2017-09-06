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

$empresas_add = NULL; // Initialize page object first

class cempresas_add extends cempresas {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'empresas';

	// Page object name
	var $PageObjName = 'empresas_add';

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

		// Table object (empresas)
		if (!isset($GLOBALS["empresas"]) || get_class($GLOBALS["empresas"]) == "cempresas") {
			$GLOBALS["empresas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["empresas"];
		}

		// Table object (empleados)
		if (!isset($GLOBALS['empleados'])) $GLOBALS['empleados'] = new cempleados();

		// User table object (empleados)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cempleados();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'empresas', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("empresas_list.php"));
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id_empresa"] != "") {
				$this->id_empresa->setQueryStringValue($_GET["id_empresa"]);
				$this->setKey("id_empresa", $this->id_empresa->CurrentValue); // Set up key
			} else {
				$this->setKey("id_empresa", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("empresas_list.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "empresas_view.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->logo->Upload->Index = $objForm->Index;
		$this->logo->Upload->UploadFile();
		$this->logo->CurrentValue = $this->logo->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->nombre->CurrentValue = NULL;
		$this->nombre->OldValue = $this->nombre->CurrentValue;
		$this->documento_tipo->CurrentValue = NULL;
		$this->documento_tipo->OldValue = $this->documento_tipo->CurrentValue;
		$this->documento_numero->CurrentValue = NULL;
		$this->documento_numero->OldValue = $this->documento_numero->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->web->CurrentValue = NULL;
		$this->web->OldValue = $this->web->CurrentValue;
		$this->logo->Upload->DbValue = NULL;
		$this->logo->OldValue = $this->logo->Upload->DbValue;
		$this->logo->CurrentValue = NULL; // Clear file related field
		$this->descripcion->CurrentValue = NULL;
		$this->descripcion->OldValue = $this->descripcion->CurrentValue;
		$this->meta_descripcion->CurrentValue = NULL;
		$this->meta_descripcion->OldValue = $this->meta_descripcion->CurrentValue;
		$this->meta_palabras_clave->CurrentValue = NULL;
		$this->meta_palabras_clave->OldValue = $this->meta_palabras_clave->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->documento_tipo->FldIsDetailKey) {
			$this->documento_tipo->setFormValue($objForm->GetValue("x_documento_tipo"));
		}
		if (!$this->documento_numero->FldIsDetailKey) {
			$this->documento_numero->setFormValue($objForm->GetValue("x_documento_numero"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->web->FldIsDetailKey) {
			$this->web->setFormValue($objForm->GetValue("x_web"));
		}
		if (!$this->descripcion->FldIsDetailKey) {
			$this->descripcion->setFormValue($objForm->GetValue("x_descripcion"));
		}
		if (!$this->meta_descripcion->FldIsDetailKey) {
			$this->meta_descripcion->setFormValue($objForm->GetValue("x_meta_descripcion"));
		}
		if (!$this->meta_palabras_clave->FldIsDetailKey) {
			$this->meta_palabras_clave->setFormValue($objForm->GetValue("x_meta_palabras_clave"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->documento_tipo->CurrentValue = $this->documento_tipo->FormValue;
		$this->documento_numero->CurrentValue = $this->documento_numero->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->web->CurrentValue = $this->web->FormValue;
		$this->descripcion->CurrentValue = $this->descripcion->FormValue;
		$this->meta_descripcion->CurrentValue = $this->meta_descripcion->FormValue;
		$this->meta_palabras_clave->CurrentValue = $this->meta_palabras_clave->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_empresa")) <> "")
			$this->id_empresa->CurrentValue = $this->getKey("id_empresa"); // id_empresa
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->logo);

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

			// Edit refer script
			// nombre

			if (!ew_Empty($this->id_empresa->CurrentValue)) {
				$this->nombre->HrefValue = "empresas_view.php?id_empresa=" . ((!empty($this->id_empresa->EditValue)) ? $this->id_empresa->EditValue : $this->id_empresa->CurrentValue); // Add prefix/suffix
				$this->nombre->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->nombre->HrefValue = ew_ConvertFullUrl($this->nombre->HrefValue);
			} else {
				$this->nombre->HrefValue = "";
			}

			// documento_tipo
			$this->documento_tipo->HrefValue = "";

			// documento_numero
			$this->documento_numero->HrefValue = "";

			// email
			$this->_email->HrefValue = "";

			// web
			$this->web->HrefValue = "";

			// logo
			$this->logo->UploadPath = '../uploads/logos/';
			if (!ew_Empty($this->logo->Upload->DbValue)) {
				$this->logo->HrefValue = ew_UploadPathEx(FALSE, $this->logo->UploadPath) . $this->logo->Upload->DbValue; // Add prefix/suffix
				$this->logo->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->logo->HrefValue = ew_ConvertFullUrl($this->logo->HrefValue);
			} else {
				$this->logo->HrefValue = "";
			}
			$this->logo->HrefValue2 = $this->logo->UploadPath . $this->logo->Upload->DbValue;

			// descripcion
			$this->descripcion->HrefValue = "";

			// meta_descripcion
			$this->meta_descripcion->HrefValue = "";

			// meta_palabras_clave
			$this->meta_palabras_clave->HrefValue = "";
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
			$this->logo->OldUploadPath = '../uploads/logos/';
			$this->logo->UploadPath = $this->logo->OldUploadPath;
		}
		$rsnew = array();

		// nombre
		$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, NULL, FALSE);

		// documento_tipo
		$this->documento_tipo->SetDbValueDef($rsnew, $this->documento_tipo->CurrentValue, NULL, FALSE);

		// documento_numero
		$this->documento_numero->SetDbValueDef($rsnew, $this->documento_numero->CurrentValue, NULL, FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, FALSE);

		// web
		$this->web->SetDbValueDef($rsnew, $this->web->CurrentValue, NULL, FALSE);

		// logo
		if (!$this->logo->Upload->KeepFile) {
			$this->logo->Upload->DbValue = ""; // No need to delete old file
			if ($this->logo->Upload->FileName == "") {
				$rsnew['logo'] = NULL;
			} else {
				$rsnew['logo'] = $this->logo->Upload->FileName;
			}
		}

		// descripcion
		$this->descripcion->SetDbValueDef($rsnew, $this->descripcion->CurrentValue, NULL, FALSE);

		// meta_descripcion
		$this->meta_descripcion->SetDbValueDef($rsnew, $this->meta_descripcion->CurrentValue, NULL, FALSE);

		// meta_palabras_clave
		$this->meta_palabras_clave->SetDbValueDef($rsnew, $this->meta_palabras_clave->CurrentValue, NULL, FALSE);
		if (!$this->logo->Upload->KeepFile) {
			$this->logo->UploadPath = '../uploads/logos/';
			if (!ew_Empty($this->logo->Upload->Value)) {
				if ($this->logo->Upload->FileName == $this->logo->Upload->DbValue) { // Overwrite if same file name
					$this->logo->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['logo'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->logo->UploadPath), $rsnew['logo']); // Get new file name
				}
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if (!$this->logo->Upload->KeepFile) {
					if (!ew_Empty($this->logo->Upload->Value)) {
						$this->logo->Upload->SaveToFile($this->logo->UploadPath, $rsnew['logo'], TRUE);
					}
					if ($this->logo->Upload->DbValue <> "")
						@unlink(ew_UploadPathEx(TRUE, $this->logo->OldUploadPath) . $this->logo->Upload->DbValue);
				}
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->id_empresa->setDbValue($conn->Insert_ID());
			$rsnew['id_empresa'] = $this->id_empresa->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// logo
		ew_CleanUploadTempPath($this->logo, $this->logo->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "empresas_list.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($empresas_add)) $empresas_add = new cempresas_add();

// Page init
$empresas_add->Page_Init();

// Page main
$empresas_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empresas_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var empresas_add = new ew_Page("empresas_add");
empresas_add.PageID = "add"; // Page ID
var EW_PAGE_ID = empresas_add.PageID; // For backward compatibility

// Form object
var fempresasadd = new ew_Form("fempresasadd");

// Validate form
fempresasadd.Validate = function() {
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
fempresasadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempresasadd.ValidateRequired = true;
<?php } else { ?>
fempresasadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
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
<?php $empresas_add->ShowPageHeader(); ?>
<?php
$empresas_add->ShowMessage();
?>
<form name="fempresasadd" id="fempresasadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($empresas_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $empresas_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="empresas">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($empresas->nombre->Visible) { // nombre ?>
	<div id="r_nombre" class="form-group">
		<label id="elh_empresas_nombre" for="x_nombre" class="col-sm-2 control-label ewLabel"><?php echo $empresas->nombre->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->nombre->CellAttributes() ?>>
<span id="el_empresas_nombre">
<input type="text" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="200" value="<?php echo $empresas->nombre->EditValue ?>"<?php echo $empresas->nombre->EditAttributes() ?>>
</span>
<?php echo $empresas->nombre->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->documento_tipo->Visible) { // documento_tipo ?>
	<div id="r_documento_tipo" class="form-group">
		<label id="elh_empresas_documento_tipo" class="col-sm-2 control-label ewLabel"><?php echo $empresas->documento_tipo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->documento_tipo->CellAttributes() ?>>
<span id="el_empresas_documento_tipo">
<div id="tp_x_documento_tipo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_documento_tipo" id="x_documento_tipo" value="{value}"<?php echo $empresas->documento_tipo->EditAttributes() ?>></div>
<div id="dsl_x_documento_tipo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $empresas->documento_tipo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($empresas->documento_tipo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio-inline"><input type="radio" data-field="x_documento_tipo" name="x_documento_tipo" id="x_documento_tipo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $empresas->documento_tipo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $empresas->documento_tipo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->documento_numero->Visible) { // documento_numero ?>
	<div id="r_documento_numero" class="form-group">
		<label id="elh_empresas_documento_numero" for="x_documento_numero" class="col-sm-2 control-label ewLabel"><?php echo $empresas->documento_numero->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->documento_numero->CellAttributes() ?>>
<span id="el_empresas_documento_numero">
<input type="text" data-field="x_documento_numero" name="x_documento_numero" id="x_documento_numero" size="12" maxlength="20" value="<?php echo $empresas->documento_numero->EditValue ?>"<?php echo $empresas->documento_numero->EditAttributes() ?>>
</span>
<?php echo $empresas->documento_numero->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->_email->Visible) { // email ?>
	<div id="r__email" class="form-group">
		<label id="elh_empresas__email" for="x__email" class="col-sm-2 control-label ewLabel"><?php echo $empresas->_email->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->_email->CellAttributes() ?>>
<span id="el_empresas__email">
<input type="text" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="200" value="<?php echo $empresas->_email->EditValue ?>"<?php echo $empresas->_email->EditAttributes() ?>>
</span>
<?php echo $empresas->_email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->web->Visible) { // web ?>
	<div id="r_web" class="form-group">
		<label id="elh_empresas_web" for="x_web" class="col-sm-2 control-label ewLabel"><?php echo $empresas->web->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->web->CellAttributes() ?>>
<span id="el_empresas_web">
<input type="text" data-field="x_web" name="x_web" id="x_web" size="30" maxlength="100" value="<?php echo $empresas->web->EditValue ?>"<?php echo $empresas->web->EditAttributes() ?>>
</span>
<?php echo $empresas->web->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->logo->Visible) { // logo ?>
	<div id="r_logo" class="form-group">
		<label id="elh_empresas_logo" class="col-sm-2 control-label ewLabel"><?php echo $empresas->logo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->logo->CellAttributes() ?>>
<span id="el_empresas_logo">
<div id="fd_x_logo">
<span title="<?php echo $empresas->logo->FldTitle() ? $empresas->logo->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($empresas->logo->ReadOnly || $empresas->logo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_logo" name="x_logo" id="x_logo">
</span>
<input type="hidden" name="fn_x_logo" id= "fn_x_logo" value="<?php echo $empresas->logo->Upload->FileName ?>">
<input type="hidden" name="fa_x_logo" id= "fa_x_logo" value="0">
<input type="hidden" name="fs_x_logo" id= "fs_x_logo" value="100">
<input type="hidden" name="fx_x_logo" id= "fx_x_logo" value="<?php echo $empresas->logo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_logo" id= "fm_x_logo" value="<?php echo $empresas->logo->UploadMaxFileSize ?>">
</div>
<table id="ft_x_logo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $empresas->logo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->descripcion->Visible) { // descripcion ?>
	<div id="r_descripcion" class="form-group">
		<label id="elh_empresas_descripcion" for="x_descripcion" class="col-sm-2 control-label ewLabel"><?php echo $empresas->descripcion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->descripcion->CellAttributes() ?>>
<span id="el_empresas_descripcion">
<textarea data-field="x_descripcion" name="x_descripcion" id="x_descripcion" cols="35" rows="4"<?php echo $empresas->descripcion->EditAttributes() ?>><?php echo $empresas->descripcion->EditValue ?></textarea>
</span>
<?php echo $empresas->descripcion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->meta_descripcion->Visible) { // meta_descripcion ?>
	<div id="r_meta_descripcion" class="form-group">
		<label id="elh_empresas_meta_descripcion" for="x_meta_descripcion" class="col-sm-2 control-label ewLabel"><?php echo $empresas->meta_descripcion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->meta_descripcion->CellAttributes() ?>>
<span id="el_empresas_meta_descripcion">
<input type="text" data-field="x_meta_descripcion" name="x_meta_descripcion" id="x_meta_descripcion" size="30" maxlength="200" value="<?php echo $empresas->meta_descripcion->EditValue ?>"<?php echo $empresas->meta_descripcion->EditAttributes() ?>>
</span>
<?php echo $empresas->meta_descripcion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas->meta_palabras_clave->Visible) { // meta_palabras_clave ?>
	<div id="r_meta_palabras_clave" class="form-group">
		<label id="elh_empresas_meta_palabras_clave" for="x_meta_palabras_clave" class="col-sm-2 control-label ewLabel"><?php echo $empresas->meta_palabras_clave->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas->meta_palabras_clave->CellAttributes() ?>>
<span id="el_empresas_meta_palabras_clave">
<input type="text" data-field="x_meta_palabras_clave" name="x_meta_palabras_clave" id="x_meta_palabras_clave" size="30" maxlength="200" value="<?php echo $empresas->meta_palabras_clave->EditValue ?>"<?php echo $empresas->meta_palabras_clave->EditAttributes() ?>>
</span>
<?php echo $empresas->meta_palabras_clave->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fempresasadd.Init();
</script>
<?php
$empresas_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empresas_add->Page_Terminate();
?>
