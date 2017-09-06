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

$empresas_list = NULL; // Initialize page object first

class cempresas_list extends cempresas {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'empresas';

	// Page object name
	var $PageObjName = 'empresas_list';

	// Grid form hidden field names
	var $FormName = 'fempresaslist';
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

		// Table object (empresas)
		if (!isset($GLOBALS["empresas"]) || get_class($GLOBALS["empresas"]) == "cempresas") {
			$GLOBALS["empresas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["empresas"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "empresas_add.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "empresas_delete.php";
		$this->MultiUpdateUrl = "empresas_update.php";

		// Table object (empleados)
		if (!isset($GLOBALS['empleados'])) $GLOBALS['empleados'] = new cempleados();

		// User table object (empleados)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cempleados();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'empresas', TRUE);

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
			$this->id_empresa->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_empresa->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id_empresa, $Default, FALSE); // id_empresa
		$this->BuildSearchSql($sWhere, $this->nombre, $Default, FALSE); // nombre
		$this->BuildSearchSql($sWhere, $this->documento_tipo, $Default, FALSE); // documento_tipo
		$this->BuildSearchSql($sWhere, $this->documento_numero, $Default, FALSE); // documento_numero
		$this->BuildSearchSql($sWhere, $this->_email, $Default, FALSE); // email
		$this->BuildSearchSql($sWhere, $this->web, $Default, FALSE); // web
		$this->BuildSearchSql($sWhere, $this->logo, $Default, FALSE); // logo
		$this->BuildSearchSql($sWhere, $this->descripcion, $Default, FALSE); // descripcion
		$this->BuildSearchSql($sWhere, $this->meta_descripcion, $Default, FALSE); // meta_descripcion
		$this->BuildSearchSql($sWhere, $this->meta_palabras_clave, $Default, FALSE); // meta_palabras_clave
		$this->BuildSearchSql($sWhere, $this->slug, $Default, FALSE); // slug
		$this->BuildSearchSql($sWhere, $this->comentarios, $Default, FALSE); // comentarios
		$this->BuildSearchSql($sWhere, $this->estrellas, $Default, FALSE); // estrellas
		$this->BuildSearchSql($sWhere, $this->contrato, $Default, FALSE); // contrato

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id_empresa->AdvancedSearch->Save(); // id_empresa
			$this->nombre->AdvancedSearch->Save(); // nombre
			$this->documento_tipo->AdvancedSearch->Save(); // documento_tipo
			$this->documento_numero->AdvancedSearch->Save(); // documento_numero
			$this->_email->AdvancedSearch->Save(); // email
			$this->web->AdvancedSearch->Save(); // web
			$this->logo->AdvancedSearch->Save(); // logo
			$this->descripcion->AdvancedSearch->Save(); // descripcion
			$this->meta_descripcion->AdvancedSearch->Save(); // meta_descripcion
			$this->meta_palabras_clave->AdvancedSearch->Save(); // meta_palabras_clave
			$this->slug->AdvancedSearch->Save(); // slug
			$this->comentarios->AdvancedSearch->Save(); // comentarios
			$this->estrellas->AdvancedSearch->Save(); // estrellas
			$this->contrato->AdvancedSearch->Save(); // contrato
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
		if ($this->id_empresa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nombre->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->documento_tipo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->documento_numero->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_email->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->web->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->logo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->descripcion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->meta_descripcion->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->meta_palabras_clave->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->slug->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->comentarios->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->estrellas->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->contrato->AdvancedSearch->IssetSession())
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
		$this->id_empresa->AdvancedSearch->UnsetSession();
		$this->nombre->AdvancedSearch->UnsetSession();
		$this->documento_tipo->AdvancedSearch->UnsetSession();
		$this->documento_numero->AdvancedSearch->UnsetSession();
		$this->_email->AdvancedSearch->UnsetSession();
		$this->web->AdvancedSearch->UnsetSession();
		$this->logo->AdvancedSearch->UnsetSession();
		$this->descripcion->AdvancedSearch->UnsetSession();
		$this->meta_descripcion->AdvancedSearch->UnsetSession();
		$this->meta_palabras_clave->AdvancedSearch->UnsetSession();
		$this->slug->AdvancedSearch->UnsetSession();
		$this->comentarios->AdvancedSearch->UnsetSession();
		$this->estrellas->AdvancedSearch->UnsetSession();
		$this->contrato->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->id_empresa->AdvancedSearch->Load();
		$this->nombre->AdvancedSearch->Load();
		$this->documento_tipo->AdvancedSearch->Load();
		$this->documento_numero->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->web->AdvancedSearch->Load();
		$this->logo->AdvancedSearch->Load();
		$this->descripcion->AdvancedSearch->Load();
		$this->meta_descripcion->AdvancedSearch->Load();
		$this->meta_palabras_clave->AdvancedSearch->Load();
		$this->slug->AdvancedSearch->Load();
		$this->comentarios->AdvancedSearch->Load();
		$this->estrellas->AdvancedSearch->Load();
		$this->contrato->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nombre); // nombre
			$this->UpdateSort($this->documento_tipo); // documento_tipo
			$this->UpdateSort($this->documento_numero); // documento_numero
			$this->UpdateSort($this->logo); // logo
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
				$this->nombre->setSort("");
				$this->documento_tipo->setSort("");
				$this->documento_numero->setSort("");
				$this->logo->setSort("");
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

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_empresa->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fempresaslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fempresaslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		// id_empresa

