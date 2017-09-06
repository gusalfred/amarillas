<?php include_once "empleados_info.php" ?>
<?php

// Create page object
if (!isset($distritos_grid)) $distritos_grid = new cdistritos_grid();

// Page init
$distritos_grid->Page_Init();

// Page main
$distritos_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$distritos_grid->Page_Render();
?>
<?php if ($distritos->Export == "") { ?>
<script type="text/javascript">

// Page object
var distritos_grid = new ew_Page("distritos_grid");
distritos_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = distritos_grid.PageID; // For backward compatibility

// Form object
var fdistritosgrid = new ew_Form("fdistritosgrid");
fdistritosgrid.FormKeyCountName = '<?php echo $distritos_grid->FormKeyCountName ?>';

// Validate form
fdistritosgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_codigo_distrito");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $distritos->codigo_distrito->FldCaption(), $distritos->codigo_distrito->ReqErrMsg)) ?>");

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
fdistritosgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "codigo_distrito", false)) return false;
	if (ew_ValueChanged(fobj, infix, "distrito", false)) return false;
	return true;
}

// Form_CustomValidate event
fdistritosgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdistritosgrid.ValidateRequired = true;
<?php } else { ?>
fdistritosgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($distritos->CurrentAction == "gridadd") {
	if ($distritos->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$distritos_grid->TotalRecs = $distritos->SelectRecordCount();
			$distritos_grid->Recordset = $distritos_grid->LoadRecordset($distritos_grid->StartRec-1, $distritos_grid->DisplayRecs);
		} else {
			if ($distritos_grid->Recordset = $distritos_grid->LoadRecordset())
				$distritos_grid->TotalRecs = $distritos_grid->Recordset->RecordCount();
		}
		$distritos_grid->StartRec = 1;
		$distritos_grid->DisplayRecs = $distritos_grid->TotalRecs;
	} else {
		$distritos->CurrentFilter = "0=1";
		$distritos_grid->StartRec = 1;
		$distritos_grid->DisplayRecs = $distritos->GridAddRowCount;
	}
	$distritos_grid->TotalRecs = $distritos_grid->DisplayRecs;
	$distritos_grid->StopRec = $distritos_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($distritos_grid->TotalRecs <= 0)
			$distritos_grid->TotalRecs = $distritos->SelectRecordCount();
	} else {
		if (!$distritos_grid->Recordset && ($distritos_grid->Recordset = $distritos_grid->LoadRecordset()))
			$distritos_grid->TotalRecs = $distritos_grid->Recordset->RecordCount();
	}
	$distritos_grid->StartRec = 1;
	$distritos_grid->DisplayRecs = $distritos_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$distritos_grid->Recordset = $distritos_grid->LoadRecordset($distritos_grid->StartRec-1, $distritos_grid->DisplayRecs);

	// Set no record found message
	if ($distritos->CurrentAction == "" && $distritos_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$distritos_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($distritos_grid->SearchWhere == "0=101")
			$distritos_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$distritos_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$distritos_grid->RenderOtherOptions();
?>
<?php $distritos_grid->ShowPageHeader(); ?>
<?php
$distritos_grid->ShowMessage();
?>
<?php if ($distritos_grid->TotalRecs > 0 || $distritos->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fdistritosgrid" class="ewForm form-inline">
<?php if ($distritos_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($distritos_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_distritos" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_distritosgrid" class="table ewTable">
<?php echo $distritos->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$distritos->RowType = EW_ROWTYPE_HEADER;

// Render list options
$distritos_grid->RenderListOptions();

// Render list options (header, left)
$distritos_grid->ListOptions->Render("header", "left");
?>
<?php if ($distritos->codigo_distrito->Visible) { // codigo_distrito ?>
	<?php if ($distritos->SortUrl($distritos->codigo_distrito) == "") { ?>
		<th data-name="codigo_distrito"><div id="elh_distritos_codigo_distrito" class="distritos_codigo_distrito"><div class="ewTableHeaderCaption"><?php echo $distritos->codigo_distrito->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigo_distrito"><div><div id="elh_distritos_codigo_distrito" class="distritos_codigo_distrito">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $distritos->codigo_distrito->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($distritos->codigo_distrito->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($distritos->codigo_distrito->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($distritos->distrito->Visible) { // distrito ?>
	<?php if ($distritos->SortUrl($distritos->distrito) == "") { ?>
		<th data-name="distrito"><div id="elh_distritos_distrito" class="distritos_distrito"><div class="ewTableHeaderCaption"><?php echo $distritos->distrito->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="distrito"><div><div id="elh_distritos_distrito" class="distritos_distrito">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $distritos->distrito->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($distritos->distrito->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($distritos->distrito->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$distritos_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$distritos_grid->StartRec = 1;
$distritos_grid->StopRec = $distritos_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($distritos_grid->FormKeyCountName) && ($distritos->CurrentAction == "gridadd" || $distritos->CurrentAction == "gridedit" || $distritos->CurrentAction == "F")) {
		$distritos_grid->KeyCount = $objForm->GetValue($distritos_grid->FormKeyCountName);
		$distritos_grid->StopRec = $distritos_grid->StartRec + $distritos_grid->KeyCount - 1;
	}
}
$distritos_grid->RecCnt = $distritos_grid->StartRec - 1;
if ($distritos_grid->Recordset && !$distritos_grid->Recordset->EOF) {
	$distritos_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $distritos_grid->StartRec > 1)
		$distritos_grid->Recordset->Move($distritos_grid->StartRec - 1);
} elseif (!$distritos->AllowAddDeleteRow && $distritos_grid->StopRec == 0) {
	$distritos_grid->StopRec = $distritos->GridAddRowCount;
}

// Initialize aggregate
$distritos->RowType = EW_ROWTYPE_AGGREGATEINIT;
$distritos->ResetAttrs();
$distritos_grid->RenderRow();
if ($distritos->CurrentAction == "gridadd")
	$distritos_grid->RowIndex = 0;
if ($distritos->CurrentAction == "gridedit")
	$distritos_grid->RowIndex = 0;
while ($distritos_grid->RecCnt < $distritos_grid->StopRec) {
	$distritos_grid->RecCnt++;
	if (intval($distritos_grid->RecCnt) >= intval($distritos_grid->StartRec)) {
		$distritos_grid->RowCnt++;
		if ($distritos->CurrentAction == "gridadd" || $distritos->CurrentAction == "gridedit" || $distritos->CurrentAction == "F") {
			$distritos_grid->RowIndex++;
			$objForm->Index = $distritos_grid->RowIndex;
			if ($objForm->HasValue($distritos_grid->FormActionName))
				$distritos_grid->RowAction = strval($objForm->GetValue($distritos_grid->FormActionName));
			elseif ($distritos->CurrentAction == "gridadd")
				$distritos_grid->RowAction = "insert";
			else
				$distritos_grid->RowAction = "";
		}

		// Set up key count
		$distritos_grid->KeyCount = $distritos_grid->RowIndex;

		// Init row class and style
		$distritos->ResetAttrs();
		$distritos->CssClass = "";
		if ($distritos->CurrentAction == "gridadd") {
			if ($distritos->CurrentMode == "copy") {
				$distritos_grid->LoadRowValues($distritos_grid->Recordset); // Load row values
				$distritos_grid->SetRecordKey($distritos_grid->RowOldKey, $distritos_grid->Recordset); // Set old record key
			} else {
				$distritos_grid->LoadDefaultValues(); // Load default values
				$distritos_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$distritos_grid->LoadRowValues($distritos_grid->Recordset); // Load row values
		}
		$distritos->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($distritos->CurrentAction == "gridadd") // Grid add
			$distritos->RowType = EW_ROWTYPE_ADD; // Render add
		if ($distritos->CurrentAction == "gridadd" && $distritos->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$distritos_grid->RestoreCurrentRowFormValues($distritos_grid->RowIndex); // Restore form values
		if ($distritos->CurrentAction == "gridedit") { // Grid edit
			if ($distritos->EventCancelled) {
				$distritos_grid->RestoreCurrentRowFormValues($distritos_grid->RowIndex); // Restore form values
			}
			if ($distritos_grid->RowAction == "insert")
				$distritos->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$distritos->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($distritos->CurrentAction == "gridedit" && ($distritos->RowType == EW_ROWTYPE_EDIT || $distritos->RowType == EW_ROWTYPE_ADD) && $distritos->EventCancelled) // Update failed
			$distritos_grid->RestoreCurrentRowFormValues($distritos_grid->RowIndex); // Restore form values
		if ($distritos->RowType == EW_ROWTYPE_EDIT) // Edit row
			$distritos_grid->EditRowCnt++;
		if ($distritos->CurrentAction == "F") // Confirm row
			$distritos_grid->RestoreCurrentRowFormValues($distritos_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$distritos->RowAttrs = array_merge($distritos->RowAttrs, array('data-rowindex'=>$distritos_grid->RowCnt, 'id'=>'r' . $distritos_grid->RowCnt . '_distritos', 'data-rowtype'=>$distritos->RowType));

		// Render row
		$distritos_grid->RenderRow();

		// Render list options
		$distritos_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($distritos_grid->RowAction <> "delete" && $distritos_grid->RowAction <> "insertdelete" && !($distritos_grid->RowAction == "insert" && $distritos->CurrentAction == "F" && $distritos_grid->EmptyRow())) {
?>
	<tr<?php echo $distritos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$distritos_grid->ListOptions->Render("body", "left", $distritos_grid->RowCnt);
?>
	<?php if ($distritos->codigo_distrito->Visible) { // codigo_distrito ?>
		<td data-name="codigo_distrito"<?php echo $distritos->codigo_distrito->CellAttributes() ?>>
<?php if ($distritos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $distritos_grid->RowCnt ?>_distritos_codigo_distrito" class="form-group distritos_codigo_distrito">
<input type="text" data-field="x_codigo_distrito" name="x<?php echo $distritos_grid->RowIndex ?>_codigo_distrito" id="x<?php echo $distritos_grid->RowIndex ?>_codigo_distrito" size="30" maxlength="8" value="<?php echo $distritos->codigo_distrito->EditValue ?>"<?php echo $distritos->codigo_distrito->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_codigo_distrito" name="o<?php echo $distritos_grid->RowIndex ?>_codigo_distrito" id="o<?php echo $distritos_grid->RowIndex ?>_codigo_distrito" value="<?php echo ew_HtmlEncode($distritos->codigo_distrito->OldValue) ?>">
<?php } ?>
<?php if ($distritos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $distritos_grid->RowCnt ?>_distritos_codigo_distrito" class="form-group distritos_codigo_distrito">
<span<?php echo $distritos->codigo_distrito->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $distritos->codigo_distrito->EditValue ?></p></span>
</span>
<input type="hidden" data-field="x_codigo_distrito" name="x<?php echo $distritos_grid->RowIndex ?>_codigo_distrito" id="x<?php echo $distritos_grid->RowIndex ?>_codigo_distrito" value="<?php echo ew_HtmlEncode($distritos->codigo_distrito->CurrentValue) ?>">
<?php } ?>
<?php if ($distritos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $distritos->codigo_distrito->ViewAttributes() ?>>
<?php echo $distritos->codigo_distrito->ListViewValue() ?></span>
<input type="hidden" data-field="x_codigo_distrito" name="x<?php echo $distritos_grid->RowIndex ?>_codigo_distrito" id="x<?php echo $distritos_grid->RowIndex ?>_codigo_distrito" value="<?php echo ew_HtmlEncode($distritos->codigo_distrito->FormValue) ?>">
<input type="hidden" data-field="x_codigo_distrito" name="o<?php echo $distritos_grid->RowIndex ?>_codigo_distrito" id="o<?php echo $distritos_grid->RowIndex ?>_codigo_distrito" value="<?php echo ew_HtmlEncode($distritos->codigo_distrito->OldValue) ?>">
<?php } ?>
<a id="<?php echo $distritos_grid->PageObjName . "_row_" . $distritos_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($distritos->distrito->Visible) { // distrito ?>
		<td data-name="distrito"<?php echo $distritos->distrito->CellAttributes() ?>>
<?php if ($distritos->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $distritos_grid->RowCnt ?>_distritos_distrito" class="form-group distritos_distrito">
<input type="text" data-field="x_distrito" name="x<?php echo $distritos_grid->RowIndex ?>_distrito" id="x<?php echo $distritos_grid->RowIndex ?>_distrito" size="30" maxlength="100" value="<?php echo $distritos->distrito->EditValue ?>"<?php echo $distritos->distrito->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_distrito" name="o<?php echo $distritos_grid->RowIndex ?>_distrito" id="o<?php echo $distritos_grid->RowIndex ?>_distrito" value="<?php echo ew_HtmlEncode($distritos->distrito->OldValue) ?>">
<?php } ?>
<?php if ($distritos->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $distritos_grid->RowCnt ?>_distritos_distrito" class="form-group distritos_distrito">
<input type="text" data-field="x_distrito" name="x<?php echo $distritos_grid->RowIndex ?>_distrito" id="x<?php echo $distritos_grid->RowIndex ?>_distrito" size="30" maxlength="100" value="<?php echo $distritos->distrito->EditValue ?>"<?php echo $distritos->distrito->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($distritos->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $distritos->distrito->ViewAttributes() ?>>
<?php echo $distritos->distrito->ListViewValue() ?></span>
<input type="hidden" data-field="x_distrito" name="x<?php echo $distritos_grid->RowIndex ?>_distrito" id="x<?php echo $distritos_grid->RowIndex ?>_distrito" value="<?php echo ew_HtmlEncode($distritos->distrito->FormValue) ?>">
<input type="hidden" data-field="x_distrito" name="o<?php echo $distritos_grid->RowIndex ?>_distrito" id="o<?php echo $distritos_grid->RowIndex ?>_distrito" value="<?php echo ew_HtmlEncode($distritos->distrito->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$distritos_grid->ListOptions->Render("body", "right", $distritos_grid->RowCnt);
?>
	</tr>
<?php if ($distritos->RowType == EW_ROWTYPE_ADD || $distritos->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdistritosgrid.UpdateOpts(<?php echo $distritos_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($distritos->CurrentAction <> "gridadd" || $distritos->CurrentMode == "copy")
		if (!$distritos_grid->Recordset->EOF) $distritos_grid->Recordset->MoveNext();
}
?>
<?php
	if ($distritos->CurrentMode == "add" || $distritos->CurrentMode == "copy" || $distritos->CurrentMode == "edit") {
		$distritos_grid->RowIndex = '$rowindex$';
		$distritos_grid->LoadDefaultValues();

		// Set row properties
		$distritos->ResetAttrs();
		$distritos->RowAttrs = array_merge($distritos->RowAttrs, array('data-rowindex'=>$distritos_grid->RowIndex, 'id'=>'r0_distritos', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($distritos->RowAttrs["class"], "ewTemplate");
		$distritos->RowType = EW_ROWTYPE_ADD;

		// Render row
		$distritos_grid->RenderRow();

		// Render list options
		$distritos_grid->RenderListOptions();
		$distritos_grid->StartRowCnt = 0;
?>
	<tr<?php echo $distritos->RowAttributes() ?>>
<?php

// Render list options (body, left)
$distritos_grid->ListOptions->Render("body", "left", $distritos_grid->RowIndex);
?>
	<?php if ($distritos->codigo_distrito->Visible) { // codigo_distrito ?>
		<td data-name="codigo_distrito">
<?php if ($distritos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_distritos_codigo_distrito" class="form-group distritos_codigo_distrito">
<input type="text" data-field="x_codigo_distrito" name="x<?php echo $distritos_grid->RowIndex ?>_codigo_distrito" id="x<?php echo $distritos_grid->RowIndex ?>_codigo_distrito" size="30" maxlength="8" value="<?php echo $distritos->codigo_distrito->EditValue ?>"<?php echo $distritos->codigo_distrito->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_distritos_codigo_distrito" class="form-group distritos_codigo_distrito">
<span<?php echo $distritos->codigo_distrito->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $distritos->codigo_distrito->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_codigo_distrito" name="x<?php echo $distritos_grid->RowIndex ?>_codigo_distrito" id="x<?php echo $distritos_grid->RowIndex ?>_codigo_distrito" value="<?php echo ew_HtmlEncode($distritos->codigo_distrito->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_codigo_distrito" name="o<?php echo $distritos_grid->RowIndex ?>_codigo_distrito" id="o<?php echo $distritos_grid->RowIndex ?>_codigo_distrito" value="<?php echo ew_HtmlEncode($distritos->codigo_distrito->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($distritos->distrito->Visible) { // distrito ?>
		<td data-name="distrito">
<?php if ($distritos->CurrentAction <> "F") { ?>
<span id="el$rowindex$_distritos_distrito" class="form-group distritos_distrito">
<input type="text" data-field="x_distrito" name="x<?php echo $distritos_grid->RowIndex ?>_distrito" id="x<?php echo $distritos_grid->RowIndex ?>_distrito" size="30" maxlength="100" value="<?php echo $distritos->distrito->EditValue ?>"<?php echo $distritos->distrito->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_distritos_distrito" class="form-group distritos_distrito">
<span<?php echo $distritos->distrito->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $distritos->distrito->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_distrito" name="x<?php echo $distritos_grid->RowIndex ?>_distrito" id="x<?php echo $distritos_grid->RowIndex ?>_distrito" value="<?php echo ew_HtmlEncode($distritos->distrito->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_distrito" name="o<?php echo $distritos_grid->RowIndex ?>_distrito" id="o<?php echo $distritos_grid->RowIndex ?>_distrito" value="<?php echo ew_HtmlEncode($distritos->distrito->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$distritos_grid->ListOptions->Render("body", "right", $distritos_grid->RowCnt);
?>
<script type="text/javascript">
fdistritosgrid.UpdateOpts(<?php echo $distritos_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($distritos->CurrentMode == "add" || $distritos->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $distritos_grid->FormKeyCountName ?>" id="<?php echo $distritos_grid->FormKeyCountName ?>" value="<?php echo $distritos_grid->KeyCount ?>">
<?php echo $distritos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($distritos->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $distritos_grid->FormKeyCountName ?>" id="<?php echo $distritos_grid->FormKeyCountName ?>" value="<?php echo $distritos_grid->KeyCount ?>">
<?php echo $distritos_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($distritos->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdistritosgrid">
</div>
<?php

// Close recordset
if ($distritos_grid->Recordset)
	$distritos_grid->Recordset->Close();
?>
<?php if ($distritos_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($distritos_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($distritos_grid->TotalRecs == 0 && $distritos->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($distritos_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($distritos->Export == "") { ?>
<script type="text/javascript">
fdistritosgrid.Init();
</script>
<?php } ?>
<?php
$distritos_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$distritos_grid->Page_Terminate();
?>
