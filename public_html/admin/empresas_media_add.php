<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "empresas_media_info.php" ?>
<?php include_once "empleados_info.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$empresas_media_add = NULL; // Initialize page object first

class cempresas_media_add extends cempresas_media {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'empresas_media';

	// Page object name
	var $PageObjName = 'empresas_media_add';

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

		// Table object (empresas_media)
		if (!isset($GLOBALS["empresas_media"]) || get_class($GLOBALS["empresas_media"]) == "cempresas_media") {
			$GLOBALS["empresas_media"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["empresas_media"];
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
			define("EW_TABLE_NAME", 'empresas_media', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("empresas_media_list.php"));
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
		global $EW_EXPORT, $empresas_media;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($empresas_media);
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
			if (@$_GET["id_empresa_media"] != "") {
				$this->id_empresa_media->setQueryStringValue($_GET["id_empresa_media"]);
				$this->setKey("id_empresa_media", $this->id_empresa_media->CurrentValue); // Set up key
			} else {
				$this->setKey("id_empresa_media", ""); // Clear key
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
					$this->Page_Terminate("empresas_media_list.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "empresas_media_view.php")
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
		$this->archivo->Upload->Index = $objForm->Index;
		$this->archivo->Upload->UploadFile();
		$this->archivo->CurrentValue = $this->archivo->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id_empresa->CurrentValue = $_GET['idm'];
		$this->archivo->Upload->DbValue = NULL;
		$this->archivo->OldValue = $this->archivo->Upload->DbValue;
		$this->archivo->CurrentValue = NULL; // Clear file related field
		$this->nombre->CurrentValue = NULL;
		$this->nombre->OldValue = $this->nombre->CurrentValue;
		$this->orden->CurrentValue = NULL;
		$this->orden->OldValue = $this->orden->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->id_empresa->FldIsDetailKey) {
			$this->id_empresa->setFormValue($objForm->GetValue("x_id_empresa"));
		}
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->orden->FldIsDetailKey) {
			$this->orden->setFormValue($objForm->GetValue("x_orden"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id_empresa->CurrentValue = $this->id_empresa->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->orden->CurrentValue = $this->orden->FormValue;
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
		$this->id_empresa_media->setDbValue($rs->fields('id_empresa_media'));
		$this->id_empresa->setDbValue($rs->fields('id_empresa'));
		$this->archivo->Upload->DbValue = $rs->fields('archivo');
		$this->archivo->CurrentValue = $this->archivo->Upload->DbValue;
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->orden->setDbValue($rs->fields('orden'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_empresa_media->DbValue = $row['id_empresa_media'];
		$this->id_empresa->DbValue = $row['id_empresa'];
		$this->archivo->Upload->DbValue = $row['archivo'];
		$this->nombre->DbValue = $row['nombre'];
		$this->orden->DbValue = $row['orden'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_empresa_media")) <> "")
			$this->id_empresa_media->CurrentValue = $this->getKey("id_empresa_media"); // id_empresa_media
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
		// id_empresa_media
		// id_empresa
		// archivo
		// nombre
		// orden

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_empresa_media
			$this->id_empresa_media->ViewValue = $this->id_empresa_media->CurrentValue;
			$this->id_empresa_media->ViewCustomAttributes = "";

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

			// archivo
			$this->archivo->UploadPath = '../uploads/media/';
			if (!ew_Empty($this->archivo->Upload->DbValue)) {
				$this->archivo->ImageAlt = $this->archivo->FldAlt();
				$this->archivo->ViewValue = ew_UploadPathEx(FALSE, $this->archivo->UploadPath) . $this->archivo->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->archivo->ViewValue = ew_UploadPathEx(TRUE, $this->archivo->UploadPath) . $this->archivo->Upload->DbValue;
				}
			} else {
				$this->archivo->ViewValue = "";
			}
			$this->archivo->ViewCustomAttributes = "";

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// orden
			$this->orden->ViewValue = $this->orden->CurrentValue;
			$this->orden->ViewCustomAttributes = "";

			// id_empresa
			$this->id_empresa->LinkCustomAttributes = "";
			$this->id_empresa->HrefValue = "";
			$this->id_empresa->TooltipValue = "";

			// archivo
			$this->archivo->LinkCustomAttributes = "";
			$this->archivo->UploadPath = '../uploads/media/';
			if (!ew_Empty($this->archivo->Upload->DbValue)) {
				$this->archivo->HrefValue = ew_UploadPathEx(FALSE, $this->archivo->UploadPath) . $this->archivo->Upload->DbValue; // Add prefix/suffix
				$this->archivo->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->archivo->HrefValue = ew_ConvertFullUrl($this->archivo->HrefValue);
			} else {
				$this->archivo->HrefValue = "";
			}
			$this->archivo->HrefValue2 = $this->archivo->UploadPath . $this->archivo->Upload->DbValue;
			$this->archivo->TooltipValue = "";
			if ($this->archivo->UseColorbox) {
				$this->archivo->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->archivo->LinkAttrs["data-rel"] = "empresas_media_x_archivo";
				$this->archivo->LinkAttrs["class"] = "ewLightbox";
			}

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// orden
			$this->orden->LinkCustomAttributes = "";
			$this->orden->HrefValue = "";
			$this->orden->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// archivo
			$this->archivo->EditAttrs["class"] = "form-control";
			$this->archivo->EditCustomAttributes = "";
			$this->archivo->UploadPath = '../uploads/media/';
			if (!ew_Empty($this->archivo->Upload->DbValue)) {
				$this->archivo->ImageAlt = $this->archivo->FldAlt();
				$this->archivo->EditValue = ew_UploadPathEx(FALSE, $this->archivo->UploadPath) . $this->archivo->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->archivo->EditValue = ew_UploadPathEx(TRUE, $this->archivo->UploadPath) . $this->archivo->Upload->DbValue;
				}
			} else {
				$this->archivo->EditValue = "";
			}
			if (!ew_Empty($this->archivo->CurrentValue))
				$this->archivo->Upload->FileName = $this->archivo->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->archivo);

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);

			// orden
			$this->orden->EditAttrs["class"] = "form-control";
			$this->orden->EditCustomAttributes = "";
			$this->orden->EditValue = ew_HtmlEncode($this->orden->CurrentValue);

			// Edit refer script
			// id_empresa

			$this->id_empresa->HrefValue = "";

			// archivo
			$this->archivo->UploadPath = '../uploads/media/';
			if (!ew_Empty($this->archivo->Upload->DbValue)) {
				$this->archivo->HrefValue = ew_UploadPathEx(FALSE, $this->archivo->UploadPath) . $this->archivo->Upload->DbValue; // Add prefix/suffix
				$this->archivo->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->archivo->HrefValue = ew_ConvertFullUrl($this->archivo->HrefValue);
			} else {
				$this->archivo->HrefValue = "";
			}
			$this->archivo->HrefValue2 = $this->archivo->UploadPath . $this->archivo->Upload->DbValue;

			// nombre
			$this->nombre->HrefValue = "";

			// orden
			$this->orden->HrefValue = "";
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
		if (!ew_CheckInteger($this->orden->FormValue)) {
			ew_AddMessage($gsFormError, $this->orden->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
			$this->archivo->OldUploadPath = '../uploads/media/';
			$this->archivo->UploadPath = $this->archivo->OldUploadPath;
		}
		$rsnew = array();

		// id_empresa
		$this->id_empresa->SetDbValueDef($rsnew, $this->id_empresa->CurrentValue, NULL, FALSE);

		// archivo
		if (!$this->archivo->Upload->KeepFile) {
			$this->archivo->Upload->DbValue = ""; // No need to delete old file
			if ($this->archivo->Upload->FileName == "") {
				$rsnew['archivo'] = NULL;
			} else {
				$rsnew['archivo'] = $this->archivo->Upload->FileName;
			}
		}

		// nombre
		$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, NULL, FALSE);

		// orden
		$this->orden->SetDbValueDef($rsnew, $this->orden->CurrentValue, NULL, FALSE);
		if (!$this->archivo->Upload->KeepFile) {
			$this->archivo->UploadPath = '../uploads/media/';
			if (!ew_Empty($this->archivo->Upload->Value)) {
				if ($this->archivo->Upload->FileName == $this->archivo->Upload->DbValue) { // Overwrite if same file name
					$this->archivo->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['archivo'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->archivo->UploadPath), $rsnew['archivo']); // Get new file name
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
				if (!$this->archivo->Upload->KeepFile) {
					if (!ew_Empty($this->archivo->Upload->Value)) {
						$this->archivo->Upload->SaveToFile($this->archivo->UploadPath, $rsnew['archivo'], TRUE);
					}
					if ($this->archivo->Upload->DbValue <> "")
						@unlink(ew_UploadPathEx(TRUE, $this->archivo->OldUploadPath) . $this->archivo->Upload->DbValue);
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
			$this->id_empresa_media->setDbValue($conn->Insert_ID());
			$rsnew['id_empresa_media'] = $this->id_empresa_media->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// archivo
		ew_CleanUploadTempPath($this->archivo, $this->archivo->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "empresas_media_list.php", "", $this->TableVar, TRUE);
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
if (!isset($empresas_media_add)) $empresas_media_add = new cempresas_media_add();

// Page init
$empresas_media_add->Page_Init();

// Page main
$empresas_media_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empresas_media_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var empresas_media_add = new ew_Page("empresas_media_add");
empresas_media_add.PageID = "add"; // Page ID
var EW_PAGE_ID = empresas_media_add.PageID; // For backward compatibility

// Form object
var fempresas_mediaadd = new ew_Form("fempresas_mediaadd");

// Validate form
fempresas_mediaadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2($empresas_media->id_empresa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_orden");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($empresas_media->orden->FldErrMsg()) ?>");

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
fempresas_mediaadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempresas_mediaadd.ValidateRequired = true;
<?php } else { ?>
fempresas_mediaadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fempresas_mediaadd.Lists["x_id_empresa"] = {"LinkField":"x_id_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $empresas_media_add->ShowPageHeader(); ?>
<?php
$empresas_media_add->ShowMessage();
?>
<form name="fempresas_mediaadd" id="fempresas_mediaadd" class="form-horizontal ewForm ewAddForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($empresas_media_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $empresas_media_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="empresas_media">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($empresas_media->id_empresa->Visible) { // id_empresa ?>
	<div id="r_id_empresa" class="form-group">
		<label id="elh_empresas_media_id_empresa" class="col-sm-2 control-label ewLabel"><?php echo $empresas_media->id_empresa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas_media->id_empresa->CellAttributes() ?>>
<span id="el_empresas_media_id_empresa">
<?php
	$wrkonchange = trim(" " . @$empresas_media->id_empresa->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$empresas_media->id_empresa->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_empresa" style="white-space: nowrap; z-index: 8980">
	<input type="text" name="sv_x_id_empresa" id="sv_x_id_empresa" value="<?php echo $empresas_media->id_empresa->EditValue ?>" size="30"<?php echo $empresas_media->id_empresa->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_id_empresa" name="x_id_empresa" id="x_id_empresa" value="<?php echo ew_HtmlEncode($empresas_media->id_empresa->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id_empresa`, `nombre` AS `DispFld` FROM `empresas`";
$sWhereWrk = "`nombre` LIKE '{query_value}%'";

// Call Lookup selecting
$empresas_media->Lookup_Selecting($empresas_media->id_empresa, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_id_empresa" id="q_x_id_empresa" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
fempresas_mediaadd.CreateAutoSuggest("x_id_empresa", false);
</script>
</span>
<?php echo $empresas_media->id_empresa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas_media->archivo->Visible) { // archivo ?>
	<div id="r_archivo" class="form-group">
		<label id="elh_empresas_media_archivo" class="col-sm-2 control-label ewLabel"><?php echo $empresas_media->archivo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas_media->archivo->CellAttributes() ?>>
<span id="el_empresas_media_archivo">
<div id="fd_x_archivo">
<span title="<?php echo $empresas_media->archivo->FldTitle() ? $empresas_media->archivo->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($empresas_media->archivo->ReadOnly || $empresas_media->archivo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_archivo" name="x_archivo" id="x_archivo">
</span>
<input type="hidden" name="fn_x_archivo" id= "fn_x_archivo" value="<?php echo $empresas_media->archivo->Upload->FileName ?>">
<input type="hidden" name="fa_x_archivo" id= "fa_x_archivo" value="0">
<input type="hidden" name="fs_x_archivo" id= "fs_x_archivo" value="100">
<input type="hidden" name="fx_x_archivo" id= "fx_x_archivo" value="<?php echo $empresas_media->archivo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_archivo" id= "fm_x_archivo" value="<?php echo $empresas_media->archivo->UploadMaxFileSize ?>">
</div>
<table id="ft_x_archivo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $empresas_media->archivo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas_media->nombre->Visible) { // nombre ?>
	<div id="r_nombre" class="form-group">
		<label id="elh_empresas_media_nombre" for="x_nombre" class="col-sm-2 control-label ewLabel"><?php echo $empresas_media->nombre->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas_media->nombre->CellAttributes() ?>>
<span id="el_empresas_media_nombre">
<input type="text" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="100" value="<?php echo $empresas_media->nombre->EditValue ?>"<?php echo $empresas_media->nombre->EditAttributes() ?>>
</span>
<?php echo $empresas_media->nombre->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empresas_media->orden->Visible) { // orden ?>
	<div id="r_orden" class="form-group">
		<label id="elh_empresas_media_orden" for="x_orden" class="col-sm-2 control-label ewLabel"><?php echo $empresas_media->orden->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empresas_media->orden->CellAttributes() ?>>
<span id="el_empresas_media_orden">
<input type="text" data-field="x_orden" name="x_orden" id="x_orden" size="2" value="<?php echo $empresas_media->orden->EditValue ?>"<?php echo $empresas_media->orden->EditAttributes() ?>>
</span>
<?php echo $empresas_media->orden->CustomMsg ?></div></div>
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
fempresas_mediaadd.Init();
</script>
<?php
$empresas_media_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empresas_media_add->Page_Terminate();
?>
