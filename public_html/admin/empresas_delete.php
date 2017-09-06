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

$empresas_delete = NULL; // Initialize page object first

class cempresas_delete extends cempresas {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{D02329FA-C783-46FA-A3B4-996FD9449799}";

	// Table name
	var $TableName = 'empresas';

	// Page object name
	var $PageObjName = 'empresas_delete';

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

		// Table object (empresas)
		if (!isset($GLOBALS["empresas"]) || get_class($GLOBALS["empresas"]) == "cempresas") {
			$GLOBALS["empresas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["empresas"];
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
			define("EW_TABLE_NAME", 'empresas', TRUE);

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
			$this->Page_Terminate(ew_GetUrl("empresas_list.php"));
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
			$this->Page_Terminate("empresas_list.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in empresas class, empresasinfo.php

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
		$this->id_empresa->setDbValue($rs->fields('id_empresa'));
		$this->codigo->setDbValue($rs->fields('codigo'));
		$this->nombre->setDbValue($rs->fields('nombre'));
		$this->url->setDbValue($rs->fields('url'));
		$this->id_categoria_nivel1->setDbValue($rs->fields('id_categoria_nivel1'));
		$this->id_categoria_nivel2->setDbValue($rs->fields('id_categoria_nivel2'));
		$this->codigo_departamento->setDbValue($rs->fields('codigo_departamento'));
		$this->codigo_provincia->setDbValue($rs->fields('codigo_provincia'));
		$this->id_rubro->setDbValue($rs->fields('id_rubro'));
		$this->rubro->setDbValue($rs->fields('rubro'));
		$this->direccion->setDbValue($rs->fields('direccion'));
		$this->direcciones->setDbValue($rs->fields('direcciones'));
		$this->web->setDbValue($rs->fields('web'));
		$this->latitud->setDbValue($rs->fields('latitud'));
		$this->longitud->setDbValue($rs->fields('longitud'));
		$this->logo->Upload->DbValue = $rs->fields('logo');
		$this->logo->CurrentValue = $this->logo->Upload->DbValue;
		$this->url_logo->setDbValue($rs->fields('url_logo'));
		$this->revisado->setDbValue($rs->fields('revisado'));
		$this->direcciones2->setDbValue($rs->fields('direcciones2'));
		$this->direcciones_pro->setDbValue($rs->fields('direcciones_pro'));
		$this->descripcion->setDbValue($rs->fields('descripcion'));
		$this->redes_twiter->setDbValue($rs->fields('redes_twiter'));
		$this->redes_facebook->setDbValue($rs->fields('redes_facebook'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_empresa->DbValue = $row['id_empresa'];
		$this->codigo->DbValue = $row['codigo'];
		$this->nombre->DbValue = $row['nombre'];
		$this->url->DbValue = $row['url'];
		$this->id_categoria_nivel1->DbValue = $row['id_categoria_nivel1'];
		$this->id_categoria_nivel2->DbValue = $row['id_categoria_nivel2'];
		$this->codigo_departamento->DbValue = $row['codigo_departamento'];
		$this->codigo_provincia->DbValue = $row['codigo_provincia'];
		$this->id_rubro->DbValue = $row['id_rubro'];
		$this->rubro->DbValue = $row['rubro'];
		$this->direccion->DbValue = $row['direccion'];
		$this->direcciones->DbValue = $row['direcciones'];
		$this->web->DbValue = $row['web'];
		$this->latitud->DbValue = $row['latitud'];
		$this->longitud->DbValue = $row['longitud'];
		$this->logo->Upload->DbValue = $row['logo'];
		$this->url_logo->DbValue = $row['url_logo'];
		$this->revisado->DbValue = $row['revisado'];
		$this->direcciones2->DbValue = $row['direcciones2'];
		$this->direcciones_pro->DbValue = $row['direcciones_pro'];
		$this->descripcion->DbValue = $row['descripcion'];
		$this->redes_twiter->DbValue = $row['redes_twiter'];
		$this->redes_facebook->DbValue = $row['redes_facebook'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_empresa
		// codigo
		// nombre
		// url
		// id_categoria_nivel1
		// id_categoria_nivel2
		// codigo_departamento
		// codigo_provincia
		// id_rubro
		// rubro
		// direccion
		// direcciones
		// web
		// latitud
		// longitud
		// logo
		// url_logo
		// revisado
		// direcciones2
		// direcciones_pro
		// descripcion
		// redes_twiter
		// redes_facebook

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_empresa
			$this->id_empresa->ViewValue = $this->id_empresa->CurrentValue;
			$this->id_empresa->ViewCustomAttributes = "";

			// codigo
			$this->codigo->ViewValue = $this->codigo->CurrentValue;
			$this->codigo->ViewCustomAttributes = "";

			// nombre
			$this->nombre->ViewValue = $this->nombre->CurrentValue;
			$this->nombre->ViewCustomAttributes = "";

			// url
			$this->url->ViewValue = $this->url->CurrentValue;
			$this->url->ViewCustomAttributes = "";

			// id_categoria_nivel1
			if (strval($this->id_categoria_nivel1->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_categoria_nivel1->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categories`";
			$sWhereWrk = "";
			$lookuptblfilter = "`parent_id` = 0";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_categoria_nivel1, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `name`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_categoria_nivel1->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_categoria_nivel1->ViewValue = $this->id_categoria_nivel1->CurrentValue;
				}
			} else {
				$this->id_categoria_nivel1->ViewValue = NULL;
			}
			$this->id_categoria_nivel1->ViewCustomAttributes = "";

			// id_categoria_nivel2
			if (strval($this->id_categoria_nivel2->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->id_categoria_nivel2->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categories`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_categoria_nivel2, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `name`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_categoria_nivel2->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_categoria_nivel2->ViewValue = $this->id_categoria_nivel2->CurrentValue;
				}
			} else {
				$this->id_categoria_nivel2->ViewValue = NULL;
			}
			$this->id_categoria_nivel2->ViewCustomAttributes = "";

			// codigo_departamento
			if (strval($this->codigo_departamento->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->codigo_departamento->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT `codigo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `departamentos`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codigo_departamento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->codigo_departamento->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->codigo_departamento->ViewValue = $this->codigo_departamento->CurrentValue;
				}
			} else {
				$this->codigo_departamento->ViewValue = NULL;
			}
			$this->codigo_departamento->ViewCustomAttributes = "";

			// codigo_provincia
			if (strval($this->codigo_provincia->CurrentValue) <> "") {
				$sFilterWrk = "`codigo`" . ew_SearchString("=", $this->codigo_provincia->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT `codigo`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `provincias`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->codigo_provincia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->codigo_provincia->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->codigo_provincia->ViewValue = $this->codigo_provincia->CurrentValue;
				}
			} else {
				$this->codigo_provincia->ViewValue = NULL;
			}
			$this->codigo_provincia->ViewCustomAttributes = "";

			// id_rubro
			if (strval($this->id_rubro->CurrentValue) <> "") {
				$sFilterWrk = "`id_rubro`" . ew_SearchString("=", $this->id_rubro->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_rubro`, `nombre` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `rubros`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_rubro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY `nombre`";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_rubro->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_rubro->ViewValue = $this->id_rubro->CurrentValue;
				}
			} else {
				$this->id_rubro->ViewValue = NULL;
			}
			$this->id_rubro->ViewCustomAttributes = "";

			// rubro
			$this->rubro->ViewValue = $this->rubro->CurrentValue;
			$this->rubro->ViewCustomAttributes = "";

			// web
			$this->web->ViewValue = $this->web->CurrentValue;
			$this->web->ViewCustomAttributes = "";

			// latitud
			$this->latitud->ViewValue = $this->latitud->CurrentValue;
			$this->latitud->ViewCustomAttributes = "";

			// longitud
			$this->longitud->ViewValue = $this->longitud->CurrentValue;
			$this->longitud->ViewCustomAttributes = "";

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

			// url_logo
			$this->url_logo->ViewValue = $this->url_logo->CurrentValue;
			$this->url_logo->ViewCustomAttributes = "";

			// revisado
			$this->revisado->ViewValue = $this->revisado->CurrentValue;
			$this->revisado->ViewCustomAttributes = "";

			// direcciones_pro
			$this->direcciones_pro->ViewValue = $this->direcciones_pro->CurrentValue;
			$this->direcciones_pro->ViewCustomAttributes = "";

			// redes_twiter
			$this->redes_twiter->ViewValue = $this->redes_twiter->CurrentValue;
			$this->redes_twiter->ViewCustomAttributes = "";

			// redes_facebook
			$this->redes_facebook->ViewValue = $this->redes_facebook->CurrentValue;
			$this->redes_facebook->ViewCustomAttributes = "";

			// nombre
			$this->nombre->LinkCustomAttributes = "";
			$this->nombre->HrefValue = "";
			$this->nombre->TooltipValue = "";

			// id_categoria_nivel1
			$this->id_categoria_nivel1->LinkCustomAttributes = "";
			$this->id_categoria_nivel1->HrefValue = "";
			$this->id_categoria_nivel1->TooltipValue = "";

			// id_categoria_nivel2
			$this->id_categoria_nivel2->LinkCustomAttributes = "";
			$this->id_categoria_nivel2->HrefValue = "";
			$this->id_categoria_nivel2->TooltipValue = "";

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
				$this->logo->LinkAttrs["data-rel"] = "empresas_x_logo";
				$this->logo->LinkAttrs["class"] = "ewLightbox";
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
				$sThisKey .= $row['id_empresa'];
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
		$Breadcrumb->Add("list", $this->TableVar, "empresas_list.php", "", $this->TableVar, TRUE);
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
if (!isset($empresas_delete)) $empresas_delete = new cempresas_delete();

// Page init
$empresas_delete->Page_Init();

// Page main
$empresas_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$empresas_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var empresas_delete = new ew_Page("empresas_delete");
empresas_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = empresas_delete.PageID; // For backward compatibility

// Form object
var fempresasdelete = new ew_Form("fempresasdelete");

// Form_CustomValidate event
fempresasdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fempresasdelete.ValidateRequired = true;
<?php } else { ?>
fempresasdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fempresasdelete.Lists["x_id_categoria_nivel1"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fempresasdelete.Lists["x_id_categoria_nivel2"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($empresas_delete->Recordset = $empresas_delete->LoadRecordset())
	$empresas_deleteTotalRecs = $empresas_delete->Recordset->RecordCount(); // Get record count
if ($empresas_deleteTotalRecs <= 0) { // No record found, exit
	if ($empresas_delete->Recordset)
		$empresas_delete->Recordset->Close();
	$empresas_delete->Page_Terminate("empresas_list.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $empresas_delete->ShowPageHeader(); ?>
<?php
$empresas_delete->ShowMessage();
?>
<form name="fempresasdelete" id="fempresasdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($empresas_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $empresas_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="empresas">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($empresas_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $empresas->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($empresas->nombre->Visible) { // nombre ?>
		<th><span id="elh_empresas_nombre" class="empresas_nombre"><?php echo $empresas->nombre->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas->id_categoria_nivel1->Visible) { // id_categoria_nivel1 ?>
		<th><span id="elh_empresas_id_categoria_nivel1" class="empresas_id_categoria_nivel1"><?php echo $empresas->id_categoria_nivel1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas->id_categoria_nivel2->Visible) { // id_categoria_nivel2 ?>
		<th><span id="elh_empresas_id_categoria_nivel2" class="empresas_id_categoria_nivel2"><?php echo $empresas->id_categoria_nivel2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($empresas->logo->Visible) { // logo ?>
		<th><span id="elh_empresas_logo" class="empresas_logo"><?php echo $empresas->logo->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$empresas_delete->RecCnt = 0;
$i = 0;
while (!$empresas_delete->Recordset->EOF) {
	$empresas_delete->RecCnt++;
	$empresas_delete->RowCnt++;

	// Set row properties
	$empresas->ResetAttrs();
	$empresas->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$empresas_delete->LoadRowValues($empresas_delete->Recordset);

	// Render row
	$empresas_delete->RenderRow();
?>
	<tr<?php echo $empresas->RowAttributes() ?>>
<?php if ($empresas->nombre->Visible) { // nombre ?>
		<td<?php echo $empresas->nombre->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas_nombre" class="empresas_nombre">
<span<?php echo $empresas->nombre->ViewAttributes() ?>>
<?php echo $empresas->nombre->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas->id_categoria_nivel1->Visible) { // id_categoria_nivel1 ?>
		<td<?php echo $empresas->id_categoria_nivel1->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas_id_categoria_nivel1" class="empresas_id_categoria_nivel1">
<span<?php echo $empresas->id_categoria_nivel1->ViewAttributes() ?>>
<?php echo $empresas->id_categoria_nivel1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas->id_categoria_nivel2->Visible) { // id_categoria_nivel2 ?>
		<td<?php echo $empresas->id_categoria_nivel2->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas_id_categoria_nivel2" class="empresas_id_categoria_nivel2">
<span<?php echo $empresas->id_categoria_nivel2->ViewAttributes() ?>>
<?php echo $empresas->id_categoria_nivel2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($empresas->logo->Visible) { // logo ?>
		<td<?php echo $empresas->logo->CellAttributes() ?>>
<span id="el<?php echo $empresas_delete->RowCnt ?>_empresas_logo" class="empresas_logo">
<span>
<?php echo ew_GetFileViewTag($empresas->logo, $empresas->logo->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$empresas_delete->Recordset->MoveNext();
}
$empresas_delete->Recordset->Close();
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
fempresasdelete.Init();
</script>
<?php
$empresas_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$empresas_delete->Page_Terminate();
?>
