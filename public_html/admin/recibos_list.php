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

$recibos_list = NULL; // Initialize page object first

class crecibos_list extends crecibos {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'recibos';

	// Page object name
	var $PageObjName = 'recibos_list';

	// Grid form hidden field names
	var $FormName = 'freciboslist';
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

		// Table object (recibos)
		if (!isset($GLOBALS["recibos"]) || get_class($GLOBALS["recibos"]) == "crecibos") {
			$GLOBALS["recibos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["recibos"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "recibos_add.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "recibos_delete.php";
		$this->MultiUpdateUrl = "recibos_update.php";

		// Table object (empleados)
		if (!isset($GLOBALS['empleados'])) $GLOBALS['empleados'] = new cempleados();

		// User table object (empleados)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cempleados();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'recibos', TRUE);

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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
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
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
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

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
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
			$this->id_recibo->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_recibo->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->numero, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$sCond = $sDefCond;
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
						$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id_empresa); // id_empresa
			$this->UpdateSort($this->fecha_emision); // fecha_emision
			$this->UpdateSort($this->fecha_vencimiento); // fecha_vencimiento
			$this->UpdateSort($this->numero); // numero
			$this->UpdateSort($this->monto_base); // monto_base
			$this->UpdateSort($this->monto_impuesto); // monto_impuesto
			$this->UpdateSort($this->monto_total); // monto_total
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
				$this->fecha_emision->setSort("");
				$this->fecha_vencimiento->setSort("");
				$this->numero->setSort("");
				$this->monto_base->setSort("");
				$this->monto_impuesto->setSort("");
				$this->monto_total->setSort("");
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

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id_recibo->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.freciboslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"freciboslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_recibo")) <> "")
			$this->id_recibo->CurrentValue = $this->getKey("id_recibo"); // id_recibo
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
if (!isset($recibos_list)) $recibos_list = new crecibos_list();

// Page init
$recibos_list->Page_Init();

// Page main
$recibos_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$recibos_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var recibos_list = new ew_Page("recibos_list");
recibos_list.PageID = "list"; // Page ID
var EW_PAGE_ID = recibos_list.PageID; // For backward compatibility

// Form object
var freciboslist = new ew_Form("freciboslist");
freciboslist.FormKeyCountName = '<?php echo $recibos_list->FormKeyCountName ?>';

