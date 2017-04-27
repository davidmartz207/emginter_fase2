<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pagina_no_encontrada extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->arrDatos['title']     = 'Página no Encontrada';
        $this->arrDatos['vista']     = 'pagina_no_encontrada_v';
    }
     
    public function index(){
        $this->load->view('includes/template',$this->arrDatos);
    }
}