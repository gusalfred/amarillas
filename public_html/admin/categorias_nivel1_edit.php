<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg11.php" ?>
<?php include_once "ewmysql11.php" ?>
<?php include_once "phpfn11.php" ?>
<?php include_once "categorias_nivel1_info.php" ?>
<?php include_once "empleados_info.php" ?>
<?php include_once "categorias_nivel2_gridcls.php" ?>
<?php include_once "userfn11.php" ?>
<?php

//
// Page class
//

$categorias_nivel1_edit = NULL; // Initialize page object first

class ccategorias_nivel1_edit extends ccategorias_nivel1 {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'categorias_nivel1';

	// Page object name
	var $PageObjName = 'categorias_nivel1_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ew_GetUrl("categorias_nivel1_list.php"));
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id_categoria_nivel1"] <> "") {
			$this->id_categoria_nivel1->setQueryStringValue($_GET["id_categoria_nivel1"]);
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
		if ($this->id_categoria_nivel1->CurrentValue == "")
			$this->Page_Terminate("categorias_nivel1_list.php"); // Invalid key, return to list

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
					$this->Page_Terminate("categorias_nivel1_list.php"); // No matching record, return to list
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
		$this->imagen->Upload->Index = $objForm->Index;
		$this->imagen->Upload->UploadFile();
		$this->imagen->CurrentValue = $this->imagen->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->categoria->FldIsDetailKey) {
			$this->categoria->setFormValue($objForm->GetValue("x_categoria"));
		}
		if (!$this->descripcion->FldIsDetailKey) {
			$this->descripcion->setFormValue($objForm->GetValue("x_descripcion"));
		}
		if (!$this->slug->FldIsDetailKey) {
			$this->slug->setFormValue($objForm->GetValue("x_slug"));
		}
		if (!$this->orden->FldIsDetailKey) {
			$this->orden->setFormValue($objForm->GetValue("x_orden"));
		}
		if (!$this->id_categoria_nivel1->FldIsDetailKey)
			$this->id_categoria_nivel1->setFormValue($objForm->GetValue("x_id_categoria_nivel1"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id_categoria_nivel1->CurrentValue = $this->id_categoria_nivel1->FormValue;
		$this->categoria->CurrentValue = $this->categoria->FormValue;
		$this->descripcion->CurrentValue = $this->descripcion->FormValue;
		$this->slug->CurrentValue = $this->slug->FormValue;
		$this->orden->CurrentValue = $this->orden->FormValue;
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

			// descripcion
			$this->descripcion->LinkCustomAttributes = "";
			$this->descripcion->HrefValue = "";
			$this->descripcion->TooltipValue = "";

			// slug
			$this->slug->LinkCustomAttributes = "";
			$this->slug->HrefValue = "";
			$this->slug->TooltipValue = "";

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

			// orden
			$this->orden->LinkCustomAttributes = "";
			$this->orden->HrefValue = "";
			$this->orden->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// categoria
			$this->categoria->EditAttrs["class"] = "form-control";
			$this->categoria->EditCustomAttributes = "";
			$this->categoria->EditValue = ew_HtmlEncode($this->categoria->CurrentValue);

			// descripcion
			$this->descripcion->EditAttrs["class"] = "form-control";
			$this->descripcion->EditCustomAttributes = "";
			$this->descripcion->EditValue = ew_HtmlEncode($this->descripcion->CurrentValue);

			// slug
			$this->slug->EditAttrs["class"] = "form-control";
			$this->slug->EditCustomAttributes = "";
			$this->slug->EditValue = ew_HtmlEncode($this->slug->CurrentValue);

			// imagen
			$this->imagen->EditAttrs["class"] = "form-control";
			$this->imagen->EditCustomAttributes = "";
			$this->imagen->UploadPath = '../uploads/categorias/';
			if (!ew_Empty($this->imagen->Upload->DbValue)) {
				$this->imagen->ImageAlt = $this->imagen->FldAlt();
				$this->imagen->EditValue = ew_UploadPathEx(FALSE, $this->imagen->UploadPath) . $this->imagen->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->imagen->EditValue = ew_UploadPathEx(TRUE, $this->imagen->UploadPath) . $this->imagen->Upload->DbValue;
				}
			} else {
				$this->imagen->EditValue = "";
			}
			if (!ew_Empty($this->imagen->CurrentValue))
				$this->imagen->Upload->FileName = $this->imagen->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->imagen);

			// orden
			$this->orden->EditAttrs["class"] = "form-control";
			$this->orden->EditCustomAttributes = "";
			$this->orden->EditValue = ew_HtmlEncode($this->orden->CurrentValue);

			// Edit refer script
			// categoria

			$this->categoria->HrefValue = "";

			// descripcion
			$this->descripcion->HrefValue = "";

			// slug
			$this->slug->HrefValue = "";

			// imagen
			$this->imagen->UploadPath = '../uploads/categorias/';
			if (!ew_Empty($this->imagen->Upload->DbValue)) {
				$this->imagen->HrefValue = ew_UploadPathEx(FALSE, $this->imagen->UploadPath) . $this->imagen->Upload->DbValue; // Add prefix/suffix
				$this->imagen->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->imagen->HrefValue = ew_ConvertFullUrl($this->imagen->HrefValue);
			} else {
				$this->imagen->HrefValue = "";
			}
			$this->imagen->HrefValue2 = $this->imagen->UploadPath . $this->imagen->Upload->DbValue;

			// orden
			$this->orden->HrefValue = "";
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
		if (!ew_CheckInteger($this->orden->FormValue)) {
			ew_AddMessage($gsFormError, $this->orden->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("categorias_nivel2", $DetailTblVar) && $GLOBALS["categorias_nivel2"]->DetailEdit) {
			if (!isset($GLOBALS["categorias_nivel2_grid"])) $GLOBALS["categorias_nivel2_grid"] = new ccategorias_nivel2_grid(); // get detail page object
			$GLOBALS["categorias_nivel2_grid"]->ValidateGridForm();
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
			$this->imagen->OldUploadPath = '../uploads/categorias/';
			$this->imagen->UploadPath = $this->imagen->OldUploadPath;
			$rsnew = array();

			// categoria
			$this->categoria->SetDbValueDef($rsnew, $this->categoria->CurrentValue, NULL, $this->categoria->ReadOnly);

			// descripcion
			$this->descripcion->SetDbValueDef($rsnew, $this->descripcion->CurrentValue, NULL, $this->descripcion->ReadOnly);

			// slug
			$this->slug->SetDbValueDef($rsnew, $this->slug->CurrentValue, NULL, $this->slug->ReadOnly);

			// imagen
			if (!($this->imagen->ReadOnly) && !$this->imagen->Upload->KeepFile) {
				$this->imagen->Upload->DbValue = $rsold['imagen']; // Get original value
				if ($this->imagen->Upload->FileName == "") {
					$rsnew['imagen'] = NULL;
				} else {
					$rsnew['imagen'] = $this->imagen->Upload->FileName;
				}
			}

			// orden
			$this->orden->SetDbValueDef($rsnew, $this->orden->CurrentValue, NULL, $this->orden->ReadOnly);
			if (!$this->imagen->Upload->KeepFile) {
				$this->imagen->UploadPath = '../uploads/categorias/';
				if (!ew_Empty($this->imagen->Upload->Value)) {
					if ($this->imagen->Upload->FileName == $this->imagen->Upload->DbValue) { // Overwrite if same file name
						$this->imagen->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['imagen'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->imagen->UploadPath), $rsnew['imagen']); // Get new file name
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
					if (!$this->imagen->Upload->KeepFile) {
						if (!ew_Empty($this->imagen->Upload->Value)) {
							$this->imagen->Upload->SaveToFile($this->imagen->UploadPath, $rsnew['imagen'], TRUE);
						}
						if ($this->imagen->Upload->DbValue <> "")
							@unlink(ew_UploadPathEx(TRUE, $this->imagen->OldUploadPath) . $this->imagen->Upload->DbValue);
					}
				}

				// Update detail records
				if ($EditRow) {
					$DetailTblVar = explode(",", $this->getCurrentDetailTable());
					if (in_array("categorias_nivel2", $DetailTblVar) && $GLOBALS["categorias_nivel2"]->DetailEdit) {
						if (!isset($GLOBALS["categorias_nivel2_grid"])) $GLOBALS["categorias_nivel2_grid"] = new ccategorias_nivel2_grid(); // Get detail page object
						$EditRow = $GLOBALS["categorias_nivel2_grid"]->GridUpdate();
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

		// imagen
		ew_CleanUploadTempPath($this->imagen, $this->imagen->Upload->Index);
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
			if (in_array("categorias_nivel2", $DetailTblVar)) {
				if (!isset($GLOBALS["categorias_nivel2_grid"]))
					$GLOBALS["categorias_nivel2_grid"] = new ccategorias_nivel2_grid;
				if ($GLOBALS["categorias_nivel2_grid"]->DetailEdit) {
					$GLOBALS["categorias_nivel2_grid"]->CurrentMode = "edit";
					$GLOBALS["categorias_nivel2_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["categorias_nivel2_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["categorias_nivel2_grid"]->setStartRecordNumber(1);
					$GLOBALS["categorias_nivel2_grid"]->id_categoria_nivel1->FldIsDetailKey = TRUE;
					$GLOBALS["categorias_nivel2_grid"]->id_categoria_nivel1->CurrentValue = $this->id_categoria_nivel1->CurrentValue;
					$GLOBALS["categorias_nivel2_grid"]->id_categoria_nivel1->setSessionValue($GLOBALS["categorias_nivel2_grid"]->id_categoria_nivel1->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "categorias_nivel1_list.php", "", $this->TableVar, TRUE);
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
if (!isset($categorias_nivel1_edit)) $categorias_nivel1_edit = new ccategorias_nivel1_edit();

// Page init
$categorias_nivel1_edit->Page_Init();

// Page main
$categorias_nivel1_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$categorias_nivel1_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var categorias_nivel1_edit = new ew_Page("categorias_nivel1_edit");
categorias_nivel1_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = categorias_nivel1_edit.PageID; // For backward compatibility

// Form object
var fcategorias_nivel1edit = new ew_Form("fcategorias_nivel1edit");

// Validate form
fcategorias_nivel1edit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_orden");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($categorias_nivel1->orden->FldErrMsg()) ?>");

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
fcategorias_nivel1edit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcategorias_nivel1edit.ValidateRequired = true;
<?php } else { ?>
fcategorias_nivel1edit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
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
<?php $categorias_nivel1_edit->ShowPageHeader(); ?>
<?php
$categorias_nivel1_edit->ShowMessage();
?>
<form name="fcategorias_nivel1edit" id="fcategorias_nivel1edit" class="form-horizontal ewForm ewEditForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($categorias_nivel1_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $categorias_nivel1_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="categorias_nivel1">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($categorias_nivel1->categoria->Visible) { // categoria ?>
	<div id="r_categoria" class="form-group">
		<label id="elh_categorias_nivel1_categoria" for="x_categoria" class="col-sm-2 control-label ewLabel"><?php echo $categorias_nivel1->categoria->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $categorias_nivel1->categoria->CellAttributes() ?>>
<span id="el_categorias_nivel1_categoria">
<input type="text" data-field="x_categoria" name="x_categoria" id="x_categoria" size="30" maxlength="200" value="<?php echo $categorias_nivel1->categoria->EditValue ?>"<?php echo $categorias_nivel1->categoria->EditAttributes() ?>>
</span>
<?php echo $categorias_nivel1->categoria->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($categorias_nivel1->descripcion->Visible) { // descripcion ?>
	<div id="r_descripcion" class="form-group">
		<label id="elh_categorias_nivel1_descripcion" for="x_descripcion" class="col-sm-2 control-label ewLabel"><?php echo $categorias_nivel1->descripcion->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $categorias_nivel1->descripcion->CellAttributes() ?>>
<span id="el_categorias_nivel1_descripcion">
<input type="text" data-field="x_descripcion" name="x_descripcion" id="x_descripcion" size="30" maxlength="255" value="<?php echo $categorias_nivel1->descripcion->EditValue ?>"<?php echo $categorias_nivel1->descripcion->EditAttributes() ?>>
</span>
<?php echo $categorias_nivel1->descripcion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($categorias_nivel1->slug->Visible) { // slug ?>
	<div id="r_slug" class="form-group">
		<label id="elh_categorias_nivel1_slug" for="x_slug" class="col-sm-2 control-label ewLabel"><?php echo $categorias_nivel1->slug->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $categorias_nivel1->slug->CellAttributes() ?>>
<span id="el_categorias_nivel1_slug">
<input type="text" data-field="x_slug" name="x_slug" id="x_slug" size="30" maxlength="255" value="<?php echo $categorias_nivel1->slug->EditValue ?>"<?php echo $categorias_nivel1->slug->EditAttributes() ?>>
</span>
<?php echo $categorias_nivel1->slug->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($categorias_nivel1->imagen->Visible) { // imagen ?>
	<div id="r_imagen" class="form-group">
		<label id="elh_categorias_nivel1_imagen" class="col-sm-2 control-label ewLabel"><?php echo $categorias_nivel1->imagen->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $categorias_nivel1->imagen->CellAttributes() ?>>
<span id="el_categorias_nivel1_imagen">
<div id="fd_x_imagen">
<span title="<?php echo $categorias_nivel1->imagen->FldTitle() ? $categorias_nivel1->imagen->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($categorias_nivel1->imagen->ReadOnly || $categorias_nivel1->imagen->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_imagen" name="x_imagen" id="x_imagen">
</span>
<input type="hidden" name="fn_x_imagen" id= "fn_x_imagen" value="<?php echo $categorias_nivel1->imagen->Upload->FileName ?>">
<?php if (@$_POST["fa_x_imagen"] == "0") { ?>
<input type="hidden" name="fa_x_imagen" id= "fa_x_imagen" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_imagen" id= "fa_x_imagen" value="1">
<?php } ?>
<input type="hidden" name="fs_x_imagen" id= "fs_x_imagen" value="200">
<input type="hidden" name="fx_x_imagen" id= "fx_x_imagen" value="<?php echo $categorias_nivel1->imagen->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_imagen" id= "fm_x_imagen" value="<?php echo $categorias_nivel1->imagen->UploadMaxFileSize ?>">
</div>
<table id="ft_x_imagen" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $categorias_nivel1->imagen->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($categorias_nivel1->orden->Visible) { // orden ?>
	<div id="r_orden" class="form-group">
		<label id="elh_categorias_nivel1_orden" for="x_orden" class="col-sm-2 control-label ewLabel"><?php echo $categorias_nivel1->orden->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $categorias_nivel1->orden->CellAttributes() ?>>
<span id="el_categorias_nivel1_orden">
<input type="text" data-field="x_orden" name="x_orden" id="x_orden" size="30" value="<?php echo $categorias_nivel1->orden->EditValue ?>"<?php echo $categorias_nivel1->orden->EditAttributes() ?>>
</span>
<?php echo $categorias_nivel1->orden->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-field="x_id_categoria_nivel1" name="x_id_categoria_nivel1" id="x_id_categoria_nivel1" value="<?php echo ew_HtmlEncode($categorias_nivel1->id_categoria_nivel1->CurrentValue) ?>">
<?php
	if (in_array("categorias_nivel2", explode(",", $categorias_nivel1->getCurrentDetailTable())) && $categorias_nivel2->DetailEdit) {
?>
<?php if ($categorias_nivel1->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("categorias_nivel2", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "categorias_nivel2_grid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fcategorias_nivel1edit.Init();
</script>
<?php
$categorias_nivel1_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$categorias_nivel1_edit->Page_Terminate();
?>
