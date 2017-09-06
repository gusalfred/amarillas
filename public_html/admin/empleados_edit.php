<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "empleados_info.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$empleados_edit = NULL; // Initialize page object first

class cempleados_edit extends cempleados {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'empleados';

	// Page object name
	var $PageObjName = 'empleados_edit';

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

		// Table object (empleados)
		if (!isset($GLOBALS["empleados"]) || get_class($GLOBALS["empleados"]) == "cempleados") {
			$GLOBALS["empleados"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["empleados"];
		}

		// User table object (empleados)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cempleados();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'empleados', TRUE);

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

		// Security
		$Security = new cAdvancedSecurity();
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
			$this->Page_Terminate(ew_GetUrl("empleados_list.php"));
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
		global $EW_EXPORT, $empleados;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($empleados);
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
		if (@$_GET["id_empleado"] <> "") {
			$this->id_empleado->setQueryStringValue($_GET["id_empleado"]);
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
		if ($this->id_empleado->CurrentValue == "")
			$this->Page_Terminate("empleados_list.php"); // Invalid key, return to list

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
					$this->Page_Terminate("empleados_list.php"); // No matching record, return to list
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
		if (!$this->id_empleado_nivel->FldIsDetailKey) {
			$this->id_empleado_nivel->setFormValue($objForm->GetValue("x_id_empleado_nivel"));
		}
		if (!$this->nombre->FldIsDetailKey) {
			$this->nombre->setFormValue($objForm->GetValue("x_nombre"));
		}
		if (!$this->usuario->FldIsDetailKey) {
			$this->usuario->setFormValue($objForm->GetValue("x_usuario"));
		}
		if (!$this->clave->FldIsDetailKey) {
			$this->clave->setFormValue($objForm->GetValue("x_clave"));
		}
		if (!$this->id_empleado->FldIsDetailKey)
			$this->id_empleado->setFormValue($objForm->GetValue("x_id_empleado"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_empleado->CurrentValue = $this->id_empleado->FormValue;
		$this->id_empleado_nivel->CurrentValue = $this->id_empleado_nivel->FormValue;
		$this->nombre->CurrentValue = $this->nombre->FormValue;
		$this->usuario->CurrentValue = $this->usuario->FormValue;
		$this->clave->CurrentValue = $this->clave->FormValue;
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
		$this->id_empleado->setDbValue($rs->fields('id_empleado'));
		$this->id_empleado_nivel->setDbValue($rs->fields('id_empleado_nivel'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->usuario->setDbValue($rs->fields('usuario'));
		$this->clave->setDbValue($rs->fields('clave'));
		$this->perfil->setDbValue($rs->fields('perfil'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_empleado->DbValue = $row['id_empleado'];
		$this->id_empleado_nivel->DbValue = $row['id_empleado_nivel'];
		$this->nombre->DbValue = $row['nombre'];
		$this->usuario->DbValue = $row['usuario'];
		$this->clave->DbValue = $row['clave'];
		$this->perfil->DbValue = $row['perfil'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_empleado
		// id_empleado_nivel
		// nombre
		// usuario
		// clave
		// perfil

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_empleado
			$this->id_empleado->ViewValue = $this->id_empleado->CurrentValue;
			$this->id_empleado->ViewCustomAttributes = "";

			// id_empleado_nivel
			if ($Security->CanAdmin()) { // System admin
			if (strval($this->id_empleado_nivel->CurrentValue) <> "") {
				$sFilterWrk = "`id_empleado_nivel`" . ew_SearchString("=", $this->id_empleado_nivel->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_empleado_nivel`, `nivel` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empleados_niveles`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_empleado_nivel, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_empleado_nivel->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_empleado_nivel->ViewValue = $this->id_empleado_nivel->CurrentValue;
				}
			} else {
				$this->id_empleado_nivel->ViewValue = NULL;
			}
			} else {
				$this->id_empleado_nivel->ViewValue = "********";
			}
			$this->id_empleado_nivel->ViewCustomAttributes = "";

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// usuario
			$this->usuario->ViewValue = $this->usuario->CurrentValue;
			$this->usuario->ViewCustomAttributes = "";

			// clave
			$this->clave->ViewValue = $this->clave->CurrentValue;
			$this->clave->ViewCustomAttributes = "";

			// id_empleado_nivel
			$this->id_empleado_nivel->LinkCustomAttributes = "";
			$this->id_empleado_nivel->HrefValue = "";
			$this->id_empleado_nivel->TooltipValue = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// usuario
			$this->usuario->LinkCustomAttributes = "";
			$this->usuario->HrefValue = "";
			$this->usuario->TooltipValue = "";

			// clave
			$this->clave->LinkCustomAttributes = "";
			$this->clave->HrefValue = "";
			$this->clave->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_empleado_nivel
			$this->id_empleado_nivel->EditAttrs["class"] = "form-control";
			$this->id_empleado_nivel->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->id_empleado_nivel->EditValue = "********";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `id_empleado_nivel`, `nivel` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `empleados_niveles`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_empleado_nivel, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_empleado_nivel->EditValue = $arwrk;
			}

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->CurrentValue);

			// usuario
			$this->usuario->EditAttrs["class"] = "form-control";
			$this->usuario->EditCustomAttributes = "";
			$this->usuario->EditValue = ew_HtmlEncode($this->usuario->CurrentValue);

			// clave
			$this->clave->EditAttrs["class"] = "form-control";
			$this->clave->EditCustomAttributes = "";
			$this->clave->EditValue = ew_HtmlEncode($this->clave->CurrentValue);

			// Edit refer script
			// id_empleado_nivel

			$this->id_empleado_nivel->HrefValue = "";

			// nombre
			$this->nombre->HrefValue = "";

			// usuario
			$this->usuario->HrefValue = "";

			// clave
			$this->clave->HrefValue = "";
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

			// id_empleado_nivel
			if ($Security->CanAdmin()) { // System admin
			$this->id_empleado_nivel->SetDbValueDef($rsnew, $this->id_empleado_nivel->CurrentValue, NULL, $this->id_empleado_nivel->ReadOnly);
			}

			// nombre
			$this->nombre->SetDbValueDef($rsnew, $this->nombre->CurrentValue, NULL, $this->nombre->ReadOnly);

			// usuario
			$this->usuario->SetDbValueDef($rsnew, $this->usuario->CurrentValue, NULL, $this->usuario->ReadOnly);

			// clave
			$this->clave->SetDbValueDef($rsnew, $this->clave->CurrentValue, NULL, $this->clave->ReadOnly || (EW_ENCRYPTED_PASSWORD && $rs->fields('clave') == $this->clave->CurrentValue));

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
		$Breadcrumb->Add("list", $this->TableVar, "empleados_list.php", "", $this->TableVar, TRUE);
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
if (!isset($empleados_edit)) $empleados_edit = new cempleados_edit();

// Page init
$empleados_edit->Page_Init();

// Page main
$empleados_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empleados_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var empleados_edit = new ew_Page("empleados_edit");
empleados_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = empleados_edit.PageID; // For backward compatibility

// Form object
var fempleadosedit = new ew_Form("fempleadosedit");

// Validate form
fempleadosedit.Validate = function() {
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
fempleadosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempleadosedit.ValidateRequired = true;
<?php } else { ?>
fempleadosedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fempleadosedit.Lists["x_id_empleado_nivel"] = {"LinkField":"x_id_empleado_nivel","Ajax":null,"AutoFill":false,"DisplayFields":["x_nivel","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $empleados_edit->ShowPageHeader(); ?>
<?php
$empleados_edit->ShowMessage();
?>
<form name="fempleadosedit" id="fempleadosedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($empleados_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $empleados_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="empleados">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($empleados->id_empleado_nivel->Visible) { // id_empleado_nivel ?>
	<div id="r_id_empleado_nivel" class="form-group">
		<label id="elh_empleados_id_empleado_nivel" for="x_id_empleado_nivel" class="col-sm-2 control-label ewLabel"><?php echo $empleados->id_empleado_nivel->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empleados->id_empleado_nivel->CellAttributes() ?>>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<span id="el_empleados_id_empleado_nivel">
<p class="form-control-static"><?php echo $empleados->id_empleado_nivel->EditValue ?></p>
</span>
<?php } else { ?>
<span id="el_empleados_id_empleado_nivel">
<select data-field="x_id_empleado_nivel" id="x_id_empleado_nivel" name="x_id_empleado_nivel"<?php echo $empleados->id_empleado_nivel->EditAttributes() ?>>
<?php
if (is_array($empleados->id_empleado_nivel->EditValue)) {
	$arwrk = $empleados->id_empleado_nivel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($empleados->id_empleado_nivel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fempleadosedit.Lists["x_id_empleado_nivel"].Options = <?php echo (is_array($empleados->id_empleado_nivel->EditValue)) ? ew_ArrayToJson($empleados->id_empleado_nivel->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php echo $empleados->id_empleado_nivel->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleados->nombre->Visible) { // nombre ?>
	<div id="r_nombre" class="form-group">
		<label id="elh_empleados_nombre" for="x_nombre" class="col-sm-2 control-label ewLabel"><?php echo $empleados->nombre->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empleados->nombre->CellAttributes() ?>>
<span id="el_empleados_nombre">
<input type="text" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="100" value="<?php echo $empleados->nombre->EditValue ?>"<?php echo $empleados->nombre->EditAttributes() ?>>
</span>
<?php echo $empleados->nombre->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleados->usuario->Visible) { // usuario ?>
	<div id="r_usuario" class="form-group">
		<label id="elh_empleados_usuario" for="x_usuario" class="col-sm-2 control-label ewLabel"><?php echo $empleados->usuario->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empleados->usuario->CellAttributes() ?>>
<span id="el_empleados_usuario">
<input type="text" data-field="x_usuario" name="x_usuario" id="x_usuario" size="30" maxlength="100" value="<?php echo $empleados->usuario->EditValue ?>"<?php echo $empleados->usuario->EditAttributes() ?>>
</span>
<?php echo $empleados->usuario->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($empleados->clave->Visible) { // clave ?>
	<div id="r_clave" class="form-group">
		<label id="elh_empleados_clave" for="x_clave" class="col-sm-2 control-label ewLabel"><?php echo $empleados->clave->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $empleados->clave->CellAttributes() ?>>
<span id="el_empleados_clave">
<input type="text" data-field="x_clave" name="x_clave" id="x_clave" size="30" maxlength="100" value="<?php echo $empleados->clave->EditValue ?>"<?php echo $empleados->clave->EditAttributes() ?>>
</span>
<?php echo $empleados->clave->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-field="x_id_empleado" name="x_id_empleado" id="x_id_empleado" value="<?php echo ew_HtmlEncode($empleados->id_empleado->CurrentValue) ?>">
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fempleadosedit.Init();
</script>
<?php
$empleados_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empleados_edit->Page_Terminate();
?>
