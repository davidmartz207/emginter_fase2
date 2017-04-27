<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form'));
        $this->load->library(array('form_validation'));
        $this->load->model('login_m','modelo');
        $this->arrDatos['title']     = 'Login';
        $this->arrDatos['vista']     = 'login_v';
        $this->arrDatos['sMsjError'] = '';
    }

    public function index(){
        $this->load->view('includes/template',$this->arrDatos);
    }

    public function conect($recover=false){
        //print_r($_POST);exit;
        
        $sEmail = $this->input->post('txtEmail');
        if($this->input->post('btIngresar')){
            $arrValidaciones = array(
                array(
                    'field'   => 'txtEmail',
                    'label'   => lang('field_email_sm'),
                    'rules'   => 'required|min_length[10]|max_length[255]
                    |valid_email|callback__existe_email'
                ),
                array(
                    'field'   => 'txtPassword',
                    'label'   => lang('field_password_sm'),
                    'rules'   => 'required|min_length[6]|max_length[25]'
                )        
            );
            $this->form_validation->set_rules($arrValidaciones);
            $this->form_validation->set_message('_existe_email',lang('email_not_exists'));
            $sPassword = $this->input->post('txtPassword');
            if($this->form_validation->run() == FALSE){
                if(form_error('txtEmail')){
                    $this->arrDatos['sMsjError'] = form_error('txtEmail');
                }elseif(form_error('txtPassword')){
                    $this->arrDatos['sMsjError'] = form_error('txtPassword');
                }
            }elseif($this->modelo->no_existe_cuenta($sEmail,$sPassword,2)){
                $this->arrDatos['sMsjError'] = lang('account_not_exists');
            }elseif($this->modelo->account_for_approving($sEmail)){
                $this->arrDatos['sMsjError'] = lang('account_for_approving');
            }    

            if($this->arrDatos['sMsjError']){
                $this->index();
            }else{
                generar_sesion($sEmail,$sPassword);
            }
        }elseif(!empty($recover)){
            $this->session->set_userdata('login_email',$sEmail);
            redirect('pass_recovery');
        }
  }

  function _existe_email($sEmail){
    return ($this->modelo->no_existe_cuenta($sEmail)) ? false : true;
  }
}