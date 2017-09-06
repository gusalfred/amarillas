<?php

// fecha
// id_empresa
// nombre
// url_aviso

?>
<?php if ($avisos->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $avisos->TableCaption() ?></h4> -->
<table id="tbl_avisosmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($avisos->fecha->Visible) { // fecha ?>
		<tr id="r_fecha">
			<td><?php echo $avisos->fecha->FldCaption() ?></td>
			<td<?php echo $avisos->fecha->CellAttributes() ?>>
<span id="el_avisos_fecha">
<span<?php echo $avisos->fecha->ViewAttributes() ?>>
<?php echo $avisos->fecha->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($avisos->id_empresa->Visible) { // id_empresa ?>
		<tr id="r_id_empresa">
			<td><?php echo $avisos->id_empresa->FldCaption() ?></td>
			<td<?php echo $avisos->id_empresa->CellAttributes() ?>>
<span id="el_avisos_id_empresa">
<span<?php echo $avisos->id_empresa->ViewAttributes() ?>>
<?php echo $avisos->id_empresa->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($avisos->nombre->Visible) { // nombre ?>
		<tr id="r_nombre">
			<td><?php echo $avisos->nombre->FldCaption() ?></td>
			<td<?php echo $avisos->nombre->CellAttributes() ?>>
<span id="el_avisos_nombre">
<span<?php echo $avisos->nombre->ViewAttributes() ?>>
<?php echo $avisos->nombre->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($avisos->url_aviso->Visible) { // url_aviso ?>
		<tr id="r_url_aviso">
			<td><?php echo $avisos->url_aviso->FldCaption() ?></td>
			<td<?php echo $avisos->url_aviso->CellAttributes() ?>>
<span id="el_avisos_url_aviso">
<span>
<?php echo ew_GetFileViewTag($avisos->url_aviso, $avisos->url_aviso->ListViewValue()) ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
