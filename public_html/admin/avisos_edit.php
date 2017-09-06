<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "avisos_info.php" ?>
<?php include_once "empleados_info.php" ?>
<?php include_once "avisos_ubicaciones_gridcls.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$avisos_edit = NULL; // Initialize page object first

class cavisos_edit extends cavisos {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'avisos';

	// Page object name
	var $PageObjName = 'avisos_edit';

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

		// Table object (avisos)
		if (!isset($GLOBALS["avisos"]) || get_class($GLOBALS["avisos"]) == "cavisos") {
			$GLOBALS["avisos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["avisos"];
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
			define("EW_TABLE_NAME", 'avisos', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("avisos_list.php"));
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

			// Process auto fill for detail table 'avisos_ubicaciones'
			if (@$_POST["grid"] == "favisos_ubicacionesgrid") {
				if (!isset($GLOBALS["avisos_ubicaciones_grid"])) $GLOBALS["avisos_ubicaciones_grid"] = new cavisos_ubicaciones_grid;
				$GLOBALS["avisos_ubicaciones_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $avisos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($avisos);
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
		if (@$_GET["id_aviso"] <> "") {
			$this->id_aviso->setQueryStringValue($_GET["id_aviso"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id_aviso->CurrentValue == "")
			$this->Page_Terminate("avisos_list.php"); // Invalid key, return to list

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
					$this->Page_Terminate("avisos_list.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					if ($this->getCurrentDetailTable() <> "") // Master/detail edit
						$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
					else
						$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		$this->url_aviso->Upload->Index = $objForm->Index;
		$this->url_aviso->Upload->UploadFile();
		$this->url_aviso->CurrentValue = $this->url_aviso->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		}
		if (!$this->id_empresa->FldIsDetailKey) {
			$this->id_empresa->setFormValue($objForm->GetValue("x_id_empresa"));
		}
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->id_aviso->FldIsDetailKey)
			$this->id_aviso->setFormValue($objForm->GetValue("x_id_aviso"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_aviso->CurrentValue = $this->id_aviso->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 7);
		$this->id_empresa->CurrentValue = $this->id_empresa->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
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
		$this->id_aviso->setDbValue($rs->fields('id_aviso'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->id_empresa->setDbValue($rs->fields('id_empresa'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->url_aviso->Upload->DbValue = $rs->fields('url_aviso');
		$this->url_aviso->CurrentValue = $this->url_aviso->Upload->DbValue;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_aviso->DbValue = $row['id_aviso'];
		$this->fecha->DbValue = $row['fecha'];
		$this->id_empresa->DbValue = $row['id_empresa'];
		$this->nombre->DbValue = $row['nombre'];
		$this->url_aviso->Upload->DbValue = $row['url_aviso'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_aviso
		// fecha
		// id_empresa
		// nombre
		// url_aviso

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_aviso
			$this->id_aviso->ViewValue = $this->id_aviso->CurrentValue;
			$this->id_aviso->ViewCustomAttributes = "";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
			$this->fecha->ViewCustomAttributes = "";

			// id_empresa
			$this->id_empresa->ViewValue = $this->id_empresa->CurrentValue;
			if (strval($this->id_empresa->CurrentValue) <> "") {
				$sFilterWrk = "`id_empresa`" . ew_SearchString("=", $this->id_empresa->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_empresa`, `nombre` AS `DispFld`, `documento_tipo` AS `Disp2Fld`, `documento_numero` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
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
					$this->id_empresa->ViewValue .= ew_ValueSeparator(1,$this->id_empresa) . $rswrk->fields('Disp2Fld');
					$this->id_empresa->ViewValue .= ew_ValueSeparator(2,$this->id_empresa) . $rswrk->fields('Disp3Fld');
					$rswrk->Close();
				} else {
					$this->id_empresa->ViewValue = $this->id_empresa->CurrentValue;
				}
			} else {
				$this->id_empresa->ViewValue = NULL;
			}
			$this->id_empresa->ViewCustomAttributes = "";

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// url_aviso
			$this->url_aviso->UploadPath = '../uploads/avisos/';
			if (!ew_Empty($this->url_aviso->Upload->DbValue)) {
				$this->url_aviso->ImageAlt = $this->url_aviso->FldAlt();
				$this->url_aviso->ViewValue = ew_UploadPathEx(FALSE, $this->url_aviso->UploadPath) . $this->url_aviso->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->url_aviso->ViewValue = ew_UploadPathEx(TRUE, $this->url_aviso->UploadPath) . $this->url_aviso->Upload->DbValue;
				}
			} else {
				$this->url_aviso->ViewValue = "";
			}
			$this->url_aviso->ViewCustomAttributes = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// id_empresa
			$this->id_empresa->LinkCustomAttributes = "";
			$this->id_empresa->HrefValue = "";
			$this->id_empresa->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// url_aviso
			$this->url_aviso->LinkCustomAttributes = "";
			$this->url_aviso->UploadPath = '../uploads/avisos/';
			if (!ew_Empty($this->url_aviso->Upload->DbValue)) {
				$this->url_aviso->HrefValue = ew_UploadPathEx(FALSE, $this->url_aviso->UploadPath) . $this->url_aviso->Upload->DbValue; // Add prefix/suffix
				$this->url_aviso->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->url_aviso->HrefValue = ew_ConvertFullUrl($this->url_aviso->HrefValue);
			} else {
				$this->url_aviso->HrefValue = "";
			}
			$this->url_aviso->HrefValue2 = $this->url_aviso->UploadPath . $this->url_aviso->Upload->DbValue;
			$this->url_aviso->TooltipValue = "";
			if ($this->url_aviso->UseColorbox) {
				$this->url_aviso->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->url_aviso->LinkAttrs["data-rel"] = "avisos_x_url_aviso";
				$this->url_aviso->LinkAttrs["class"] = "ewLightbox";
			}
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = 'readonly';
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha->CurrentValue, 7));

			// id_empresa
			$this->id_empresa->EditAttrs["class"] = "form-control";
			$this->id_empresa->EditCustomAttributes = 'disabled';
			$this->id_empresa->EditValue = ew_HtmlEncode($this->id_empresa->CurrentValue);
			if (strval($this->id_empresa->CurrentValue) <> "") {
				$sFilterWrk = "`id_empresa`" . ew_SearchString("=", $this->id_empresa->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_empresa`, `nombre` AS `DispFld`, `documento_tipo` AS `Disp2Fld`, `documento_numero` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empresas`";
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
					$this->id_empresa->EditValue .= ew_ValueSeparator(1,$this->id_empresa) . ew_HtmlEncode($rswrk->fields('Disp2Fld'));
					$this->id_empresa->EditValue .= ew_ValueSeparator(2,$this->id_empresa) . ew_HtmlEncode($rswrk->fields('Disp3Fld'));
					$rswrk->Close();
				} else {
					$this->id_empresa->EditValue = ew_HtmlEncode($this->id_empresa->CurrentValue);
				}
			} else {
				$this->id_empresa->EditValue = NULL;
			}

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);

			// url_aviso
			$this->url_aviso->EditAttrs["class"] = "form-control";
			$this->url_aviso->EditCustomAttributes = "";
			$this->url_aviso->UploadPath = '../uploads/avisos/';
			if (!ew_Empty($this->url_aviso->Upload->DbValue)) {
				$this->url_aviso->ImageAlt = $this->url_aviso->FldAlt();
				$this->url_aviso->EditValue = ew_UploadPathEx(FALSE, $this->url_aviso->UploadPath) . $this->url_aviso->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->url_aviso->EditValue = ew_UploadPathEx(TRUE, $this->url_aviso->UploadPath) . $this->url_aviso->Upload->DbValue;
				}
			} else {
				$this->url_aviso->EditValue = "";
			}
			if (!ew_Empty($this->url_aviso->CurrentValue))
				$this->url_aviso->Upload->FileName = $this->url_aviso->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->url_aviso);

			// Edit refer script
			// fecha

			$this->fecha->HrefValue = "";

			// id_empresa
			$this->id_empresa->HrefValue = "";

			// nombre
			$this->nombre->HrefValue = "";

			// url_aviso
			$this->url_aviso->UploadPath = '../uploads/avisos/';
			if (!ew_Empty($this->url_aviso->Upload->DbValue)) {
				$this->url_aviso->HrefValue = ew_UploadPathEx(FALSE, $this->url_aviso->UploadPath) . $this->url_aviso->Upload->DbValue; // Add prefix/suffix
				$this->url_aviso->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->url_aviso->HrefValue = ew_ConvertFullUrl($this->url_aviso->HrefValue);
			} else {
				$this->url_aviso->HrefValue = "";
			}
			$this->url_aviso->HrefValue2 = $this->url_aviso->UploadPath . $this->url_aviso->Upload->DbValue;
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
		if (!ew_CheckEuroDate($this->fecha->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha->FldErrMsg());
		}
		if (!ew_CheckInteger($this->id_empresa->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_empresa->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("avisos_ubicaciones", $DetailTblVar) && $GLOBALS["avisos_ubicaciones"]->DetailEdit) {
			if (!isset($GLOBALS["avisos_ubicaciones_grid"])) $GLOBALS["avisos_ubicaciones_grid"] = new cavisos_ubicaciones_grid(); // get detail page object
			$GLOBALS["avisos_ubicaciones_grid"]->ValidateGridForm();
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$this->url_aviso->OldUploadPath = '../uploads/avisos/';
			$this->url_aviso->UploadPath = $this->url_aviso->OldUploadPath;
			$rsnew = array();

			// fecha
			$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 7), NULL, $this->fecha->ReadOnly);

			// id_empresa
			$this->id_empresa->SetDbValueDef($rsnew, $this->id_empresa->CurrentValue, NULL, $this->id_empresa->ReadOnly);

			// nombre
			$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, NULL, $this->nombre->ReadOnly);

			// url_aviso
			if (!($this->url_aviso->ReadOnly) && !$this->url_aviso->Upload->KeepFile) {
				$this->url_aviso->Upload->DbValue = $rsold['url_aviso']; // Get original value
				if ($this->url_aviso->Upload->FileName == "") {
					$rsnew['url_aviso'] = NULL;
				} else {
					$rsnew['url_aviso'] = $this->url_aviso->Upload->FileName;
				}
			}
			if (!$this->url_aviso->Upload->KeepFile) {
				$this->url_aviso->UploadPath = '../uploads/avisos/';
				if (!ew_Empty($this->url_aviso->Upload->Value)) {
					if ($this->url_aviso->Upload->FileName == $this->url_aviso->Upload->DbValue) { // Overwrite if same file name
						$this->url_aviso->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['url_aviso'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->url_aviso->UploadPath), $rsnew['url_aviso']); // Get new file name
					}
				}
			}

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
					if (!$this->url_aviso->Upload->KeepFile) {
						if (!ew_Empty($this->url_aviso->Upload->Value)) {
							$this->url_aviso->Upload->SaveToFile($this->url_aviso->UploadPath, $rsnew['url_aviso'], TRUE);
						}
						if ($this->url_aviso->Upload->DbValue <> "")
							@unlink(ew_UploadPathEx(TRUE, $this->url_aviso->OldUploadPath) . $this->url_aviso->Upload->DbValue);
					}
				}

				// Update detail records
				if ($EditRow) {
					$DetailTblVar = explode(",", $this->getCurrentDetailTable());
					if (in_array("avisos_ubicaciones", $DetailTblVar) && $GLOBALS["avisos_ubicaciones"]->DetailEdit) {
						if (!isset($GLOBALS["avisos_ubicaciones_grid"])) $GLOBALS["avisos_ubicaciones_grid"] = new cavisos_ubicaciones_grid(); // Get detail page object
						$EditRow = $GLOBALS["avisos_ubicaciones_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
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

		// url_aviso
		ew_CleanUploadTempPath($this->url_aviso, $this->url_aviso->Upload->Index);
		return $EditRow;
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("avisos_ubicaciones", $DetailTblVar)) {
				if (!isset($GLOBALS["avisos_ubicaciones_grid"]))
					$GLOBALS["avisos_ubicaciones_grid"] = new cavisos_ubicaciones_grid;
				if ($GLOBALS["avisos_ubicaciones_grid"]->DetailEdit) {
					$GLOBALS["avisos_ubicaciones_grid"]->CurrentMode = "edit";
					$GLOBALS["avisos_ubicaciones_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["avisos_ubicaciones_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["avisos_ubicaciones_grid"]->setStartRecordNumber(1);
					$GLOBALS["avisos_ubicaciones_grid"]->id_aviso->FldIsDetailKey = TRUE;
					$GLOBALS["avisos_ubicaciones_grid"]->id_aviso->CurrentValue = $this->id_aviso->CurrentValue;
					$GLOBALS["avisos_ubicaciones_grid"]->id_aviso->setSessionValue($GLOBALS["avisos_ubicaciones_grid"]->id_aviso->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "avisos_list.php", "", $this->TableVar, TRUE);
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
if (!isset($avisos_edit)) $avisos_edit = new cavisos_edit();

// Page init
$avisos_edit->Page_Init();

// Page main
$avisos_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$avisos_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var avisos_edit = new ew_Page("avisos_edit");
avisos_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = avisos_edit.PageID; // For backward compatibility

// Form object
var favisosedit = new ew_Form("favisosedit");

// Validate form
favisosedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($avisos->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_id_empresa");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($avisos->id_empresa->FldErrMsg()) ?>");

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
favisosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
favisosedit.ValidateRequired = true;
<?php } else { ?>
favisosedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
favisosedit.Lists["x_id_empresa"] = {"LinkField":"x_id_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_documento_tipo","x_documento_numero",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $avisos_edit->ShowPageHeader(); ?>
<?php
$avisos_edit->ShowMessage();
?>
<form name="favisosedit" id="favisosedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($avisos_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $avisos_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="avisos">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($avisos->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label id="elh_avisos_fecha" for="x_fecha" class="col-sm-2 control-label ewLabel"><?php echo $avisos->fecha->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $avisos->fecha->CellAttributes() ?>>
<span id="el_avisos_fecha">
<input type="text" data-field="x_fecha" name="x_fecha" id="x_fecha" value="<?php echo $avisos->fecha->EditValue ?>"<?php echo $avisos->fecha->EditAttributes() ?>>
</span>
<?php echo $avisos->fecha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($avisos->id_empresa->Visible) { // id_empresa ?>
	<div id="r_id_empresa" class="form-group">
		<label id="elh_avisos_id_empresa" class="col-sm-2 control-label ewLabel"><?php echo $avisos->id_empresa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $avisos->id_empresa->CellAttributes() ?>>
<span id="el_avisos_id_empresa">
<?php
	$wrkonchange = trim(" " . @$avisos->id_empresa->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$avisos->id_empresa->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_empresa" style="white-space: nowrap; z-index: 8970">
	<input type="text" name="sv_x_id_empresa" id="sv_x_id_empresa" value="<?php echo $avisos->id_empresa->EditValue ?>" size="30"<?php echo $avisos->id_empresa->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_id_empresa" name="x_id_empresa" id="x_id_empresa" value="<?php echo ew_HtmlEncode($avisos->id_empresa->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT `id_empresa`, `nombre` AS `DispFld`, `documento_tipo` AS `Disp2Fld`, `documento_numero` AS `Disp3Fld` FROM `empresas`";
$sWhereWrk = "`nombre` LIKE '{query_value}%' OR CONCAT(`nombre`,'" . ew_ValueSeparator(1, $Page->id_empresa) . "',`documento_tipo`,'" . ew_ValueSeparator(2, $Page->id_empresa) . "',`documento_numero`) LIKE '{query_value}%'";

// Call Lookup selecting
$avisos->Lookup_Selecting($avisos->id_empresa, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x_id_empresa" id="q_x_id_empresa" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
favisosedit.CreateAutoSuggest("x_id_empresa", false);
</script>
</span>
<?php echo $avisos->id_empresa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($avisos->nombre->Visible) { // nombre ?>
	<div id="r_nombre" class="form-group">
		<label id="elh_avisos_nombre" for="x_nombre" class="col-sm-2 control-label ewLabel"><?php echo $avisos->nombre->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $avisos->nombre->CellAttributes() ?>>
<span id="el_avisos_nombre">
<input type="text" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="100" value="<?php echo $avisos->nombre->EditValue ?>"<?php echo $avisos->nombre->EditAttributes() ?>>
</span>
<?php echo $avisos->nombre->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($avisos->url_aviso->Visible) { // url_aviso ?>
	<div id="r_url_aviso" class="form-group">
		<label id="elh_avisos_url_aviso" class="col-sm-2 control-label ewLabel"><?php echo $avisos->url_aviso->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $avisos->url_aviso->CellAttributes() ?>>
<span id="el_avisos_url_aviso">
<div id="fd_x_url_aviso">
<span title="<?php echo $avisos->url_aviso->FldTitle() ? $avisos->url_aviso->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($avisos->url_aviso->ReadOnly || $avisos->url_aviso->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_url_aviso" name="x_url_aviso" id="x_url_aviso">
</span>
<input type="hidden" name="fn_x_url_aviso" id= "fn_x_url_aviso" value="<?php echo $avisos->url_aviso->Upload->FileName ?>">
<?php if (@$_POST["fa_x_url_aviso"] == "0") { ?>
<input type="hidden" name="fa_x_url_aviso" id= "fa_x_url_aviso" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_url_aviso" id= "fa_x_url_aviso" value="1">
<?php } ?>
<input type="hidden" name="fs_x_url_aviso" id= "fs_x_url_aviso" value="200">
<input type="hidden" name="fx_x_url_aviso" id= "fx_x_url_aviso" value="<?php echo $avisos->url_aviso->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_url_aviso" id= "fm_x_url_aviso" value="<?php echo $avisos->url_aviso->UploadMaxFileSize ?>">
</div>
<table id="ft_x_url_aviso" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $avisos->url_aviso->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-field="x_id_aviso" name="x_id_aviso" id="x_id_aviso" value="<?php echo ew_HtmlEncode($avisos->id_aviso->CurrentValue) ?>">
<?php
	if (in_array("avisos_ubicaciones", explode(",", $avisos->getCurrentDetailTable())) && $avisos_ubicaciones->DetailEdit) {
?>
<?php if ($avisos->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("avisos_ubicaciones", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "avisos_ubicaciones_grid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
favisosedit.Init();
</script>
<?php
$avisos_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$avisos_edit->Page_Terminate();
?>
