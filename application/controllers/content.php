<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form'));
        $this->load->library(array('form_validation'));
        $this->load->model('content_m','modelo');
        $this->arrDatos['title']     = '';
        $this->arrDatos['vista']     = 'content_v';
        $this->arrDatos['sMsjError'] = '';
    }
     
    public function index($url_post=''){
        $this->arrDatos['arrContent'] = '';
        
        # buscamos el post en la db
        if(!empty($url_post)){
            $this->arrDatos['arrContent'] = $this->modelo->get_content($url_post);
            $this->arrDatos['url_post']   = 'content/'.html_escape($url_post);
        }
        
        //echo '<pre>',print_r($this->arrDatos['arrContent']),'</pre>';
        # si no se encontro el post en el idioma en el que nos encontramos
        # buscaremos el ultimo
        if(is_array($this->arrDatos['arrContent'])){
            
        }
	$this->load->view('includes/template',$this->arrDatos);
    }
}