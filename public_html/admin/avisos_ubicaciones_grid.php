<?php include_once "empleados_info.php" ?>
<?php

// Create page object
if (!isset($avisos_ubicaciones_grid)) $avisos_ubicaciones_grid = new cavisos_ubicaciones_grid();

// Page init
$avisos_ubicaciones_grid->Page_Init();

// Page main
$avisos_ubicaciones_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$avisos_ubicaciones_grid->Page_Render();
?>
<?php if ($avisos_ubicaciones->Export == "") { ?>
<script type="text/javascript">

// Page object
var avisos_ubicaciones_grid = new ew_Page("avisos_ubicaciones_grid");
avisos_ubicaciones_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = avisos_ubicaciones_grid.PageID; // For backward compatibility

// Form object
var favisos_ubicacionesgrid = new ew_Form("favisos_ubicacionesgrid");
favisos_ubicacionesgrid.FormKeyCountName = '<?php echo $avisos_ubicaciones_grid->FormKeyCountName ?>';

// Validate form
favisos_ubicacionesgrid.Validate = function() {
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
favisos_ubicacionesgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_ubicacion", false)) return false;
	return true;
}

// Form_CustomValidate event
favisos_ubicacionesgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
favisos_ubicacionesgrid.ValidateRequired = true;
<?php } else { ?>
favisos_ubicacionesgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
favisos_ubicacionesgrid.Lists["x_id_ubicacion"] = {"LinkField":"x_id_ubicacion","Ajax":null,"AutoFill":false,"DisplayFields":["x_ubicacion","x_dimensiones","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($avisos_ubicaciones->CurrentAction == "gridadd") {
	if ($avisos_ubicaciones->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$avisos_ubicaciones_grid->TotalRecs = $avisos_ubicaciones->SelectRecordCount();
			$avisos_ubicaciones_grid->Recordset = $avisos_ubicaciones_grid->LoadRecordset($avisos_ubicaciones_grid->StartRec-1, $avisos_ubicaciones_grid->DisplayRecs);
		} else {
			if ($avisos_ubicaciones_grid->Recordset = $avisos_ubicaciones_grid->LoadRecordset())
				$avisos_ubicaciones_grid->TotalRecs = $avisos_ubicaciones_grid->Recordset->RecordCount();
		}
		$avisos_ubicaciones_grid->StartRec = 1;
		$avisos_ubicaciones_grid->DisplayRecs = $avisos_ubicaciones_grid->TotalRecs;
	} else {
		$avisos_ubicaciones->CurrentFilter = "0=1";
		$avisos_ubicaciones_grid->StartRec = 1;
		$avisos_ubicaciones_grid->DisplayRecs = $avisos_ubicaciones->GridAddRowCount;
	}
	$avisos_ubicaciones_grid->TotalRecs = $avisos_ubicaciones_grid->DisplayRecs;
	$avisos_ubicaciones_grid->StopRec = $avisos_ubicaciones_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($avisos_ubicaciones_grid->TotalRecs <= 0)
			$avisos_ubicaciones_grid->TotalRecs = $avisos_ubicaciones->SelectRecordCount();
	} else {
		if (!$avisos_ubicaciones_grid->Recordset && ($avisos_ubicaciones_grid->Recordset = $avisos_ubicaciones_grid->LoadRecordset()))
			$avisos_ubicaciones_grid->TotalRecs = $avisos_ubicaciones_grid->Recordset->RecordCount();
	}
	$avisos_ubicaciones_grid->StartRec = 1;
	$avisos_ubicaciones_grid->DisplayRecs = $avisos_ubicaciones_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$avisos_ubicaciones_grid->Recordset = $avisos_ubicaciones_grid->LoadRecordset($avisos_ubicaciones_grid->StartRec-1, $avisos_ubicaciones_grid->DisplayRecs);

	// Set no record found message
	if ($avisos_ubicaciones->CurrentAction == "" && $avisos_ubicaciones_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$avisos_ubicaciones_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($avisos_ubicaciones_grid->SearchWhere == "0=101")
			$avisos_ubicaciones_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$avisos_ubicaciones_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$avisos_ubicaciones_grid->RenderOtherOptions();
?>
<?php $avisos_ubicaciones_grid->ShowPageHeader(); ?>
<?php
$avisos_ubicaciones_grid->ShowMessage();
?>
<?php if ($avisos_ubicaciones_grid->TotalRecs > 0 || $avisos_ubicaciones->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="favisos_ubicacionesgrid" class="ewForm form-inline">
<?php if ($avisos_ubicaciones_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($avisos_ubicaciones_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_avisos_ubicaciones" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_avisos_ubicacionesgrid" class="table ewTable">
<?php echo $avisos_ubicaciones->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$avisos_ubicaciones->RowType = EW_ROWTYPE_HEADER;

// Render list options
$avisos_ubicaciones_grid->RenderListOptions();

// Render list options (header, left)
$avisos_ubicaciones_grid->ListOptions->Render("header", "left");
?>
<?php if ($avisos_ubicaciones->id_ubicacion->Visible) { // id_ubicacion ?>
	<?php if ($avisos_ubicaciones->SortUrl($avisos_ubicaciones->id_ubicacion) == "") { ?>
		<th data-name="id_ubicacion"><div id="elh_avisos_ubicaciones_id_ubicacion" class="avisos_ubicaciones_id_ubicacion"><div class="ewTableHeaderCaption"><?php echo $avisos_ubicaciones->id_ubicacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_ubicacion"><div><div id="elh_avisos_ubicaciones_id_ubicacion" class="avisos_ubicaciones_id_ubicacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $avisos_ubicaciones->id_ubicacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($avisos_ubicaciones->id_ubicacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($avisos_ubicaciones->id_ubicacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$avisos_ubicaciones_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$avisos_ubicaciones_grid->StartRec = 1;
$avisos_ubicaciones_grid->StopRec = $avisos_ubicaciones_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($avisos_ubicaciones_grid->FormKeyCountName) && ($avisos_ubicaciones->CurrentAction == "gridadd" || $avisos_ubicaciones->CurrentAction == "gridedit" || $avisos_ubicaciones->CurrentAction == "F")) {
		$avisos_ubicaciones_grid->KeyCount = $objForm->GetValue($avisos_ubicaciones_grid->FormKeyCountName);
		$avisos_ubicaciones_grid->StopRec = $avisos_ubicaciones_grid->StartRec + $avisos_ubicaciones_grid->KeyCount - 1;
	}
}
$avisos_ubicaciones_grid->RecCnt = $avisos_ubicaciones_grid->StartRec - 1;
if ($avisos_ubicaciones_grid->Recordset && !$avisos_ubicaciones_grid->Recordset->EOF) {
	$avisos_ubicaciones_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $avisos_ubicaciones_grid->StartRec > 1)
		$avisos_ubicaciones_grid->Recordset->Move($avisos_ubicaciones_grid->StartRec - 1);
} elseif (!$avisos_ubicaciones->AllowAddDeleteRow && $avisos_ubicaciones_grid->StopRec == 0) {
	$avisos_ubicaciones_grid->StopRec = $avisos_ubicaciones->GridAddRowCount;
}

// Initialize aggregate
$avisos_ubicaciones->RowType = EW_ROWTYPE_AGGREGATEINIT;
$avisos_ubicaciones->ResetAttrs();
$avisos_ubicaciones_grid->RenderRow();
if ($avisos_ubicaciones->CurrentAction == "gridadd")
	$avisos_ubicaciones_grid->RowIndex = 0;
if ($avisos_ubicaciones->CurrentAction == "gridedit")
	$avisos_ubicaciones_grid->RowIndex = 0;
while ($avisos_ubicaciones_grid->RecCnt < $avisos_ubicaciones_grid->StopRec) {
	$avisos_ubicaciones_grid->RecCnt++;
	if (intval($avisos_ubicaciones_grid->RecCnt) >= intval($avisos_ubicaciones_grid->StartRec)) {
		$avisos_ubicaciones_grid->RowCnt++;
		if ($avisos_ubicaciones->CurrentAction == "gridadd" || $avisos_ubicaciones->CurrentAction == "gridedit" || $avisos_ubicaciones->CurrentAction == "F") {
			$avisos_ubicaciones_grid->RowIndex++;
			$objForm->Index = $avisos_ubicaciones_grid->RowIndex;
			if ($objForm->HasValue($avisos_ubicaciones_grid->FormActionName))
				$avisos_ubicaciones_grid->RowAction = strval($objForm->GetValue($avisos_ubicaciones_grid->FormActionName));
			elseif ($avisos_ubicaciones->CurrentAction == "gridadd")
				$avisos_ubicaciones_grid->RowAction = "insert";
			else
				$avisos_ubicaciones_grid->RowAction = "";
		}

		// Set up key count
		$avisos_ubicaciones_grid->KeyCount = $avisos_ubicaciones_grid->RowIndex;

		// Init row class and style
		$avisos_ubicaciones->ResetAttrs();
		$avisos_ubicaciones->CssClass = "";
		if ($avisos_ubicaciones->CurrentAction == "gridadd") {
			if ($avisos_ubicaciones->CurrentMode == "copy") {
				$avisos_ubicaciones_grid->LoadRowValues($avisos_ubicaciones_grid->Recordset); // Load row values
				$avisos_ubicaciones_grid->SetRecordKey($avisos_ubicaciones_grid->RowOldKey, $avisos_ubicaciones_grid->Recordset); // Set old record key
			} else {
				$avisos_ubicaciones_grid->LoadDefaultValues(); // Load default values
				$avisos_ubicaciones_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$avisos_ubicaciones_grid->LoadRowValues($avisos_ubicaciones_grid->Recordset); // Load row values
		}
		$avisos_ubicaciones->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($avisos_ubicaciones->CurrentAction == "gridadd") // Grid add
			$avisos_ubicaciones->RowType = EW_ROWTYPE_ADD; // Render add
		if ($avisos_ubicaciones->CurrentAction == "gridadd" && $avisos_ubicaciones->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$avisos_ubicaciones_grid->RestoreCurrentRowFormValues($avisos_ubicaciones_grid->RowIndex); // Restore form values
		if ($avisos_ubicaciones->CurrentAction == "gridedit") { // Grid edit
			if ($avisos_ubicaciones->EventCancelled) {
				$avisos_ubicaciones_grid->RestoreCurrentRowFormValues($avisos_ubicaciones_grid->RowIndex); // Restore form values
			}
			if ($avisos_ubicaciones_grid->RowAction == "insert")
				$avisos_ubicaciones->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$avisos_ubicaciones->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($avisos_ubicaciones->CurrentAction == "gridedit" && ($avisos_ubicaciones->RowType == EW_ROWTYPE_EDIT || $avisos_ubicaciones->RowType == EW_ROWTYPE_ADD) && $avisos_ubicaciones->EventCancelled) // Update failed
			$avisos_ubicaciones_grid->RestoreCurrentRowFormValues($avisos_ubicaciones_grid->RowIndex); // Restore form values
		if ($avisos_ubicaciones->RowType == EW_ROWTYPE_EDIT) // Edit row
			$avisos_ubicaciones_grid->EditRowCnt++;
		if ($avisos_ubicaciones->CurrentAction == "F") // Confirm row
			$avisos_ubicaciones_grid->RestoreCurrentRowFormValues($avisos_ubicaciones_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$avisos_ubicaciones->RowAttrs = array_merge($avisos_ubicaciones->RowAttrs, array('data-rowindex'=>$avisos_ubicaciones_grid->RowCnt, 'id'=>'r' . $avisos_ubicaciones_grid->RowCnt . '_avisos_ubicaciones', 'data-rowtype'=>$avisos_ubicaciones->RowType));

		// Render row
		$avisos_ubicaciones_grid->RenderRow();

		// Render list options
		$avisos_ubicaciones_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($avisos_ubicaciones_grid->RowAction <> "delete" && $avisos_ubicaciones_grid->RowAction <> "insertdelete" && !($avisos_ubicaciones_grid->RowAction == "insert" && $avisos_ubicaciones->CurrentAction == "F" && $avisos_ubicaciones_grid->EmptyRow())) {
?>
	<tr<?php echo $avisos_ubicaciones->RowAttributes() ?>>
<?php

// Render list options (body, left)
$avisos_ubicaciones_grid->ListOptions->Render("body", "left", $avisos_ubicaciones_grid->RowCnt);
?>
	<?php if ($avisos_ubicaciones->id_ubicacion->Visible) { // id_ubicacion ?>
		<td data-name="id_ubicacion"<?php echo $avisos_ubicaciones->id_ubicacion->CellAttributes() ?>>
<?php if ($avisos_ubicaciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $avisos_ubicaciones_grid->RowCnt ?>_avisos_ubicaciones_id_ubicacion" class="form-group avisos_ubicaciones_id_ubicacion">
<select data-field="x_id_ubicacion" id="x<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_ubicacion" name="x<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_ubicacion"<?php echo $avisos_ubicaciones->id_ubicacion->EditAttributes() ?>>
<?php
if (is_array($avisos_ubicaciones->id_ubicacion->EditValue)) {
	$arwrk = $avisos_ubicaciones->id_ubicacion->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($avisos_ubicaciones->id_ubicacion->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$avisos_ubicaciones->id_ubicacion) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $avisos_ubicaciones->id_ubicacion->OldValue = "";
?>
</select>
<script type="text/javascript">
favisos_ubicacionesgrid.Lists["x_id_ubicacion"].Options = <?php echo (is_array($avisos_ubicaciones->id_ubicacion->EditValue)) ? ew_ArrayToJson($avisos_ubicaciones->id_ubicacion->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_id_ubicacion" name="o<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_ubicacion" id="o<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_ubicacion" value="<?php echo ew_HtmlEncode($avisos_ubicaciones->id_ubicacion->OldValue) ?>">
<?php } ?>
<?php if ($avisos_ubicaciones->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $avisos_ubicaciones_grid->RowCnt ?>_avisos_ubicaciones_id_ubicacion" class="form-group avisos_ubicaciones_id_ubicacion">
<select data-field="x_id_ubicacion" id="x<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_ubicacion" name="x<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_ubicacion"<?php echo $avisos_ubicaciones->id_ubicacion->EditAttributes() ?>>
<?php
if (is_array($avisos_ubicaciones->id_ubicacion->EditValue)) {
	$arwrk = $avisos_ubicaciones->id_ubicacion->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($avisos_ubicaciones->id_ubicacion->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$avisos_ubicaciones->id_ubicacion) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $avisos_ubicaciones->id_ubicacion->OldValue = "";
?>
</select>
<script type="text/javascript">
favisos_ubicacionesgrid.Lists["x_id_ubicacion"].Options = <?php echo (is_array($avisos_ubicaciones->id_ubicacion->EditValue)) ? ew_ArrayToJson($avisos_ubicaciones->id_ubicacion->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($avisos_ubicaciones->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $avisos_ubicaciones->id_ubicacion->ViewAttributes() ?>>
<?php echo $avisos_ubicaciones->id_ubicacion->ListViewValue() ?></span>
<input type="hidden" data-field="x_id_ubicacion" name="x<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_ubicacion" id="x<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_ubicacion" value="<?php echo ew_HtmlEncode($avisos_ubicaciones->id_ubicacion->FormValue) ?>">
<input type="hidden" data-field="x_id_ubicacion" name="o<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_ubicacion" id="o<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_ubicacion" value="<?php echo ew_HtmlEncode($avisos_ubicaciones->id_ubicacion->OldValue) ?>">
<?php } ?>
<a id="<?php echo $avisos_ubicaciones_grid->PageObjName . "_row_" . $avisos_ubicaciones_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($avisos_ubicaciones->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id_aviso_ubicacion" name="x<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_aviso_ubicacion" id="x<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_aviso_ubicacion" value="<?php echo ew_HtmlEncode($avisos_ubicaciones->id_aviso_ubicacion->CurrentValue) ?>">
<input type="hidden" data-field="x_id_aviso_ubicacion" name="o<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_aviso_ubicacion" id="o<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_aviso_ubicacion" value="<?php echo ew_HtmlEncode($avisos_ubicaciones->id_aviso_ubicacion->OldValue) ?>">
<?php } ?>
<?php if ($avisos_ubicaciones->RowType == EW_ROWTYPE_EDIT || $avisos_ubicaciones->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_id_aviso_ubicacion" name="x<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_aviso_ubicacion" id="x<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_aviso_ubicacion" value="<?php echo ew_HtmlEncode($avisos_ubicaciones->id_aviso_ubicacion->CurrentValue) ?>">
<?php } ?>
<?php

// Render list options (body, right)
$avisos_ubicaciones_grid->ListOptions->Render("body", "right", $avisos_ubicaciones_grid->RowCnt);
?>
	</tr>
<?php if ($avisos_ubicaciones->RowType == EW_ROWTYPE_ADD || $avisos_ubicaciones->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
favisos_ubicacionesgrid.UpdateOpts(<?php echo $avisos_ubicaciones_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($avisos_ubicaciones->CurrentAction <> "gridadd" || $avisos_ubicaciones->CurrentMode == "copy")
		if (!$avisos_ubicaciones_grid->Recordset->EOF) $avisos_ubicaciones_grid->Recordset->MoveNext();
}
?>
<?php
	if ($avisos_ubicaciones->CurrentMode == "add" || $avisos_ubicaciones->CurrentMode == "copy" || $avisos_ubicaciones->CurrentMode == "edit") {
		$avisos_ubicaciones_grid->RowIndex = '$rowindex$';
		$avisos_ubicaciones_grid->LoadDefaultValues();

		// Set row properties
		$avisos_ubicaciones->ResetAttrs();
		$avisos_ubicaciones->RowAttrs = array_merge($avisos_ubicaciones->RowAttrs, array('data-rowindex'=>$avisos_ubicaciones_grid->RowIndex, 'id'=>'r0_avisos_ubicaciones', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($avisos_ubicaciones->RowAttrs["class"], "ewTemplate");
		$avisos_ubicaciones->RowType = EW_ROWTYPE_ADD;

		// Render row
		$avisos_ubicaciones_grid->RenderRow();

		// Render list options
		$avisos_ubicaciones_grid->RenderListOptions();
		$avisos_ubicaciones_grid->StartRowCnt = 0;
?>
	<tr<?php echo $avisos_ubicaciones->RowAttributes() ?>>
<?php

// Render list options (body, left)
$avisos_ubicaciones_grid->ListOptions->Render("body", "left", $avisos_ubicaciones_grid->RowIndex);
?>
	<?php if ($avisos_ubicaciones->id_ubicacion->Visible) { // id_ubicacion ?>
		<td data-name="id_ubicacion">
<?php if ($avisos_ubicaciones->CurrentAction <> "F") { ?>
<span id="el$rowindex$_avisos_ubicaciones_id_ubicacion" class="form-group avisos_ubicaciones_id_ubicacion">
<select data-field="x_id_ubicacion" id="x<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_ubicacion" name="x<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_ubicacion"<?php echo $avisos_ubicaciones->id_ubicacion->EditAttributes() ?>>
<?php
if (is_array($avisos_ubicaciones->id_ubicacion->EditValue)) {
	$arwrk = $avisos_ubicaciones->id_ubicacion->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($avisos_ubicaciones->id_ubicacion->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$avisos_ubicaciones->id_ubicacion) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $avisos_ubicaciones->id_ubicacion->OldValue = "";
?>
</select>
<script type="text/javascript">
favisos_ubicacionesgrid.Lists["x_id_ubicacion"].Options = <?php echo (is_array($avisos_ubicaciones->id_ubicacion->EditValue)) ? ew_ArrayToJson($avisos_ubicaciones->id_ubicacion->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_avisos_ubicaciones_id_ubicacion" class="form-group avisos_ubicaciones_id_ubicacion">
<span<?php echo $avisos_ubicaciones->id_ubicacion->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $avisos_ubicaciones->id_ubicacion->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_id_ubicacion" name="x<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_ubicacion" id="x<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_ubicacion" value="<?php echo ew_HtmlEncode($avisos_ubicaciones->id_ubicacion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_id_ubicacion" name="o<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_ubicacion" id="o<?php echo $avisos_ubicaciones_grid->RowIndex ?>_id_ubicacion" value="<?php echo ew_HtmlEncode($avisos_ubicaciones->id_ubicacion->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$avisos_ubicaciones_grid->ListOptions->Render("body", "right", $avisos_ubicaciones_grid->RowCnt);
?>
<script type="text/javascript">
favisos_ubicacionesgrid.UpdateOpts(<?php echo $avisos_ubicaciones_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($avisos_ubicaciones->CurrentMode == "add" || $avisos_ubicaciones->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $avisos_ubicaciones_grid->FormKeyCountName ?>" id="<?php echo $avisos_ubicaciones_grid->FormKeyCountName ?>" value="<?php echo $avisos_ubicaciones_grid->KeyCount ?>">
<?php echo $avisos_ubicaciones_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($avisos_ubicaciones->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $avisos_ubicaciones_grid->FormKeyCountName ?>" id="<?php echo $avisos_ubicaciones_grid->FormKeyCountName ?>" value="<?php echo $avisos_ubicaciones_grid->KeyCount ?>">
<?php echo $avisos_ubicaciones_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($avisos_ubicaciones->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="favisos_ubicacionesgrid">
</div>
<?php

// Close recordset
if ($avisos_ubicaciones_grid->Recordset)
	$avisos_ubicaciones_grid->Recordset->Close();
?>
<?php if ($avisos_ubicaciones_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($avisos_ubicaciones_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($avisos_ubicaciones_grid->TotalRecs == 0 && $avisos_ubicaciones->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($avisos_ubicaciones_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($avisos_ubicaciones->Export == "") { ?>
<script type="text/javascript">
favisos_ubicacionesgrid.Init();
</script>
<?php } ?>
<?php
$avisos_ubicaciones_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$avisos_ubicaciones_grid->Page_Terminate();
?>
