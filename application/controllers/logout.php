<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {
  public function __construct(){
      parent::__construct();
      $this->load->model('login_m','modelo');
  }
  public function index(){


    $this->modelo->destruirsesion($this->session->userdata('sesion'));
  	cerrar_sesion();



  	//llamamos a la función del controlador SuperController removeCache()
 	$this->removeCache();
  }
}