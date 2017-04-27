<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About_us extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form'));
        $this->load->library(array('form_validation'));
        $this->load->model('about_us_m','modelo');
        $this->arrDatos['title']     = '';
        $this->arrDatos['vista']     = 'about_us_v';
        $this->arrDatos['sMsjError'] = '';
    }
     
    public function index(){
        $this->arrDatos['arrContent'] = $this->modelo->get_content();
        $this->arrDatos['url_post']   = 'about-us';
        $this->load->view('includes/template',$this->arrDatos);
    }
}