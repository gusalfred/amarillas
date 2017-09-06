<?php

// categoria
?>
<?php if ($categorias_nivel2->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $categorias_nivel2->TableCaption() ?></h4> -->
<table id="tbl_categorias_nivel2master" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($categorias_nivel2->categoria->Visible) { // categoria ?>
		<tr id="r_categoria">
			<td><?php echo $categorias_nivel2->categoria->FldCaption() ?></td>
			<td<?php echo $categorias_nivel2->categoria->CellAttributes() ?>>
<span id="el_categorias_nivel2_categoria">
<span<?php echo $categorias_nivel2->categoria->ViewAttributes() ?>>
<?php echo $categorias_nivel2->categoria->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
