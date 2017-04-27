<?php 
/*$seconds_to_cache = 3600;
$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
header("Expires: $ts");
header("Pragma: cache");
header("Cache-Control: max-age=$seconds_to_cache");
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo ((isset($title) and !empty($title)) ? $title.' - EMG International' : 'EMG International'); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="index,follow">
<meta name="title" content="<?php echo ((isset($title) and !empty($title)) ? $title.' - EMG International' : 'EMG International'); ?>" />
<meta name="description" content="<?php echo ((isset($meta_descripcion) and !empty($meta_descripcion)) ? $meta_descripcion : 'At EMG International our customers and your satisfaction'); ?>" />
<meta name="keywords" content="<?php echo ((isset($meta_keywords) and !empty($meta_keywords)) ? $meta_keywords : 'Engine, Manufacture, Emginter, International'); ?>" />
<?php 
#canonical
$controlador = get_controller();
$lang        = get_lang();
if(isset($url_post)){echo '<link rel="canonical" href="'.base_url().$lang.'/'.$url_post.'" />';
}else{echo '<link rel="canonical" href="'.site_url($controlador).'" />';}
?>
<link rel="shortcut icon" href="<?php echo base_url().'includes/images/logo.png'; ?>"><link rel="stylesheet" href="<?php echo base_url(); ?>includes/css/bootstrap.min.css"><link rel="stylesheet" href="<?php echo base_url(); ?>includes/css/font-awesome-4.2.0/css/font-awesome.min.css"><link rel="stylesheet" href="<?php echo base_url(); ?>includes/js/slicknav/slicknav.css" />
<?php
if(get_controller() == 'home'){echo '<link rel="stylesheet" href="'.base_url().'includes/js/owl-carousel/owl.carousel.css">';echo '<link rel="stylesheet" href="'.base_url().'includes/js/owl-carousel/owl.theme.css">';}
if(in_array(get_controller(),array('orders','products'))){
    //echo '<link rel="stylesheet" href="'.base_url().'includes/js/jquery-ui/jquery-ui.min.css">';
    echo '<link rel="stylesheet" href="'.base_url().'includes/js/jquery-ui-1.11.4/jquery-ui.min.css">';
}
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>includes/css/style_ppal.css">
</head><body><noscript><?php show_msj_error('<b>Attention: You must enable Javascript in your browser immediately to the proper functioning of Emginter.com</b>',FALSE)?></noscript>