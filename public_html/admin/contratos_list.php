<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "contratos_info.php" ?>
<?php include_once "empleados_info.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$contratos_list = NULL; // Initialize page object first

class ccontratos_list extends ccontratos {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'contratos';

	// Page object name
	var $PageObjName = 'contratos_list';

	// Grid form hidden field names
	var $FormName = 'fcontratoslist';
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

		// Table object (contratos)
		if (!isset($GLOBALS["contratos"]) || get_class($GLOBALS["contratos"]) == "ccontratos") {
			$GLOBALS["contratos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["contratos"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "contratos_add.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "contratos_delete.php";
		$this->MultiUpdateUrl = "contratos_update.php";

		// Table object (empleados)
		if (!isset($GLOBALS['empleados'])) $GLOBALS['empleados'] = new cempleados();

		// User table object (empleados)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cempleados();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'contratos', TRUE);

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
		global $EW_EXPORT, $contratos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($contratos);
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
			$this->id_contrato->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_contrato->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id_contrato, $Default, FALSE); // id_contrato
		$this->BuildSearchSql($sWhere, $this->id_empresa, $Default, FALSE); // id_empresa
		$this->BuildSearchSql($sWhere, $this->tipo, $Default, FALSE); // tipo
		$this->BuildSearchSql($sWhere, $this->numero, $Default, FALSE); // numero
		$this->BuildSearchSql($sWhere, $this->fecha, $Default, FALSE); // fecha
		$this->BuildSearchSql($sWhere, $this->id_plan, $Default, FALSE); // id_plan
		$this->BuildSearchSql($sWhere, $this->fecha_desde, $Default, FALSE); // fecha_desde
		$this->BuildSearchSql($sWhere, $this->fecha_hasta, $Default, FALSE); // fecha_hasta
		$this->BuildSearchSql($sWhere, $this->id_empleado, $Default, FALSE); // id_empleado
		$this->BuildSearchSql($sWhere, $this->id_recibo, $Default, FALSE); // id_recibo
		$this->BuildSearchSql($sWhere, $this->monto, $Default, FALSE); // monto
		$this->BuildSearchSql($sWhere, $this->descuento, $Default, FALSE); // descuento
		$this->BuildSearchSql($sWhere, $this->sub_total, $Default, FALSE); // sub_total
		$this->BuildSearchSql($sWhere, $this->impuesto, $Default, FALSE); // impuesto
		$this->BuildSearchSql($sWhere, $this->monto_total, $Default, FALSE); // monto_total
		$this->BuildSearchSql($sWhere, $this->observaciones, $Default, FALSE); // observaciones
		$this->BuildSearchSql($sWhere, $this->estatus, $Default, FALSE); // estatus

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id_contrato->AdvancedSearch->Save(); // id_contrato
			$this->id_empresa->AdvancedSearch->Save(); // id_empresa
			$this->tipo->AdvancedSearch->Save(); // tipo
			$this->numero->AdvancedSearch->Save(); // numero
			$this->fecha->AdvancedSearch->Save(); // fecha
			$this->id_plan->AdvancedSearch->Save(); // id_plan
			$this->fecha_desde->AdvancedSearch->Save(); // fecha_desde
			$this->fecha_hasta->AdvancedSearch->Save(); // fecha_hasta
			$this->id_empleado->AdvancedSearch->Save(); // id_empleado
			$this->id_recibo->AdvancedSearch->Save(); // id_recibo
			$this->monto->AdvancedSearch->Save(); // monto
			$this->descuento->AdvancedSearch->Save(); // descuento
			$this->sub_total->AdvancedSearch->Save(); // sub_total
			$this->impuesto->AdvancedSearch->Save(); // impuesto
			$this->monto_total->AdvancedSearch->Save(); // monto_total
			$this->observaciones->AdvancedSearch->Save(); // observaciones
			$this->estatus->AdvancedSearch->Save(); // estatus
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
		if ($this->id_contrato->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_empresa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tipo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->numero->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_plan->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_desde->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fecha_hasta->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_empleado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_recibo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->monto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->descuento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->sub_total->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->impuesto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->monto_total->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->observaciones->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->estatus->AdvancedSearch->IssetSession())
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
		$this->id_contrato->AdvancedSearch->UnsetSession();
		$this->id_empresa->AdvancedSearch->UnsetSession();
		$this->tipo->AdvancedSearch->UnsetSession();
		$this->numero->AdvancedSearch->UnsetSession();
		$this->fecha->AdvancedSearch->UnsetSession();
		$this->id_plan->AdvancedSearch->UnsetSession();
		$this->fecha_desde->AdvancedSearch->UnsetSession();
		$this->fecha_hasta->AdvancedSearch->UnsetSession();
		$this->id_empleado->AdvancedSearch->UnsetSession();
		$this->id_recibo->AdvancedSearch->UnsetSession();
		$this->monto->AdvancedSearch->UnsetSession();
		$this->descuento->AdvancedSearch->UnsetSession();
		$this->sub_total->AdvancedSearch->UnsetSession();
		$this->impuesto->AdvancedSearch->UnsetSession();
		$this->monto_total->AdvancedSearch->UnsetSession();
		$this->observaciones->AdvancedSearch->UnsetSession();
		$this->estatus->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->id_contrato->AdvancedSearch->Load();
		$this->id_empresa->AdvancedSearch->Load();
		$this->tipo->AdvancedSearch->Load();
		$this->numero->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->id_plan->AdvancedSearch->Load();
		$this->fecha_desde->AdvancedSearch->Load();
		$this->fecha_hasta->AdvancedSearch->Load();
		$this->id_empleado->AdvancedSearch->Load();
		$this->id_recibo->AdvancedSearch->Load();
		$this->monto->AdvancedSearch->Load();
		$this->descuento->AdvancedSearch->Load();
		$this->sub_total->AdvancedSearch->Load();
		$this->impuesto->AdvancedSearch->Load();
		$this->monto_total->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->estatus->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id_empresa); // id_empresa
			$this->UpdateSort($this->tipo); // tipo
			$this->UpdateSort($this->numero); // numero
			$this->UpdateSort($this->fecha); // fecha
			$this->UpdateSort($this->id_plan); // id_plan
			$this->UpdateSort($this->sub_total); // sub_total
			$this->UpdateSort($this->impuesto); // impuesto
			$this->UpdateSort($this->estatus); // estatus
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
				$this->id_empresa->setSort("");
				$this->tipo->setSort("");
				$this->numero->setSort("");
				$this->fecha->setSort("");
				$this->id_plan->setSort("");
				$this->sub_total->setSort("");
				$this->impuesto->setSort("");
				$this->estatus->setSort("");
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

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

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

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

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

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_contrato->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fcontratoslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fcontratoslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		// id_contrato

