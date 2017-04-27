<?php
/*
|--------------------------------------------------------------------------
| Image Preset Sizes
|--------------------------------------------------------------------------
|
| Specify the preset sizes you want to use in your code. Only these preset 
| will be accepted by the controller for security.
|
| Each preset exists of a width and height. If one of the dimensions are 
| equal to 0, it will automatically calculate a matching width or height 
| to maintain the original ratio.
|
| If both dimensions are specified it will automatically crop the 
| resulting image so that it fits those dimensions.
|
*/

$config['image_sizes']['square']         = array(100, 100);
$config['image_sizes']['texto_home']     = array(303, 227);
$config['image_sizes']['catalogos']      = array(200, 200);
$config['image_sizes']['catalogos_rectangular'] = array(280,350);
$config['image_sizes']['img_about_us']   = array(350, 388);
$config['image_sizes']['publi_image']    = array(500, 121);#521
$config['image_sizes']['product_min']    = array(140, 140);
$config['image_sizes']['product_page']   = array(200, 120);
$config['image_sizes']['product_slider'] = array(80, 80);
$config['image_sizes']['new_release']    = array(228, 146);
$config['image_sizes']['product']        = array(400, 300);//array(218, 136);
$config['image_sizes']['avatar']         = array(150, 150);
$config['image_sizes']['album']          = array(160, 150);
$config['image_sizes']['long']           = array(280, 600);
$config['image_sizes']['wide']           = array(600, 200);
$config['image_sizes']['hero']           = array(940, 320);
$config['image_sizes']['slider_top']     = array(1600, 400);#1600 - 372

$config['image_sizes']['small']        = array(280, 0);
$config['image_sizes']['medium']       = array(340, 0);
$config['image_sizes']['large']        = array(800, 0);