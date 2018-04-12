<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form'));
        $this->load->library(array('form_validation'));
        $this->load->model('news_m','modelo');
        $this->arrDatos['title']     = '';
        $this->arrDatos['vista']     = 'news_v';
        $this->arrDatos['sMsjError'] = '';
    }
     
    public function index($id_noticia=null){

        $this->arrDatos['arrContent'] = $this->modelo->get_news($id_noticia);
        $this->arrDatos['url_post']   = 'news';
        $this->load->view('includes/template',$this->arrDatos);
    }
}