<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_account extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form'));
        $this->load->library(array('form_validation'));
        $this->load->model('my_account_m','modelo');
        $this->arrDatos['title']     = 'Mi Cuenta';
        $this->arrDatos['vista']     = 'my_account_v';
        $this->arrDatos['sMsjError'] = '';
    }
     
    public function index(){
        if(!$this->session->userdata('submit')){
            $this->arrDatos['arrDatos'] = $this->modelo->get_data();
        }else{
            $this->session->set_userdata('submit',0);
        }
        //echo '<pre>',print_r($this->arrDatos['arrDatos']),'<pre>';exit;
	$this->load->view('includes/template',$this->arrDatos);
    }
    
    public function upd(){
        //echo '<pre>',print_r($_POST),'</pre>';exit;
        if($this->input->post('btUpdate')){
            $this->session->set_userdata('submit',1);
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
                    'field' => 'txtPassword_new',
                    'label' => lang('field_password_new'),
                    'rules' => 'min_length[6]|max_length[30]'
                ),
                array(
                    'field' => 'txtPassword_new_2',
                    'label' => lang('field_password_new_conf'),
                    'rules' => 'matches[txtPassword_new]'
                ),
                array(
                    'field' => 'txtPassword_old',
                    'label' => lang('field_password_old'),
                    'rules' => 'required|min_length[6]|max_length[30]|callback__invalido_password'
                )
            );
            $this->form_validation->set_rules($arrValidaciones);
            $txtNombre         = $this->input->post('txtNombre');
            $txtApellido       = $this->input->post('txtApellido');
            $txtEmail          = $this->input->post('txtEmail');
            $txtCompany        = $this->input->post('txtCompany');
            $txtAddressCompany = $this->input->post('txtAddressCompany');
            $txtPhoneNumber    = $this->input->post('txtPhoneNumber');
            $txtFax            = $this->input->post('txtFax');
            $txtPassword_new   = $this->input->post('txtPassword_new');

            $this->arrDatos['txtNombre']         = html_escape($txtNombre);
            $this->arrDatos['txtApellido']       = html_escape($txtApellido);
            $this->arrDatos['txtEmail']          = html_escape($txtEmail);
            $this->arrDatos['txtCompany']        = html_escape($txtCompany);
            $this->arrDatos['txtAddressCompany'] = html_escape($txtAddressCompany);
            $this->arrDatos['txtPhoneNumber']    = html_escape($txtPhoneNumber);
            $this->arrDatos['txtFax']            = html_escape($txtFax);
            $this->arrDatos['txtPassword_new']   = html_escape($txtPassword_new);
            $this->arrDatos['imagen']            = '';

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
                }elseif(form_error('txtPassword_new')){
                    $this->arrDatos['sMsjError'] = form_error('txtPassword_new');
                }elseif(form_error('txtPassword_new_2')){
                    $this->arrDatos['sMsjError'] = form_error('txtPassword_new_2');
                }elseif(form_error('txtPassword_old')){
                    $this->arrDatos['sMsjError'] = form_error('txtPassword_old');
                }    
            }else{
                if($this->modelo->set_data($this->arrDatos)){
                    $this->arrDatos['sMsjConf'] = lang('msjconf_update');
                }else{
                    $this->arrDatos['sMsjError'] = lang('msjerror_update');
                }
            }
        }
        $this->index();
    }

    function _invalido_email($email){
        if($this->modelo->existe_email($email)){
          $this->form_validation->set_message('_invalido_email',lang('email_registered'));
          return FALSE;
        }else{
          return TRUE;
        }
    }
    
    function _invalido_password($password){
        if(!$this->modelo->es_valido_password($password)){
          $this->form_validation->set_message('_invalido_password',lang('error_password'));
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