<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pass_recovery extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form'));
        $this->load->library(array('form_validation'));
        $this->load->model('pass_recovery_m','modelo');
        $this->arrDatos['title']     = 'Recover Password';
        $this->arrDatos['vista']     = 'pass_recovery_v';
        $this->arrDatos['sMsjError'] = '';
    }

    public function index(){
        if($login_email=$this->session->userdata('login_email')){
            $this->arrDatos['login_email'] = $login_email;
            $this->session->set_userdata('login_email','');
        }
        $this->load->view('includes/template',$this->arrDatos);
    }

    public function send_email(){
        //print_r($_POST);exit;
        $arrValidaciones = array(
            array(
                'field'   => 'txtEmail',
                'label'   => lang('field_email_sm'),
                'rules'   => 'required|min_length[10]|max_length[255]
                |valid_email|callback__existe_email'
            )        
        );
        $this->form_validation->set_rules($arrValidaciones);
        $this->form_validation->set_message('_existe_email', lang('email_not_registered'));

        $sEmail = $this->input->post('txtEmail');
        if($this->form_validation->run() == FALSE){
            if(form_error('txtEmail')){
                $this->arrDatos['sMsjError'] = form_error('txtEmail');
            }
        }
        if(!$this->arrDatos['sMsjError']){
            $sEmail         = html_escape($sEmail);
            $pass           = rnd_string(8);
            $mensaje_footer = '<br><hr><br>
                                Sincerely / Atentamente, 
                                <br>EMG International
                                <br><a target="_blank" href="'.site_url('login').'">Emginter.com</a>';
            $mensaje = 'We received a request to update the password associated with this email address at <b>Emginter.com</b>.
                        <br><br> Here is your new login information:
                        <br> Email: <b>'.$sEmail.'</b>
                        <br> Password: <b>'.$pass.'</b>
                        <br> Connect: <a target="_blank" href="'.site_url('login').'">Click here to Log in Emginter</a>
                        <br>
                        <br>
                        <br>If you did not request a password reset, you will still need to change your password in order to access your account.';
            
            $mensaje .= '<br><hr><br>';
                
            $mensaje .= 'Recibimos una solicitud para actualizar la contraseña asociada con esta dirección de correo electrónico en <b>Emginter.com</b>.

                        <br><br> Estos son sus nuevos datos de acceso:
                        <br> Correo Electrónico: <b>'.$sEmail.'</b>
                        <br> Contraseña: <b>'.$pass.'</b>
                        <br> Conectar: <a target="_blank" href="'.site_url('login').'">Click aquí para Iniciar Sesión en Emginter</a>
                        <br>
                        <br>
                        <br>Si Usted no solicitó la actualización de su contraseña, de todas formas tendrá que hacerlo para poder acceder a su cuenta';

            $mensaje .= $mensaje_footer;//EMG International - Recuperar Contraseña‏ / Password Recovery
            //$asunto   = 'EMG International - '.lang('pass_recovery');
            $asunto   = 'EMG International - Recuperar Contraseña‏ / Password Recovery';
            if(send_email($sEmail,$asunto,$mensaje,'EMG International',get_config_db('email_contact'))){
                $this->arrDatos['sMsjConf'] = lang('msjconf_pass_recovery');
                $this->modelo->set_data($sEmail,$pass);
            }else{
                $this->arrDatos['sMsjError'] = lang('msjerror_contact');
            }
        }
        $this->index();
  }

  function _existe_email($sEmail){
    return $this->modelo->existe_cuenta($sEmail);
  }
}