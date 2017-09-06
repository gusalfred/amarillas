<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "avisos_info.php" ?>
<?php include_once "empleados_info.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$avisos_delete = NULL; // Initialize page object first

class cavisos_delete extends cavisos {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'avisos';

	// Page object name
	var $PageObjName = 'avisos_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("avisos_list.php"));
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
			$this->Page_Terminate("avisos_list.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in avisos class, avisosinfo.php

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

		$this->url_aviso->CellCssStyle = "width: 300px;";
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
				$sThisKey .= $row['id_aviso'];
				$this->LoadDbValues($row);
				$this->url_aviso->OldUploadPath = '../uploads/avisos/';
				@unlink(ew_UploadPathEx(TRUE, $this->url_aviso->OldUploadPath) . $row['url_aviso']);
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
		$Breadcrumb->Add("list", $this->TableVar, "avisos_list.php", "", $this->TableVar, TRUE);
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
if (!isset($avisos_delete)) $avisos_delete = new cavisos_delete();

// Page init
$avisos_delete->Page_Init();

// Page main
$avisos_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$avisos_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var avisos_delete = new ew_Page("avisos_delete");
avisos_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = avisos_delete.PageID; // For backward compatibility

// Form object
var favisosdelete = new ew_Form("favisosdelete");

// Form_CustomValidate event
favisosdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
favisosdelete.ValidateRequired = true;
<?php } else { ?>
favisosdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
favisosdelete.Lists["x_id_empresa"] = {"LinkField":"x_id_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_documento_tipo","x_documento_numero",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($avisos_delete->Recordset = $avisos_delete->LoadRecordset())
	$avisos_deleteTotalRecs = $avisos_delete->Recordset->RecordCount(); // Get record count
if ($avisos_deleteTotalRecs <= 0) { // No record found, exit
	if ($avisos_delete->Recordset)
		$avisos_delete->Recordset->Close();
	$avisos_delete->Page_Terminate("avisos_list.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $avisos_delete->ShowPageHeader(); ?>
<?php
$avisos_delete->ShowMessage();
?>
<form name="favisosdelete" id="favisosdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($avisos_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $avisos_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="avisos">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($avisos_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $avisos->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($avisos->fecha->Visible) { // fecha ?>
		<th><span id="elh_avisos_fecha" class="avisos_fecha"><?php echo $avisos->fecha->FldCaption() ?></span></th>
<?php } ?>
<?php if ($avisos->id_empresa->Visible) { // id_empresa ?>
		<th><span id="elh_avisos_id_empresa" class="avisos_id_empresa"><?php echo $avisos->id_empresa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($avisos->nombre->Visible) { // nombre ?>
		<th><span id="elh_avisos_nombre" class="avisos_nombre"><?php echo $avisos->nombre->FldCaption() ?></span></th>
<?php } ?>
<?php if ($avisos->url_aviso->Visible) { // url_aviso ?>
		<th><span id="elh_avisos_url_aviso" class="avisos_url_aviso"><?php echo $avisos->url_aviso->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$avisos_delete->RecCnt = 0;
$i = 0;
while (!$avisos_delete->Recordset->EOF) {
	$avisos_delete->RecCnt++;
	$avisos_delete->RowCnt++;

	// Set row properties
	$avisos->ResetAttrs();
	$avisos->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$avisos_delete->LoadRowValues($avisos_delete->Recordset);

	// Render row
	$avisos_delete->RenderRow();
?>
	<tr<?php echo $avisos->RowAttributes() ?>>
<?php if ($avisos->fecha->Visible) { // fecha ?>
		<td<?php echo $avisos->fecha->CellAttributes() ?>>
<span id="el<?php echo $avisos_delete->RowCnt ?>_avisos_fecha" class="avisos_fecha">
<span<?php echo $avisos->fecha->ViewAttributes() ?>>
<?php echo $avisos->fecha->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($avisos->id_empresa->Visible) { // id_empresa ?>
		<td<?php echo $avisos->id_empresa->CellAttributes() ?>>
<span id="el<?php echo $avisos_delete->RowCnt ?>_avisos_id_empresa" class="avisos_id_empresa">
<span<?php echo $avisos->id_empresa->ViewAttributes() ?>>
<?php echo $avisos->id_empresa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($avisos->nombre->Visible) { // nombre ?>
		<td<?php echo $avisos->nombre->CellAttributes() ?>>
<span id="el<?php echo $avisos_delete->RowCnt ?>_avisos_nombre" class="avisos_nombre">
<span<?php echo $avisos->nombre->ViewAttributes() ?>>
<?php echo $avisos->nombre->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($avisos->url_aviso->Visible) { // url_aviso ?>
		<td<?php echo $avisos->url_aviso->CellAttributes() ?>>
<span id="el<?php echo $avisos_delete->RowCnt ?>_avisos_url_aviso" class="avisos_url_aviso">
<span>
<?php echo ew_GetFileViewTag($avisos->url_aviso, $avisos->url_aviso->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$avisos_delete->Recordset->MoveNext();
}
$avisos_delete->Recordset->Close();
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
favisosdelete.Init();
</script>
<?php
$avisos_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$avisos_delete->Page_Terminate();
?>
