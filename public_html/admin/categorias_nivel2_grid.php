<?php include_once "empleados_info.php" ?>
<?php

// Create page object
if (!isset($categorias_nivel2_grid)) $categorias_nivel2_grid = new ccategorias_nivel2_grid();

// Page init
$categorias_nivel2_grid->Page_Init();

// Page main
$categorias_nivel2_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$categorias_nivel2_grid->Page_Render();
?>
<?php if ($categorias_nivel2->Export == "") { ?>
<script type="text/javascript">

// Page object
var categorias_nivel2_grid = new ew_Page("categorias_nivel2_grid");
categorias_nivel2_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = categorias_nivel2_grid.PageID; // For backward compatibility

// Form object
var fcategorias_nivel2grid = new ew_Form("fcategorias_nivel2grid");
fcategorias_nivel2grid.FormKeyCountName = '<?php echo $categorias_nivel2_grid->FormKeyCountName ?>';

// Validate form
fcategorias_nivel2grid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_categoria");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $categorias_nivel2->categoria->FldCaption(), $categorias_nivel2->categoria->ReqErrMsg)) ?>");

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
fcategorias_nivel2grid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "categoria", false)) return false;
	return true;
}

// Form_CustomValidate event
fcategorias_nivel2grid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcategorias_nivel2grid.ValidateRequired = true;
<?php } else { ?>
fcategorias_nivel2grid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($categorias_nivel2->CurrentAction == "gridadd") {
	if ($categorias_nivel2->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$categorias_nivel2_grid->TotalRecs = $categorias_nivel2->SelectRecordCount();
			$categorias_nivel2_grid->Recordset = $categorias_nivel2_grid->LoadRecordset($categorias_nivel2_grid->StartRec-1, $categorias_nivel2_grid->DisplayRecs);
		} else {
			if ($categorias_nivel2_grid->Recordset = $categorias_nivel2_grid->LoadRecordset())
				$categorias_nivel2_grid->TotalRecs = $categorias_nivel2_grid->Recordset->RecordCount();
		}
		$categorias_nivel2_grid->StartRec = 1;
		$categorias_nivel2_grid->DisplayRecs = $categorias_nivel2_grid->TotalRecs;
	} else {
		$categorias_nivel2->CurrentFilter = "0=1";
		$categorias_nivel2_grid->StartRec = 1;
		$categorias_nivel2_grid->DisplayRecs = $categorias_nivel2->GridAddRowCount;
	}
	$categorias_nivel2_grid->TotalRecs = $categorias_nivel2_grid->DisplayRecs;
	$categorias_nivel2_grid->StopRec = $categorias_nivel2_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		if ($categorias_nivel2_grid->TotalRecs <= 0)
			$categorias_nivel2_grid->TotalRecs = $categorias_nivel2->SelectRecordCount();
	} else {
		if (!$categorias_nivel2_grid->Recordset && ($categorias_nivel2_grid->Recordset = $categorias_nivel2_grid->LoadRecordset()))
			$categorias_nivel2_grid->TotalRecs = $categorias_nivel2_grid->Recordset->RecordCount();
	}
	$categorias_nivel2_grid->StartRec = 1;
	$categorias_nivel2_grid->DisplayRecs = $categorias_nivel2_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$categorias_nivel2_grid->Recordset = $categorias_nivel2_grid->LoadRecordset($categorias_nivel2_grid->StartRec-1, $categorias_nivel2_grid->DisplayRecs);

	// Set no record found message
	if ($categorias_nivel2->CurrentAction == "" && $categorias_nivel2_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$categorias_nivel2_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($categorias_nivel2_grid->SearchWhere == "0=101")
			$categorias_nivel2_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$categorias_nivel2_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$categorias_nivel2_grid->RenderOtherOptions();
?>
<?php $categorias_nivel2_grid->ShowPageHeader(); ?>
<?php
$categorias_nivel2_grid->ShowMessage();
?>
<?php if ($categorias_nivel2_grid->TotalRecs > 0 || $categorias_nivel2->CurrentAction <> "") { ?>
<div class="ewGrid">
<div id="fcategorias_nivel2grid" class="ewForm form-inline">
<?php if ($categorias_nivel2_grid->ShowOtherOptions) { ?>
<div class="ewGridUpperPanel">
<?php
	foreach ($categorias_nivel2_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_categorias_nivel2" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_categorias_nivel2grid" class="table ewTable">
<?php echo $categorias_nivel2->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$categorias_nivel2->RowType = EW_ROWTYPE_HEADER;

// Render list options
$categorias_nivel2_grid->RenderListOptions();

// Render list options (header, left)
$categorias_nivel2_grid->ListOptions->Render("header", "left");
?>
<?php if ($categorias_nivel2->categoria->Visible) { // categoria ?>
	<?php if ($categorias_nivel2->SortUrl($categorias_nivel2->categoria) == "") { ?>
		<th data-name="categoria"><div id="elh_categorias_nivel2_categoria" class="categorias_nivel2_categoria"><div class="ewTableHeaderCaption"><?php echo $categorias_nivel2->categoria->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="categoria"><div><div id="elh_categorias_nivel2_categoria" class="categorias_nivel2_categoria">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $categorias_nivel2->categoria->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($categorias_nivel2->categoria->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($categorias_nivel2->categoria->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$categorias_nivel2_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$categorias_nivel2_grid->StartRec = 1;
$categorias_nivel2_grid->StopRec = $categorias_nivel2_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($categorias_nivel2_grid->FormKeyCountName) && ($categorias_nivel2->CurrentAction == "gridadd" || $categorias_nivel2->CurrentAction == "gridedit" || $categorias_nivel2->CurrentAction == "F")) {
		$categorias_nivel2_grid->KeyCount = $objForm->GetValue($categorias_nivel2_grid->FormKeyCountName);
		$categorias_nivel2_grid->StopRec = $categorias_nivel2_grid->StartRec + $categorias_nivel2_grid->KeyCount - 1;
	}
}
$categorias_nivel2_grid->RecCnt = $categorias_nivel2_grid->StartRec - 1;
if ($categorias_nivel2_grid->Recordset && !$categorias_nivel2_grid->Recordset->EOF) {
	$categorias_nivel2_grid->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $categorias_nivel2_grid->StartRec > 1)
		$categorias_nivel2_grid->Recordset->Move($categorias_nivel2_grid->StartRec - 1);
} elseif (!$categorias_nivel2->AllowAddDeleteRow && $categorias_nivel2_grid->StopRec == 0) {
	$categorias_nivel2_grid->StopRec = $categorias_nivel2->GridAddRowCount;
}

// Initialize aggregate
$categorias_nivel2->RowType = EW_ROWTYPE_AGGREGATEINIT;
$categorias_nivel2->ResetAttrs();
$categorias_nivel2_grid->RenderRow();
if ($categorias_nivel2->CurrentAction == "gridadd")
	$categorias_nivel2_grid->RowIndex = 0;
if ($categorias_nivel2->CurrentAction == "gridedit")
	$categorias_nivel2_grid->RowIndex = 0;
while ($categorias_nivel2_grid->RecCnt < $categorias_nivel2_grid->StopRec) {
	$categorias_nivel2_grid->RecCnt++;
	if (intval($categorias_nivel2_grid->RecCnt) >= intval($categorias_nivel2_grid->StartRec)) {
		$categorias_nivel2_grid->RowCnt++;
		if ($categorias_nivel2->CurrentAction == "gridadd" || $categorias_nivel2->CurrentAction == "gridedit" || $categorias_nivel2->CurrentAction == "F") {
			$categorias_nivel2_grid->RowIndex++;
			$objForm->Index = $categorias_nivel2_grid->RowIndex;
			if ($objForm->HasValue($categorias_nivel2_grid->FormActionName))
				$categorias_nivel2_grid->RowAction = strval($objForm->GetValue($categorias_nivel2_grid->FormActionName));
			elseif ($categorias_nivel2->CurrentAction == "gridadd")
				$categorias_nivel2_grid->RowAction = "insert";
			else
				$categorias_nivel2_grid->RowAction = "";
		}

		// Set up key count
		$categorias_nivel2_grid->KeyCount = $categorias_nivel2_grid->RowIndex;

		// Init row class and style
		$categorias_nivel2->ResetAttrs();
		$categorias_nivel2->CssClass = "";
		if ($categorias_nivel2->CurrentAction == "gridadd") {
			if ($categorias_nivel2->CurrentMode == "copy") {
				$categorias_nivel2_grid->LoadRowValues($categorias_nivel2_grid->Recordset); // Load row values
				$categorias_nivel2_grid->SetRecordKey($categorias_nivel2_grid->RowOldKey, $categorias_nivel2_grid->Recordset); // Set old record key
			} else {
				$categorias_nivel2_grid->LoadDefaultValues(); // Load default values
				$categorias_nivel2_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$categorias_nivel2_grid->LoadRowValues($categorias_nivel2_grid->Recordset); // Load row values
		}
		$categorias_nivel2->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($categorias_nivel2->CurrentAction == "gridadd") // Grid add
			$categorias_nivel2->RowType = EW_ROWTYPE_ADD; // Render add
		if ($categorias_nivel2->CurrentAction == "gridadd" && $categorias_nivel2->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$categorias_nivel2_grid->RestoreCurrentRowFormValues($categorias_nivel2_grid->RowIndex); // Restore form values
		if ($categorias_nivel2->CurrentAction == "gridedit") { // Grid edit
			if ($categorias_nivel2->EventCancelled) {
				$categorias_nivel2_grid->RestoreCurrentRowFormValues($categorias_nivel2_grid->RowIndex); // Restore form values
			}
			if ($categorias_nivel2_grid->RowAction == "insert")
				$categorias_nivel2->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$categorias_nivel2->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($categorias_nivel2->CurrentAction == "gridedit" && ($categorias_nivel2->RowType == EW_ROWTYPE_EDIT || $categorias_nivel2->RowType == EW_ROWTYPE_ADD) && $categorias_nivel2->EventCancelled) // Update failed
			$categorias_nivel2_grid->RestoreCurrentRowFormValues($categorias_nivel2_grid->RowIndex); // Restore form values
		if ($categorias_nivel2->RowType == EW_ROWTYPE_EDIT) // Edit row
			$categorias_nivel2_grid->EditRowCnt++;
		if ($categorias_nivel2->CurrentAction == "F") // Confirm row
			$categorias_nivel2_grid->RestoreCurrentRowFormValues($categorias_nivel2_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$categorias_nivel2->RowAttrs = array_merge($categorias_nivel2->RowAttrs, array('data-rowindex'=>$categorias_nivel2_grid->RowCnt, 'id'=>'r' . $categorias_nivel2_grid->RowCnt . '_categorias_nivel2', 'data-rowtype'=>$categorias_nivel2->RowType));

		// Render row
		$categorias_nivel2_grid->RenderRow();

		// Render list options
		$categorias_nivel2_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($categorias_nivel2_grid->RowAction <> "delete" && $categorias_nivel2_grid->RowAction <> "insertdelete" && !($categorias_nivel2_grid->RowAction == "insert" && $categorias_nivel2->CurrentAction == "F" && $categorias_nivel2_grid->EmptyRow())) {
?>
	<tr<?php echo $categorias_nivel2->RowAttributes() ?>>
<?php

// Render list options (body, left)
$categorias_nivel2_grid->ListOptions->Render("body", "left", $categorias_nivel2_grid->RowCnt);
?>
	<?php if ($categorias_nivel2->categoria->Visible) { // categoria ?>
		<td data-name="categoria"<?php echo $categorias_nivel2->categoria->CellAttributes() ?>>
<?php if ($categorias_nivel2->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $categorias_nivel2_grid->RowCnt ?>_categorias_nivel2_categoria" class="form-group categorias_nivel2_categoria">
<input type="text" data-field="x_categoria" name="x<?php echo $categorias_nivel2_grid->RowIndex ?>_categoria" id="x<?php echo $categorias_nivel2_grid->RowIndex ?>_categoria" size="30" maxlength="200" value="<?php echo $categorias_nivel2->categoria->EditValue ?>"<?php echo $categorias_nivel2->categoria->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_categoria" name="o<?php echo $categorias_nivel2_grid->RowIndex ?>_categoria" id="o<?php echo $categorias_nivel2_grid->RowIndex ?>_categoria" value="<?php echo ew_HtmlEncode($categorias_nivel2->categoria->OldValue) ?>">
<?php } ?>
<?php if ($categorias_nivel2->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $categorias_nivel2_grid->RowCnt ?>_categorias_nivel2_categoria" class="form-group categorias_nivel2_categoria">
<input type="text" data-field="x_categoria" name="x<?php echo $categorias_nivel2_grid->RowIndex ?>_categoria" id="x<?php echo $categorias_nivel2_grid->RowIndex ?>_categoria" size="30" maxlength="200" value="<?php echo $categorias_nivel2->categoria->EditValue ?>"<?php echo $categorias_nivel2->categoria->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($categorias_nivel2->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $categorias_nivel2->categoria->ViewAttributes() ?>>
<?php echo $categorias_nivel2->categoria->ListViewValue() ?></span>
<input type="hidden" data-field="x_categoria" name="x<?php echo $categorias_nivel2_grid->RowIndex ?>_categoria" id="x<?php echo $categorias_nivel2_grid->RowIndex ?>_categoria" value="<?php echo ew_HtmlEncode($categorias_nivel2->categoria->FormValue) ?>">
<input type="hidden" data-field="x_categoria" name="o<?php echo $categorias_nivel2_grid->RowIndex ?>_categoria" id="o<?php echo $categorias_nivel2_grid->RowIndex ?>_categoria" value="<?php echo ew_HtmlEncode($categorias_nivel2->categoria->OldValue) ?>">
<?php } ?>
<a id="<?php echo $categorias_nivel2_grid->PageObjName . "_row_" . $categorias_nivel2_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($categorias_nivel2->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id_categoria_nivel2" name="x<?php echo $categorias_nivel2_grid->RowIndex ?>_id_categoria_nivel2" id="x<?php echo $categorias_nivel2_grid->RowIndex ?>_id_categoria_nivel2" value="<?php echo ew_HtmlEncode($categorias_nivel2->id_categoria_nivel2->CurrentValue) ?>">
<input type="hidden" data-field="x_id_categoria_nivel2" name="o<?php echo $categorias_nivel2_grid->RowIndex ?>_id_categoria_nivel2" id="o<?php echo $categorias_nivel2_grid->RowIndex ?>_id_categoria_nivel2" value="<?php echo ew_HtmlEncode($categorias_nivel2->id_categoria_nivel2->OldValue) ?>">
<?php } ?>
<?php if ($categorias_nivel2->RowType == EW_ROWTYPE_EDIT || $categorias_nivel2->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_id_categoria_nivel2" name="x<?php echo $categorias_nivel2_grid->RowIndex ?>_id_categoria_nivel2" id="x<?php echo $categorias_nivel2_grid->RowIndex ?>_id_categoria_nivel2" value="<?php echo ew_HtmlEncode($categorias_nivel2->id_categoria_nivel2->CurrentValue) ?>">
<?php } ?>
<?php

// Render list options (body, right)
$categorias_nivel2_grid->ListOptions->Render("body", "right", $categorias_nivel2_grid->RowCnt);
?>
	</tr>
<?php if ($categorias_nivel2->RowType == EW_ROWTYPE_ADD || $categorias_nivel2->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fcategorias_nivel2grid.UpdateOpts(<?php echo $categorias_nivel2_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($categorias_nivel2->CurrentAction <> "gridadd" || $categorias_nivel2->CurrentMode == "copy")
		if (!$categorias_nivel2_grid->Recordset->EOF) $categorias_nivel2_grid->Recordset->MoveNext();
}
?>
<?php
	if ($categorias_nivel2->CurrentMode == "add" || $categorias_nivel2->CurrentMode == "copy" || $categorias_nivel2->CurrentMode == "edit") {
		$categorias_nivel2_grid->RowIndex = '$rowindex$';
		$categorias_nivel2_grid->LoadDefaultValues();

		// Set row properties
		$categorias_nivel2->ResetAttrs();
		$categorias_nivel2->RowAttrs = array_merge($categorias_nivel2->RowAttrs, array('data-rowindex'=>$categorias_nivel2_grid->RowIndex, 'id'=>'r0_categorias_nivel2', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($categorias_nivel2->RowAttrs["class"], "ewTemplate");
		$categorias_nivel2->RowType = EW_ROWTYPE_ADD;

		// Render row
		$categorias_nivel2_grid->RenderRow();

		// Render list options
		$categorias_nivel2_grid->RenderListOptions();
		$categorias_nivel2_grid->StartRowCnt = 0;
?>
	<tr<?php echo $categorias_nivel2->RowAttributes() ?>>
<?php

// Render list options (body, left)
$categorias_nivel2_grid->ListOptions->Render("body", "left", $categorias_nivel2_grid->RowIndex);
?>
	<?php if ($categorias_nivel2->categoria->Visible) { // categoria ?>
		<td data-name="categoria">
<?php if ($categorias_nivel2->CurrentAction <> "F") { ?>
<span id="el$rowindex$_categorias_nivel2_categoria" class="form-group categorias_nivel2_categoria">
<input type="text" data-field="x_categoria" name="x<?php echo $categorias_nivel2_grid->RowIndex ?>_categoria" id="x<?php echo $categorias_nivel2_grid->RowIndex ?>_categoria" size="30" maxlength="200" value="<?php echo $categorias_nivel2->categoria->EditValue ?>"<?php echo $categorias_nivel2->categoria->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_categorias_nivel2_categoria" class="form-group categorias_nivel2_categoria">
<span<?php echo $categorias_nivel2->categoria->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $categorias_nivel2->categoria->ViewValue ?></p></span>
</span>
<input type="hidden" data-field="x_categoria" name="x<?php echo $categorias_nivel2_grid->RowIndex ?>_categoria" id="x<?php echo $categorias_nivel2_grid->RowIndex ?>_categoria" value="<?php echo ew_HtmlEncode($categorias_nivel2->categoria->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_categoria" name="o<?php echo $categorias_nivel2_grid->RowIndex ?>_categoria" id="o<?php echo $categorias_nivel2_grid->RowIndex ?>_categoria" value="<?php echo ew_HtmlEncode($categorias_nivel2->categoria->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$categorias_nivel2_grid->ListOptions->Render("body", "right", $categorias_nivel2_grid->RowCnt);
?>
<script type="text/javascript">
fcategorias_nivel2grid.UpdateOpts(<?php echo $categorias_nivel2_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($categorias_nivel2->CurrentMode == "add" || $categorias_nivel2->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $categorias_nivel2_grid->FormKeyCountName ?>" id="<?php echo $categorias_nivel2_grid->FormKeyCountName ?>" value="<?php echo $categorias_nivel2_grid->KeyCount ?>">
<?php echo $categorias_nivel2_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($categorias_nivel2->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $categorias_nivel2_grid->FormKeyCountName ?>" id="<?php echo $categorias_nivel2_grid->FormKeyCountName ?>" value="<?php echo $categorias_nivel2_grid->KeyCount ?>">
<?php echo $categorias_nivel2_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($categorias_nivel2->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fcategorias_nivel2grid">
</div>
<?php

// Close recordset
if ($categorias_nivel2_grid->Recordset)
	$categorias_nivel2_grid->Recordset->Close();
?>
<?php if ($categorias_nivel2_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel">
<?php
	foreach ($categorias_nivel2_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($categorias_nivel2_grid->TotalRecs == 0 && $categorias_nivel2->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($categorias_nivel2_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($categorias_nivel2->Export == "") { ?>
<script type="text/javascript">
fcategorias_nivel2grid.Init();
</script>
<?php } ?>
<?php
$categorias_nivel2_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$categorias_nivel2_grid->Page_Terminate();
?>
