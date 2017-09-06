<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "recibos_info.php" ?>
<?php include_once "empleados_info.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$recibos_edit = NULL; // Initialize page object first

class crecibos_edit extends crecibos {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'recibos';

	// Page object name
	var $PageObjName = 'recibos_edit';

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

		// Table object (recibos)
		if (!isset($GLOBALS["recibos"]) || get_class($GLOBALS["recibos"]) == "crecibos") {
			$GLOBALS["recibos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["recibos"];
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
			define("EW_TABLE_NAME", 'recibos', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("recibos_list.php"));
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
		global $EW_EXPORT, $recibos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($recibos);
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
		if (@$_GET["id_recibo"] <> "") {
			$this->id_recibo->setQueryStringValue($_GET["id_recibo"]);
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
		if ($this->id_recibo->CurrentValue == "")
			$this->Page_Terminate("recibos_list.php"); // Invalid key, return to list

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
					$this->Page_Terminate("recibos_list.php"); // No matching record, return to list
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
		if (!$this->fecha_emision->FldIsDetailKey) {
			$this->fecha_emision->setFormValue($objForm->GetValue("x_fecha_emision"));
			$this->fecha_emision->CurrentValue = ew_UnFormatDateTime($this->fecha_emision->CurrentValue, 7);
		}
		if (!$this->fecha_vencimiento->FldIsDetailKey) {
			$this->fecha_vencimiento->setFormValue($objForm->GetValue("x_fecha_vencimiento"));
			$this->fecha_vencimiento->CurrentValue = ew_UnFormatDateTime($this->fecha_vencimiento->CurrentValue, 7);
		}
		if (!$this->numero->FldIsDetailKey) {
			$this->numero->setFormValue($objForm->GetValue("x_numero"));
		}
		if (!$this->monto_base->FldIsDetailKey) {
			$this->monto_base->setFormValue($objForm->GetValue("x_monto_base"));
		}
		if (!$this->monto_impuesto->FldIsDetailKey) {
			$this->monto_impuesto->setFormValue($objForm->GetValue("x_monto_impuesto"));
		}
		if (!$this->monto_total->FldIsDetailKey) {
			$this->monto_total->setFormValue($objForm->GetValue("x_monto_total"));
		}
		if (!$this->id_recibo->FldIsDetailKey)
			$this->id_recibo->setFormValue($objForm->GetValue("x_id_recibo"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_recibo->CurrentValue = $this->id_recibo->FormValue;
		$this->id_empresa->CurrentValue = $this->id_empresa->FormValue;
		$this->fecha_emision->CurrentValue = $this->fecha_emision->FormValue;
		$this->fecha_emision->CurrentValue = ew_UnFormatDateTime($this->fecha_emision->CurrentValue, 7);
		$this->fecha_vencimiento->CurrentValue = $this->fecha_vencimiento->FormValue;
		$this->fecha_vencimiento->CurrentValue = ew_UnFormatDateTime($this->fecha_vencimiento->CurrentValue, 7);
		$this->numero->CurrentValue = $this->numero->FormValue;
		$this->monto_base->CurrentValue = $this->monto_base->FormValue;
		$this->monto_impuesto->CurrentValue = $this->monto_impuesto->FormValue;
		$this->monto_total->CurrentValue = $this->monto_total->FormValue;
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
		$this->id_recibo->setDbValue($rs->fields('id_recibo'));
		$this->id_empresa->setDbValue($rs->fields('id_empresa'));
		$this->fecha_emision->setDbValue($rs->fields('fecha_emision'));
		$this->fecha_vencimiento->setDbValue($rs->fields('fecha_vencimiento'));
		$this->numero->setDbValue($rs->fields('numero'));
		$this->monto_base->setDbValue($rs->fields('monto_base'));
		$this->monto_impuesto->setDbValue($rs->fields('monto_impuesto'));
		$this->monto_total->setDbValue($rs->fields('monto_total'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_recibo->DbValue = $row['id_recibo'];
		$this->id_empresa->DbValue = $row['id_empresa'];
		$this->fecha_emision->DbValue = $row['fecha_emision'];
		$this->fecha_vencimiento->DbValue = $row['fecha_vencimiento'];
		$this->numero->DbValue = $row['numero'];
		$this->monto_base->DbValue = $row['monto_base'];
		$this->monto_impuesto->DbValue = $row['monto_impuesto'];
		$this->monto_total->DbValue = $row['monto_total'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->monto_base->FormValue == $this->monto_base->CurrentValue && is_numeric(ew_StrToFloat($this->monto_base->CurrentValue)))
			$this->monto_base->CurrentValue = ew_StrToFloat($this->monto_base->CurrentValue);

		// Convert decimal values if posted back
		if ($this->monto_impuesto->FormValue == $this->monto_impuesto->CurrentValue && is_numeric(ew_StrToFloat($this->monto_impuesto->CurrentValue)))
			$this->monto_impuesto->CurrentValue = ew_StrToFloat($this->monto_impuesto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->monto_total->FormValue == $this->monto_total->CurrentValue && is_numeric(ew_StrToFloat($this->monto_total->CurrentValue)))
			$this->monto_total->CurrentValue = ew_StrToFloat($this->monto_total->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id_recibo
		// id_empresa
		// fecha_emision
		// fecha_vencimiento
		// numero
		// monto_base
		// monto_impuesto
		// monto_total

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_recibo
			$this->id_recibo->ViewValue = $this->id_recibo->CurrentValue;
			$this->id_recibo->ViewCustomAttributes = "";

			// id_empresa
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
			$sSqlWrk .= " ORDER BY `nombre`";
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

			// fecha_emision
			$this->fecha_emision->ViewValue = $this->fecha_emision->CurrentValue;
			$this->fecha_emision->ViewValue = ew_FormatDateTime($this->fecha_emision->ViewValue, 7);
			$this->fecha_emision->ViewCustomAttributes = "";

			// fecha_vencimiento
			$this->fecha_vencimiento->ViewValue = $this->fecha_vencimiento->CurrentValue;
			$this->fecha_vencimiento->ViewValue = ew_FormatDateTime($this->fecha_vencimiento->ViewValue, 7);
			$this->fecha_vencimiento->ViewCustomAttributes = "";

			// numero
			$this->numero->ViewValue = $this->numero->CurrentValue;
			$this->numero->ViewCustomAttributes = "";

			// monto_base
			$this->monto_base->ViewValue = $this->monto_base->CurrentValue;
			$this->monto_base->ViewCustomAttributes = "";

			// monto_impuesto
			$this->monto_impuesto->ViewValue = $this->monto_impuesto->CurrentValue;
			$this->monto_impuesto->ViewCustomAttributes = "";

			// monto_total
			$this->monto_total->ViewValue = $this->monto_total->CurrentValue;
			$this->monto_total->ViewCustomAttributes = "";

			// id_empresa
			$this->id_empresa->LinkCustomAttributes = "";
			$this->id_empresa->HrefValue = "";
			$this->id_empresa->TooltipValue = "";

			// fecha_emision
			$this->fecha_emision->LinkCustomAttributes = "";
			$this->fecha_emision->HrefValue = "";
			$this->fecha_emision->TooltipValue = "";

			// fecha_vencimiento
			$this->fecha_vencimiento->LinkCustomAttributes = "";
			$this->fecha_vencimiento->HrefValue = "";
			$this->fecha_vencimiento->TooltipValue = "";

			// numero
			$this->numero->LinkCustomAttributes = "";
			$this->numero->HrefValue = "";
			$this->numero->TooltipValue = "";

			// monto_base
			$this->monto_base->LinkCustomAttributes = "";
			$this->monto_base->HrefValue = "";
			$this->monto_base->TooltipValue = "";

			// monto_impuesto
			$this->monto_impuesto->LinkCustomAttributes = "";
			$this->monto_impuesto->HrefValue = "";
			$this->monto_impuesto->TooltipValue = "";

			// monto_total
			$this->monto_total->LinkCustomAttributes = "";
			$this->monto_total->HrefValue = "";
			$this->monto_total->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id_empresa
			$this->id_empresa->EditAttrs["class"] = "form-control";
			$this->id_empresa->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `id_empresa`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `empresas`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_empresa, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_empresa->EditValue = $arwrk;

			// fecha_emision
			$this->fecha_emision->EditAttrs["class"] = "form-control";
			$this->fecha_emision->EditCustomAttributes = "";
			$this->fecha_emision->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_emision->CurrentValue, 7));

			// fecha_vencimiento
			$this->fecha_vencimiento->EditAttrs["class"] = "form-control";
			$this->fecha_vencimiento->EditCustomAttributes = "";
			$this->fecha_vencimiento->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha_vencimiento->CurrentValue, 7));

			// numero
			$this->numero->EditAttrs["class"] = "form-control";
			$this->numero->EditCustomAttributes = "";
			$this->numero->EditValue = ew_HtmlEncode($this->numero->CurrentValue);

			// monto_base
			$this->monto_base->EditAttrs["class"] = "form-control";
			$this->monto_base->EditCustomAttributes = "";
			$this->monto_base->EditValue = ew_HtmlEncode($this->monto_base->CurrentValue);
			if (strval($this->monto_base->EditValue) <> "" && is_numeric($this->monto_base->EditValue)) $this->monto_base->EditValue = ew_FormatNumber($this->monto_base->EditValue, -2, -1, -2, 0);

			// monto_impuesto
			$this->monto_impuesto->EditAttrs["class"] = "form-control";
			$this->monto_impuesto->EditCustomAttributes = "";
			$this->monto_impuesto->EditValue = ew_HtmlEncode($this->monto_impuesto->CurrentValue);
			if (strval($this->monto_impuesto->EditValue) <> "" && is_numeric($this->monto_impuesto->EditValue)) $this->monto_impuesto->EditValue = ew_FormatNumber($this->monto_impuesto->EditValue, -2, -1, -2, 0);

			// monto_total
			$this->monto_total->EditAttrs["class"] = "form-control";
			$this->monto_total->EditCustomAttributes = "";
			$this->monto_total->EditValue = ew_HtmlEncode($this->monto_total->CurrentValue);
			if (strval($this->monto_total->EditValue) <> "" && is_numeric($this->monto_total->EditValue)) $this->monto_total->EditValue = ew_FormatNumber($this->monto_total->EditValue, -2, -1, -2, 0);

			// Edit refer script
			// id_empresa

			$this->id_empresa->HrefValue = "";

			// fecha_emision
			$this->fecha_emision->HrefValue = "";

			// fecha_vencimiento
			$this->fecha_vencimiento->HrefValue = "";

			// numero
			$this->numero->HrefValue = "";

			// monto_base
			$this->monto_base->HrefValue = "";

			// monto_impuesto
			$this->monto_impuesto->HrefValue = "";

			// monto_total
			$this->monto_total->HrefValue = "";
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
		if (!ew_CheckEuroDate($this->fecha_emision->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_emision->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fecha_vencimiento->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha_vencimiento->FldErrMsg());
		}
		if (!ew_CheckNumber($this->monto_base->FormValue)) {
			ew_AddMessage($gsFormError, $this->monto_base->FldErrMsg());
		}
		if (!ew_CheckNumber($this->monto_impuesto->FormValue)) {
			ew_AddMessage($gsFormError, $this->monto_impuesto->FldErrMsg());
		}
		if (!ew_CheckNumber($this->monto_total->FormValue)) {
			ew_AddMessage($gsFormError, $this->monto_total->FldErrMsg());
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

			// fecha_emision
			$this->fecha_emision->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_emision->CurrentValue, 7), NULL, $this->fecha_emision->ReadOnly);

			// fecha_vencimiento
			$this->fecha_vencimiento->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha_vencimiento->CurrentValue, 7), NULL, $this->fecha_vencimiento->ReadOnly);

			// numero
			$this->numero->SetDbValueDef($rsnew, $this->numero->CurrentValue, NULL, $this->numero->ReadOnly);

			// monto_base
			$this->monto_base->SetDbValueDef($rsnew, $this->monto_base->CurrentValue, NULL, $this->monto_base->ReadOnly);

			// monto_impuesto
			$this->monto_impuesto->SetDbValueDef($rsnew, $this->monto_impuesto->CurrentValue, NULL, $this->monto_impuesto->ReadOnly);

			// monto_total
			$this->monto_total->SetDbValueDef($rsnew, $this->monto_total->CurrentValue, NULL, $this->monto_total->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "recibos_list.php", "", $this->TableVar, TRUE);
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
if (!isset($recibos_edit)) $recibos_edit = new crecibos_edit();

// Page init
$recibos_edit->Page_Init();

// Page main
$recibos_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$recibos_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var recibos_edit = new ew_Page("recibos_edit");
recibos_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = recibos_edit.PageID; // For backward compatibility

// Form object
var frecibosedit = new ew_Form("frecibosedit");

// Validate form
frecibosedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_fecha_emision");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($recibos->fecha_emision->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fecha_vencimiento");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($recibos->fecha_vencimiento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_monto_base");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($recibos->monto_base->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_monto_impuesto");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($recibos->monto_impuesto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_monto_total");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($recibos->monto_total->FldErrMsg()) ?>");

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
frecibosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frecibosedit.ValidateRequired = true;
<?php } else { ?>
frecibosedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frecibosedit.Lists["x_id_empresa"] = {"LinkField":"x_id_empresa","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

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
<?php $recibos_edit->ShowPageHeader(); ?>
<?php
$recibos_edit->ShowMessage();
?>
<form name="frecibosedit" id="frecibosedit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($recibos_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $recibos_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="recibos">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($recibos->id_empresa->Visible) { // id_empresa ?>
	<div id="r_id_empresa" class="form-group">
		<label id="elh_recibos_id_empresa" for="x_id_empresa" class="col-sm-2 control-label ewLabel"><?php echo $recibos->id_empresa->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $recibos->id_empresa->CellAttributes() ?>>
<span id="el_recibos_id_empresa">
<select data-field="x_id_empresa" id="x_id_empresa" name="x_id_empresa"<?php echo $recibos->id_empresa->EditAttributes() ?>>
<?php
if (is_array($recibos->id_empresa->EditValue)) {
	$arwrk = $recibos->id_empresa->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($recibos->id_empresa->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
frecibosedit.Lists["x_id_empresa"].Options = <?php echo (is_array($recibos->id_empresa->EditValue)) ? ew_ArrayToJson($recibos->id_empresa->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $recibos->id_empresa->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($recibos->fecha_emision->Visible) { // fecha_emision ?>
	<div id="r_fecha_emision" class="form-group">
		<label id="elh_recibos_fecha_emision" for="x_fecha_emision" class="col-sm-2 control-label ewLabel"><?php echo $recibos->fecha_emision->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $recibos->fecha_emision->CellAttributes() ?>>
<span id="el_recibos_fecha_emision">
<input type="text" data-field="x_fecha_emision" name="x_fecha_emision" id="x_fecha_emision" size="10" value="<?php echo $recibos->fecha_emision->EditValue ?>"<?php echo $recibos->fecha_emision->EditAttributes() ?>>
<?php if (!$recibos->fecha_emision->ReadOnly && !$recibos->fecha_emision->Disabled && !isset($recibos->fecha_emision->EditAttrs["readonly"]) && !isset($recibos->fecha_emision->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("frecibosedit", "x_fecha_emision", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $recibos->fecha_emision->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($recibos->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
	<div id="r_fecha_vencimiento" class="form-group">
		<label id="elh_recibos_fecha_vencimiento" for="x_fecha_vencimiento" class="col-sm-2 control-label ewLabel"><?php echo $recibos->fecha_vencimiento->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $recibos->fecha_vencimiento->CellAttributes() ?>>
<span id="el_recibos_fecha_vencimiento">
<input type="text" data-field="x_fecha_vencimiento" name="x_fecha_vencimiento" id="x_fecha_vencimiento" size="10" value="<?php echo $recibos->fecha_vencimiento->EditValue ?>"<?php echo $recibos->fecha_vencimiento->EditAttributes() ?>>
<?php if (!$recibos->fecha_vencimiento->ReadOnly && !$recibos->fecha_vencimiento->Disabled && !isset($recibos->fecha_vencimiento->EditAttrs["readonly"]) && !isset($recibos->fecha_vencimiento->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("frecibosedit", "x_fecha_vencimiento", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $recibos->fecha_vencimiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($recibos->numero->Visible) { // numero ?>
	<div id="r_numero" class="form-group">
		<label id="elh_recibos_numero" for="x_numero" class="col-sm-2 control-label ewLabel"><?php echo $recibos->numero->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $recibos->numero->CellAttributes() ?>>
<span id="el_recibos_numero">
<input type="text" data-field="x_numero" name="x_numero" id="x_numero" size="6" maxlength="20" value="<?php echo $recibos->numero->EditValue ?>"<?php echo $recibos->numero->EditAttributes() ?>>
</span>
<?php echo $recibos->numero->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($recibos->monto_base->Visible) { // monto_base ?>
	<div id="r_monto_base" class="form-group">
		<label id="elh_recibos_monto_base" for="x_monto_base" class="col-sm-2 control-label ewLabel"><?php echo $recibos->monto_base->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $recibos->monto_base->CellAttributes() ?>>
<span id="el_recibos_monto_base">
<input type="text" data-field="x_monto_base" name="x_monto_base" id="x_monto_base" size="5" value="<?php echo $recibos->monto_base->EditValue ?>"<?php echo $recibos->monto_base->EditAttributes() ?>>
</span>
<?php echo $recibos->monto_base->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($recibos->monto_impuesto->Visible) { // monto_impuesto ?>
	<div id="r_monto_impuesto" class="form-group">
		<label id="elh_recibos_monto_impuesto" for="x_monto_impuesto" class="col-sm-2 control-label ewLabel"><?php echo $recibos->monto_impuesto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $recibos->monto_impuesto->CellAttributes() ?>>
<span id="el_recibos_monto_impuesto">
<input type="text" data-field="x_monto_impuesto" name="x_monto_impuesto" id="x_monto_impuesto" size="5" value="<?php echo $recibos->monto_impuesto->EditValue ?>"<?php echo $recibos->monto_impuesto->EditAttributes() ?>>
</span>
<?php echo $recibos->monto_impuesto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($recibos->monto_total->Visible) { // monto_total ?>
	<div id="r_monto_total" class="form-group">
		<label id="elh_recibos_monto_total" for="x_monto_total" class="col-sm-2 control-label ewLabel"><?php echo $recibos->monto_total->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $recibos->monto_total->CellAttributes() ?>>
<span id="el_recibos_monto_total">
<input type="text" data-field="x_monto_total" name="x_monto_total" id="x_monto_total" size="5" value="<?php echo $recibos->monto_total->EditValue ?>"<?php echo $recibos->monto_total->EditAttributes() ?>>
</span>
<?php echo $recibos->monto_total->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-field="x_id_recibo" name="x_id_recibo" id="x_id_recibo" value="<?php echo ew_HtmlEncode($recibos->id_recibo->CurrentValue) ?>">
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
frecibosedit.Init();
</script>
<?php
$recibos_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$recibos_edit->Page_Terminate();
?>
