<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->arrDatos['title']     = 'Contact';
        $this->arrDatos['vista']     = 'contact_v';
        $this->arrDatos['sMsjError'] = '';
        $this->arrDatos['arrPaises'] = get_paises();
        
        # tipo de contacto
        $arrTiposContactos = array();
        if(get_lang()=='es'){
            $arrTiposContactos = array(
                array('nombre' => 'Ventas'),
                array('nombre' => 'Servicio al Cliente')
            );
        }elseif(get_lang()=='en'){
            $arrTiposContactos = array(
                array('nombre' => 'Sales'),
                array('nombre' => 'Customer Service')
            );
        }
        $this->arrDatos['arrTiposContactos'] = $arrTiposContactos;
    }
     
    public function index(){
	$this->load->view('includes/template',$this->arrDatos);
    }
    
    public function send(){
        //echo '<pre>',print_r($_POST),'</pre>';echo '<pre>',print_r($_FILES),'</pre>';
        $arrValidaciones = array(
            array(
                'field' => 'selTipoContacto',
                'label' => lang('field_contact_type'),
                'rules' => 'required'
            ),
            array(
                'field' => 'txtNombre',
                'label' => lang('field_full_name'),
                'rules' => 'required|min_length[3]|max_length[255]'
            ),
            array(
                'field' => 'txtEmail',
                'label' => 'Email',
                'rules' => 'required|min_length[3]|max_length[255]|valid_email'
            ),
            array(
                'field' => 'txtCompany',
                'label' => lang('field_company'),
                'rules' => 'min_length[3]|max_length[255]'
            ),
            array(
                'field' => 'selPaises',
                'label' => lang('field_country'),
                'rules' => 'required'
            ),
            array(
                'field' => 'txtAddressCompany',
                'label' => lang('field_address_company'),
                'rules' => 'min_length[3]|max_length[255]'
            ),
            array(
                'field' => 'txtCountryCode',
                'label' => lang('field_country_code'),
                'rules' => 'integer|min_length[2]|max_length[5]'
            ),
            array(
                'field' => 'txtCityCode',
                'label' => lang('field_city_code'),
                'rules' => 'integer|min_length[2]|max_length[10]'
            ),
            array(
                'field' => 'txtPhoneNumber',
                'label' => lang('field_number_phone'),
                'rules' => 'integer|min_length[3]|max_length[20]'
            ),
            array(
                'field' => 'txtaComentarios',
                'label' => lang('field_comments'),
                'rules' => 'required|min_length[3]|max_length[10000]'
            )
        );
        $this->form_validation->set_rules($arrValidaciones);
        $selTipoContacto   = $this->input->post('selTipoContacto');
        $txtNombre         = $this->input->post('txtNombre');
        $txtEmail          = $this->input->post('txtEmail');
        $txtCompany        = $this->input->post('txtCompany');
        $selPaises         = $this->input->post('selPaises');
        $txtAddressCompany = $this->input->post('txtAddressCompany');
        $txtCountryCode    = $this->input->post('txtCountryCode');
        $txtCityCode       = $this->input->post('txtCityCode');
        $txtPhoneNumber    = $this->input->post('txtPhoneNumber');
        $txtaComentarios   = $this->input->post('txtaComentarios');
        
        $this->arrDatos['selTipoContacto']   = set_value('selTipoContacto');
        $this->arrDatos['txtNombre']         = set_value('txtNombre');
        $this->arrDatos['txtEmail']          = set_value('txtEmail');
        $this->arrDatos['txtCompany']        = set_value('txtCompany');
        $this->arrDatos['selPaises']         = set_value('selPaises');
        $this->arrDatos['txtAddressCompany'] = set_value('txtAddressCompany');
        $this->arrDatos['txtCountryCode']    = set_value('txtCountryCode');
        $this->arrDatos['txtCityCode']       = set_value('txtCityCode');
        $this->arrDatos['txtPhoneNumber']    = set_value('txtPhoneNumber');
        $this->arrDatos['txtaComentarios']   = set_value('txtaComentarios');
        if($this->form_validation->run() == FALSE){
            if(form_error('selTipoContacto')){
                $this->arrDatos['sMsjError'] = form_error('selTipoContacto');
            }elseif(form_error('txtNombre')){
                $this->arrDatos['sMsjError'] = form_error('txtNombre');
            }elseif(form_error('txtEmail')){
                $this->arrDatos['sMsjError'] = form_error('txtEmail');
            }elseif(form_error('txtCompany')){
                $this->arrDatos['sMsjError'] = form_error('txtCompany');
            }elseif(form_error('selPaises')){
                $this->arrDatos['sMsjError'] = form_error('selPaises');
            }elseif(form_error('txtAddressCompany')){
                $this->arrDatos['sMsjError'] = form_error('txtAddressCompany');
            }elseif(form_error('txtCountryCode')){
                $this->arrDatos['sMsjError'] = form_error('txtCountryCode');
            }elseif(form_error('txtCityCode')){
                $this->arrDatos['sMsjError'] = form_error('txtCityCode');
            }elseif(form_error('txtPhoneNumber')){
                $this->arrDatos['sMsjError'] = form_error('txtPhoneNumber');
            }elseif(form_error('txtaComentarios')){
                $this->arrDatos['sMsjError'] = form_error('txtaComentarios');
            }
        }else{
            $txtNombre         = html_escape($txtNombre);
            $txtEmail          = html_escape($txtEmail);
            $txtCompany        = html_escape($txtCompany);
            $selPaises         = html_escape($selPaises);
            $txtAddressCompany = html_escape($txtAddressCompany);
            $txtCountryCode    = html_escape($txtCountryCode);
            $txtCityCode       = html_escape($txtCityCode);
            $txtPhoneNumber    = html_escape($txtPhoneNumber);        
            $txtaComentarios   = html_escape($txtaComentarios);
        
            $mensaje = "Mensaje enviado desde el formulario de contacto de EMG.
                        <br>Tipo de contacto: $selTipoContacto
                        <br>Enviado por: $txtNombre
                        <br>Email: $txtEmail
                        <br>Empresa: $txtCompany
                        <br>País: $selPaises
                        <br>Dirección de la Empresa: $txtAddressCompany
                        <br>Teléfono de contacto: $txtCountryCode - $txtCityCode - $txtPhoneNumber
                        <br>Comentarios: $txtaComentarios";

            # el email lo envia el usuario que contacta
            if(send_email(get_config_db('email_contact'),'Contact from EMG International',$mensaje,$txtNombre,$txtEmail,FALSE)){
                $this->arrDatos['sMsjConf'] = lang('msjconf_contact'); 
                $this->arrDatos['selTipoContacto']   = '';
                $this->arrDatos['txtNombre']         = '';
                $this->arrDatos['txtEmail']          = '';
                $this->arrDatos['txtCompany']        = '';
                $this->arrDatos['selPaises']         = '';
                $this->arrDatos['txtAddressCompany'] = '';
                $this->arrDatos['txtCountryCode']    = '';
                $this->arrDatos['txtCityCode']       = '';
                $this->arrDatos['txtPhoneNumber']    = '';
                $this->arrDatos['txtaComentarios']   = '';
            }else{
                $this->arrDatos['sMsjError'] = lang('msjerror_contact');
            }
        }
        $this->index();
    }
}