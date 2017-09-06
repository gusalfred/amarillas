<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "categorias_nivel1_info.php" ?>
<?php include_once "empleados_info.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$categorias_nivel1_delete = NULL; // Initialize page object first

class ccategorias_nivel1_delete extends ccategorias_nivel1 {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'categorias_nivel1';

	// Page object name
	var $PageObjName = 'categorias_nivel1_delete';

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

		// Table object (categorias_nivel1)
		if (!isset($GLOBALS["categorias_nivel1"]) || get_class($GLOBALS["categorias_nivel1"]) == "ccategorias_nivel1") {
			$GLOBALS["categorias_nivel1"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["categorias_nivel1"];
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
			define("EW_TABLE_NAME", 'categorias_nivel1', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("categorias_nivel1_list.php"));
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

			// Process auto fill for detail table 'categorias_nivel2'
			if (@$_POST["grid"] == "fcategorias_nivel2grid") {
				if (!isset($GLOBALS["categorias_nivel2_grid"])) $GLOBALS["categorias_nivel2_grid"] = new ccategorias_nivel2_grid;
				$GLOBALS["categorias_nivel2_grid"]->Page_Init();
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
		global $EW_EXPORT, $categorias_nivel1;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($categorias_nivel1);
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
			$this->Page_Terminate("categorias_nivel1_list.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in categorias_nivel1 class, categorias_nivel1info.php

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
		$this->id_categoria_nivel1->setDbValue($rs->fields('id_categoria_nivel1'));
		$this->categoria->setDbValue($rs->fields('categoria'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->slug->setDbValue($rs->fields('slug'));
		$this->imagen->Upload->DbValue = $rs->fields('imagen');
		$this->imagen->CurrentValue = $this->imagen->Upload->DbValue;
		$this->orden->setDbValue($rs->fields('orden'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_categoria_nivel1->DbValue = $row['id_categoria_nivel1'];
		$this->categoria->DbValue = $row['categoria'];
		$this->descripcion->DbValue = $row['descripcion'];
		$this->slug->DbValue = $row['slug'];
		$this->imagen->Upload->DbValue = $row['imagen'];
		$this->orden->DbValue = $row['orden'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_categoria_nivel1
		// categoria
		// descripcion
		// slug
		// imagen
		// orden

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_categoria_nivel1
			$this->id_categoria_nivel1->ViewValue = $this->id_categoria_nivel1->CurrentValue;
			$this->id_categoria_nivel1->ViewCustomAttributes = "";

			// categoria
			$this->categoria->ViewValue = $this->categoria->CurrentValue;
			$this->categoria->ViewCustomAttributes = "";

			// descripcion
			$this->descripcion->ViewValue = $this->descripcion->CurrentValue;
			$this->descripcion->ViewCustomAttributes = "";

			// slug
			$this->slug->ViewValue = $this->slug->CurrentValue;
			$this->slug->ViewCustomAttributes = "";

			// imagen
			$this->imagen->UploadPath = '../uploads/categorias/';
			if (!ew_Empty($this->imagen->Upload->DbValue)) {
				$this->imagen->ImageAlt = $this->imagen->FldAlt();
				$this->imagen->ViewValue = ew_UploadPathEx(FALSE, $this->imagen->UploadPath) . $this->imagen->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->imagen->ViewValue = ew_UploadPathEx(TRUE, $this->imagen->UploadPath) . $this->imagen->Upload->DbValue;
				}
			} else {
				$this->imagen->ViewValue = "";
			}
			$this->imagen->ViewCustomAttributes = "";

			// orden
			$this->orden->ViewValue = $this->orden->CurrentValue;
			$this->orden->ViewCustomAttributes = "";

			// categoria
			$this->categoria->LinkCustomAttributes = "";
			$this->categoria->HrefValue = "";
			$this->categoria->TooltipValue = "";

			// imagen
			$this->imagen->LinkCustomAttributes = "";
			$this->imagen->UploadPath = '../uploads/categorias/';
			if (!ew_Empty($this->imagen->Upload->DbValue)) {
				$this->imagen->HrefValue = ew_UploadPathEx(FALSE, $this->imagen->UploadPath) . $this->imagen->Upload->DbValue; // Add prefix/suffix
				$this->imagen->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->imagen->HrefValue = ew_ConvertFullUrl($this->imagen->HrefValue);
			} else {
				$this->imagen->HrefValue = "";
			}
			$this->imagen->HrefValue2 = $this->imagen->UploadPath . $this->imagen->Upload->DbValue;
			$this->imagen->TooltipValue = "";
			if ($this->imagen->UseColorbox) {
				$this->imagen->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->imagen->LinkAttrs["data-rel"] = "categorias_nivel1_x_imagen";
				$this->imagen->LinkAttrs["class"] = "ewLightbox";
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
				$sThisKey .= $row['id_categoria_nivel1'];
				$this->LoadDbValues($row);
				$this->imagen->OldUploadPath = '../uploads/categorias/';
				@unlink(ew_UploadPathEx(TRUE, $this->imagen->OldUploadPath) . $row['imagen']);
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
		$Breadcrumb->Add("list", $this->TableVar, "categorias_nivel1_list.php", "", $this->TableVar, TRUE);
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
if (!isset($categorias_nivel1_delete)) $categorias_nivel1_delete = new ccategorias_nivel1_delete();

// Page init
$categorias_nivel1_delete->Page_Init();

// Page main
$categorias_nivel1_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$categorias_nivel1_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var categorias_nivel1_delete = new ew_Page("categorias_nivel1_delete");
categorias_nivel1_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = categorias_nivel1_delete.PageID; // For backward compatibility

// Form object
var fcategorias_nivel1delete = new ew_Form("fcategorias_nivel1delete");

// Form_CustomValidate event
fcategorias_nivel1delete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcategorias_nivel1delete.ValidateRequired = true;
<?php } else { ?>
fcategorias_nivel1delete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($categorias_nivel1_delete->Recordset = $categorias_nivel1_delete->LoadRecordset())
	$categorias_nivel1_deleteTotalRecs = $categorias_nivel1_delete->Recordset->RecordCount(); // Get record count
if ($categorias_nivel1_deleteTotalRecs <= 0) { // No record found, exit
	if ($categorias_nivel1_delete->Recordset)
		$categorias_nivel1_delete->Recordset->Close();
	$categorias_nivel1_delete->Page_Terminate("categorias_nivel1_list.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $categorias_nivel1_delete->ShowPageHeader(); ?>
<?php
$categorias_nivel1_delete->ShowMessage();
?>
<form name="fcategorias_nivel1delete" id="fcategorias_nivel1delete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($categorias_nivel1_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $categorias_nivel1_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="categorias_nivel1">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($categorias_nivel1_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $categorias_nivel1->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($categorias_nivel1->categoria->Visible) { // categoria ?>
		<th><span id="elh_categorias_nivel1_categoria" class="categorias_nivel1_categoria"><?php echo $categorias_nivel1->categoria->FldCaption() ?></span></th>
<?php } ?>
<?php if ($categorias_nivel1->imagen->Visible) { // imagen ?>
		<th><span id="elh_categorias_nivel1_imagen" class="categorias_nivel1_imagen"><?php echo $categorias_nivel1->imagen->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$categorias_nivel1_delete->RecCnt = 0;
$i = 0;
while (!$categorias_nivel1_delete->Recordset->EOF) {
	$categorias_nivel1_delete->RecCnt++;
	$categorias_nivel1_delete->RowCnt++;

	// Set row properties
	$categorias_nivel1->ResetAttrs();
	$categorias_nivel1->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$categorias_nivel1_delete->LoadRowValues($categorias_nivel1_delete->Recordset);

	// Render row
	$categorias_nivel1_delete->RenderRow();
?>
	<tr<?php echo $categorias_nivel1->RowAttributes() ?>>
<?php if ($categorias_nivel1->categoria->Visible) { // categoria ?>
		<td<?php echo $categorias_nivel1->categoria->CellAttributes() ?>>
<span id="el<?php echo $categorias_nivel1_delete->RowCnt ?>_categorias_nivel1_categoria" class="categorias_nivel1_categoria">
<span<?php echo $categorias_nivel1->categoria->ViewAttributes() ?>>
<?php echo $categorias_nivel1->categoria->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($categorias_nivel1->imagen->Visible) { // imagen ?>
		<td<?php echo $categorias_nivel1->imagen->CellAttributes() ?>>
<span id="el<?php echo $categorias_nivel1_delete->RowCnt ?>_categorias_nivel1_imagen" class="categorias_nivel1_imagen">
<span>
<?php echo ew_GetFileViewTag($categorias_nivel1->imagen, $categorias_nivel1->imagen->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$categorias_nivel1_delete->Recordset->MoveNext();
}
$categorias_nivel1_delete->Recordset->Close();
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
fcategorias_nivel1delete.Init();
</script>
<?php
$categorias_nivel1_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$categorias_nivel1_delete->Page_Terminate();
?>
