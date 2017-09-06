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

$avisos_list = NULL; // Initialize page object first

class cavisos_list extends cavisos {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'avisos';

	// Page object name
	var $PageObjName = 'avisos_list';

	// Grid form hidden field names
	var $FormName = 'favisoslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Table object (avisos)
		if (!isset($GLOBALS["avisos"]) || get_class($GLOBALS["avisos"]) == "cavisos") {
			$GLOBALS["avisos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["avisos"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "avisos_add.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "avisos_delete.php";
		$this->MultiUpdateUrl = "avisos_update.php";

		// Table object (empleados)
		if (!isset($GLOBALS['empleados'])) $GLOBALS['empleados'] = new cempleados();

		// User table object (empleados)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cempleados();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'avisos', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}

		// Update last accessed time
		if ($UserProfile->IsValidUser(CurrentUserName(), session_id())) {
		} else {
			echo $Language->Phrase("UserProfileCorrupted");
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set "checkbox" visible
		if (count($this->CustomActions) > 0)
			$this->ListOptions->Items["checkbox"]->Visible = TRUE;
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = EW_SELECT_LIMIT;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id_aviso->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_aviso->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id_aviso, $Default, FALSE); // id_aviso
		$this->BuildSearchSql($sWhere, $this->fecha, $Default, FALSE); // fecha
		$this->BuildSearchSql($sWhere, $this->id_empresa, $Default, FALSE); // id_empresa
		$this->BuildSearchSql($sWhere, $this->nombre, $Default, FALSE); // nombre
		$this->BuildSearchSql($sWhere, $this->url_aviso, $Default, FALSE); // url_aviso

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id_aviso->AdvancedSearch->Save(); // id_aviso
			$this->fecha->AdvancedSearch->Save(); // fecha
			$this->id_empresa->AdvancedSearch->Save(); // id_empresa
			$this->nombre->AdvancedSearch->Save(); // nombre
			$this->url_aviso->AdvancedSearch->Save(); // url_aviso
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Check if search parm exists
	function CheckSearchParms() {
		if ($this->id_aviso->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_empresa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nombre->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->url_aviso->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->id_aviso->AdvancedSearch->UnsetSession();
		$this->fecha->AdvancedSearch->UnsetSession();
		$this->id_empresa->AdvancedSearch->UnsetSession();
		$this->nombre->AdvancedSearch->UnsetSession();
		$this->url_aviso->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->id_aviso->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->id_empresa->AdvancedSearch->Load();
		$this->nombre->AdvancedSearch->Load();
		$this->url_aviso->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->fecha); // fecha
			$this->UpdateSort($this->id_empresa); // id_empresa
			$this->UpdateSort($this->nombre); // nombre
			$this->UpdateSort($this->url_aviso); // url_aviso
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
				$this->fecha->setSort("DESC");
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->fecha->setSort("");
				$this->id_empresa->setSort("");
				$this->nombre->setSort("");
				$this->url_aviso->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;

		// "detail_avisos_ubicaciones"
		$item = &$this->ListOptions->Add("detail_avisos_ubicaciones");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'avisos_ubicaciones') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["avisos_ubicaciones_grid"])) $GLOBALS["avisos_ubicaciones_grid"] = new cavisos_ubicaciones_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssStyle = "white-space: nowrap;";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = FALSE;
			$item->ShowInButtonGroup = FALSE;
		}

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_avisos_ubicaciones"
		$oListOpt = &$this->ListOptions->Items["detail_avisos_ubicaciones"];
		if ($Security->AllowList(CurrentProjectID() . 'avisos_ubicaciones')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("avisos_ubicaciones", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("avisos_ubicaciones_list.php?" . EW_TABLE_SHOW_MASTER . "=avisos&fk_id_aviso=" . urlencode(strval($this->id_aviso->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["avisos_ubicaciones_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'avisos_ubicaciones')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=avisos_ubicaciones")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "avisos_ubicaciones";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_aviso->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_avisos_ubicaciones");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=avisos_ubicaciones") . "\">" . $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["avisos_ubicaciones"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["avisos_ubicaciones"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'avisos_ubicaciones') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "avisos_ubicaciones";
		}

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink) . "\">" . $Language->Phrase("AddMasterDetailLink") . "</a>";
			$item->Visible = ($DetailTableLink <> "" && $Security->CanAdd());

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detailadd_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.favisoslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
			}
		}
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"favisoslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch())
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// id_aviso

		$this->id_aviso->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_aviso"]);
		if ($this->id_aviso->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_aviso->AdvancedSearch->SearchOperator = @$_GET["z_id_aviso"];

		// fecha
		$this->fecha->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha"]);
		if ($this->fecha->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha->AdvancedSearch->SearchOperator = @$_GET["z_fecha"];

		// id_empresa
		$this->id_empresa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_empresa"]);
		if ($this->id_empresa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_empresa->AdvancedSearch->SearchOperator = @$_GET["z_id_empresa"];

		// nombre
		$this->nombre->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nombre"]);
		if ($this->nombre->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nombre->AdvancedSearch->SearchOperator = @$_GET["z_nombre"];

		// url_aviso
		$this->url_aviso->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_url_aviso"]);
		if ($this->url_aviso->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->url_aviso->AdvancedSearch->SearchOperator = @$_GET["z_url_aviso"];
	}

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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_aviso")) <> "")
			$this->id_aviso->CurrentValue = $this->getKey("id_aviso"); // id_aviso
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
				$this->url_aviso->LinkAttrs["data-rel"] = "avisos_x" . $this->RowCnt . "_url_aviso";
				$this->url_aviso->LinkAttrs["class"] = "ewLightbox";
			}
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = 'readonly';
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue, 7), 7));

			// id_empresa
			$this->id_empresa->EditAttrs["class"] = "form-control";
			$this->id_empresa->EditCustomAttributes = 'disabled';
			$this->id_empresa->EditValue = ew_HtmlEncode($this->id_empresa->AdvancedSearch->SearchValue);
			if (strval($this->id_empresa->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`id_empresa`" . ew_SearchString("=", $this->id_empresa->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER);
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
					$this->id_empresa->EditValue = ew_HtmlEncode($this->id_empresa->AdvancedSearch->SearchValue);
				}
			} else {
				$this->id_empresa->EditValue = NULL;
			}

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->AdvancedSearch->SearchValue);

			// url_aviso
			$this->url_aviso->EditAttrs["class"] = "form-control";
			$this->url_aviso->EditCustomAttributes = "";
			$this->url_aviso->EditValue = ew_HtmlEncode($this->url_aviso->AdvancedSearch->SearchValue);
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckInteger($this->id_empresa->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id_empresa->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->id_aviso->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->id_empresa->AdvancedSearch->Load();
		$this->nombre->AdvancedSearch->Load();
		$this->url_aviso->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
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
if (!isset($avisos_list)) $avisos_list = new cavisos_list();

// Page init
$avisos_list->Page_Init();

// Page main
$avisos_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$avisos_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var avisos_list = new ew_Page("avisos_list");
avisos_list.PageID = "list"; // Page ID
var EW_PAGE_ID = avisos_list.PageID; // For backward compatibility

// Form object
var favisoslist = new ew_Form("favisoslist");
favisoslist.FormKeyCountName = '<?php echo $avisos_list->FormKeyCountName ?>';

// Form_CustomValidate event
favisoslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
favisoslist.ValidateRequired = true;
<?php } else { ?>
favisoslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
favisoslist.Lists["x_id_empresa"] = {"LinkField":"x_id_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_documento_tipo","x_documento_numero",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var favisoslistsrch = new ew_Form("favisoslistsrch");

// Validate function for search
favisoslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_id_empresa");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($avisos->id_empresa->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
favisoslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
favisoslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
favisoslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
favisoslistsrch.Lists["x_id_empresa"] = {"LinkField":"x_id_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","x_documento_tipo","x_documento_numero",""],"ParentFields":[],"FilterFields":[],"Options":[]};
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($avisos_list->TotalRecs > 0 && $avisos_list->ExportOptions->Visible()) { ?>
<?php $avisos_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($avisos_list->SearchOptions->Visible()) { ?>
<?php $avisos_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($avisos_list->TotalRecs <= 0)
			$avisos_list->TotalRecs = $avisos->SelectRecordCount();
	} else {
		if (!$avisos_list->Recordset && ($avisos_list->Recordset = $avisos_list->LoadRecordset()))
			$avisos_list->TotalRecs = $avisos_list->Recordset->RecordCount();
	}
	$avisos_list->StartRec = 1;
	if ($avisos_list->DisplayRecs <= 0 || ($avisos->Export <> "" && $avisos->ExportAll)) // Display all records
		$avisos_list->DisplayRecs = $avisos_list->TotalRecs;
	if (!($avisos->Export <> "" && $avisos->ExportAll))
		$avisos_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$avisos_list->Recordset = $avisos_list->LoadRecordset($avisos_list->StartRec-1, $avisos_list->DisplayRecs);

	// Set no record found message
	if ($avisos->CurrentAction == "" && $avisos_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$avisos_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($avisos_list->SearchWhere == "0=101")
			$avisos_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$avisos_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$avisos_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($avisos->Export == "" && $avisos->CurrentAction == "") { ?>
<form name="favisoslistsrch" id="favisoslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($avisos_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="favisoslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="avisos">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$avisos_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$avisos->RowType = EW_ROWTYPE_SEARCH;

// Render row
$avisos->ResetAttrs();
$avisos_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($avisos->id_empresa->Visible) { // id_empresa ?>
	<div id="xsc_id_empresa" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $avisos->id_empresa->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_empresa" id="z_id_empresa" value="="></span>
		<span class="ewSearchField">
<?php
	$wrkonchange = trim(" " . @$avisos->id_empresa->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$avisos->id_empresa->EditAttrs["onchange"] = "";
?>
<span id="as_x_id_empresa" style="white-space: nowrap; z-index: 8970">
	<input type="text" name="sv_x_id_empresa" id="sv_x_id_empresa" value="<?php echo $avisos->id_empresa->EditValue ?>" size="30"<?php echo $avisos->id_empresa->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_id_empresa" name="x_id_empresa" id="x_id_empresa" value="<?php echo ew_HtmlEncode($avisos->id_empresa->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
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
favisoslistsrch.CreateAutoSuggest("x_id_empresa", false);
</script>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($avisos->nombre->Visible) { // nombre ?>
	<div id="xsc_nombre" class="ewCell form-group">
		<label for="x_nombre" class="ewSearchCaption ewLabel"><?php echo $avisos->nombre->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombre" id="z_nombre" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="100" value="<?php echo $avisos->nombre->EditValue ?>"<?php echo $avisos->nombre->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $avisos_list->ShowPageHeader(); ?>
<?php
$avisos_list->ShowMessage();
?>
<?php if ($avisos_list->TotalRecs > 0 || $avisos->CurrentAction <> "") { ?>
<div class="ewGrid">
<div class="ewGridUpperPanel">
<?php if ($avisos->CurrentAction <> "gridadd" && $avisos->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($avisos_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="favisoslist" id="favisoslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($avisos_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $avisos_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="avisos">
<div id="gmp_avisos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($avisos_list->TotalRecs > 0) { ?>
<table id="tbl_avisoslist" class="table ewTable">
<?php echo $avisos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$avisos->RowType = EW_ROWTYPE_HEADER;

// Render list options
$avisos_list->RenderListOptions();

// Render list options (header, left)
$avisos_list->ListOptions->Render("header", "left");
?>
<?php if ($avisos->fecha->Visible) { // fecha ?>
	<?php if ($avisos->SortUrl($avisos->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_avisos_fecha" class="avisos_fecha"><div class="ewTableHeaderCaption"><?php echo $avisos->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $avisos->SortUrl($avisos->fecha) ?>',1);"><div id="elh_avisos_fecha" class="avisos_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $avisos->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($avisos->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($avisos->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($avisos->id_empresa->Visible) { // id_empresa ?>
	<?php if ($avisos->SortUrl($avisos->id_empresa) == "") { ?>
		<th data-name="id_empresa"><div id="elh_avisos_id_empresa" class="avisos_id_empresa"><div class="ewTableHeaderCaption"><?php echo $avisos->id_empresa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_empresa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $avisos->SortUrl($avisos->id_empresa) ?>',1);"><div id="elh_avisos_id_empresa" class="avisos_id_empresa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $avisos->id_empresa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($avisos->id_empresa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($avisos->id_empresa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($avisos->nombre->Visible) { // nombre ?>
	<?php if ($avisos->SortUrl($avisos->nombre) == "") { ?>
		<th data-name="nombre"><div id="elh_avisos_nombre" class="avisos_nombre"><div class="ewTableHeaderCaption"><?php echo $avisos->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $avisos->SortUrl($avisos->nombre) ?>',1);"><div id="elh_avisos_nombre" class="avisos_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $avisos->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($avisos->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($avisos->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($avisos->url_aviso->Visible) { // url_aviso ?>
	<?php if ($avisos->SortUrl($avisos->url_aviso) == "") { ?>
		<th data-name="url_aviso"><div id="elh_avisos_url_aviso" class="avisos_url_aviso"><div class="ewTableHeaderCaption" style="width: 300px;"><?php echo $avisos->url_aviso->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="url_aviso"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $avisos->SortUrl($avisos->url_aviso) ?>',1);"><div id="elh_avisos_url_aviso" class="avisos_url_aviso">
			<div class="ewTableHeaderBtn" style="width: 300px;"><span class="ewTableHeaderCaption"><?php echo $avisos->url_aviso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($avisos->url_aviso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($avisos->url_aviso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$avisos_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($avisos->ExportAll && $avisos->Export <> "") {
	$avisos_list->StopRec = $avisos_list->TotalRecs;
} else {

	// Set the last record to display
	if ($avisos_list->TotalRecs > $avisos_list->StartRec + $avisos_list->DisplayRecs - 1)
		$avisos_list->StopRec = $avisos_list->StartRec + $avisos_list->DisplayRecs - 1;
	else
		$avisos_list->StopRec = $avisos_list->TotalRecs;
}
$avisos_list->RecCnt = $avisos_list->StartRec - 1;
if ($avisos_list->Recordset && !$avisos_list->Recordset->EOF) {
	$avisos_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $avisos_list->StartRec > 1)
		$avisos_list->Recordset->Move($avisos_list->StartRec - 1);
} elseif (!$avisos->AllowAddDeleteRow && $avisos_list->StopRec == 0) {
	$avisos_list->StopRec = $avisos->GridAddRowCount;
}

// Initialize aggregate
$avisos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$avisos->ResetAttrs();
$avisos_list->RenderRow();
while ($avisos_list->RecCnt < $avisos_list->StopRec) {
	$avisos_list->RecCnt++;
	if (intval($avisos_list->RecCnt) >= intval($avisos_list->StartRec)) {
		$avisos_list->RowCnt++;

		// Set up key count
		$avisos_list->KeyCount = $avisos_list->RowIndex;

		// Init row class and style
		$avisos->ResetAttrs();
		$avisos->CssClass = "";
		if ($avisos->CurrentAction == "gridadd") {
		} else {
			$avisos_list->LoadRowValues($avisos_list->Recordset); // Load row values
		}
		$avisos->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$avisos->RowAttrs = array_merge($avisos->RowAttrs, array('data-rowindex'=>$avisos_list->RowCnt, 'id'=>'r' . $avisos_list->RowCnt . '_avisos', 'data-rowtype'=>$avisos->RowType));

		// Render row
		$avisos_list->RenderRow();

		// Render list options
		$avisos_list->RenderListOptions();
?>
	<tr<?php echo $avisos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$avisos_list->ListOptions->Render("body", "left", $avisos_list->RowCnt);
?>
	<?php if ($avisos->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $avisos->fecha->CellAttributes() ?>>
<span<?php echo $avisos->fecha->ViewAttributes() ?>>
<?php echo $avisos->fecha->ListViewValue() ?></span>
<a id="<?php echo $avisos_list->PageObjName . "_row_" . $avisos_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($avisos->id_empresa->Visible) { // id_empresa ?>
		<td data-name="id_empresa"<?php echo $avisos->id_empresa->CellAttributes() ?>>
<span<?php echo $avisos->id_empresa->ViewAttributes() ?>>
<?php echo $avisos->id_empresa->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($avisos->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $avisos->nombre->CellAttributes() ?>>
<span<?php echo $avisos->nombre->ViewAttributes() ?>>
<?php echo $avisos->nombre->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($avisos->url_aviso->Visible) { // url_aviso ?>
		<td data-name="url_aviso"<?php echo $avisos->url_aviso->CellAttributes() ?>>
<span>
<?php echo ew_GetFileViewTag($avisos->url_aviso, $avisos->url_aviso->ListViewValue()) ?>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$avisos_list->ListOptions->Render("body", "right", $avisos_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($avisos->CurrentAction <> "gridadd")
		$avisos_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($avisos->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($avisos_list->Recordset)
	$avisos_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($avisos->CurrentAction <> "gridadd" && $avisos->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($avisos_list->Pager)) $avisos_list->Pager = new cPrevNextPager($avisos_list->StartRec, $avisos_list->DisplayRecs, $avisos_list->TotalRecs) ?>
<?php if ($avisos_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($avisos_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $avisos_list->PageUrl() ?>start=<?php echo $avisos_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($avisos_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $avisos_list->PageUrl() ?>start=<?php echo $avisos_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $avisos_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($avisos_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $avisos_list->PageUrl() ?>start=<?php echo $avisos_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($avisos_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $avisos_list->PageUrl() ?>start=<?php echo $avisos_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $avisos_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $avisos_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $avisos_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $avisos_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($avisos_list->TotalRecs == 0 && $avisos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($avisos_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
favisoslistsrch.Init();
favisoslist.Init();
</script>
<?php
$avisos_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$avisos_list->Page_Terminate();
?>
