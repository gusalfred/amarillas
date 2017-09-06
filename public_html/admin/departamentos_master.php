<?php

// codigo_departamento
// departamento

?>
<?php if ($departamentos->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $departamentos->TableCaption() ?></h4> -->
<table id="tbl_departamentosmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($departamentos->codigo_departamento->Visible) { // codigo_departamento ?>
		<tr id="r_codigo_departamento">
			<td><?php echo $departamentos->codigo_departamento->FldCaption() ?></td>
			<td<?php echo $departamentos->codigo_departamento->CellAttributes() ?>>
<span id="el_departamentos_codigo_departamento">
<span<?php echo $departamentos->codigo_departamento->ViewAttributes() ?>>
<?php echo $departamentos->codigo_departamento->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($departamentos->departamento->Visible) { // departamento ?>
		<tr id="r_departamento">
			<td><?php echo $departamentos->departamento->FldCaption() ?></td>
			<td<?php echo $departamentos->departamento->CellAttributes() ?>>
<span id="el_departamentos_departamento">
<span<?php echo $departamentos->departamento->ViewAttributes() ?>>
<?php echo $departamentos->departamento->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