		$this->id_contrato->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_contrato"]);
		if ($this->id_contrato->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_contrato->AdvancedSearch->SearchOperator = @$_GET["z_id_contrato"];

		// id_empresa
		$this->id_empresa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_empresa"]);
		if ($this->id_empresa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_empresa->AdvancedSearch->SearchOperator = @$_GET["z_id_empresa"];

		// tipo
		$this->tipo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tipo"]);
		if ($this->tipo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tipo->AdvancedSearch->SearchOperator = @$_GET["z_tipo"];

		// numero
		$this->numero->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_numero"]);
		if ($this->numero->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->numero->AdvancedSearch->SearchOperator = @$_GET["z_numero"];

		// fecha
		$this->fecha->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha"]);
		if ($this->fecha->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha->AdvancedSearch->SearchOperator = @$_GET["z_fecha"];

		// id_plan
		$this->id_plan->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_plan"]);
		if ($this->id_plan->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_plan->AdvancedSearch->SearchOperator = @$_GET["z_id_plan"];

		// fecha_desde
		$this->fecha_desde->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha_desde"]);
		if ($this->fecha_desde->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha_desde->AdvancedSearch->SearchOperator = @$_GET["z_fecha_desde"];

		// fecha_hasta
		$this->fecha_hasta->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_fecha_hasta"]);
		if ($this->fecha_hasta->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->fecha_hasta->AdvancedSearch->SearchOperator = @$_GET["z_fecha_hasta"];

		// id_empleado
		$this->id_empleado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_empleado"]);
		if ($this->id_empleado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_empleado->AdvancedSearch->SearchOperator = @$_GET["z_id_empleado"];

		// id_recibo
		$this->id_recibo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_recibo"]);
		if ($this->id_recibo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_recibo->AdvancedSearch->SearchOperator = @$_GET["z_id_recibo"];

		// monto
		$this->monto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_monto"]);
		if ($this->monto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->monto->AdvancedSearch->SearchOperator = @$_GET["z_monto"];

		// descuento
		$this->descuento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_descuento"]);
		if ($this->descuento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->descuento->AdvancedSearch->SearchOperator = @$_GET["z_descuento"];

		// sub_total
		$this->sub_total->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_sub_total"]);
		if ($this->sub_total->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->sub_total->AdvancedSearch->SearchOperator = @$_GET["z_sub_total"];

		// impuesto
		$this->impuesto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_impuesto"]);
		if ($this->impuesto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->impuesto->AdvancedSearch->SearchOperator = @$_GET["z_impuesto"];

		// monto_total
		$this->monto_total->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_monto_total"]);
		if ($this->monto_total->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->monto_total->AdvancedSearch->SearchOperator = @$_GET["z_monto_total"];

		// observaciones
		$this->observaciones->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_observaciones"]);
		if ($this->observaciones->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->observaciones->AdvancedSearch->SearchOperator = @$_GET["z_observaciones"];

		// estatus
		$this->estatus->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_estatus"]);
		if ($this->estatus->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->estatus->AdvancedSearch->SearchOperator = @$_GET["z_estatus"];
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
		$this->id_contrato->setDbValue($rs->fields('id_contrato'));
		$this->id_empresa->setDbValue($rs->fields('id_empresa'));
		$this->tipo->setDbValue($rs->fields('tipo'));
		$this->numero->setDbValue($rs->fields('numero'));
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->id_plan->setDbValue($rs->fields('id_plan'));
		$this->fecha_desde->setDbValue($rs->fields('fecha_desde'));
		$this->fecha_hasta->setDbValue($rs->fields('fecha_hasta'));
		$this->id_empleado->setDbValue($rs->fields('id_empleado'));
		$this->id_recibo->setDbValue($rs->fields('id_recibo'));
		$this->monto->setDbValue($rs->fields('monto'));
		$this->descuento->setDbValue($rs->fields('descuento'));
		$this->sub_total->setDbValue($rs->fields('sub_total'));
		$this->impuesto->setDbValue($rs->fields('impuesto'));
		$this->monto_total->setDbValue($rs->fields('monto_total'));
		$this->observaciones->setDbValue($rs->fields('observaciones'));
		$this->estatus->setDbValue($rs->fields('estatus'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_contrato->DbValue = $row['id_contrato'];
		$this->id_empresa->DbValue = $row['id_empresa'];
		$this->tipo->DbValue = $row['tipo'];
		$this->numero->DbValue = $row['numero'];
		$this->fecha->DbValue = $row['fecha'];
		$this->id_plan->DbValue = $row['id_plan'];
		$this->fecha_desde->DbValue = $row['fecha_desde'];
		$this->fecha_hasta->DbValue = $row['fecha_hasta'];
		$this->id_empleado->DbValue = $row['id_empleado'];
		$this->id_recibo->DbValue = $row['id_recibo'];
		$this->monto->DbValue = $row['monto'];
		$this->descuento->DbValue = $row['descuento'];
		$this->sub_total->DbValue = $row['sub_total'];
		$this->impuesto->DbValue = $row['impuesto'];
		$this->monto_total->DbValue = $row['monto_total'];
		$this->observaciones->DbValue = $row['observaciones'];
		$this->estatus->DbValue = $row['estatus'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_contrato")) <> "")
			$this->id_contrato->CurrentValue = $this->getKey("id_contrato"); // id_contrato
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

		// Convert decimal values if posted back
		if ($this->sub_total->FormValue == $this->sub_total->CurrentValue && is_numeric(ew_StrToFloat($this->sub_total->CurrentValue)))
			$this->sub_total->CurrentValue = ew_StrToFloat($this->sub_total->CurrentValue);

		// Convert decimal values if posted back
		if ($this->impuesto->FormValue == $this->impuesto->CurrentValue && is_numeric(ew_StrToFloat($this->impuesto->CurrentValue)))
			$this->impuesto->CurrentValue = ew_StrToFloat($this->impuesto->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id_contrato
		// id_empresa
		// tipo
		// numero
		// fecha
		// id_plan
		// fecha_desde
		// fecha_hasta
		// id_empleado
		// id_recibo
		// monto
		// descuento
		// sub_total
		// impuesto
		// monto_total
		// observaciones
		// estatus

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_contrato
			$this->id_contrato->ViewValue = $this->id_contrato->CurrentValue;
			$this->id_contrato->ViewCustomAttributes = "";

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

			// tipo
			if (strval($this->tipo->CurrentValue) <> "") {
				switch ($this->tipo->CurrentValue) {
					case $this->tipo->FldTagValue(1):
						$this->tipo->ViewValue = $this->tipo->FldTagCaption(1) <> "" ? $this->tipo->FldTagCaption(1) : $this->tipo->CurrentValue;
						break;
					case $this->tipo->FldTagValue(2):
						$this->tipo->ViewValue = $this->tipo->FldTagCaption(2) <> "" ? $this->tipo->FldTagCaption(2) : $this->tipo->CurrentValue;
						break;
					default:
						$this->tipo->ViewValue = $this->tipo->CurrentValue;
				}
			} else {
				$this->tipo->ViewValue = NULL;
			}
			$this->tipo->ViewCustomAttributes = "";

			// numero
			$this->numero->ViewValue = $this->numero->CurrentValue;
			$this->numero->ViewCustomAttributes = "";

			// fecha
			$this->fecha->ViewValue = $this->fecha->CurrentValue;
			$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
			$this->fecha->ViewCustomAttributes = "";

			// id_plan
			if (strval($this->id_plan->CurrentValue) <> "") {
				$sFilterWrk = "`id_plan`" . ew_SearchString("=", $this->id_plan->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_plan`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `planes`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_plan, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_plan->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_plan->ViewValue = $this->id_plan->CurrentValue;
				}
			} else {
				$this->id_plan->ViewValue = NULL;
			}
			$this->id_plan->ViewCustomAttributes = "";

			// fecha_desde
			$this->fecha_desde->ViewValue = $this->fecha_desde->CurrentValue;
			$this->fecha_desde->ViewValue = ew_FormatDateTime($this->fecha_desde->ViewValue, 7);
			$this->fecha_desde->ViewCustomAttributes = "";

			// fecha_hasta
			$this->fecha_hasta->ViewValue = $this->fecha_hasta->CurrentValue;
			$this->fecha_hasta->ViewValue = ew_FormatDateTime($this->fecha_hasta->ViewValue, 7);
			$this->fecha_hasta->ViewCustomAttributes = "";

			// id_empleado
			if (strval($this->id_empleado->CurrentValue) <> "") {
				$sFilterWrk = "`id_empleado`" . ew_SearchString("=", $this->id_empleado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_empleado`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `empleados`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_empleado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_empleado->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_empleado->ViewValue = $this->id_empleado->CurrentValue;
				}
			} else {
				$this->id_empleado->ViewValue = NULL;
			}
			$this->id_empleado->ViewCustomAttributes = "";

			// id_recibo
			$this->id_recibo->ViewValue = $this->id_recibo->CurrentValue;
			$this->id_recibo->ViewCustomAttributes = "";

			// monto
			$this->monto->ViewValue = $this->monto->CurrentValue;
			$this->monto->ViewCustomAttributes = "";

			// descuento
			$this->descuento->ViewValue = $this->descuento->CurrentValue;
			$this->descuento->ViewCustomAttributes = "";

			// sub_total
			$this->sub_total->ViewValue = $this->sub_total->CurrentValue;
			$this->sub_total->ViewCustomAttributes = "";

			// impuesto
			$this->impuesto->ViewValue = $this->impuesto->CurrentValue;
			$this->impuesto->ViewCustomAttributes = "";

			// monto_total
			$this->monto_total->ViewValue = $this->monto_total->CurrentValue;
			$this->monto_total->ViewCustomAttributes = "";

			// observaciones
			$this->observaciones->ViewValue = $this->observaciones->CurrentValue;
			$this->observaciones->ViewCustomAttributes = "";

			// estatus
			$this->estatus->ViewValue = $this->estatus->CurrentValue;
			$this->estatus->ViewCustomAttributes = "";

			// id_empresa
			$this->id_empresa->LinkCustomAttributes = "";
			$this->id_empresa->HrefValue = "";
			$this->id_empresa->TooltipValue = "";

			// tipo
			$this->tipo->LinkCustomAttributes = "";
			$this->tipo->HrefValue = "";
			$this->tipo->TooltipValue = "";

			// numero
			$this->numero->LinkCustomAttributes = "";
			$this->numero->HrefValue = "";
			$this->numero->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// id_plan
			$this->id_plan->LinkCustomAttributes = "";
			$this->id_plan->HrefValue = "";
			$this->id_plan->TooltipValue = "";

			// sub_total
			$this->sub_total->LinkCustomAttributes = "";
			$this->sub_total->HrefValue = "";
			$this->sub_total->TooltipValue = "";

			// impuesto
			$this->impuesto->LinkCustomAttributes = "";
			$this->impuesto->HrefValue = "";
			$this->impuesto->TooltipValue = "";

			// estatus
			$this->estatus->LinkCustomAttributes = "";
			$this->estatus->HrefValue = "";
			$this->estatus->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id_empresa
			$this->id_empresa->EditAttrs["class"] = "form-control";
			$this->id_empresa->EditCustomAttributes = 'disabled';
			$this->id_empresa->EditValue = ew_HtmlEncode($this->id_empresa->AdvancedSearch->SearchValue);

			// tipo
			$this->tipo->EditAttrs["class"] = "form-control";
			$this->tipo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->tipo->FldTagValue(1), $this->tipo->FldTagCaption(1) <> "" ? $this->tipo->FldTagCaption(1) : $this->tipo->FldTagValue(1));
			$arwrk[] = array($this->tipo->FldTagValue(2), $this->tipo->FldTagCaption(2) <> "" ? $this->tipo->FldTagCaption(2) : $this->tipo->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->tipo->EditValue = $arwrk;

			// numero
			$this->numero->EditAttrs["class"] = "form-control";
			$this->numero->EditCustomAttributes = "";
			$this->numero->EditValue = ew_HtmlEncode($this->numero->AdvancedSearch->SearchValue);

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue, 7), 7));

			// id_plan
			$this->id_plan->EditAttrs["class"] = "form-control";
			$this->id_plan->EditCustomAttributes = "";

			// sub_total
			$this->sub_total->EditAttrs["class"] = "form-control";
			$this->sub_total->EditCustomAttributes = ' v-model="subtotal" readonly ';
			$this->sub_total->EditValue = ew_HtmlEncode($this->sub_total->AdvancedSearch->SearchValue);

			// impuesto
			$this->impuesto->EditAttrs["class"] = "form-control";
			$this->impuesto->EditCustomAttributes = ' v-model="impuesto" readonly ';
			$this->impuesto->EditValue = ew_HtmlEncode($this->impuesto->AdvancedSearch->SearchValue);

			// estatus
			$this->estatus->EditAttrs["class"] = "form-control";
			$this->estatus->EditCustomAttributes = "";
			$this->estatus->EditValue = ew_HtmlEncode($this->estatus->AdvancedSearch->SearchValue);
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
		$this->id_contrato->AdvancedSearch->Load();
		$this->id_empresa->AdvancedSearch->Load();
		$this->tipo->AdvancedSearch->Load();
		$this->numero->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->id_plan->AdvancedSearch->Load();
		$this->fecha_desde->AdvancedSearch->Load();
		$this->fecha_hasta->AdvancedSearch->Load();
		$this->id_empleado->AdvancedSearch->Load();
		$this->id_recibo->AdvancedSearch->Load();
		$this->monto->AdvancedSearch->Load();
		$this->descuento->AdvancedSearch->Load();
		$this->sub_total->AdvancedSearch->Load();
		$this->impuesto->AdvancedSearch->Load();
		$this->monto_total->AdvancedSearch->Load();
		$this->observaciones->AdvancedSearch->Load();
		$this->estatus->AdvancedSearch->Load();
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
if (!isset($contratos_list)) $contratos_list = new ccontratos_list();

// Page init
$contratos_list->Page_Init();

// Page main
$contratos_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contratos_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var contratos_list = new ew_Page("contratos_list");
contratos_list.PageID = "list"; // Page ID
var EW_PAGE_ID = contratos_list.PageID; // For backward compatibility

// Form object
var fcontratoslist = new ew_Form("fcontratoslist");
fcontratoslist.FormKeyCountName = '<?php echo $contratos_list->FormKeyCountName ?>';

// Form_CustomValidate event
fcontratoslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontratoslist.ValidateRequired = true;
<?php } else { ?>
fcontratoslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcontratoslist.Lists["x_id_empresa"] = {"LinkField":"x_id_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontratoslist.Lists["x_id_plan"] = {"LinkField":"x_id_plan","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fcontratoslistsrch = new ew_Form("fcontratoslistsrch");

// Validate function for search
fcontratoslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fcontratoslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontratoslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fcontratoslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($contratos_list->TotalRecs > 0 && $contratos_list->ExportOptions->Visible()) { ?>
<?php $contratos_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($contratos_list->SearchOptions->Visible()) { ?>
<?php $contratos_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($contratos_list->TotalRecs <= 0)
			$contratos_list->TotalRecs = $contratos->SelectRecordCount();
	} else {
		if (!$contratos_list->Recordset && ($contratos_list->Recordset = $contratos_list->LoadRecordset()))
			$contratos_list->TotalRecs = $contratos_list->Recordset->RecordCount();
	}
	$contratos_list->StartRec = 1;
	if ($contratos_list->DisplayRecs <= 0 || ($contratos->Export <> "" && $contratos->ExportAll)) // Display all records
		$contratos_list->DisplayRecs = $contratos_list->TotalRecs;
	if (!($contratos->Export <> "" && $contratos->ExportAll))
		$contratos_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$contratos_list->Recordset = $contratos_list->LoadRecordset($contratos_list->StartRec-1, $contratos_list->DisplayRecs);

	// Set no record found message
	if ($contratos->CurrentAction == "" && $contratos_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$contratos_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($contratos_list->SearchWhere == "0=101")
			$contratos_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$contratos_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$contratos_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($contratos->Export == "" && $contratos->CurrentAction == "") { ?>
<form name="fcontratoslistsrch" id="fcontratoslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($contratos_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fcontratoslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="contratos">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$contratos_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$contratos->RowType = EW_ROWTYPE_SEARCH;

// Render row
$contratos->ResetAttrs();
$contratos_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($contratos->numero->Visible) { // numero ?>
	<div id="xsc_numero" class="ewCell form-group">
		<label for="x_numero" class="ewSearchCaption ewLabel"><?php echo $contratos->numero->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_numero" id="z_numero" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_numero" name="x_numero" id="x_numero" size="10" maxlength="20" value="<?php echo $contratos->numero->EditValue ?>"<?php echo $contratos->numero->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $contratos_list->ShowPageHeader(); ?>
<?php
$contratos_list->ShowMessage();
?>
<?php if ($contratos_list->TotalRecs > 0 || $contratos->CurrentAction <> "") { ?>
<div class="ewGrid">
<div class="ewGridUpperPanel">
<?php if ($contratos->CurrentAction <> "gridadd" && $contratos->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($contratos_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="fcontratoslist" id="fcontratoslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($contratos_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $contratos_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="contratos">
<div id="gmp_contratos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($contratos_list->TotalRecs > 0) { ?>
<table id="tbl_contratoslist" class="table ewTable">
<?php echo $contratos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$contratos->RowType = EW_ROWTYPE_HEADER;

// Render list options
$contratos_list->RenderListOptions();

// Render list options (header, left)
$contratos_list->ListOptions->Render("header", "left");
?>
<?php if ($contratos->id_empresa->Visible) { // id_empresa ?>
	<?php if ($contratos->SortUrl($contratos->id_empresa) == "") { ?>
		<th data-name="id_empresa"><div id="elh_contratos_id_empresa" class="contratos_id_empresa"><div class="ewTableHeaderCaption"><?php echo $contratos->id_empresa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_empresa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contratos->SortUrl($contratos->id_empresa) ?>',1);"><div id="elh_contratos_id_empresa" class="contratos_id_empresa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contratos->id_empresa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contratos->id_empresa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contratos->id_empresa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($contratos->tipo->Visible) { // tipo ?>
	<?php if ($contratos->SortUrl($contratos->tipo) == "") { ?>
		<th data-name="tipo"><div id="elh_contratos_tipo" class="contratos_tipo"><div class="ewTableHeaderCaption"><?php echo $contratos->tipo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tipo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contratos->SortUrl($contratos->tipo) ?>',1);"><div id="elh_contratos_tipo" class="contratos_tipo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contratos->tipo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contratos->tipo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contratos->tipo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($contratos->numero->Visible) { // numero ?>
	<?php if ($contratos->SortUrl($contratos->numero) == "") { ?>
		<th data-name="numero"><div id="elh_contratos_numero" class="contratos_numero"><div class="ewTableHeaderCaption"><?php echo $contratos->numero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="numero"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contratos->SortUrl($contratos->numero) ?>',1);"><div id="elh_contratos_numero" class="contratos_numero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contratos->numero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contratos->numero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contratos->numero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($contratos->fecha->Visible) { // fecha ?>
	<?php if ($contratos->SortUrl($contratos->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_contratos_fecha" class="contratos_fecha"><div class="ewTableHeaderCaption"><?php echo $contratos->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contratos->SortUrl($contratos->fecha) ?>',1);"><div id="elh_contratos_fecha" class="contratos_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contratos->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contratos->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contratos->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($contratos->id_plan->Visible) { // id_plan ?>
	<?php if ($contratos->SortUrl($contratos->id_plan) == "") { ?>
		<th data-name="id_plan"><div id="elh_contratos_id_plan" class="contratos_id_plan"><div class="ewTableHeaderCaption"><?php echo $contratos->id_plan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_plan"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contratos->SortUrl($contratos->id_plan) ?>',1);"><div id="elh_contratos_id_plan" class="contratos_id_plan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contratos->id_plan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contratos->id_plan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contratos->id_plan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($contratos->sub_total->Visible) { // sub_total ?>
	<?php if ($contratos->SortUrl($contratos->sub_total) == "") { ?>
		<th data-name="sub_total"><div id="elh_contratos_sub_total" class="contratos_sub_total"><div class="ewTableHeaderCaption"><?php echo $contratos->sub_total->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sub_total"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contratos->SortUrl($contratos->sub_total) ?>',1);"><div id="elh_contratos_sub_total" class="contratos_sub_total">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contratos->sub_total->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contratos->sub_total->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contratos->sub_total->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($contratos->impuesto->Visible) { // impuesto ?>
	<?php if ($contratos->SortUrl($contratos->impuesto) == "") { ?>
		<th data-name="impuesto"><div id="elh_contratos_impuesto" class="contratos_impuesto"><div class="ewTableHeaderCaption"><?php echo $contratos->impuesto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="impuesto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contratos->SortUrl($contratos->impuesto) ?>',1);"><div id="elh_contratos_impuesto" class="contratos_impuesto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contratos->impuesto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contratos->impuesto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contratos->impuesto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($contratos->estatus->Visible) { // estatus ?>
	<?php if ($contratos->SortUrl($contratos->estatus) == "") { ?>
		<th data-name="estatus"><div id="elh_contratos_estatus" class="contratos_estatus"><div class="ewTableHeaderCaption"><?php echo $contratos->estatus->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estatus"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contratos->SortUrl($contratos->estatus) ?>',1);"><div id="elh_contratos_estatus" class="contratos_estatus">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contratos->estatus->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contratos->estatus->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contratos->estatus->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$contratos_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($contratos->ExportAll && $contratos->Export <> "") {
	$contratos_list->StopRec = $contratos_list->TotalRecs;
} else {

	// Set the last record to display
	if ($contratos_list->TotalRecs > $contratos_list->StartRec + $contratos_list->DisplayRecs - 1)
		$contratos_list->StopRec = $contratos_list->StartRec + $contratos_list->DisplayRecs - 1;
	else
		$contratos_list->StopRec = $contratos_list->TotalRecs;
}
$contratos_list->RecCnt = $contratos_list->StartRec - 1;
if ($contratos_list->Recordset && !$contratos_list->Recordset->EOF) {
	$contratos_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $contratos_list->StartRec > 1)
		$contratos_list->Recordset->Move($contratos_list->StartRec - 1);
} elseif (!$contratos->AllowAddDeleteRow && $contratos_list->StopRec == 0) {
	$contratos_list->StopRec = $contratos->GridAddRowCount;
}

// Initialize aggregate
$contratos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$contratos->ResetAttrs();
$contratos_list->RenderRow();
while ($contratos_list->RecCnt < $contratos_list->StopRec) {
	$contratos_list->RecCnt++;
	if (intval($contratos_list->RecCnt) >= intval($contratos_list->StartRec)) {
		$contratos_list->RowCnt++;

		// Set up key count
		$contratos_list->KeyCount = $contratos_list->RowIndex;

		// Init row class and style
		$contratos->ResetAttrs();
		$contratos->CssClass = "";
		if ($contratos->CurrentAction == "gridadd") {
		} else {
			$contratos_list->LoadRowValues($contratos_list->Recordset); // Load row values
		}
		$contratos->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$contratos->RowAttrs = array_merge($contratos->RowAttrs, array('data-rowindex'=>$contratos_list->RowCnt, 'id'=>'r' . $contratos_list->RowCnt . '_contratos', 'data-rowtype'=>$contratos->RowType));

		// Render row
		$contratos_list->RenderRow();

		// Render list options
		$contratos_list->RenderListOptions();
?>
	<tr<?php echo $contratos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$contratos_list->ListOptions->Render("body", "left", $contratos_list->RowCnt);
?>
	<?php if ($contratos->id_empresa->Visible) { // id_empresa ?>
		<td data-name="id_empresa"<?php echo $contratos->id_empresa->CellAttributes() ?>>
<span<?php echo $contratos->id_empresa->ViewAttributes() ?>>
<?php echo $contratos->id_empresa->ListViewValue() ?></span>
<a id="<?php echo $contratos_list->PageObjName . "_row_" . $contratos_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contratos->tipo->Visible) { // tipo ?>
		<td data-name="tipo"<?php echo $contratos->tipo->CellAttributes() ?>>
<span<?php echo $contratos->tipo->ViewAttributes() ?>>
<?php echo $contratos->tipo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($contratos->numero->Visible) { // numero ?>
		<td data-name="numero"<?php echo $contratos->numero->CellAttributes() ?>>
<span<?php echo $contratos->numero->ViewAttributes() ?>>
<?php echo $contratos->numero->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($contratos->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $contratos->fecha->CellAttributes() ?>>
<span<?php echo $contratos->fecha->ViewAttributes() ?>>
<?php echo $contratos->fecha->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($contratos->id_plan->Visible) { // id_plan ?>
		<td data-name="id_plan"<?php echo $contratos->id_plan->CellAttributes() ?>>
<span<?php echo $contratos->id_plan->ViewAttributes() ?>>
<?php echo $contratos->id_plan->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($contratos->sub_total->Visible) { // sub_total ?>
		<td data-name="sub_total"<?php echo $contratos->sub_total->CellAttributes() ?>>
<span<?php echo $contratos->sub_total->ViewAttributes() ?>>
<?php echo $contratos->sub_total->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($contratos->impuesto->Visible) { // impuesto ?>
		<td data-name="impuesto"<?php echo $contratos->impuesto->CellAttributes() ?>>
<span<?php echo $contratos->impuesto->ViewAttributes() ?>>
<?php echo $contratos->impuesto->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($contratos->estatus->Visible) { // estatus ?>
		<td data-name="estatus"<?php echo $contratos->estatus->CellAttributes() ?>>
<span<?php echo $contratos->estatus->ViewAttributes() ?>>
<?php echo $contratos->estatus->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$contratos_list->ListOptions->Render("body", "right", $contratos_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($contratos->CurrentAction <> "gridadd")
		$contratos_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($contratos->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($contratos_list->Recordset)
	$contratos_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($contratos->CurrentAction <> "gridadd" && $contratos->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($contratos_list->Pager)) $contratos_list->Pager = new cPrevNextPager($contratos_list->StartRec, $contratos_list->DisplayRecs, $contratos_list->TotalRecs) ?>
<?php if ($contratos_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($contratos_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $contratos_list->PageUrl() ?>start=<?php echo $contratos_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($contratos_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $contratos_list->PageUrl() ?>start=<?php echo $contratos_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $contratos_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($contratos_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $contratos_list->PageUrl() ?>start=<?php echo $contratos_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($contratos_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $contratos_list->PageUrl() ?>start=<?php echo $contratos_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $contratos_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $contratos_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $contratos_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $contratos_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($contratos_list->TotalRecs == 0 && $contratos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($contratos_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fcontratoslistsrch.Init();
fcontratoslist.Init();
</script>
<?php
$contratos_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$contratos_list->Page_Terminate();
?>