// Form_CustomValidate event
freciboslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
freciboslist.ValidateRequired = true;
<?php } else { ?>
freciboslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
freciboslist.Lists["x_id_empresa"] = {"LinkField":"x_id_empresa","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var freciboslistsrch = new ew_Form("freciboslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($recibos_list->TotalRecs > 0 && $recibos_list->ExportOptions->Visible()) { ?>
<?php $recibos_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($recibos_list->SearchOptions->Visible()) { ?>
<?php $recibos_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($recibos_list->TotalRecs <= 0)
			$recibos_list->TotalRecs = $recibos->SelectRecordCount();
	} else {
		if (!$recibos_list->Recordset && ($recibos_list->Recordset = $recibos_list->LoadRecordset()))
			$recibos_list->TotalRecs = $recibos_list->Recordset->RecordCount();
	}
	$recibos_list->StartRec = 1;
	if ($recibos_list->DisplayRecs <= 0 || ($recibos->Export <> "" && $recibos->ExportAll)) // Display all records
		$recibos_list->DisplayRecs = $recibos_list->TotalRecs;
	if (!($recibos->Export <> "" && $recibos->ExportAll))
		$recibos_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$recibos_list->Recordset = $recibos_list->LoadRecordset($recibos_list->StartRec-1, $recibos_list->DisplayRecs);

	// Set no record found message
	if ($recibos->CurrentAction == "" && $recibos_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$recibos_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($recibos_list->SearchWhere == "0=101")
			$recibos_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$recibos_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$recibos_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($recibos->Export == "" && $recibos->CurrentAction == "") { ?>
<form name="freciboslistsrch" id="freciboslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($recibos_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="freciboslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="recibos">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($recibos_list->BasicSearch->getKeyword()) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($recibos_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $recibos_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($recibos_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($recibos_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($recibos_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($recibos_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $recibos_list->ShowPageHeader(); ?>
<?php
$recibos_list->ShowMessage();
?>
<?php if ($recibos_list->TotalRecs > 0 || $recibos->CurrentAction <> "") { ?>
<div class="ewGrid">
<div class="ewGridUpperPanel">
<?php if ($recibos->CurrentAction <> "gridadd" && $recibos->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($recibos_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="freciboslist" id="freciboslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($recibos_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $recibos_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="recibos">
<div id="gmp_recibos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($recibos_list->TotalRecs > 0) { ?>
<table id="tbl_reciboslist" class="table ewTable">
<?php echo $recibos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$recibos->RowType = EW_ROWTYPE_HEADER;

// Render list options
$recibos_list->RenderListOptions();

// Render list options (header, left)
$recibos_list->ListOptions->Render("header", "left");
?>
<?php if ($recibos->id_empresa->Visible) { // id_empresa ?>
	<?php if ($recibos->SortUrl($recibos->id_empresa) == "") { ?>
		<th data-name="id_empresa"><div id="elh_recibos_id_empresa" class="recibos_id_empresa"><div class="ewTableHeaderCaption"><?php echo $recibos->id_empresa->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_empresa"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $recibos->SortUrl($recibos->id_empresa) ?>',1);"><div id="elh_recibos_id_empresa" class="recibos_id_empresa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $recibos->id_empresa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($recibos->id_empresa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($recibos->id_empresa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($recibos->fecha_emision->Visible) { // fecha_emision ?>
	<?php if ($recibos->SortUrl($recibos->fecha_emision) == "") { ?>
		<th data-name="fecha_emision"><div id="elh_recibos_fecha_emision" class="recibos_fecha_emision"><div class="ewTableHeaderCaption"><?php echo $recibos->fecha_emision->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_emision"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $recibos->SortUrl($recibos->fecha_emision) ?>',1);"><div id="elh_recibos_fecha_emision" class="recibos_fecha_emision">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $recibos->fecha_emision->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($recibos->fecha_emision->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($recibos->fecha_emision->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($recibos->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
	<?php if ($recibos->SortUrl($recibos->fecha_vencimiento) == "") { ?>
		<th data-name="fecha_vencimiento"><div id="elh_recibos_fecha_vencimiento" class="recibos_fecha_vencimiento"><div class="ewTableHeaderCaption"><?php echo $recibos->fecha_vencimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_vencimiento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $recibos->SortUrl($recibos->fecha_vencimiento) ?>',1);"><div id="elh_recibos_fecha_vencimiento" class="recibos_fecha_vencimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $recibos->fecha_vencimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($recibos->fecha_vencimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($recibos->fecha_vencimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($recibos->numero->Visible) { // numero ?>
	<?php if ($recibos->SortUrl($recibos->numero) == "") { ?>
		<th data-name="numero"><div id="elh_recibos_numero" class="recibos_numero"><div class="ewTableHeaderCaption"><?php echo $recibos->numero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="numero"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $recibos->SortUrl($recibos->numero) ?>',1);"><div id="elh_recibos_numero" class="recibos_numero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $recibos->numero->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($recibos->numero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($recibos->numero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($recibos->monto_base->Visible) { // monto_base ?>
	<?php if ($recibos->SortUrl($recibos->monto_base) == "") { ?>
		<th data-name="monto_base"><div id="elh_recibos_monto_base" class="recibos_monto_base"><div class="ewTableHeaderCaption"><?php echo $recibos->monto_base->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="monto_base"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $recibos->SortUrl($recibos->monto_base) ?>',1);"><div id="elh_recibos_monto_base" class="recibos_monto_base">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $recibos->monto_base->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($recibos->monto_base->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($recibos->monto_base->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($recibos->monto_impuesto->Visible) { // monto_impuesto ?>
	<?php if ($recibos->SortUrl($recibos->monto_impuesto) == "") { ?>
		<th data-name="monto_impuesto"><div id="elh_recibos_monto_impuesto" class="recibos_monto_impuesto"><div class="ewTableHeaderCaption"><?php echo $recibos->monto_impuesto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="monto_impuesto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $recibos->SortUrl($recibos->monto_impuesto) ?>',1);"><div id="elh_recibos_monto_impuesto" class="recibos_monto_impuesto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $recibos->monto_impuesto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($recibos->monto_impuesto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($recibos->monto_impuesto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($recibos->monto_total->Visible) { // monto_total ?>
	<?php if ($recibos->SortUrl($recibos->monto_total) == "") { ?>
		<th data-name="monto_total"><div id="elh_recibos_monto_total" class="recibos_monto_total"><div class="ewTableHeaderCaption"><?php echo $recibos->monto_total->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="monto_total"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $recibos->SortUrl($recibos->monto_total) ?>',1);"><div id="elh_recibos_monto_total" class="recibos_monto_total">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $recibos->monto_total->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($recibos->monto_total->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($recibos->monto_total->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$recibos_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($recibos->ExportAll && $recibos->Export <> "") {
	$recibos_list->StopRec = $recibos_list->TotalRecs;
} else {

	// Set the last record to display
	if ($recibos_list->TotalRecs > $recibos_list->StartRec + $recibos_list->DisplayRecs - 1)
		$recibos_list->StopRec = $recibos_list->StartRec + $recibos_list->DisplayRecs - 1;
	else
		$recibos_list->StopRec = $recibos_list->TotalRecs;
}
$recibos_list->RecCnt = $recibos_list->StartRec - 1;
if ($recibos_list->Recordset && !$recibos_list->Recordset->EOF) {
	$recibos_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $recibos_list->StartRec > 1)
		$recibos_list->Recordset->Move($recibos_list->StartRec - 1);
} elseif (!$recibos->AllowAddDeleteRow && $recibos_list->StopRec == 0) {
	$recibos_list->StopRec = $recibos->GridAddRowCount;
}

// Initialize aggregate
$recibos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$recibos->ResetAttrs();
$recibos_list->RenderRow();
while ($recibos_list->RecCnt < $recibos_list->StopRec) {
	$recibos_list->RecCnt++;
	if (intval($recibos_list->RecCnt) >= intval($recibos_list->StartRec)) {
		$recibos_list->RowCnt++;

		// Set up key count
		$recibos_list->KeyCount = $recibos_list->RowIndex;

		// Init row class and style
		$recibos->ResetAttrs();
		$recibos->CssClass = "";
		if ($recibos->CurrentAction == "gridadd") {
		} else {
			$recibos_list->LoadRowValues($recibos_list->Recordset); // Load row values
		}
		$recibos->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$recibos->RowAttrs = array_merge($recibos->RowAttrs, array('data-rowindex'=>$recibos_list->RowCnt, 'id'=>'r' . $recibos_list->RowCnt . '_recibos', 'data-rowtype'=>$recibos->RowType));

		// Render row
		$recibos_list->RenderRow();

		// Render list options
		$recibos_list->RenderListOptions();
?>
	<tr<?php echo $recibos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$recibos_list->ListOptions->Render("body", "left", $recibos_list->RowCnt);
?>
	<?php if ($recibos->id_empresa->Visible) { // id_empresa ?>
		<td data-name="id_empresa"<?php echo $recibos->id_empresa->CellAttributes() ?>>
<span<?php echo $recibos->id_empresa->ViewAttributes() ?>>
<?php echo $recibos->id_empresa->ListViewValue() ?></span>
<a id="<?php echo $recibos_list->PageObjName . "_row_" . $recibos_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($recibos->fecha_emision->Visible) { // fecha_emision ?>
		<td data-name="fecha_emision"<?php echo $recibos->fecha_emision->CellAttributes() ?>>
<span<?php echo $recibos->fecha_emision->ViewAttributes() ?>>
<?php echo $recibos->fecha_emision->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($recibos->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
		<td data-name="fecha_vencimiento"<?php echo $recibos->fecha_vencimiento->CellAttributes() ?>>
<span<?php echo $recibos->fecha_vencimiento->ViewAttributes() ?>>
<?php echo $recibos->fecha_vencimiento->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($recibos->numero->Visible) { // numero ?>
		<td data-name="numero"<?php echo $recibos->numero->CellAttributes() ?>>
<span<?php echo $recibos->numero->ViewAttributes() ?>>
<?php echo $recibos->numero->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($recibos->monto_base->Visible) { // monto_base ?>
		<td data-name="monto_base"<?php echo $recibos->monto_base->CellAttributes() ?>>
<span<?php echo $recibos->monto_base->ViewAttributes() ?>>
<?php echo $recibos->monto_base->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($recibos->monto_impuesto->Visible) { // monto_impuesto ?>
		<td data-name="monto_impuesto"<?php echo $recibos->monto_impuesto->CellAttributes() ?>>
<span<?php echo $recibos->monto_impuesto->ViewAttributes() ?>>
<?php echo $recibos->monto_impuesto->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($recibos->monto_total->Visible) { // monto_total ?>
		<td data-name="monto_total"<?php echo $recibos->monto_total->CellAttributes() ?>>
<span<?php echo $recibos->monto_total->ViewAttributes() ?>>
<?php echo $recibos->monto_total->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$recibos_list->ListOptions->Render("body", "right", $recibos_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($recibos->CurrentAction <> "gridadd")
		$recibos_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($recibos->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($recibos_list->Recordset)
	$recibos_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($recibos->CurrentAction <> "gridadd" && $recibos->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($recibos_list->Pager)) $recibos_list->Pager = new cPrevNextPager($recibos_list->StartRec, $recibos_list->DisplayRecs, $recibos_list->TotalRecs) ?>
<?php if ($recibos_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($recibos_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $recibos_list->PageUrl() ?>start=<?php echo $recibos_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($recibos_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $recibos_list->PageUrl() ?>start=<?php echo $recibos_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $recibos_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($recibos_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $recibos_list->PageUrl() ?>start=<?php echo $recibos_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($recibos_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $recibos_list->PageUrl() ?>start=<?php echo $recibos_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $recibos_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $recibos_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $recibos_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $recibos_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($recibos_list->TotalRecs == 0 && $recibos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($recibos_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
freciboslistsrch.Init();
freciboslist.Init();
</script>
<?php
$recibos_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$recibos_list->Page_Terminate();
?>
