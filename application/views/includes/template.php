<?php
if(!isset($linea)){
    $linea="";
}

if(!isset($session)){
    $session="";
}
$this->load->view('includes/cabecera_html',array(
    'title'=>$title,
    'meta_keywords'=>isset($meta_keywords)?$meta_keywords:'EMG International',
    'meta_descripcion'=>isset($meta_descripcion)?$meta_descripcion:'',
));
#incluimos la cabacera grafica
$this->load->view('includes/cabecera_grafica',array("session"=>$session));
$this->load->view('includes/news_top');
$this->load->view('includes/slider_top');
$this->load->view('new_releases');
$this->load->view('includes/destacados');
$this->load->view($vista, array("linea"=>$linea,"session"=>$session));
$this->load->view('includes/footer');