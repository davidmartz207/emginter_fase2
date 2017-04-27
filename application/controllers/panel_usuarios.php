<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_usuarios extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library(array('pagination','form_validation'));
        $this->load->model('panel_usuarios_m','modelo');
        $this->arrDatos['title']     = 'Panel Usuarios';
        $this->arrDatos['vista']     = 'panel_usuarios_list_v';
        $this->arrDatos['sMsjError'] = '';
        
        $this->arrDatos['arrTiposUsuarios'] = $this->modelo->get_tipos_usuarios();
        
        // tamaño maximo de las imagenes a subir
        $this->max_size_file     = 3000000;#2mb
        $this->str_max_size_file = '3MB';
        $this->width_image       = 300;#px
        $this->hight_image       = 300;#px;
        
        if(!user_is_admin()){
            redirect('panel_inicio');
        }
    }

    public function index(){
        #
        if($this->session->userdata('sMsjConf')){
            $this->arrDatos['sMsjConf'] = $this->session->userdata('sMsjConf');
            $this->session->set_userdata('sMsjConf','');
        }elseif($this->session->userdata('sMsjError')){
            $this->arrDatos['sMsjError'] = $this->session->userdata('sMsjError');
            $this->session->set_userdata('sMsjError','');
        }      
        
        $config['base_url']            = site_url('panel_usuarios').'/index/';
        $config['total_rows']          = $this->modelo->get_rows();
        $config['per_page']            = 40;#max
        $config['uri_segment']         = 4;
        
        $selOrderBy                    = $this->input->post('selOrderBy');
        $selUpDown                     = $this->input->post('selUpDown');
        if(empty($selOrderBy) and $this->session->userdata('panel_usuarios_order_by')){
            $selOrderBy = $this->session->userdata('panel_usuarios_order_by');
        }else{
            $this->session->set_userdata('panel_usuarios_order_by',$selOrderBy);
        }
        if(empty($selUpDown) and $this->session->userdata('panel_usuarios_selUpDown')){
            $selUpDown = $this->session->userdata('panel_usuarios_selUpDown');
        }else{
            $this->session->set_userdata('panel_usuarios_selUpDown',$selUpDown);
        }
        
        // para filtrar los usuarios no aprobados
        $search_estatus = $this->session->userdata('search_estatus');
        $search_estatus = (int)$search_estatus;
        
        $desde                       = ($this->uri->segment($config['uri_segment'])) ? $this->uri->segment($config['uri_segment']) : 0;
        $this->arrDatos['arrResult'] = $this->modelo->get_usuarios($search_estatus,$config['per_page'],$desde,$selOrderBy,$selUpDown);
        
        // reset al search_estatus
        $this->session->set_userdata('search_estatus',0);
        
        $config['num_links']           = round($config['total_rows']/$config['per_page']);
        //$config['page_query_string'] = TRUE;
        // $config['use_page_numbers'] = TRUE;
        //$config['query_string_segment'] = 'page';
        $config['first_link'] = false;
        //$config['last_link'] = false;

        $config['full_tag_open']       = '<ul class="pagination">';
        $config['full_tag_close']      = '</ul>';

        /*$config['first_link'] = '&laquo; Primero';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
         * 
         */
        $config['last_link'] = 'Último &raquo;';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = 'Próximo <span class="fa fa-arrow-circle-o-right"></span>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<span class="fa fa-arrow-circle-o-left"></span> Anterior';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        // $config['display_pages'] = FALSE;
        //$config['anchor_class'] = 'follow_link';
        #-------------------------------------------------------------
        $this->pagination->initialize($config);
        $this->arrDatos['pagination']  = $this->pagination->create_links();
	$this->load->view('panel/includes/template',$this->arrDatos);
    }
    
    public function ins(){
        //echo '<pre>',print_r($_POST),'</pre>';echo '<pre>',print_r($_FILES),'</pre>';       
        $arrValidaciones = array(
            array(
                'field' => 'selTiposUsuarios',
                'label' => 'Tipos de Usuarios',
                'rules' => 'required|integer'
            ),
            array(
                'field' => 'selEstatus',
                'label' => 'Estatus Inicial',
                'rules' => 'required|integer'
            ),
            array(
                'field' => 'txtNombre',
                'label' => lang('field_first_name'),
                'rules' => 'required|max_length[255]'
            ),
            array(
                'field' => 'txtApellido',
                'label' => lang('field_last_name'),
                'rules' => 'required|max_length[255]'
            ),
            array(
                'field' => 'txtEmail',
                'label' => lang('field_email_sm'),
                'rules' => 'required|max_length[255]|valid_email|callback__invalido_email'
            ),
            array(
                'field' => 'txtCompany',
                'label' => lang('field_company'),
                'rules' => 'required|max_length[255]'
            ),
            array(
                'field' => 'txtAddressCompany',
                'label' => lang('field_address_company'),
                'rules' => 'max_length[10000]'
            ),
            array(
                'field' => 'txtPhoneNumber',
                'label' => lang('field_number_phone'),
                'rules' => 'required|integer|max_length[50]'
            ),
            array(
                'field' => 'txtFax',
                'label' => lang('field_fax'),
                'rules' => 'integer|max_length[50]'
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
        $selTiposUsuarios  = $this->input->post('selTiposUsuarios');
        $txtNombre         = $this->input->post('txtNombre');
        $txtApellido       = $this->input->post('txtApellido');
        $txtEmail          = $this->input->post('txtEmail');
        $txtCompany        = $this->input->post('txtCompany');
        $txtAddressCompany = $this->input->post('txtAddressCompany');
        $txtPhoneNumber    = $this->input->post('txtPhoneNumber');
        $txtFax            = $this->input->post('txtFax');
        $txtPassword       = $this->input->post('txtPassword');
        $selEstatus        = $this->input->post('selEstatus');
        
        $this->arrDatos['selTiposUsuarios']  = (int)$selTiposUsuarios;
        $this->arrDatos['txtNombre']         = html_escape($txtNombre);
        $this->arrDatos['txtApellido']       = html_escape($txtApellido);
        $this->arrDatos['txtEmail']          = html_escape($txtEmail);
        $this->arrDatos['txtCompany']        = html_escape($txtCompany);
        $this->arrDatos['txtAddressCompany'] = html_escape($txtAddressCompany);
        $this->arrDatos['txtPhoneNumber']    = html_escape($txtPhoneNumber);
        $this->arrDatos['txtFax']            = html_escape($txtFax);
        $this->arrDatos['txtPassword']       = html_escape($txtPassword);
        $this->arrDatos['selEstatus']        = (int)$selEstatus;
        
        //echo '<pre>',print_r($this->arrDatos),'</pre>';exit;
                
        if($this->form_validation->run() == FALSE){
            if(form_error('selTiposUsuarios')){
                $this->arrDatos['sMsjError'] = form_error('selTiposUsuarios');
            }elseif(form_error('txtNombre')){
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
            }elseif(form_error('selEstatus')){
                $this->arrDatos['sMsjError'] = form_error('selEstatus');
            }
        }else{
            if($this->modelo->registrar($this->arrDatos)){
                
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
                $mensaje = 'Se ha registrado un usuario desde el panel de control de EMG International,
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
                
                $this->arrDatos['sMsjConf'] = 'Registrado con éxito';
                $this->arrDatos['selTiposUsuarios']  = '';
                $this->arrDatos['txtNombre']         = '';
                $this->arrDatos['txtApellido']       = '';
                $this->arrDatos['txtEmail']          = '';
                $this->arrDatos['txtCompany']        = '';
                $this->arrDatos['txtAddressCompany'] = '';
                $this->arrDatos['txtPhoneNumber']    = '';
                $this->arrDatos['txtFax']            = '';
                $this->arrDatos['selEstatus']        = '';
                $this->arrDatos['txtPassword']       = '';
            }else{
                $this->arrDatos['sMsjError'] = lang('msjerror_register');
            }
        }
        $this->arrDatos['title'] = 'Crear usuario';
        $this->arrDatos['vista'] = 'panel_usuarios_ins_v';
        $this->session->set_userdata('controller','panel_usuarios');
        $this->load->view('panel/includes/template',$this->arrDatos);
        //echo '<pre>',print_r($this->arrDatos),'</pre>';
    }

    public function upd($id=''){
        //echo '<pre>',print_r($_POST),'</pre>';exit;
        $id = (int)$id;
        if(!empty($id)){
            if($this->input->post('btUpdate')){
                $this->session->set_userdata('submit',1);
                $arrValidaciones = array(
                    array(
                        'field' => 'selTiposUsuarios',
                        'label' => 'Tipos de Usuarios',
                        'rules' => 'required|integer'
                    ),
                    array(
                        'field' => 'selEstatus',
                        'label' => 'Estatus Inicial',
                        'rules' => 'required|integer'
                    ),
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
                        'rules' => 'required|integer|min_length[3]|max_length[50]'
                    ),
                    array(
                        'field' => 'txtFax',
                        'label' => lang('field_fax'),
                        'rules' => 'integer|min_length[3]|max_length[50]'
                    ),
                    array(
                        'field' => 'txtPassword',
                        'label' => lang('field_password_sm'),
                        'rules' => 'min_length[6]|max_length[30]'
                    ),
                    array(
                        'field' => 'txtPassword2',
                        'label' => lang('field_confirm_password'),
                        'rules' => 'matches[txtPassword]'
                    )
                );
                $this->form_validation->set_rules($arrValidaciones);
                $selTiposUsuarios  = $this->input->post('selTiposUsuarios');
                $txtNombre         = $this->input->post('txtNombre');
                $txtApellido       = $this->input->post('txtApellido');
                $txtEmail          = $this->input->post('txtEmail');
                $txtCompany        = $this->input->post('txtCompany');
                $txtAddressCompany = $this->input->post('txtAddressCompany');
                $txtPhoneNumber    = $this->input->post('txtPhoneNumber');
                $txtFax            = $this->input->post('txtFax');
                $txtPassword       = $this->input->post('txtPassword');
                $selEstatus        = $this->input->post('selEstatus');

                $this->arrDatos['selTiposUsuarios']  = (int)$selTiposUsuarios;
                $this->arrDatos['txtNombre']         = html_escape($txtNombre);
                $this->arrDatos['txtApellido']       = html_escape($txtApellido);
                $this->arrDatos['txtEmail']          = html_escape($txtEmail);
                $this->arrDatos['txtCompany']        = html_escape($txtCompany);
                $this->arrDatos['txtAddressCompany'] = html_escape($txtAddressCompany);
                $this->arrDatos['txtPhoneNumber']    = html_escape($txtPhoneNumber);
                $this->arrDatos['txtFax']            = html_escape($txtFax);
                $this->arrDatos['txtPassword']       = html_escape($txtPassword);
                $this->arrDatos['selEstatus']        = (int)$selEstatus;
                $this->arrDatos['imagen']            = '';
                //echo '<pre>',print_r($this->arrDatos),'</pre>';exit;

                $this->arrDatos['id']                = $this->input->post('hddId');
                $this->arrDatos['id']                = (int)$this->arrDatos['id'];
                $this->arrDatos['imagen_actual']     = $this->input->post('hddImage');
                $this->arrDatos['hddEstatus']        = $this->input->post('hddEstatus');
                
                if($this->form_validation->run() == FALSE){
                    if(form_error('selTiposUsuarios')){
                        $this->arrDatos['sMsjError'] = form_error('selTiposUsuarios');
                    }elseif(form_error('txtNombre')){
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
                    }elseif(form_error('selEstatus')){
                        $this->arrDatos['sMsjError'] = form_error('selEstatus');
                    }
                }else{
                    if($this->modelo->set_data($this->arrDatos)){
                        $this->session->set_userdata('sMsjConf_update','Actualizado con éxito');
                        redirect('panel_usuarios/upd/'.$id);
                    }else{
                        $this->arrDatos['sMsjError'] = lang('msjerror_update');
                    }
                }
            }else{
                $arrDatos = $this->modelo->get_data($id);
                if(is_array($arrDatos) and count($arrDatos)>0){
                    $this->arrDatos['id']                = $id;
                    $this->arrDatos['hddEstatus']        = $arrDatos['estatus'];
                    $this->arrDatos['selTiposUsuarios']  = $arrDatos['id_tipo_usuario'];
                    $this->arrDatos['txtNombre']         = $arrDatos['nombre'];
                    $this->arrDatos['txtApellido']       = $arrDatos['apellido'];
                    $this->arrDatos['txtEmail']          = $arrDatos['email'];
                    $this->arrDatos['txtCompany']        = $arrDatos['empresa'];
                    $this->arrDatos['txtAddressCompany'] = $arrDatos['direccion_empresa'];
                    $this->arrDatos['txtPhoneNumber']    = $arrDatos['telefono'];
                    $this->arrDatos['txtFax']            = $arrDatos['fax'];
                    $this->arrDatos['imagen_actual']     = $arrDatos['path_img'];
                    $this->arrDatos['selEstatus']        = $arrDatos['estatus'];
                    
                    if($this->session->userdata('submit')){
                        $this->arrDatos['sMsjConf'] = $this->session->userdata('sMsjConf_update');
                    }
                    //echo '<pre>',print_r($arrDatos),'</pre>';exit;
                }
            }
            $this->arrDatos['title'] = 'Edit Usuario';
            $this->arrDatos['vista'] = 'panel_usuarios_upd_v';
            $this->session->set_userdata('controller','panel_usuarios');
            $this->load->view('panel/includes/template',$this->arrDatos);
            //echo '<pre>',print_r($this->arrDatos),'</pre>';
        }else {
            redirect('panel_inicio');
        }
    }
    
    function _invalido_email($email){
        $id = $this->input->post('hddId');
        $id = (int)$id;
        if($this->modelo->existe_email($email,$id)){
          $this->form_validation->set_message('_invalido_email', lang('email_registered'));
          return FALSE;
        }else{
          return TRUE;
        }
    }
    
    function users_in_wait(){
        $this->session->set_userdata('search_estatus',2);
        $this->index();
    }
    
         
    function enabled_user($id,$email){
        if(!user_is_admin() or !user_is_operador()){
            $id = (int)$id;
            if(!empty($id) and !empty($email)){
                if($this->modelo->enabled_user($id)){
                    $this->session->set_userdata('sMsjConf','La cuenta de usuario fue activada');

                    $txtEmail           = urldecode($email);
                    $txtEmail           = base64_decode($txtEmail);
                    $EMG_email_contacto = get_config_db('email_contact');
                    $fecha              = date('d/m/Y H:i:s');
                    $mensaje_footer     = '<br><hr><br>
                                            Thank you / Gracias, 
                                            <br>EMG International
                                            <br><a target="_blank" href="'.site_url('login').'">Emginter.com</a>';

                    # ----------------------------------------------------------
                    # email al administrador -----------------------------------
                    # ----------------------------------------------------------
                    $mensaje = 'Se ha aprobado la cuenta <b>'.$txtEmail.'</b> el '
                               .$fecha.' por un administrador';
                    $mensaje .= $mensaje_footer;
                    send_email($EMG_email_contacto,'EMG International - Se ha aprobado una cuenta de usuario'
                               ,$mensaje,'EMG International',$EMG_email_contacto);

                    # ----------------------------------------------------------
                    # email al usuario -----------------------------------------
                    # ----------------------------------------------------------
                    $mensaje_ingles   = get_config_db('mensaje_registro_aprobacion',2);
                    $mensaje_spanish  = get_config_db('mensaje_registro_aprobacion',3);
                    $mensaje   =  $mensaje_ingles.'<br><br>'.$mensaje_spanish;
                    $mensaje .= $mensaje_footer;
                    send_email($txtEmail,'Your Account at EMG International / Su cuenta en EMG International'
                               ,$mensaje,'EMG International',$EMG_email_contacto);
                }else{
                    $this->session->set_userdata('sMsjError','No se pudo activar el usuario');
                }
            }
        }else{
            $this->session->set_userdata('sMsjError','Usted no tiene permisos para aprobar usuarios');
        }
        redirect('panel_usuarios/users_in_wait');
    }
    
    // gestiona la eliminacion
    function del($id){
        $id = (int)$id;
        // solo los administradores y operadores pueden eliminar
        if(empty($id)){
            $this->session->set_userdata('sMsjError','¡Incorrect parameters.!');
        }elseif(!user_is_admin() and !user_is_operador()){
            $this->session->set_userdata('sMsjError','¡Your account does not have permission to delete data!');  
        }else{
            $this->modelo->delete($id);
            $this->session->set_userdata('sMsjConf','cuenta de usuario eliminada con éxito.');
        }
        redirect('panel_usuarios');
    }
}