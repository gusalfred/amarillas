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

$contratos_delete = NULL; // Initialize page object first

class ccontratos_delete extends ccontratos {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'contratos';

	// Page object name
	var $PageObjName = 'contratos_delete';

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

		// Table object (contratos)
		if (!isset($GLOBALS["contratos"]) || get_class($GLOBALS["contratos"]) == "ccontratos") {
			$GLOBALS["contratos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["contratos"];
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
			define("EW_TABLE_NAME", 'contratos', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("contratos_list.php"));
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
			$this->Page_Terminate("contratos_list.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in contratos class, contratosinfo.php

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

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
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
				$sThisKey .= $row['id_contrato'];
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
		$Breadcrumb->Add("list", $this->TableVar, "contratos_list.php", "", $this->TableVar, TRUE);
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
if (!isset($contratos_delete)) $contratos_delete = new ccontratos_delete();

// Page init
$contratos_delete->Page_Init();

// Page main
$contratos_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contratos_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var contratos_delete = new ew_Page("contratos_delete");
contratos_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = contratos_delete.PageID; // For backward compatibility

// Form object
var fcontratosdelete = new ew_Form("fcontratosdelete");

// Form_CustomValidate event
fcontratosdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontratosdelete.ValidateRequired = true;
<?php } else { ?>
fcontratosdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcontratosdelete.Lists["x_id_empresa"] = {"LinkField":"x_id_empresa","Ajax":true,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontratosdelete.Lists["x_id_plan"] = {"LinkField":"x_id_plan","Ajax":null,"AutoFill":false,"DisplayFields":["x_nombre","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($contratos_delete->Recordset = $contratos_delete->LoadRecordset())
	$contratos_deleteTotalRecs = $contratos_delete->Recordset->RecordCount(); // Get record count
if ($contratos_deleteTotalRecs <= 0) { // No record found, exit
	if ($contratos_delete->Recordset)
		$contratos_delete->Recordset->Close();
	$contratos_delete->Page_Terminate("contratos_list.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $contratos_delete->ShowPageHeader(); ?>
<?php
$contratos_delete->ShowMessage();
?>
<form name="fcontratosdelete" id="fcontratosdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($contratos_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $contratos_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="contratos">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($contratos_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $contratos->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($contratos->id_empresa->Visible) { // id_empresa ?>
		<th><span id="elh_contratos_id_empresa" class="contratos_id_empresa"><?php echo $contratos->id_empresa->FldCaption() ?></span></th>
<?php } ?>
<?php if ($contratos->tipo->Visible) { // tipo ?>
		<th><span id="elh_contratos_tipo" class="contratos_tipo"><?php echo $contratos->tipo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($contratos->numero->Visible) { // numero ?>
		<th><span id="elh_contratos_numero" class="contratos_numero"><?php echo $contratos->numero->FldCaption() ?></span></th>
<?php } ?>
<?php if ($contratos->fecha->Visible) { // fecha ?>
		<th><span id="elh_contratos_fecha" class="contratos_fecha"><?php echo $contratos->fecha->FldCaption() ?></span></th>
<?php } ?>
<?php if ($contratos->id_plan->Visible) { // id_plan ?>
		<th><span id="elh_contratos_id_plan" class="contratos_id_plan"><?php echo $contratos->id_plan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($contratos->sub_total->Visible) { // sub_total ?>
		<th><span id="elh_contratos_sub_total" class="contratos_sub_total"><?php echo $contratos->sub_total->FldCaption() ?></span></th>
<?php } ?>
<?php if ($contratos->impuesto->Visible) { // impuesto ?>
		<th><span id="elh_contratos_impuesto" class="contratos_impuesto"><?php echo $contratos->impuesto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($contratos->estatus->Visible) { // estatus ?>
		<th><span id="elh_contratos_estatus" class="contratos_estatus"><?php echo $contratos->estatus->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$contratos_delete->RecCnt = 0;
$i = 0;
while (!$contratos_delete->Recordset->EOF) {
	$contratos_delete->RecCnt++;
	$contratos_delete->RowCnt++;

	// Set row properties
	$contratos->ResetAttrs();
	$contratos->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$contratos_delete->LoadRowValues($contratos_delete->Recordset);

	// Render row
	$contratos_delete->RenderRow();
?>
	<tr<?php echo $contratos->RowAttributes() ?>>
<?php if ($contratos->id_empresa->Visible) { // id_empresa ?>
		<td<?php echo $contratos->id_empresa->CellAttributes() ?>>
<span id="el<?php echo $contratos_delete->RowCnt ?>_contratos_id_empresa" class="contratos_id_empresa">
<span<?php echo $contratos->id_empresa->ViewAttributes() ?>>
<?php echo $contratos->id_empresa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($contratos->tipo->Visible) { // tipo ?>
		<td<?php echo $contratos->tipo->CellAttributes() ?>>
<span id="el<?php echo $contratos_delete->RowCnt ?>_contratos_tipo" class="contratos_tipo">
<span<?php echo $contratos->tipo->ViewAttributes() ?>>
<?php echo $contratos->tipo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($contratos->numero->Visible) { // numero ?>
		<td<?php echo $contratos->numero->CellAttributes() ?>>
<span id="el<?php echo $contratos_delete->RowCnt ?>_contratos_numero" class="contratos_numero">
<span<?php echo $contratos->numero->ViewAttributes() ?>>
<?php echo $contratos->numero->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($contratos->fecha->Visible) { // fecha ?>
		<td<?php echo $contratos->fecha->CellAttributes() ?>>
<span id="el<?php echo $contratos_delete->RowCnt ?>_contratos_fecha" class="contratos_fecha">
<span<?php echo $contratos->fecha->ViewAttributes() ?>>
<?php echo $contratos->fecha->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($contratos->id_plan->Visible) { // id_plan ?>
		<td<?php echo $contratos->id_plan->CellAttributes() ?>>
<span id="el<?php echo $contratos_delete->RowCnt ?>_contratos_id_plan" class="contratos_id_plan">
<span<?php echo $contratos->id_plan->ViewAttributes() ?>>
<?php echo $contratos->id_plan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($contratos->sub_total->Visible) { // sub_total ?>
		<td<?php echo $contratos->sub_total->CellAttributes() ?>>
<span id="el<?php echo $contratos_delete->RowCnt ?>_contratos_sub_total" class="contratos_sub_total">
<span<?php echo $contratos->sub_total->ViewAttributes() ?>>
<?php echo $contratos->sub_total->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($contratos->impuesto->Visible) { // impuesto ?>
		<td<?php echo $contratos->impuesto->CellAttributes() ?>>
<span id="el<?php echo $contratos_delete->RowCnt ?>_contratos_impuesto" class="contratos_impuesto">
<span<?php echo $contratos->impuesto->ViewAttributes() ?>>
<?php echo $contratos->impuesto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($contratos->estatus->Visible) { // estatus ?>
		<td<?php echo $contratos->estatus->CellAttributes() ?>>
<span id="el<?php echo $contratos_delete->RowCnt ?>_contratos_estatus" class="contratos_estatus">
<span<?php echo $contratos->estatus->ViewAttributes() ?>>
<?php echo $contratos->estatus->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$contratos_delete->Recordset->MoveNext();
}
$contratos_delete->Recordset->Close();
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
fcontratosdelete.Init();
</script>
<?php
$contratos_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$contratos_delete->Page_Terminate();
?>
