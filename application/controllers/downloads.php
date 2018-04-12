<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Downloads extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form'));
        $this->load->library(array('pagination','form_validation'));
        $this->load->model('downloads_m','modelo');
        $this->arrDatos['title']     = '';
        $this->arrDatos['vista']     = 'downloads_v';
        $this->arrDatos['sMsjError'] = '';
        $this->arrDatos['arrCatalog'] = get_catalog_products();
    }
     
    public function index(){
        $this->load->view('includes/template',$this->arrDatos);
    }
}