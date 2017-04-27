<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_inicio extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('panel_inicio_m','modelo');
        $this->arrDatos['title']     = 'Panel de Control';
        $this->arrDatos['vista']     = 'panel_inicio_v';
        $this->arrDatos['sMsjError'] = '';
    }
     
    public function index(){
        $this->arrDatos['num_users']         = $this->modelo->get_max_users();
        $this->arrDatos['num_users_in_wait'] = $this->modelo->get_max_users(true);
        $this->arrDatos['num_products']      = $this->modelo->get_max_products();
        
        $this->arrDatos['arrProducts']  = $this->modelo->get_products();
	$this->load->view('panel/includes/template',$this->arrDatos);
    }
}