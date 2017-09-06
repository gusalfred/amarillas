<?php

// categoria
// imagen

?>
<?php if ($categorias_nivel1->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $categorias_nivel1->TableCaption() ?></h4> -->
<table id="tbl_categorias_nivel1master" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($categorias_nivel1->categoria->Visible) { // categoria ?>
		<tr id="r_categoria">
			<td><?php echo $categorias_nivel1->categoria->FldCaption() ?></td>
			<td<?php echo $categorias_nivel1->categoria->CellAttributes() ?>>
<span id="el_categorias_nivel1_categoria">
<span<?php echo $categorias_nivel1->categoria->ViewAttributes() ?>>
<?php echo $categorias_nivel1->categoria->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($categorias_nivel1->imagen->Visible) { // imagen ?>
		<tr id="r_imagen">
			<td><?php echo $categorias_nivel1->imagen->FldCaption() ?></td>
			<td<?php echo $categorias_nivel1->imagen->CellAttributes() ?>>
<span id="el_categorias_nivel1_imagen">
<span>
<?php echo ew_GetFileViewTag($categorias_nivel1->imagen, $categorias_nivel1->imagen->ListViewValue()) ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
