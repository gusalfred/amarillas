<?php

// Global user functions
// Page Loading event
function Page_Loading() {

	//echo "Page Loading";
}

// Page Rendering event
function Page_Rendering() {

	//echo "Page Rendering";
}

// Page Unloaded event
function Page_Unloaded() {

	//echo "Page Unloaded";
}

function dbal_conn(){
	$config = new \Doctrine\DBAL\Configuration();
	$connectionParams = array(
		'dbname' 	=> EW_CONN_DB,
		'user'		=> EW_CONN_USER,
		'password' 	=> EW_CONN_PASS,
		'host' 		=> EW_CONN_HOST,
		'driver' 	=> 'pdo_mysql',
		'charset' 	=> 'utf8',
	);
	return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
}

function id_usuario(){
  return @$_SESSION['id_usuario'];
}

function fecha($fecha){
	return empty($fecha) ? '' : substr($fecha,8,2).'/'.substr($fecha,5,2).'/'.substr($fecha,0,4);
}

function fecha_sql($fecha){ //dd/mm/aaaa
	return empty($fecha) ? '' : substr($fecha,6,4).'/'.substr($fecha,3,2).'/'.substr($fecha,0,2);
}

function numero($numero){
	return empty($numero) ? '' : number_format($numero, 2, ',', '.');
}

function empresa_valora($id){
	global $conn;
	$conn->Execute('UPDATE empresas SET estrellas =
	(SELECT AVG(empresas_valoraciones.valor) AS valor
	FROM empresas_valoraciones WHERE id_empresa = '.$id.')
	WHERE empresas.id_empresa = '.$id);
}

function form_dropdown($name = '', $options = array(), $selected = array(), $extra = '')
{
	if ( ! is_array($selected)) { $selected = array($selected); }

	// If no selected state was submitted we will attempt to set it automatically
	if (count($selected) === 0)
	{

		// If the form name appears in the $_POST array we have a winner!
		if (isset($_POST[$name])) { $selected = array($_POST[$name]); }
	}
	if ($extra != '') $extra = ' '.$extra;
	$multiple = (count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';
	$form = '<select name="'.$name.'"'.$extra.$multiple.">\n";
	foreach ($options as $key => $val)
	{
		$key = (string) $key;
		if (is_array($val) && ! empty($val))
		{
			$form .= '<optgroup label="'.$key.'">'."\n";
			foreach ($val as $optgroup_key => $optgroup_val)
			{
				$sel = (in_array($optgroup_key, $selected)) ? ' selected="selected"' : '';
				$form .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
			}
			$form .= '</optgroup>'."\n";
		}
		else
		{
			$sel = (in_array($key, $selected)) ? ' selected="selected"' : '';
			$form .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
		}
	}
	$form .= '</select>';
	return $form;
}

function slug($title, $separator = '-')
{
	$title = ascii($title);

	// Convert all dashes/underscores into separator
	$flip = $separator == '-' ? '_' : '-';
	$title = preg_replace('!['.preg_quote($flip).']+!u', $separator, $title);

	// Remove all characters that are not the separator, letters, numbers, or whitespace.
	$title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($title));

	// Replace all separator characters and whitespace by a single separator
	$title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);
	return trim($title, $separator);
}
?>
