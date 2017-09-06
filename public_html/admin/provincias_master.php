<?php

// codigo_provincia
// provincia

?>
<?php if ($provincias->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $provincias->TableCaption() ?></h4> -->
<table id="tbl_provinciasmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($provincias->codigo_provincia->Visible) { // codigo_provincia ?>
		<tr id="r_codigo_provincia">
			<td><?php echo $provincias->codigo_provincia->FldCaption() ?></td>
			<td<?php echo $provincias->codigo_provincia->CellAttributes() ?>>
<span id="el_provincias_codigo_provincia">
<span<?php echo $provincias->codigo_provincia->ViewAttributes() ?>>
<?php echo $provincias->codigo_provincia->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($provincias->provincia->Visible) { // provincia ?>
		<tr id="r_provincia">
			<td><?php echo $provincias->provincia->FldCaption() ?></td>
			<td<?php echo $provincias->provincia->CellAttributes() ?>>
<span id="el_provincias_provincia">
<span<?php echo $provincias->provincia->ViewAttributes() ?>>
<?php echo $provincias->provincia->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
