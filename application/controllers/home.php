<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form'));
        $this->load->library(array('form_validation'));
        $this->load->model('home_m','modelo');
        $this->arrDatos['title']       = '';
        $this->arrDatos['vista']       = 'home_v';
        $this->arrDatos['sMsjError']   = '';
        $this->arrDatos['show_slider'] = true;;
        
        //$this->lang->load('global');
    }
     
    public function index(){
        $this->arrDatos['arrTextoHome']      = $this->modelo->get_text_home();
        $this->arrDatos['arrImageLink']      = $this->modelo->get_image_link();
        $this->arrDatos['arrNewRelease']     = $this->modelo->get_new_release();
        $this->arrDatos['arrTiposProductos'] = get_product_type();
        $this->load->view('includes/template',$this->arrDatos);
    }
    
    public function registro(){
        //print_r($_POST);exit;
        $arrValidaciones = array(
            array(
                'field' => 'txtNombre',
                'label' => 'Nombre y Apellido',
                'rules' => 'required|min_length[3]|max_length[255]|callback__invalida_cadena'
            ),
            array(
                'field' => 'txtEmail',
                'label' => 'Email',
                'rules' => 'required|min_length[3]|max_length[255]
                            |valid_email|callback__invalido_email'
            ),
            array(
                'field' => 'txtPass',
                'label' => 'Contraseña',
                'rules' => 'required|min_length[6]|max_length[15]'
            )
        );
        $this->form_validation->set_rules($arrValidaciones);

        $txtNombre = $this->input->post('txtNombre');
        $txtEmail  = $this->input->post('txtEmail');
        $txtPass   = $this->input->post('txtPass');

        if($this->form_validation->run() == FALSE){
            if(form_error('txtNombre')){
                $this->arrDatos['sMsjError'] = form_error('txtNombre');
                
            }elseif(form_error('txtEmail')){
                $this->arrDatos['sMsjError'] = form_error('txtEmail');
                
            }elseif(form_error('txtPass')){
                $this->arrDatos['sMsjError'] = form_error('txtPass');
            }
        }elseif(user_is_logged()){
            $this->arrDatos['sMsjWarning'] = 'Para registrar una nueva cuenta, antes debe '.anchor('logout/index/6','Cerrar Sesión').'.';
        }else{
            if($this->modelo->registrar($txtNombre,$txtEmail,$txtPass)){
                generar_sesion($txtEmail,$txtPass);
            }else{
                $this->arrDatos['sMsjError'] = 'Imposible agregar el registro.';  
            }
        }
        $this->index();
    }
    
    function _invalido_email($email){
        if(no_es_email_valido($email)){
          $this->form_validation->set_message('_invalido_email', 'El Email no es correcto.');

        }elseif($this->modelo->existe_email($email)){
          $this->form_validation->set_message('_invalido_email', 'El Email ya se encuentra registrado.');

        }else{
          return true;
        }
        return false;
    }
    
    function _invalida_cadena($cadena){
       if(!es_correcto_nombre($cadena)){
          $this->form_validation->set_message('_invalida_cadena', 'El campo <b>Nombre y Apellido</b> es Inválido.');
          return false;
        }else{
          return true;
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/home.php */