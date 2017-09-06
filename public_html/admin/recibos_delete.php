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

$recibos_delete = NULL; // Initialize page object first

class crecibos_delete extends crecibos {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'recibos';

	// Page object name
	var $PageObjName = 'recibos_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("recibos_list.php"));
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
			$this->Page_Terminate("recibos_list.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in recibos class, recibosinfo.php

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
				$sThisKey .= $row['id_recibo'];
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
		$Breadcrumb->Add("list", $this->TableVar, "recibos_list.php", "", $this->TableVar, TRUE);
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
if (!isset($recibos_delete)) $recibos_delete = new crecibos_delete();

// Page init
$recibos_delete->Page_Init();

// Page main
$recibos_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$recibos_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var recibos_delete = new ew_Page("recibos_delete");
recibos_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = recibos_delete.PageID; // For backward compatibility

// Form object
var frecibosdelete = new ew_Form("frecibosdelete");

// Form_CustomValidate event
frecibosdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frecibosdelete.ValidateRequired = true;
<?php } else { ?>
frecibosdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frecibosdelete.Lists["x_id_empresa"] = {"LinkField":"x_id_empresa","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($recibos_delete->Recordset = $recibos_delete->LoadRecordset())
	$recibos_deleteTotalRecs = $recibos_delete->Recordset->RecordCount(); // Get record count
if ($recibos_deleteTotalRecs <= 0) { // No record found, exit
	if ($recibos_delete->Recordset)
		$recibos_delete->Recordset->Close();
	$recibos_delete->Page_Terminate("recibos_list.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $recibos_delete->ShowPageHeader(); ?>
<?php
$recibos_delete->ShowMessage();
?>
<form name="frecibosdelete" id="frecibosdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($recibos_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $recibos_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="recibos">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($recibos_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $recibos->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($recibos->id_empresa->Visible) { // id_empresa ?>
		<th><span id="elh_recibos_id_empresa" class="recibos_id_empresa"><?php echo $recibos->id_empresa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($recibos->fecha_emision->Visible) { // fecha_emision ?>
		<th><span id="elh_recibos_fecha_emision" class="recibos_fecha_emision"><?php echo $recibos->fecha_emision->FldCaption() ?></span></th>
<?php } ?>
<?php if ($recibos->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
		<th><span id="elh_recibos_fecha_vencimiento" class="recibos_fecha_vencimiento"><?php echo $recibos->fecha_vencimiento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($recibos->numero->Visible) { // numero ?>
		<th><span id="elh_recibos_numero" class="recibos_numero"><?php echo $recibos->numero->FldCaption() ?></span></th>
<?php } ?>
<?php if ($recibos->monto_base->Visible) { // monto_base ?>
		<th><span id="elh_recibos_monto_base" class="recibos_monto_base"><?php echo $recibos->monto_base->FldCaption() ?></span></th>
<?php } ?>
<?php if ($recibos->monto_impuesto->Visible) { // monto_impuesto ?>
		<th><span id="elh_recibos_monto_impuesto" class="recibos_monto_impuesto"><?php echo $recibos->monto_impuesto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($recibos->monto_total->Visible) { // monto_total ?>
		<th><span id="elh_recibos_monto_total" class="recibos_monto_total"><?php echo $recibos->monto_total->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$recibos_delete->RecCnt = 0;
$i = 0;
while (!$recibos_delete->Recordset->EOF) {
	$recibos_delete->RecCnt++;
	$recibos_delete->RowCnt++;

	// Set row properties
	$recibos->ResetAttrs();
	$recibos->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$recibos_delete->LoadRowValues($recibos_delete->Recordset);

	// Render row
	$recibos_delete->RenderRow();
?>
	<tr<?php echo $recibos->RowAttributes() ?>>
<?php if ($recibos->id_empresa->Visible) { // id_empresa ?>
		<td<?php echo $recibos->id_empresa->CellAttributes() ?>>
<span id="el<?php echo $recibos_delete->RowCnt ?>_recibos_id_empresa" class="recibos_id_empresa">
<span<?php echo $recibos->id_empresa->ViewAttributes() ?>>
<?php echo $recibos->id_empresa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($recibos->fecha_emision->Visible) { // fecha_emision ?>
		<td<?php echo $recibos->fecha_emision->CellAttributes() ?>>
<span id="el<?php echo $recibos_delete->RowCnt ?>_recibos_fecha_emision" class="recibos_fecha_emision">
<span<?php echo $recibos->fecha_emision->ViewAttributes() ?>>
<?php echo $recibos->fecha_emision->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($recibos->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
		<td<?php echo $recibos->fecha_vencimiento->CellAttributes() ?>>
<span id="el<?php echo $recibos_delete->RowCnt ?>_recibos_fecha_vencimiento" class="recibos_fecha_vencimiento">
<span<?php echo $recibos->fecha_vencimiento->ViewAttributes() ?>>
<?php echo $recibos->fecha_vencimiento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($recibos->numero->Visible) { // numero ?>
		<td<?php echo $recibos->numero->CellAttributes() ?>>
<span id="el<?php echo $recibos_delete->RowCnt ?>_recibos_numero" class="recibos_numero">
<span<?php echo $recibos->numero->ViewAttributes() ?>>
<?php echo $recibos->numero->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($recibos->monto_base->Visible) { // monto_base ?>
		<td<?php echo $recibos->monto_base->CellAttributes() ?>>
<span id="el<?php echo $recibos_delete->RowCnt ?>_recibos_monto_base" class="recibos_monto_base">
<span<?php echo $recibos->monto_base->ViewAttributes() ?>>
<?php echo $recibos->monto_base->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($recibos->monto_impuesto->Visible) { // monto_impuesto ?>
		<td<?php echo $recibos->monto_impuesto->CellAttributes() ?>>
<span id="el<?php echo $recibos_delete->RowCnt ?>_recibos_monto_impuesto" class="recibos_monto_impuesto">
<span<?php echo $recibos->monto_impuesto->ViewAttributes() ?>>
<?php echo $recibos->monto_impuesto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($recibos->monto_total->Visible) { // monto_total ?>
		<td<?php echo $recibos->monto_total->CellAttributes() ?>>
<span id="el<?php echo $recibos_delete->RowCnt ?>_recibos_monto_total" class="recibos_monto_total">
<span<?php echo $recibos->monto_total->ViewAttributes() ?>>
<?php echo $recibos->monto_total->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$recibos_delete->Recordset->MoveNext();
}
$recibos_delete->Recordset->Close();
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
frecibosdelete.Init();
</script>
<?php
$recibos_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$recibos_delete->Page_Terminate();
?>
