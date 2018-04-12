<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model('register_m','modelo');
        $this->arrDatos['title']     = '';
        $this->arrDatos['vista']     = 'register_v';
        $this->arrDatos['sMsjError'] = '';
    }
     
    public function index(){
	$this->load->view('includes/template',$this->arrDatos);
    }
    
    public function process(){
        //echo '<pre>',print_r($_POST),'</pre>';echo '<pre>',print_r($_FILES),'</pre>';
        $arrValidaciones = array(
            array(
                'field' => 'txtNombre',
                'label' => lang('field_first_name'),
                'rules' => 'required|min_length[3]|max_length[255]'
            ),
            array(
                'field' => 'txtApellido',
                'label' => lang('field_last_name'),
                'rules' => 'required|min_length[3]|max_length[255]'
            ),
            array(
                'field' => 'txtEmail',
                'label' => lang('field_email_sm'),
                'rules' => 'required|min_length[3]|max_length[255]|valid_email|callback__invalido_email'
            ),
            array(
                'field' => 'txtCompany',
                'label' => lang('field_company'),
                'rules' => 'required|min_length[3]|max_length[255]'
            ),
            array(
                'field' => 'txtAddressCompany',
                'label' => lang('field_address_company'),
                'rules' => 'min_length[3]|max_length[10000]'
            ),
            array(
                'field' => 'txtPhoneNumber',
                'label' => lang('field_number_phone'),
                'rules' => 'required|min_length[3]|max_length[50]|callback__invalido_number_phone'
            ),
            array(
                'field' => 'txtFax',
                'label' => lang('field_fax'),
                'rules' => 'min_length[3]|max_length[50]|callback__invalido_fax'
            ),
            array(
                'field' => 'txtPassword',
                'label' => lang('field_password_sm'),
                'rules' => 'required|min_length[6]|max_length[30]'
            ),
            array(
                'field' => 'txtPassword2',
                'label' => lang('field_confirm_password'),
                'rules' => 'required|matches[txtPassword]'
            )
        );
        $this->form_validation->set_rules($arrValidaciones);
        $txtNombre         = $this->input->post('txtNombre');
        $txtApellido       = $this->input->post('txtApellido');
        $txtEmail          = $this->input->post('txtEmail');
        $txtPassword       = $this->input->post('txtPassword');
        $txtCompany        = $this->input->post('txtCompany');
        $txtAddressCompany = $this->input->post('txtAddressCompany');
        $txtPhoneNumber    = $this->input->post('txtPhoneNumber');
        $txtFax            = $this->input->post('txtFax');
        
        $this->arrDatos['txtNombre']         = html_escape($txtNombre);
        $this->arrDatos['txtApellido']       = html_escape($txtApellido);
        $this->arrDatos['txtEmail']          = html_escape($txtEmail);
        $this->arrDatos['txtPassword']       = html_escape($txtPassword);
        $this->arrDatos['txtCompany']        = html_escape($txtCompany);
        $this->arrDatos['txtAddressCompany'] = html_escape($txtAddressCompany);
        $this->arrDatos['txtPhoneNumber']    = html_escape($txtPhoneNumber);
        $this->arrDatos['txtFax']            = html_escape($txtFax);
        
        //echo '<pre>',print_r($this->arrDatos),'</pre>';exit;
                
        if($this->form_validation->run() == FALSE){
            if(form_error('txtNombre')){
                $this->arrDatos['sMsjError'] = form_error('txtNombre');
            }elseif(form_error('txtApellido')){
                $this->arrDatos['sMsjError'] = form_error('txtApellido');
            }elseif(form_error('txtEmail')){
                $this->arrDatos['sMsjError'] = form_error('txtEmail');
            }elseif(form_error('txtCompany')){
                $this->arrDatos['sMsjError'] = form_error('txtCompany');
            }elseif(form_error('txtAddressCompany')){
                $this->arrDatos['sMsjError'] = form_error('txtAddressCompany');
            }elseif(form_error('txtPhoneNumber')){
                $this->arrDatos['sMsjError'] = form_error('txtPhoneNumber');
            }elseif(form_error('txtFax')){
                $this->arrDatos['sMsjError'] = form_error('txtFax');
            }elseif(form_error('txtPassword')){
                $this->arrDatos['sMsjError'] = form_error('txtPassword');
            }elseif(form_error('txtPassword2')){
                $this->arrDatos['sMsjError'] = form_error('txtPassword2');
            }
        }else{
            if($this->modelo->registrar($this->arrDatos)){
                $this->arrDatos['sMsjConf'] = lang('msjconf_register');
                
                #enviar email al administrador
                $EMG_email_contacto = get_config_db('email_contact');
                $fecha              = date('d/m/Y H:i:s');
                $mensaje_footer     = '<br><hr><br>
                                            Thank you / Gracias, 
                                            <br>EMG International
                                            <br><a target="_blank" href="'.site_url('login').'">Emginter.com</a>';
                # --------------------------------------------------------------
                # email al administrador ---------------------------------------
                # --------------------------------------------------------------
                $mensaje = 'Hola, un usuario se ha registrado en EMG International desde el formulario de registro de la Web,
                           <br>Datos de la cuenta:
                           <br>Fecha: '.$fecha.'
                           <br>Nombre completo: '.$this->arrDatos['txtNombre'].' '.$this->arrDatos['txtApellido'].'
                           <br>Email: '.$this->arrDatos['txtEmail'].'
                           <br>Empresa: '.$this->arrDatos['txtCompany'].'
                           <br>Dirección de la empresa: '.$this->arrDatos['txtAddressCompany'].'
                           <br>Teléfono: '.$this->arrDatos['txtPhoneNumber'].'
                           <br>Fax: '.$this->arrDatos['txtFax'].'
                           <br>
                           <br>Para aprobar la cuenta debe ir al <a href="'.site_url('panel_usuarios').'/users_in_wait">Panel de control</a>';
                
                $mensaje .= $mensaje_footer;
                send_email($EMG_email_contacto,'Se han registrado en EMG International',$mensaje,'EMG International',$EMG_email_contacto);      
                
                # --------------------------------------------------------------
                # email al usuario ---------------------------------------------
                # --------------------------------------------------------------
                $mensaje_ingles   = get_config_db('mensaje_registro',2);
                $mensaje_spanish  = get_config_db('mensaje_registro',3);
                $mensaje   =  $mensaje_ingles.'<br><br>'.$mensaje_spanish;
                $mensaje  .= '<br><br>Fecha: '.$fecha;
                $mensaje  .= $mensaje_footer;
                send_email($this->arrDatos['txtEmail'],'Your Account at EMG International / Su cuenta en EMG International'
                           ,$mensaje,'EMG International',$EMG_email_contacto);
                #---------------------------------------------------------------

                $this->arrDatos['txtNombre']         = '';
                $this->arrDatos['txtApellido']       = '';
                $this->arrDatos['txtEmail']          = '';
                $this->arrDatos['txtPassword']       = '';
                $this->arrDatos['txtCompany']        = '';
                $this->arrDatos['txtAddressCompany'] = '';
                $this->arrDatos['txtPhoneNumber']    = '';
                $this->arrDatos['txtFax']            = '';
            }else{
                $this->arrDatos['sMsjError'] = lang('msjerror_register');
            }
        }
        $this->index();
    }

    function _invalido_email($email){
        if($this->modelo->existe_email($email)){
          $this->form_validation->set_message('_invalido_email', lang('email_registered'));
          return FALSE;
        }else{
          return TRUE;
        }
    }
    
    function _invalido_number_phone($cadena){
        if(!is_valid_number_phone($cadena)){
          $this->form_validation->set_message('_invalido_number_phone', lang('number_phone_registered'));
          return FALSE;
        }else{
          return TRUE;
        }
    }
    
    function _invalido_fax($cadena){
        if(!empty($cadena) and !is_valid_number_phone($cadena)){
          $this->form_validation->set_message('_invalido_fax', lang('fax_registered'));
          return FALSE;
        }else{
          return TRUE;
        }
    }
}