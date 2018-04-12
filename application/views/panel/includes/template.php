<?php
$this->load->view('panel/includes/cabecera_html',$title);
$this->load->view('panel/includes/menu_superior');
$this->load->view('panel/includes/cabecera_grafica',$title);
?>
<!-- Page container -->
    <div class="page-container container">
<?php 
$this->load->view('panel/includes/sidebar');
$this->load->view('panel/'.$vista);
$this->load->view('panel/includes/footer');