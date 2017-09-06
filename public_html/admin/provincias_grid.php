<?php include_once "empleados_info.php" ?>
<?php

// Create page object
if (!isset($provincias_grid)) $provincias_grid = new cprovincias_grid();

// Page init
$provincias_grid->Page_Init();

// Page main
$provincias_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$provincias_grid->Page_Render();
?>
<?php if ($provincias->Export == "") { ?>
<script type="text/javascript">

// Page object
var provincias_grid = new ew_Page("provincias_grid");
provincias_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = provincias_grid.PageID; // For backward compatibility

// Form object
var fprovinciasgrid = new ew_Form("fprovinciasgrid");
fprovinciasgrid.FormKeyCountName = '<?php echo $provincias_grid->FormKeyCountName ?>';

// Validate form
fprovinciasgrid.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_codigo_provincia");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $provincias->codigo_provincia->FldCaption(), $provincias->codigo_provincia->ReqErrMsg)) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fprovinciasgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "codigo_provincia", false)) return false;
	if (ew_ValueChanged(fobj, infix, "provincia", false)) return false;
	return true;
}

// Form_CustomValidate event
fprovinciasgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprovinciasgrid.ValidateRequired = true;
<?php } else { ?>
fprovinciasgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($provincias->CurrentAction == "gridadd") {
	if ($provincias->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$provincias_grid->TotalRecs = $provincias->SelectRecordCount();
			$provincias_grid->Recordset = $provincias_grid->LoadRecordset($provincias_grid->StartRec-1, $provincias_grid->DisplayRecs);
		} else {
			if ($provincias_grid->Recordset = $provincias_grid->LoadRecordset())
				$provincias_grid->TotalRecs = $provincias_grid->Recordset->RecordCount();
		}
		$provincias_grid->StartRec = 1;
		$provincias_grid->DisplayRecs = $provincias_grid->TotalRecs;
	} else {
		$provincias->CurrentFilter = "0=1";
		$provincias_grid->StartRec = 1;
		$provincias_grid->DisplayRecs = $provincias->GridAddRowCount;
	}
	$provincias_grid->TotalRecs = $provincias_grid->DisplayRecs;
	$provincias_grid->StopRec = $provincias_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($provincias_grid->TotalRecs <= 0)
			$provincias_grid->TotalRecs = $provincias->SelectRecordCount();
	} else {
		if (!$provincias_grid->Recordset && ($provincias_grid->Recordset = $provincias_grid->LoadRecordset()))
			$provincias_grid->TotalRecs = $provincias_grid->Recordset->RecordCount();
	}
	$provincias_grid->StartRec = 1;
	$provincias_grid->DisplayRecs = $provincias_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$provincias_grid->Recordset = $provincias_grid->LoadRecordset($provincias_grid->StartRec-1, $provincias_grid->DisplayRecs);

	// Set no record found message
	if ($provincias->CurrentAction == "" && $provincias_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$provincias_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($provincias_grid->SearchWhere == "0=101")
			$provincias_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$provincias_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$provincias_grid->RenderOtherOptions();
?>
<?php $provincias_grid->ShowPageHeader(); ?>
<?php
$provincias_grid->ShowMessage();
?>
<?php if ($provincias_grid->TotalRecs > 0 || $provincias->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fprovinciasgrid" class="ewForm form-inline">
<?php if ($provincias_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($provincias_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_provincias" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_provinciasgrid" class="table ewTable">
<?php echo $provincias->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$provincias->RowType = EW_ROWTYPE_HEADER;

// Render list options
$provincias_grid->RenderListOptions();

// Render list options (header, left)
$provincias_grid->ListOptions->Render("header", "left");
?>
<?php if ($provincias->codigo_provincia->Visible) { // codigo_provincia ?>
	<?php if ($provincias->SortUrl($provincias->codigo_provincia) == "") { ?>
		<th data-name="codigo_provincia"><div id="elh_provincias_codigo_provincia" class="provincias_codigo_provincia"><div class="ewTableHeaderCaption"><?php echo $provincias->codigo_provincia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigo_provincia"><div><div id="elh_provincias_codigo_provincia" class="provincias_codigo_provincia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $provincias->codigo_provincia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($provincias->codigo_provincia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($provincias->codigo_provincia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($provincias->provincia->Visible) { // provincia ?>
	<?php if ($provincias->SortUrl($provincias->provincia) == "") { ?>
		<th data-name="provincia"><div id="elh_provincias_provincia" class="provincias_provincia"><div class="ewTableHeaderCaption"><?php echo $provincias->provincia->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="provincia"><div><div id="elh_provincias_provincia" class="provincias_provincia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $provincias->provincia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($provincias->provincia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($provincias->provincia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$provincias_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$provincias_grid->StartRec = 1;
$provincias_grid->StopRec = $provincias_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($provincias_grid->FormKeyCountName) && ($provincias->CurrentAction == "gridadd" || $provincias->CurrentAction == "gridedit" || $provincias->CurrentAction == "F")) {
		$provincias_grid->KeyCount = $objForm->GetValue($provincias_grid->FormKeyCountName);
		$provincias_grid->StopRec = $provincias_grid->StartRec + $provincias_grid->KeyCount - 1;
	}
}
$provincias_grid->RecCnt = $provincias_grid->StartRec - 1;
if ($provincias_grid->Recordset && !$provincias_grid->Recordset->EOF) {
	$provincias_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $provincias_grid->StartRec > 1)
		$provincias_grid->Recordset->Move($provincias_grid->StartRec - 1);
} elseif (!$provincias->AllowAddDeleteRow && $provincias_grid->StopRec == 0) {
	$provincias_grid->StopRec = $provincias->GridAddRowCount;
}

// Initialize aggregate
$provincias->RowType = EW_ROWTYPE_AGGREGATEINIT;
$provincias->ResetAttrs();
$provincias_grid->RenderRow();
if ($provincias->CurrentAction == "gridadd")
	$provincias_grid->RowIndex = 0;
if ($provincias->CurrentAction == "gridedit")
	$provincias_grid->RowIndex = 0;
while ($provincias_grid->RecCnt < $provincias_grid->StopRec) {
	$provincias_grid->RecCnt++;
	if (intval($provincias_grid->RecCnt) >= intval($provincias_grid->StartRec)) {
		$provincias_grid->RowCnt++;
		if ($provincias->CurrentAction == "gridadd" || $provincias->CurrentAction == "gridedit" || $provincias->CurrentAction == "F") {
			$provincias_grid->RowIndex++;
			$objForm->Index = $provincias_grid->RowIndex;
			if ($objForm->HasValue($provincias_grid->FormActionName))
				$provincias_grid->RowAction = strval($objForm->GetValue($provincias_grid->FormActionName));
			elseif ($provincias->CurrentAction == "gridadd")
				$provincias_grid->RowAction = "insert";
			else
				$provincias_grid->RowAction = "";
		}

		// Set up key count
		$provincias_grid->KeyCount = $provincias_grid->RowIndex;

		// Init row class and style
		$provincias->ResetAttrs();
		$provincias->CssClass = "";
		if ($provincias->CurrentAction == "gridadd") {
			if ($provincias->CurrentMode == "copy") {
				$provincias_grid->LoadRowValues($provincias_grid->Recordset); // Load row values
				$provincias_grid->SetRecordKey($provincias_grid->RowOldKey, $provincias_grid->Recordset); // Set old record key
			} else {
				$provincias_grid->LoadDefaultValues(); // Load default values
				$provincias_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$provincias_grid->LoadRowValues($provincias_grid->Recordset); // Load row values
		}
		$provincias->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($provincias->CurrentAction == "gridadd") // Grid add
			$provincias->RowType = EW_ROWTYPE_ADD; // Render add
		if ($provincias->CurrentAction == "gridadd" && $provincias->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$provincias_grid->RestoreCurrentRowFormValues($provincias_grid->RowIndex); // Restore form values
		if ($provincias->CurrentAction == "gridedit") { // Grid edit
			if ($provincias->EventCancelled) {
				$provincias_grid->RestoreCurrentRowFormValues($provincias_grid->RowIndex); // Restore form values
			}
			if ($provincias_grid->RowAction == "insert")
				$provincias->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$provincias->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($provincias->CurrentAction == "gridedit" && ($provincias->RowType == EW_ROWTYPE_EDIT || $provincias->RowType == EW_ROWTYPE_ADD) && $provincias->EventCancelled) // Update failed
			$provincias_grid->RestoreCurrentRowFormValues($provincias_grid->RowIndex); // Restore form values
		if ($provincias->RowType == EW_ROWTYPE_EDIT) // Edit row
			$provincias_grid->EditRowCnt++;
		if ($provincias->CurrentAction == "F") // Confirm row
			$provincias_grid->RestoreCurrentRowFormValues($provincias_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$provincias->RowAttrs = array_merge($provincias->RowAttrs, array('data-rowindex'=>$provincias_grid->RowCnt, 'id'=>'r' . $provincias_grid->RowCnt . '_provincias', 'data-rowtype'=>$provincias->RowType));

		// Render row
		$provincias_grid->RenderRow();

		// Render list options
		$provincias_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($provincias_grid->RowAction <> "delete" && $provincias_grid->RowAction <> "insertdelete" && !($provincias_grid->RowAction == "insert" && $provincias->CurrentAction == "F" && $provincias_grid->EmptyRow())) {
?>
	<tr<?php echo $provincias->RowAttributes() ?>>
<?php

// Render list options (body, left)
$provincias_grid->ListOptions->Render("body", "left", $provincias_grid->RowCnt);
?>
	<?php if ($provincias->codigo_provincia->Visible) { // codigo_provincia ?>
		<td data-name="codigo_provincia"<?php echo $provincias->codigo_provincia->CellAttributes() ?>>
<?php if ($provincias->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $provincias_grid->RowCnt ?>_provincias_codigo_provincia" class="form-group provincias_codigo_provincia">
<input type="text" data-field="x_codigo_provincia" name="x<?php echo $provincias_grid->RowIndex ?>_codigo_provincia" id="x<?php echo $provincias_grid->RowIndex ?>_codigo_provincia" size="30" maxlength="4" value="<?php echo $provincias->codigo_provincia->EditValue ?>"<?php echo $provincias->codigo_provincia->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_codigo_provincia" name="o<?php echo $provincias_grid->RowIndex ?>_codigo_provincia" id="o<?php echo $provincias_grid->RowIndex ?>_codigo_provincia" value="<?php echo ew_HtmlEncode($provincias->codigo_provincia->OldValue) ?>">
<?php } ?>
<?php if ($provincias->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $provincias_grid->RowCnt ?>_provincias_codigo_provincia" class="form-group provincias_codigo_provincia">
<span<?php echo $provincias->codigo_provincia->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $provincias->codigo_provincia->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_codigo_provincia" name="x<?php echo $provincias_grid->RowIndex ?>_codigo_provincia" id="x<?php echo $provincias_grid->RowIndex ?>_codigo_provincia" value="<?php echo ew_HtmlEncode($provincias->codigo_provincia->CurrentValue) ?>">
<?php } ?>
<?php if ($provincias->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $provincias->codigo_provincia->ViewAttributes() ?>>
<?php echo $provincias->codigo_provincia->ListViewValue() ?></span>
<input type="hidden" data-field="x_codigo_provincia" name="x<?php echo $provincias_grid->RowIndex ?>_codigo_provincia" id="x<?php echo $provincias_grid->RowIndex ?>_codigo_provincia" value="<?php echo ew_HtmlEncode($provincias->codigo_provincia->FormValue) ?>">
<input type="hidden" data-field="x_codigo_provincia" name="o<?php echo $provincias_grid->RowIndex ?>_codigo_provincia" id="o<?php echo $provincias_grid->RowIndex ?>_codigo_provincia" value="<?php echo ew_HtmlEncode($provincias->codigo_provincia->OldValue) ?>">
<?php } ?>
<a id="<?php echo $provincias_grid->PageObjName . "_row_" . $provincias_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($provincias->provincia->Visible) { // provincia ?>
		<td data-name="provincia"<?php echo $provincias->provincia->CellAttributes() ?>>
<?php if ($provincias->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $provincias_grid->RowCnt ?>_provincias_provincia" class="form-group provincias_provincia">
<input type="text" data-field="x_provincia" name="x<?php echo $provincias_grid->RowIndex ?>_provincia" id="x<?php echo $provincias_grid->RowIndex ?>_provincia" size="30" maxlength="100" value="<?php echo $provincias->provincia->EditValue ?>"<?php echo $provincias->provincia->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_provincia" name="o<?php echo $provincias_grid->RowIndex ?>_provincia" id="o<?php echo $provincias_grid->RowIndex ?>_provincia" value="<?php echo ew_HtmlEncode($provincias->provincia->OldValue) ?>">
<?php } ?>
<?php if ($provincias->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $provincias_grid->RowCnt ?>_provincias_provincia" class="form-group provincias_provincia">
<input type="text" data-field="x_provincia" name="x<?php echo $provincias_grid->RowIndex ?>_provincia" id="x<?php echo $provincias_grid->RowIndex ?>_provincia" size="30" maxlength="100" value="<?php echo $provincias->provincia->EditValue ?>"<?php echo $provincias->provincia->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($provincias->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $provincias->provincia->ViewAttributes() ?>>
<?php echo $provincias->provincia->ListViewValue() ?></span>
<input type="hidden" data-field="x_provincia" name="x<?php echo $provincias_grid->RowIndex ?>_provincia" id="x<?php echo $provincias_grid->RowIndex ?>_provincia" value="<?php echo ew_HtmlEncode($provincias->provincia->FormValue) ?>">
<input type="hidden" data-field="x_provincia" name="o<?php echo $provincias_grid->RowIndex ?>_provincia" id="o<?php echo $provincias_grid->RowIndex ?>_provincia" value="<?php echo ew_HtmlEncode($provincias->provincia->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$provincias_grid->ListOptions->Render("body", "right", $provincias_grid->RowCnt);
?>
	</tr>
<?php if ($provincias->RowType == EW_ROWTYPE_ADD || $provincias->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fprovinciasgrid.UpdateOpts(<?php echo $provincias_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($provincias->CurrentAction <> "gridadd" || $provincias->CurrentMode == "copy")
		if (!$provincias_grid->Recordset->EOF) $provincias_grid->Recordset->MoveNext();
}
?>
<?php
	if ($provincias->CurrentMode == "add" || $provincias->CurrentMode == "copy" || $provincias->CurrentMode == "edit") {
		$provincias_grid->RowIndex = '$rowindex$';
		$provincias_grid->LoadDefaultValues();

		// Set row properties
		$provincias->ResetAttrs();
		$provincias->RowAttrs = array_merge($provincias->RowAttrs, array('data-rowindex'=>$provincias_grid->RowIndex, 'id'=>'r0_provincias', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($provincias->RowAttrs["class"], "ewTemplate");
		$provincias->RowType = EW_ROWTYPE_ADD;

		// Render row
		$provincias_grid->RenderRow();

		// Render list options
		$provincias_grid->RenderListOptions();
		$provincias_grid->StartRowCnt = 0;
?>
	<tr<?php echo $provincias->RowAttributes() ?>>
<?php

// Render list options (body, left)
$provincias_grid->ListOptions->Render("body", "left", $provincias_grid->RowIndex);
?>
	<?php if ($provincias->codigo_provincia->Visible) { // codigo_provincia ?>
		<td data-name="codigo_provincia">
<?php if ($provincias->CurrentAction <> "F") { ?>
<span id="el$rowindex$_provincias_codigo_provincia" class="form-group provincias_codigo_provincia">
<input type="text" data-field="x_codigo_provincia" name="x<?php echo $provincias_grid->RowIndex ?>_codigo_provincia" id="x<?php echo $provincias_grid->RowIndex ?>_codigo_provincia" size="30" maxlength="4" value="<?php echo $provincias->codigo_provincia->EditValue ?>"<?php echo $provincias->codigo_provincia->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_provincias_codigo_provincia" class="form-group provincias_codigo_provincia">
<span<?php echo $provincias->codigo_provincia->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $provincias->codigo_provincia->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codigo_provincia" name="x<?php echo $provincias_grid->RowIndex ?>_codigo_provincia" id="x<?php echo $provincias_grid->RowIndex ?>_codigo_provincia" value="<?php echo ew_HtmlEncode($provincias->codigo_provincia->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codigo_provincia" name="o<?php echo $provincias_grid->RowIndex ?>_codigo_provincia" id="o<?php echo $provincias_grid->RowIndex ?>_codigo_provincia" value="<?php echo ew_HtmlEncode($provincias->codigo_provincia->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($provincias->provincia->Visible) { // provincia ?>
		<td data-name="provincia">
<?php if ($provincias->CurrentAction <> "F") { ?>
<span id="el$rowindex$_provincias_provincia" class="form-group provincias_provincia">
<input type="text" data-field="x_provincia" name="x<?php echo $provincias_grid->RowIndex ?>_provincia" id="x<?php echo $provincias_grid->RowIndex ?>_provincia" size="30" maxlength="100" value="<?php echo $provincias->provincia->EditValue ?>"<?php echo $provincias->provincia->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_provincias_provincia" class="form-group provincias_provincia">
<span<?php echo $provincias->provincia->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $provincias->provincia->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_provincia" name="x<?php echo $provincias_grid->RowIndex ?>_provincia" id="x<?php echo $provincias_grid->RowIndex ?>_provincia" value="<?php echo ew_HtmlEncode($provincias->provincia->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_provincia" name="o<?php echo $provincias_grid->RowIndex ?>_provincia" id="o<?php echo $provincias_grid->RowIndex ?>_provincia" value="<?php echo ew_HtmlEncode($provincias->provincia->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$provincias_grid->ListOptions->Render("body", "right", $provincias_grid->RowCnt);
?>
<script type="text/javascript">
fprovinciasgrid.UpdateOpts(<?php echo $provincias_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($provincias->CurrentMode == "add" || $provincias->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $provincias_grid->FormKeyCountName ?>" id="<?php echo $provincias_grid->FormKeyCountName ?>" value="<?php echo $provincias_grid->KeyCount ?>">
<?php echo $provincias_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($provincias->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $provincias_grid->FormKeyCountName ?>" id="<?php echo $provincias_grid->FormKeyCountName ?>" value="<?php echo $provincias_grid->KeyCount ?>">
<?php echo $provincias_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($provincias->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fprovinciasgrid">
</div>
<?php

// Close recordset
if ($provincias_grid->Recordset)
	$provincias_grid->Recordset->Close();
?>
<?php if ($provincias_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($provincias_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($provincias_grid->TotalRecs == 0 && $provincias->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($provincias_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($provincias->Export == "") { ?>
<script type="text/javascript">
fprovinciasgrid.Init();
</script>
<?php } ?>
<?php
$provincias_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$provincias_grid->Page_Terminate();
?>
