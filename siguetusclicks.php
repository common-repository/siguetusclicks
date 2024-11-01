<?php
/**
 * @package SigueTusClicks
 * @version 1.2.1
 */
/*
Plugin Name: SigueTusClicks
Plugin URI: http://siguetusclicks.wokomedia.com/
Description: This plugin generates heat map of your web.
Version: 1.2.1
Author: Wokomedia
Author URI: http://www.wokomedia.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*
Futuras mejoras:
	- clicks mínimos como filtro del listado
	- filtros por clase de página
*/

//Cargamos el idioma del plugin
function siguetusclicks_init() {
	load_plugin_textdomain( 'siguetusclicks', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
add_action('init', 'siguetusclicks_init');

//Cargamos AJAX necesarios
function siguetusclicks_code() { 
	if (is_front_page()) {
		$siguetusclicks_type = 'home'; 
		$siguetusclicks_id = '0';
	} else if (is_tag()) {
		$siguetusclicks_type = 'tag';
		$term  = get_term_by( 'slug', get_query_var('tag'), 'post_tag' ); 
		$siguetusclicks_id = $term->term_id;
	} else if (is_category()) {
		$siguetusclicks_type = 'cat'; 
		$siguetusclicks_id = get_query_var('cat');
	} else if (get_post_type != '' && get_the_id() > 0) {
		$siguetusclicks_type = get_post_type(get_the_id()); 
		$siguetusclicks_id = get_the_id();
	}

	?>
	<script type="text/javascript">
	jQuery('body').click(function(e){ var json = jQuery.getJSON( '<?php  echo admin_url('admin-ajax.php'); ?>', { action: 'siguetusclicks_action_callback', type: '<?php echo $siguetusclicks_type; ?>', id: '<?php echo $siguetusclicks_id; ?>', x: e.pageX, y: e.pageY, width: jQuery('body').width(), heigth: jQuery('body').height() }); });
	</script>
	<?php
}
add_action( 'wp_footer', 'siguetusclicks_code' );

function siguetusclicks_action_callback(){  
	$upload_dir = wp_upload_dir();
	require_once 'Mobile_Detect.php';
	$detect = new Mobile_Detect;
	if ( !$detect->isMobile() && $_REQUEST['type'] != '' && $_REQUEST['id'] != '' ) {
		if(!is_dir($upload_dir['basedir'].'/siguetusclicks/')) mkdir($upload_dir['basedir'].'/siguetusclicks/');
		$f = fopen($upload_dir['basedir'].'/siguetusclicks/'.$_REQUEST['type'].'_'.$_REQUEST['id'].'.txt', 'a+');
		unset($_REQUEST['action']);
		fwrite($f, implode(",", $_REQUEST)."\n");
		fclose($f);
	}
}  
if (get_option('siguetusclicks-users') == '1' || get_option('siguetusclicks-users') == '3') add_action( 'wp_ajax_siguetusclicks_action_callback', 'siguetusclicks_action_callback' );
if (get_option('siguetusclicks-users') == '1' || get_option('siguetusclicks-users') == '2' || get_option('siguetusclicks-users') == '') add_action( 'wp_ajax_nopriv_siguetusclicks_action_callback', 'siguetusclicks_action_callback' );

//Quitamos la barra superior en el mapa de calor
if (isset($_REQUEST['noadminbar']) && $_REQUEST['noadminbar'] == 'yes') add_filter('show_admin_bar', '__return_false');

//Metemos un botón en la barra superior para acceder directamente al mapa de calor de la página que estamos visitando
function siguetusclicks_add_toolbar_items($admin_bar){
	 if (current_user_can('manage_options') ) {if (is_front_page()){
		$admin_bar->add_menu( array(
			'id'    => 'siguetusclicks',
			'title' =>  __('Mapa de Calor'),
			'href'  => admin_url( 'admin.php?page=mapa.php' )."&id=0&type=home",
			'meta'  => array( 'title' => __('Mapa de Calor'))
		));

	} else if (get_the_id() > 0) {
		$admin_bar->add_menu( array(
			'id'    => 'siguetusclicks',
			'title' =>  __('Mapa de Calor'),
			'href'  => admin_url( 'admin.php?page=mapa.php' )."&id=".get_the_id()."&type=".get_post_type(get_the_id()),
			'meta'  => array( 'title' => __('Mapa de Calor'))
		));
	}}
}
add_action('admin_bar_menu', 'siguetusclicks_add_toolbar_items', 100);

//Página de configuraci&oacute;n y visionado de mapas de calor
add_action( 'admin_menu', 'register_siguetusclicksconfig' );
function register_siguetusclicksconfig() {
	add_menu_page(__('SigueTusClicks', "siguetusclicks"), __('SigueTusClicks', "siguetusclicks"), 'manage_options', 'siguetusclicks/siguetusclicks.php', 'siguetusclicksConfig', plugin_dir_url( __FILE__ ).'img/ko.png');
}

function siguetusclicksConfig () {
	$upload_dir = wp_upload_dir();
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'deletemap') { //REETEAR MAPA
		unlink($upload_dir['basedir'].'/siguetusclicks/'.$_REQUEST['type']."_".$_REQUEST['id'].".txt");
		echo "<h3 style='border: 1px solid green; color: green; text-align:center; padding: 5px 5px 5px 5px;'>".__("Dato borrado correctamente", "siguetusclicks")."</h3>";
	} else if(isset($_REQUEST['delete']) && $_REQUEST['delete'] != '') { //REETEAR MAPAS
		$files = scandir($upload_dir['basedir'].'/siguetusclicks/');
		if (count($files) > 2) {
			foreach ($files as $file) {
				if ($file  != '.' && $file != '..') {
					unlink($upload_dir['basedir'].'/siguetusclicks/'.$file);
				}
			}
		}
		echo "<h3 style='border: 1px solid green; color: green; text-align:center; padding: 5px 5px 5px 5px;'>".__("Datos borrados correctamente", "siguetusclicks")."</h3>";
	} else if (isset($_REQUEST['update']) && $_REQUEST['update'] != '') { //GUARDAR DATOS
		update_option('siguetusclicks-width',$_REQUEST['siguetusclicks-width']);
		update_option('siguetusclicks-align',$_REQUEST['siguetusclicks-align']);
		update_option('siguetusclicks-color',$_REQUEST['siguetusclicks-color']);
		update_option('siguetusclicks-min',$_REQUEST['siguetusclicks-min']);
		update_option('siguetusclicks-mode',$_REQUEST['siguetusclicks-mode']);
		update_option('siguetusclicks-users',$_REQUEST['siguetusclicks-users']);
	}
	?>
	<h1><?php _e('SigueTusClicks', "siguetusclicks"); ?></h1>
	<div style="position: absolute; top: 10px;right: 30px;">POWERED BY:<br/><a href="http://wokomedia.com/"><img src="<?php echo plugin_dir_url( __FILE__ ).'img/woko.png'; ?>" alt="Wokomedia"></a></div>
	<h2><?php _e('Configuraci&oacute;n', "siguetusclicks"); ?></h2>
	<p><?php _e('<p>Para el correcto funcionamiento de los mapas de calor, debes darnos el ancho m&iacute;nimo normal de tu web y a la alineaci&oacute;n normal de la web. Puedes consultar nuestra documentaci&oacute;n <a href="http://siguetusclicks.wokomedia.com/documentacion/">aqu&iacute;</a>.</p>'); ?>
	
	<form action="" method="post">
		<table class="wp-list-table widefat fixed pages" style="display: table;">
			<tr>
				<th><?php _e("Registrar clicks", "siguetusclicks"); ?></th>
				<td>
					<select name="siguetusclicks-users">
						<option value="1"<?php if (get_option('siguetusclicks-users') == '1') echo " selected='selected'"; ?>><?php _e("Todos los usuarios", "siguetusclicks"); ?></option>
						<option value="2"<?php if (get_option('siguetusclicks-users') == '2') echo " selected='selected'"; ?>><?php _e("Usuarios no logeados", "siguetusclicks"); ?></option>
						<option value="3"<?php if (get_option('siguetusclicks-users') == '3') echo " selected='selected'"; ?>><?php _e("Usuarios logeados", "siguetusclicks"); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php _e("Modo", "siguetusclicks"); ?></th>
				<td>
					<select name="siguetusclicks-mode">
						<option value="image"<?php if (get_option('siguetusclicks-mode') == 'image') echo " selected='selected'"; ?>><?php _e("Imagen (Mayor consumo de recursos)", "siguetusclicks"); ?></option>
						<option value="html"<?php if (get_option('siguetusclicks-mode') == 'html') echo " selected='selected'"; ?>><?php _e("HTML (menor consumo de recursos)", "siguetusclicks"); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php _e("Tama&ntilde;o m&iacute;nimo", "siguetusclicks"); ?></th>
				<td><input type="text" name="siguetusclicks-width" value="<?php echo get_option('siguetusclicks-width'); ?>"/> px</td>
			</tr>
			<tr>
				<th><?php _e("Alineaci&oacute;n", "siguetusclicks"); ?></th>
				<td>
					<select name="siguetusclicks-align">
						<option value="1"<?php if (get_option('siguetusclicks-align') == 1) echo " selected='selected'"; ?>><?php _e("izquierda", "siguetusclicks"); ?></option>
						<option value="2"<?php if (get_option('siguetusclicks-align') == 2) echo " selected='selected'"; ?>><?php _e("centrado", "siguetusclicks"); ?></option>
						<option value="3"<?php if (get_option('siguetusclicks-align') == 3) echo " selected='selected'"; ?>><?php _e("derecha", "siguetusclicks"); ?></option>
						<option value="4"<?php if (get_option('siguetusclicks-align') == 4) echo " selected='selected'"; ?>><?php _e("ancho 100%", "siguetusclicks"); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php _e("Color puntos (Modo HTML)", "siguetusclicks"); ?></th>
				<td>
					<select name="siguetusclicks-color">
						<option value="red"<?php if (get_option('siguetusclicks-color') == 'red') echo " selected='selected'"; ?>><?php _e("rojo", "siguetusclicks"); ?></option>
						<option value="blue"<?php if (get_option('siguetusclicks-color') == 'blue') echo " selected='selected'"; ?>><?php _e("azul", "siguetusclicks"); ?></option>
						<option value="green"<?php if (get_option('siguetusclicks-color') == 'green') echo " selected='selected'"; ?>><?php _e("verde", "siguetusclicks"); ?></option>
						<option value="yellow"<?php if (get_option('siguetusclicks-color') == 'yellow') echo " selected='selected'"; ?>><?php _e("amarillo", "siguetusclicks"); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php _e("Clicks m&iacute;nimos", "siguetusclicks"); ?></th>
				<td>
					<select name="siguetusclicks-min">
						<option value="0"<?php if (get_option('siguetusclicks-min') == '0') echo " selected='selected'"; ?>>0</option>
						<option value="10"<?php if (get_option('siguetusclicks-min') == '10') echo " selected='selected'"; ?>>10</option>
						<option value="100"<?php if (get_option('siguetusclicks-min') == '100') echo " selected='selected'"; ?>>100</option>
						<option value="250"<?php if (get_option('siguetusclicks-min') == '250') echo " selected='selected'"; ?>>250</option>
						<option value="500"<?php if (get_option('siguetusclicks-min') == '500') echo " selected='selected'"; ?>>500</option>
						<option value="1000"<?php if (get_option('siguetusclicks-min') == '1000') echo " selected='selected'"; ?>>1000</option>
					</select>
				</td>
			</tr>
		</table><p><input type="submit" name="update" id="submit" class="button button-primary" value="<?php _e('Guardar', "siguetusclicks"); ?>"  /></p>
	</form>
	<hr/>
	<h2><?php _e('Listado de p&aacute;ginas', "siguetusclicks"); ?></h2>
	<?php

	$files = scandir($upload_dir['basedir'].'/siguetusclicks/');
	$total = 0;
	$count = 0;
	if (count($files) > 2) {
		echo "<table class='wp-list-table widefat fixed pages' style='display: table;' cellpadding='0' cellspacing='0'>";
		echo "<thead><tr><th>".__("Nombre", "siguetusclicks")."</th><th>".__("Clicks", "siguetusclicks")."</th><th>".__("Tama&ntilde;o fichero<br/>de datos", "siguetusclicks")."</th><th>".__("&Uacute;ltimo dato<br/>recogido", "siguetusclicks")."</th><th>".__("&Uacute;ltima modificaci&oacute;n<br/>de la p&aacute;gina", "siguetusclicks")."</th><th></th></tr></thead>";
		foreach ($files as $file) {
			if ($file  != '.' && $file != '..' && preg_match("/.txt/", $file)) { 

				$linecount = 0;
				$handle = fopen($upload_dir['basedir'].'/siguetusclicks/'.$file, "r");
				while(!feof($handle)){
				  $line = fgets($handle, 4096);
				  $linecount = $linecount + substr_count($line, PHP_EOL);
				}
				fclose($handle);


				if ($linecount >= get_option('siguetusclicks-min')) {
					$count ++;
					list($type, $id) = split ("_", str_replace(".txt", "", $file));

					if (($count%2) == 0) echo "<tr style='background-color: #ccc;'>";
					else echo "<tr>";
					$filetime = date("Y-m-d H:i:s", filectime($upload_dir['basedir'].'/siguetusclicks/'.$file));			
					$filesize = human_filesize(filesize($upload_dir['basedir'].'/siguetusclicks/'.$file));
					if ($type == 'home') {
						echo "<td><a href='".admin_url( 'admin.php?page=mapa.php' )."&id=".$id."&type=".$type."' target='_blank'>".__("Portada", "siguetusclicks")." </a></td>";
						//$id = get_option('page_on_front');
					} else if ($type == 'cat') {
						echo "<td><a href='".admin_url( 'admin.php?page=mapa.php' )."&id=".$id."&type=".$type."' target='_blank'>".get_cat_name( $id )."</a></td>";
					} else if ($type == 'tag') {
						$term  = get_term_by( 'id', $id, 'post_tag' );
						echo "<td><a href='".admin_url( 'admin.php?page=mapa.php' )."&id=".$id."&type=".$type."' target='_blank'>".$term->name."</a></td>";
					} else echo "<td><a href='".admin_url( 'admin.php?page=mapa.php' )."&id=".$id."&type=".$type."' target='_blank'>".get_the_title( $id )."</a></td>";

					$post = get_post($id);

					$advise = "";
					if (strtotime($filetime) < strtotime($post->post_modified)) $advise = " style='color: red;'";

					echo "<td>".$linecount."</td><td>".$filesize."</td><td>".$filetime."</td><td".$advise.">".$post->post_modified."</td><td><a class='button button-primary' href='/wp-admin/admin.php?page=siguetusclicks/siguetusclicks.php&action=deletemap&id=".$id."&type=".$type."'>".__("Borrar", "siguetusclicks")." </a></td>";

					echo "</tr>";
					$total = $total + $linecount;
				}
			}
		}
		echo "</table>";
	}
	?>
	<p><?php echo sprintf(__("%s clicks registrados", "siguetusclicks"), $total); ?></p>
	<p><a class='button button-primary' href='<?php echo admin_url("admin.php?page=mapa.php"); ?>&type=all'><?php _e("Ver todos", "siguetusclicks"); ?></a></p>
	<hr/>
	<h2><?php _e('Resetear mapas', "siguetusclicks"); ?></h2>
	<form action="" method="post">
		<input class="button button-primary" type="submit" name="delete" id="submit" class="button" onclick="return confirm('Quieres todos los datos?');" value="<?php _e('Borrar todos los datos', "siguetusclicks"); ?>"  />
	</form>
	<?php
}

//Mapas de calor
function siguetusclicksMap_register_admin_page() {
	global $_registered_pages;
	$menu_slug = plugin_basename('mapa.php');
	$hookname = get_plugin_page_hookname($menu_slug,'');
	if (!empty($hookname)) {
		add_action($hookname, 'siguetusclicksMap');
	}
	$_registered_pages[$hookname] = true;
}
add_action('admin_menu', 'siguetusclicksMap_register_admin_page');

function siguetusclicksMap () {
	ini_set("display_errors", 0);
	$min_width = get_option('siguetusclicks-width');
	$align = get_option('siguetusclicks-align');
	$color = get_option('siguetusclicks-color'); 
	$mode = get_option('siguetusclicks-mode');  
	/*$mode = 'image'; */

	if ( current_user_can( 'manage_options' ) ) { 
		$upload_dir = wp_upload_dir();


		if($_REQUEST['type'] == 'all') {
			
			$files = scandir($upload_dir['basedir'].'/siguetusclicks/');
			if (count($files) > 2) {
				$datas = array();
				foreach ($files as $file) {
					if ($file  != '.' && $file != '..' && preg_match("/.txt/", $file)) {
						$temp = csv_to_array($upload_dir['basedir'].'/siguetusclicks/'.$file);
						
						foreach ($temp as $item) {
							$datas[] = $item;
						}
					} 
				}
			}
		} else $datas = csv_to_array($upload_dir['basedir'].'/siguetusclicks/'.$_REQUEST['type'].'_'.$_REQUEST['id'].'.txt');
		$width = 0;
		$height = 0;
		$count = 0;

		$pngdata = array();		
		foreach ($datas as $data) {
			if ($data[4] > $width) $width = $data[4];
			if ($data[5] > $height) $height = $data[5];
			$count++;	
		}
		foreach ($datas as $data) {
			if ($data['4'] >= $min_width) {	
				if ($align == 2) { $data['2'] = ($width/2) + ($data['2'] - ($data['4'] /2)) ; } 
				else if ($align == 3) { $data['2'] = $width-($data['4']-$data['2']); } 
				else if ($align == 4) { $data['2'] = ($width*$data['2'])/$data['4']; }

				$pngdata[$data['2']][$data['3']] = $pngdata[$data['2']][$data['3']] +2;
			}
		}
		
		if ($align == 4) $width = $min_width;

		
		if ($mode == 'image') {//Creamos la imagen	
			require_once('gd-heatmap/gd_heatmap.php');
			$config = array(
			  'debug' => false,
			  'width' => $width,
			  'height' => $height,
			  'noc' => 32,
			  'r' => 50,
			  'dither' => FALSE,
			  'format' => 'png',
			);
			$heatmapdatas = array();
			foreach ($pngdata as $x => $ydatas) {
				foreach ($ydatas as $y => $data) {




					$heatmapdatas[] = array($x-24, $y-24, $data);
				}		
			}
			/*echo "<pre>";
			print_r ($heatmapdatas);
			echo "</pre>";*/
			$heatmap = new gd_heatmap($heatmapdatas, $config);
			$upload_dir = wp_upload_dir();
			//$heatmap->output();
			$heatmap->output($upload_dir['basedir'].'/siguetusclicks/'.$_REQUEST['type'].'_'.$_REQUEST['id'].'.png');
		}
	?>
	<style media="screen" type="text/css">
		#adminmenuback, #adminmenuwrap, #wpfooter {
			display: none !important;
		}
		#wpcontent {
			margin-left: 0px;
			padding-left: 0px;
		}
		body { margin: 0 auto; padding: 0; font-family: arial; width: <?php echo $width; ?>px; }
		iframe { width: <?php echo $width; ?>px; height: <?php echo $height; ?>px; }
		h1 { background-color: #000000; color: #ffffff; margin: 0; padding: 5px; width: <?php echo $width; ?>px; }
		div#opacity { opacity: 0.5; position: absolute;  width: <?php echo $width; ?>px; height: <?php echo $height; ?>px; z-index: 50; background-color: #000000;}
		div#heatmap {  position: relative;  width: <?php echo $width; ?>px; height: <?php echo $height; ?>px; z-index: 100;}
		div#heatmap div.point { opacity:0.07; position: absolute; border-radius: 12px; background-color: <?php echo $color; ?>; min-width: 24px; min-height: 24px; z-index: 100;}
	</style>
	<h1><?php if ($_REQUEST['type'] == 'all') { ?><?php echo sprintf (__("%d clicks registrados en toda la web"), $count); ?><?php } else if ($_REQUEST['type'] == 'cat') {?><?php echo sprintf (__("%d clicks registrados en '%s'", "siguetusclicks"), $count, get_cat_name($_REQUEST['id'])); ?><?php } else if ($_REQUEST['type'] == 'tag') {  $term  = get_term_by( 'id', $_REQUEST['id'], 'post_tag' ); ?><?php echo sprintf (__("%d clicks registrados en '%s'", "siguetusclicks"), $count, $term->name); ?><?php } else if ($_REQUEST['id'] == 0) {?><?php echo sprintf (__("%d clicks registrados en '%s'", "siguetusclicks"), $count, __("Portada", "siguetusclicks")); ?><?php } else { ?><?php echo sprintf (__("%d clicks registrados en '%s'", "siguetusclicks"), $count, get_the_title($_REQUEST['id'])); ?><?php } ?></h1>
	<div id="heatmap">
		<div id="opacity"></div>
		<iframe src="<?php if ($_REQUEST['type'] == 'cat') echo get_category_link($_REQUEST['id']) ; else if ($_REQUEST['type'] == 'tag') echo get_tag_link($_REQUEST['id']); else if ($_REQUEST['id'] > 0) echo get_permalink($_REQUEST['id']); else echo '/'; ?>?noadminbar=yes" scrolling="no" frameborder="0" ></iframe>
		<?php
			if ($mode == 'html') {
				foreach ($datas as $data) {
					if ($data['4'] >= $min_width) {
						if ($align == 2) { $data['2'] = ($width/2) + ($data['2'] - ($data['4'] /2)) ; } 
						else if ($align == 3) { $data['2'] = $width-($data['4']-$data['2']); } 
						else if ($align == 4) { $data['2'] = ($width*$data['2'])/$data['4']; }
						?><div class="point" style="top: <?php echo $data[3]-12; ?>px; left: <?php echo $data[2]-12; ?>px;"></div><?php 
					}
				}
			} else if ($mode == 'image') { ?>
				<img style="position: absolute; top: 0px; left: 0px; opacity: 0.7;" src="<?php echo '/wp-content/uploads/siguetusclicks/'.$_REQUEST['type'].'_'.$_REQUEST['id'].'.png?hash='.date('Ymdhis'); ?>" />
		<?php } ?>
	</div>
	<?php 
	}
}

// Funciones varias
function human_filesize($bytes, $decimals = 2) {
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

function csv_to_array($filename='', $delimiter=',') {
    if(!file_exists($filename) || !is_readable($filename)) return FALSE;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE) {
        while (($row = fgetcsv($handle, 00, $delimiter)) !== FALSE) {
                $data[] = $row;
        }
        fclose($handle);
    }
    return $data;
}

?>
