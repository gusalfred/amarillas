<?php

// nombre
// id_categoria_nivel1
// id_categoria_nivel2
// logo

?>
<?php if ($empresas->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $empresas->TableCaption() ?></h4> -->
<table id="tbl_empresasmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($empresas->nombre->Visible) { // nombre ?>
		<tr id="r_nombre">
			<td><?php echo $empresas->nombre->FldCaption() ?></td>
			<td<?php echo $empresas->nombre->CellAttributes() ?>>
<span id="el_empresas_nombre">
<span<?php echo $empresas->nombre->ViewAttributes() ?>>
<?php echo $empresas->nombre->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($empresas->id_categoria_nivel1->Visible) { // id_categoria_nivel1 ?>
		<tr id="r_id_categoria_nivel1">
			<td><?php echo $empresas->id_categoria_nivel1->FldCaption() ?></td>
			<td<?php echo $empresas->id_categoria_nivel1->CellAttributes() ?>>
<span id="el_empresas_id_categoria_nivel1">
<span<?php echo $empresas->id_categoria_nivel1->ViewAttributes() ?>>
<?php echo $empresas->id_categoria_nivel1->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($empresas->id_categoria_nivel2->Visible) { // id_categoria_nivel2 ?>
		<tr id="r_id_categoria_nivel2">
			<td><?php echo $empresas->id_categoria_nivel2->FldCaption() ?></td>
			<td<?php echo $empresas->id_categoria_nivel2->CellAttributes() ?>>
<span id="el_empresas_id_categoria_nivel2">
<span<?php echo $empresas->id_categoria_nivel2->ViewAttributes() ?>>
<?php echo $empresas->id_categoria_nivel2->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($empresas->logo->Visible) { // logo ?>
		<tr id="r_logo">
			<td><?php echo $empresas->logo->FldCaption() ?></td>
			<td<?php echo $empresas->logo->CellAttributes() ?>>
<span id="el_empresas_logo">
<span>
<?php echo ew_GetFileViewTag($empresas->logo, $empresas->logo->ListViewValue()) ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