		$this->id_empresa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_empresa"]);
		if ($this->id_empresa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_empresa->AdvancedSearch->SearchOperator = @$_GET["z_id_empresa"];

		// nombre
		$this->nombre->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nombre"]);
		if ($this->nombre->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nombre->AdvancedSearch->SearchOperator = @$_GET["z_nombre"];

		// documento_tipo
		$this->documento_tipo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_documento_tipo"]);
		if ($this->documento_tipo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->documento_tipo->AdvancedSearch->SearchOperator = @$_GET["z_documento_tipo"];

		// documento_numero
		$this->documento_numero->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_documento_numero"]);
		if ($this->documento_numero->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->documento_numero->AdvancedSearch->SearchOperator = @$_GET["z_documento_numero"];

		// email
		$this->_email->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__email"]);
		if ($this->_email->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_email->AdvancedSearch->SearchOperator = @$_GET["z__email"];

		// web
		$this->web->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_web"]);
		if ($this->web->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->web->AdvancedSearch->SearchOperator = @$_GET["z_web"];

		// logo
		$this->logo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_logo"]);
		if ($this->logo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->logo->AdvancedSearch->SearchOperator = @$_GET["z_logo"];

		// descripcion
		$this->descripcion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_descripcion"]);
		if ($this->descripcion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->descripcion->AdvancedSearch->SearchOperator = @$_GET["z_descripcion"];

		// meta_descripcion
		$this->meta_descripcion->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_meta_descripcion"]);
		if ($this->meta_descripcion->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->meta_descripcion->AdvancedSearch->SearchOperator = @$_GET["z_meta_descripcion"];

		// meta_palabras_clave
		$this->meta_palabras_clave->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_meta_palabras_clave"]);
		if ($this->meta_palabras_clave->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->meta_palabras_clave->AdvancedSearch->SearchOperator = @$_GET["z_meta_palabras_clave"];

		// slug
		$this->slug->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_slug"]);
		if ($this->slug->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->slug->AdvancedSearch->SearchOperator = @$_GET["z_slug"];

		// comentarios
		$this->comentarios->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_comentarios"]);
		if ($this->comentarios->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->comentarios->AdvancedSearch->SearchOperator = @$_GET["z_comentarios"];

		// estrellas
		$this->estrellas->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_estrellas"]);
		if ($this->estrellas->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->estrellas->AdvancedSearch->SearchOperator = @$_GET["z_estrellas"];

		// contrato
		$this->contrato->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_contrato"]);
		if ($this->contrato->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->contrato->AdvancedSearch->SearchOperator = @$_GET["z_contrato"];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
				$this->logo->LinkAttrs["data-rel"] = "empresas_x" . $this->RowCnt . "_logo";
				$this->logo->LinkAttrs["class"] = "ewLightbox";
			}
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nombre
			$this->nombre->EditAttrs["class"] = "form-control";
			$this->nombre->EditCustomAttributes = "";
			$this->nombre->EditValue = ew_HtmlEncode($this->nombre->AdvancedSearch->SearchValue);

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
			$this->documento_numero->EditValue = ew_HtmlEncode($this->documento_numero->AdvancedSearch->SearchValue);

			// logo
			$this->logo->EditAttrs["class"] = "form-control";
			$this->logo->EditCustomAttributes = "";
			$this->logo->EditValue = ew_HtmlEncode($this->logo->AdvancedSearch->SearchValue);
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
		$this->id_empresa->AdvancedSearch->Load();
		$this->nombre->AdvancedSearch->Load();
		$this->documento_tipo->AdvancedSearch->Load();
		$this->documento_numero->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->web->AdvancedSearch->Load();
		$this->logo->AdvancedSearch->Load();
		$this->descripcion->AdvancedSearch->Load();
		$this->meta_descripcion->AdvancedSearch->Load();
		$this->meta_palabras_clave->AdvancedSearch->Load();
		$this->slug->AdvancedSearch->Load();
		$this->comentarios->AdvancedSearch->Load();
		$this->estrellas->AdvancedSearch->Load();
		$this->contrato->AdvancedSearch->Load();
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
if (!isset($empresas_list)) $empresas_list = new cempresas_list();

// Page init
$empresas_list->Page_Init();

// Page main
$empresas_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empresas_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var empresas_list = new ew_Page("empresas_list");
empresas_list.PageID = "list"; // Page ID
var EW_PAGE_ID = empresas_list.PageID; // For backward compatibility

// Form object
var fempresaslist = new ew_Form("fempresaslist");
fempresaslist.FormKeyCountName = '<?php echo $empresas_list->FormKeyCountName ?>';

// Form_CustomValidate event
fempresaslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempresaslist.ValidateRequired = true;
<?php } else { ?>
fempresaslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fempresaslistsrch = new ew_Form("fempresaslistsrch");

// Validate function for search
fempresaslistsrch.Validate = function(fobj) {
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
fempresaslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempresaslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fempresaslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($empresas_list->TotalRecs > 0 && $empresas_list->ExportOptions->Visible()) { ?>
<?php $empresas_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($empresas_list->SearchOptions->Visible()) { ?>
<?php $empresas_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($empresas_list->TotalRecs <= 0)
			$empresas_list->TotalRecs = $empresas->SelectRecordCount();
	} else {
		if (!$empresas_list->Recordset && ($empresas_list->Recordset = $empresas_list->LoadRecordset()))
			$empresas_list->TotalRecs = $empresas_list->Recordset->RecordCount();
	}
	$empresas_list->StartRec = 1;
	if ($empresas_list->DisplayRecs <= 0 || ($empresas->Export <> "" && $empresas->ExportAll)) // Display all records
		$empresas_list->DisplayRecs = $empresas_list->TotalRecs;
	if (!($empresas->Export <> "" && $empresas->ExportAll))
		$empresas_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$empresas_list->Recordset = $empresas_list->LoadRecordset($empresas_list->StartRec-1, $empresas_list->DisplayRecs);

	// Set no record found message
	if ($empresas->CurrentAction == "" && $empresas_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$empresas_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($empresas_list->SearchWhere == "0=101")
			$empresas_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$empresas_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$empresas_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($empresas->Export == "" && $empresas->CurrentAction == "") { ?>
<form name="fempresaslistsrch" id="fempresaslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($empresas_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fempresaslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="empresas">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$empresas_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$empresas->RowType = EW_ROWTYPE_SEARCH;

// Render row
$empresas->ResetAttrs();
$empresas_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($empresas->nombre->Visible) { // nombre ?>
	<div id="xsc_nombre" class="ewCell form-group">
		<label for="x_nombre" class="ewSearchCaption ewLabel"><?php echo $empresas->nombre->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nombre" id="z_nombre" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_nombre" name="x_nombre" id="x_nombre" size="30" maxlength="200" value="<?php echo $empresas->nombre->EditValue ?>"<?php echo $empresas->nombre->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($empresas->documento_numero->Visible) { // documento_numero ?>
	<div id="xsc_documento_numero" class="ewCell form-group">
		<label for="x_documento_numero" class="ewSearchCaption ewLabel"><?php echo $empresas->documento_numero->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_documento_numero" id="z_documento_numero" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_documento_numero" name="x_documento_numero" id="x_documento_numero" size="12" maxlength="20" value="<?php echo $empresas->documento_numero->EditValue ?>"<?php echo $empresas->documento_numero->EditAttributes() ?>>
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
<?php $empresas_list->ShowPageHeader(); ?>
<?php
$empresas_list->ShowMessage();
?>
<?php if ($empresas_list->TotalRecs > 0 || $empresas->CurrentAction <> "") { ?>
<div class="ewGrid">
<div class="ewGridUpperPanel">
<?php if ($empresas->CurrentAction <> "gridadd" && $empresas->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($empresas_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="fempresaslist" id="fempresaslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($empresas_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $empresas_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="empresas">
<div id="gmp_empresas" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($empresas_list->TotalRecs > 0) { ?>
<table id="tbl_empresaslist" class="table ewTable">
<?php echo $empresas->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$empresas->RowType = EW_ROWTYPE_HEADER;

// Render list options
$empresas_list->RenderListOptions();

// Render list options (header, left)
$empresas_list->ListOptions->Render("header", "left");
?>
<?php if ($empresas->nombre->Visible) { // nombre ?>
	<?php if ($empresas->SortUrl($empresas->nombre) == "") { ?>
		<th data-name="nombre"><div id="elh_empresas_nombre" class="empresas_nombre"><div class="ewTableHeaderCaption"><?php echo $empresas->nombre->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nombre"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empresas->SortUrl($empresas->nombre) ?>',1);"><div id="elh_empresas_nombre" class="empresas_nombre">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->nombre->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empresas->nombre->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->nombre->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empresas->documento_tipo->Visible) { // documento_tipo ?>
	<?php if ($empresas->SortUrl($empresas->documento_tipo) == "") { ?>
		<th data-name="documento_tipo"><div id="elh_empresas_documento_tipo" class="empresas_documento_tipo"><div class="ewTableHeaderCaption"><?php echo $empresas->documento_tipo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="documento_tipo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empresas->SortUrl($empresas->documento_tipo) ?>',1);"><div id="elh_empresas_documento_tipo" class="empresas_documento_tipo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->documento_tipo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empresas->documento_tipo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->documento_tipo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empresas->documento_numero->Visible) { // documento_numero ?>
	<?php if ($empresas->SortUrl($empresas->documento_numero) == "") { ?>
		<th data-name="documento_numero"><div id="elh_empresas_documento_numero" class="empresas_documento_numero"><div class="ewTableHeaderCaption"><?php echo $empresas->documento_numero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="documento_numero"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empresas->SortUrl($empresas->documento_numero) ?>',1);"><div id="elh_empresas_documento_numero" class="empresas_documento_numero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->documento_numero->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empresas->documento_numero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->documento_numero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($empresas->logo->Visible) { // logo ?>
	<?php if ($empresas->SortUrl($empresas->logo) == "") { ?>
		<th data-name="logo"><div id="elh_empresas_logo" class="empresas_logo"><div class="ewTableHeaderCaption"><?php echo $empresas->logo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="logo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $empresas->SortUrl($empresas->logo) ?>',1);"><div id="elh_empresas_logo" class="empresas_logo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $empresas->logo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($empresas->logo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($empresas->logo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$empresas_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($empresas->ExportAll && $empresas->Export <> "") {
	$empresas_list->StopRec = $empresas_list->TotalRecs;
} else {

	// Set the last record to display
	if ($empresas_list->TotalRecs > $empresas_list->StartRec + $empresas_list->DisplayRecs - 1)
		$empresas_list->StopRec = $empresas_list->StartRec + $empresas_list->DisplayRecs - 1;
	else
		$empresas_list->StopRec = $empresas_list->TotalRecs;
}
$empresas_list->RecCnt = $empresas_list->StartRec - 1;
if ($empresas_list->Recordset && !$empresas_list->Recordset->EOF) {
	$empresas_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $empresas_list->StartRec > 1)
		$empresas_list->Recordset->Move($empresas_list->StartRec - 1);
} elseif (!$empresas->AllowAddDeleteRow && $empresas_list->StopRec == 0) {
	$empresas_list->StopRec = $empresas->GridAddRowCount;
}

// Initialize aggregate
$empresas->RowType = EW_ROWTYPE_AGGREGATEINIT;
$empresas->ResetAttrs();
$empresas_list->RenderRow();
while ($empresas_list->RecCnt < $empresas_list->StopRec) {
	$empresas_list->RecCnt++;
	if (intval($empresas_list->RecCnt) >= intval($empresas_list->StartRec)) {
		$empresas_list->RowCnt++;

		// Set up key count
		$empresas_list->KeyCount = $empresas_list->RowIndex;

		// Init row class and style
		$empresas->ResetAttrs();
		$empresas->CssClass = "";
		if ($empresas->CurrentAction == "gridadd") {
		} else {
			$empresas_list->LoadRowValues($empresas_list->Recordset); // Load row values
		}
		$empresas->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$empresas->RowAttrs = array_merge($empresas->RowAttrs, array('data-rowindex'=>$empresas_list->RowCnt, 'id'=>'r' . $empresas_list->RowCnt . '_empresas', 'data-rowtype'=>$empresas->RowType));

		// Render row
		$empresas_list->RenderRow();

		// Render list options
		$empresas_list->RenderListOptions();
?>
	<tr<?php echo $empresas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$empresas_list->ListOptions->Render("body", "left", $empresas_list->RowCnt);
?>
	<?php if ($empresas->nombre->Visible) { // nombre ?>
		<td data-name="nombre"<?php echo $empresas->nombre->CellAttributes() ?>>
<span<?php echo $empresas->nombre->ViewAttributes() ?>>
<?php if ((!ew_EmptyStr($empresas->nombre->ListViewValue())) && $empresas->nombre->LinkAttributes() <> "") { ?>
<a<?php echo $empresas->nombre->LinkAttributes() ?>><?php echo $empresas->nombre->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $empresas->nombre->ListViewValue() ?>
<?php } ?>
</span>
<a id="<?php echo $empresas_list->PageObjName . "_row_" . $empresas_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($empresas->documento_tipo->Visible) { // documento_tipo ?>
		<td data-name="documento_tipo"<?php echo $empresas->documento_tipo->CellAttributes() ?>>
<span<?php echo $empresas->documento_tipo->ViewAttributes() ?>>
<?php echo $empresas->documento_tipo->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($empresas->documento_numero->Visible) { // documento_numero ?>
		<td data-name="documento_numero"<?php echo $empresas->documento_numero->CellAttributes() ?>>
<span<?php echo $empresas->documento_numero->ViewAttributes() ?>>
<?php echo $empresas->documento_numero->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($empresas->logo->Visible) { // logo ?>
		<td data-name="logo"<?php echo $empresas->logo->CellAttributes() ?>>
<span>
<?php echo ew_GetFileViewTag($empresas->logo, $empresas->logo->ListViewValue()) ?>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$empresas_list->ListOptions->Render("body", "right", $empresas_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($empresas->CurrentAction <> "gridadd")
		$empresas_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($empresas->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($empresas_list->Recordset)
	$empresas_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($empresas->CurrentAction <> "gridadd" && $empresas->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($empresas_list->Pager)) $empresas_list->Pager = new cPrevNextPager($empresas_list->StartRec, $empresas_list->DisplayRecs, $empresas_list->TotalRecs) ?>
<?php if ($empresas_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($empresas_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $empresas_list->PageUrl() ?>start=<?php echo $empresas_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($empresas_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $empresas_list->PageUrl() ?>start=<?php echo $empresas_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $empresas_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($empresas_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $empresas_list->PageUrl() ?>start=<?php echo $empresas_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($empresas_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $empresas_list->PageUrl() ?>start=<?php echo $empresas_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $empresas_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $empresas_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $empresas_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $empresas_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($empresas_list->TotalRecs == 0 && $empresas->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($empresas_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fempresaslistsrch.Init();
fempresaslist.Init();
</script>
<?php
$empresas_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empresas_list->Page_Terminate();
?>
