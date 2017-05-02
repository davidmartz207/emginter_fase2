<?php
$this->load->view('includes/cabecera_html',$title);
#incluimos la cabacera grafica
$this->load->view('includes/cabecera_grafica');
$this->load->view('includes/slider_top');
$this->load->view('new_releases');
$this->load->view('includes/destacados');
$this->load->view($vista);
$this->load->view('includes/footer');