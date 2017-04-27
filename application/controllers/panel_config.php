<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Panel_config extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library(array('pagination','form_validation'));
        $this->load->model('panel_config_m','modelo');
        $this->arrDatos['title']     = 'Panel Configuraciones';
        $this->arrDatos['vista']     = 'panel_config_list_v';
        $this->arrDatos['sMsjError'] = '';
        
        $this->_init_chkeditor();
    }
    
    public function index(){
        $config['base_url']            = site_url('panel_config').'/index/';
        $config['total_rows']          = $this->modelo->get_rows();
        $config['per_page']            = 40;#max
        $config['uri_segment']         = 4;
        
        $selOrderBy                    = $this->input->post('selOrderBy');
        $selUpDown                     = $this->input->post('selUpDown');
        if(empty($selOrderBy) and $this->session->userdata('panel_config_order_by')){
            $selOrderBy = $this->session->userdata('panel_config_order_by');
        }else{
            $this->session->set_userdata('panel_config_order_by',$selOrderBy);
        }
        if(empty($selUpDown) and $this->session->userdata('panel_config_selUpDown')){
            $selUpDown = $this->session->userdata('panel_config_selUpDown');
        }else{
            $this->session->set_userdata('panel_config_selUpDown',$selUpDown);
        }
        $desde                       = ($this->uri->segment($config['uri_segment'])) ? $this->uri->segment($config['uri_segment']) : 0;
        $this->arrDatos['arrResult'] = $this->modelo->get_all_data($config['per_page'],$desde,$selOrderBy,$selUpDown);
        
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

    public function upd($data=''){        
        //echo '<pre>',print_r($_POST),'</pre>';

        // este parametro es obligatorio, debe ser el id del registro
        if(!empty($data)){
            // forzamos el valor integer
            $id = (int)$data;
            
            // hicieron click en el boton de Edit...
            if($this->input->post('btEditar')){
                $arrValidaciones = array(
                    array(
                        'field' => 'txtTelefono',
                        'label' => 'Teléfono',
                        'rules' => 'required|max_length[255]'
                    ),
                    array(
                        'field' => 'txtFax',
                        'label' => 'Fax',
                        'rules' => 'max_length[255]'
                    ),
                    array(
                        'field' => 'txtEmail_contact',
                        'label' => 'Email de Contacto',
                        'rules' => 'required|max_length[255]|valid_email'
                    ),
                    array(
                        'field' => 'txtEmail_public',
                        'label' => 'Email de Pedidos',
                        'rules' => 'required|max_length[255]|valid_email'
                    ),
                    array(
                        'field' => 'txtaDireccion',
                        'label' => 'Dirección',
                        'rules' => 'required|max_length[10000]'
                    ),
                    array(
                        'field' => 'txtaHorariosJornadaLaboral',
                        'label' => 'Horarios Jornada Laboral',
                        'rules' => 'required|max_length[10000]'
                    ),
                    array(
                        'field' => 'txtaMsjRegistro',
                        'label' => 'Mensaje de Registro',
                        'rules' => 'required|max_length[10000]'
                    ),
                    array(
                        'field' => 'txtaMsjRegistroAprobacion',
                        'label' => 'Mensaje de aprobacion de registro',
                        'rules' => 'required|max_length[10000]'
                    ),
                    array(
                        'field' => 'txtaTextoProductos',
                        'label' => 'Mensaje Página de Productos',
                        'rules' => 'max_length[10000]'
                    ),
                    array(
                        'field' => 'txtaTextoGuiaProductos',
                        'label' => 'Mensaje Página Guía de Productos',
                        'rules' => 'max_length[10000]'
                    ),
                    array(
                        'field' => 'txtaTextoContacto',
                        'label' => 'Mensaje Página de Contacto',
                        'rules' => 'max_length[10000]'
                    ),
                    array(
                        'field' => 'selEstatus',
                        'label' => 'Estatus',
                        'rules' => 'required|integer'
                    )
                );
                $this->form_validation->set_rules($arrValidaciones);
                
                # define as global - $pre_filter['key'];
                global $pre_filter;
                //$this->config->set_item('global_xss_filtering',FALSE);
                
                $txtTelefono                = $this->input->post('txtTelefono');
                $txtFax                     = $this->input->post('txtFax');
                $txtEmail_contact           = $this->input->post('txtEmail_contact');
                $txtEmail_public            = $this->input->post('txtEmail_public');
                $txtaDireccion              = $pre_filter['txtaDireccion'];
                $txtaHorariosJornadaLaboral = $pre_filter['txtaHorariosJornadaLaboral'];
                $txtaMsjRegistro            = $pre_filter['txtaMsjRegistro'];
                $txtaMsjRegistroAprobacion  = $pre_filter['txtaMsjRegistroAprobacion'];
                $txtaTextoProductos         = $pre_filter['txtaTextoProductos'];
                $txtaTextoGuiaProductos     = $pre_filter['txtaTextoGuiaProductos'];
                $txtaTextoContacto          = $pre_filter['txtaTextoContacto'];
                $estatus                    = $this->input->post('selEstatus');

                $this->arrDatos['txtTelefono']                  = html_escape($txtTelefono);
                $this->arrDatos['txtFax']                       = html_escape($txtFax);
                $this->arrDatos['txtEmail_contact']             = html_escape($txtEmail_contact);
                $this->arrDatos['txtEmail_public']              = html_escape($txtEmail_public);
                $this->arrDatos['txtaDireccion']                = $txtaDireccion;
                $this->arrDatos['txtaHorariosJornadaLaboral']   = $txtaHorariosJornadaLaboral;
                $this->arrDatos['txtaMsjRegistro']              = $txtaMsjRegistro;
                $this->arrDatos['txtaMsjRegistroAprobacion']    = $txtaMsjRegistroAprobacion;
                $this->arrDatos['txtaTextoProductos']           = $txtaTextoProductos;
                $this->arrDatos['txtaTextoGuiaProductos']       = $txtaTextoGuiaProductos;
                $this->arrDatos['txtaTextoContacto']            = $txtaTextoContacto;
                $this->arrDatos['selEstatus']                   = $estatus;
                $this->arrDatos['id']                           = (int)$this->input->post('hddId');
                //echo 'arrDatos: <pre>',print_r($this->arrDatos),'</pre>';exit;

                if($this->form_validation->run() == FALSE){
                    if(form_error('txtTelefono')){
                        $this->arrDatos['sMsjError'] = form_error('txtTelefono');
                    }elseif(form_error('txtFax')){
                        $this->arrDatos['sMsjError'] = form_error('txtFax');
                    }elseif(form_error('txtEmail_contact')){
                        $this->arrDatos['sMsjError'] = form_error('txtEmail_contact');
                    }elseif(form_error('txtEmail_public')){
                        $this->arrDatos['sMsjError'] = form_error('txtEmail_public');
                    }elseif(form_error('txtaDireccion')){
                        $this->arrDatos['sMsjError'] = form_error('txtaDireccion');
                    }elseif(form_error('txtaHorariosJornadaLaboral')){
                        $this->arrDatos['sMsjError'] = form_error('txtaHorariosJornadaLaboral');
                    }elseif(form_error('txtaMsjRegistro')){
                        $this->arrDatos['sMsjError'] = form_error('txtaMsjRegistro');
                    }elseif(form_error('txtaMsjRegistroAprobacion')){
                        $this->arrDatos['sMsjError'] = form_error('txtaMsjRegistroAprobacion');
                    }elseif(form_error('texto_productos')){
                        $this->arrDatos['sMsjError'] = form_error('texto_productos');
                    }elseif(form_error('texto_guia_productos')){
                        $this->arrDatos['sMsjError'] = form_error('texto_guia_productos');
                    }elseif(form_error('texto_contacto')){
                        $this->arrDatos['sMsjError'] = form_error('texto_contacto');
                    }elseif(form_error('selEstatus')){
                        $this->arrDatos['sMsjError'] = form_error('selEstatus');
                    }
                }else{
                    // actualizamos los datos
                    if($this->modelo->set_data($this->arrDatos)){
                        $this->session->set_userdata('controller','panel_config');
                        
                        // indicamos que se actualizo el registro
                        $this->session->set_userdata('submit',1);
                        $this->session->set_userdata('msj','¡Successfully updated data');
                        
                        // recargamos la vista con lo nuevos datos
                        redirect('panel_config/upd/'.$this->arrDatos['id']);
                    }else{
                        $this->arrDatos['sMsjError'] = 'Unable to update';
                    }
                }
            }else{
                $arrDatos = $this->modelo->get_data($id);
                if(is_array($arrDatos) and count($arrDatos)>0){
                    $this->arrDatos['id']                         = $id;
                    $this->arrDatos['selIdiomas']                 = $arrDatos['idioma'];
                    $this->arrDatos['txtTelefono']                = $arrDatos['telefono'];
                    $this->arrDatos['txtFax']                     = $arrDatos['fax'];
                    $this->arrDatos['txtEmail_contact']           = $arrDatos['email_contact'];
                    $this->arrDatos['txtEmail_public']            = $arrDatos['email_public'];
                    $this->arrDatos['txtaDireccion']              = $arrDatos['direccion'];
                    $this->arrDatos['txtaHorariosJornadaLaboral'] = $arrDatos['horarios_jornada_laboral'];
                    
                    $this->arrDatos['txtaMsjRegistro']            = $arrDatos['mensaje_registro'];
                    $this->arrDatos['txtaMsjRegistroAprobacion']  = $arrDatos['msj_registro_aprobacion'];
                    
                    $this->arrDatos['txtaTextoProductos']         = $arrDatos['texto_productos'];
                    $this->arrDatos['txtaTextoGuiaProductos']     = $arrDatos['texto_guia_productos'];
                    $this->arrDatos['txtaTextoContacto']          = $arrDatos['texto_contacto'];
                    $this->arrDatos['selEstatus']                 = $arrDatos['estatus'];
                    //echo '<pre>',print_r($arrDatos),'</pre>';exit;
                    
                    // cuando actualizamos, recargamos la web con los nuevos datos
                    // en ese caso mostramos un mensaje de confirmacion
                    if($this->session->userdata('submit')==1){
                        $this->arrDatos['sMsjConf'] = $this->session->userdata('msj');
                        // reset
                        $this->session->set_userdata('submit',0);
                    }
                }
            }

            $this->arrDatos['title'] = 'Edit Configuración';
            $this->arrDatos['vista'] = 'panel_config_upd_v';
            // indico en que controlador estoy..
            $this->session->set_userdata('controller','panel_config');
	    $this->load->view('panel/includes/template',$this->arrDatos);
            //echo '<pre>',print_r($this->arrDatos),'</pre>';
        }else{
            redirect('panel_inicio');
        }
    }
    
    public function _init_chkeditor(){
        # ckEditor *************************************************************
        # fuente: http://nukium.com/developpement/php-frameworks/codeigniter/ckeditor-helper-for-codeigniter/
        $this->load->helper('ckeditor');
        //Ckeditor's configuration
        // txtaTextoContacto  txtaTextoGuiaProductos   txtaTextoProductos
	$this->arrDatos['ckeditor_txtaDireccion'] = array(
            //ID of the textarea that will be replaced
            'id' 	=> 	'txtaDireccion',
            'path'	=>	'includes/js/ckeditor',

            //Optionnal values
            'config' => array(
                'toolbar'   => 	'Full', //Using the Full toolbar
                'width'     => 	'100%',	//Setting a custom width
                'height'    => 	'300px',//Setting a custom height

                # kcFinder
                'filebrowserImageBrowseUrl' => base_url().'includes/js/ckeditor/kcfinder/browse.php?Type=images',
                'filebrowserImageUploadUrl' => base_url().'includes/js/ckeditor/kcfinder/upload.php?Type=images',
            )
	);
        $this->arrDatos['ckeditor_txtaHorariosJornadaLaboral'] = array(
            //ID of the textarea that will be replaced
            'id' 	=> 	'txtaHorariosJornadaLaboral',
            'path'	=>	'includes/js/ckeditor',

            //Optionnal values
            'config' => array(
                'toolbar'   => 	'Full', //Using the Full toolbar
                'width'     => 	'100%',	//Setting a custom width
                'height'    => 	'300px',//Setting a custom height

                # kcFinder
                'filebrowserImageBrowseUrl' => base_url().'includes/js/ckeditor/kcfinder/browse.php?Type=images',
                'filebrowserImageUploadUrl' => base_url().'includes/js/ckeditor/kcfinder/upload.php?Type=images',
            )
	);
        $this->arrDatos['ckeditor_txtaMsjRegistro'] = array(
            //ID of the textarea that will be replaced
            'id' 	=> 	'txtaMsjRegistro',
            'path'	=>	'includes/js/ckeditor',

            //Optionnal values
            'config' => array(
                'toolbar'   => 	'Full', //Using the Full toolbar
                'width'     => 	'100%',	//Setting a custom width
                'height'    => 	'300px',//Setting a custom height

                # kcFinder
                'filebrowserImageBrowseUrl' => base_url().'includes/js/ckeditor/kcfinder/browse.php?Type=images',
                'filebrowserImageUploadUrl' => base_url().'includes/js/ckeditor/kcfinder/upload.php?Type=images',
            )
	);
        $this->arrDatos['ckeditor_txtaMsjRegistroAprobacion'] = array(
            //ID of the textarea that will be replaced
            'id' 	=> 	'txtaMsjRegistroAprobacion',
            'path'	=>	'includes/js/ckeditor',

            //Optionnal values
            'config' => array(
                'toolbar'   => 	'Full', //Using the Full toolbar
                'width'     => 	'100%',	//Setting a custom width
                'height'    => 	'300px',//Setting a custom height

                # kcFinder
                'filebrowserImageBrowseUrl' => base_url().'includes/js/ckeditor/kcfinder/browse.php?Type=images',
                'filebrowserImageUploadUrl' => base_url().'includes/js/ckeditor/kcfinder/upload.php?Type=images',
            )
	);
        $this->arrDatos['ckeditor_txtaTextoContacto'] = array(
            //ID of the textarea that will be replaced
            'id' 	=> 	'txtaTextoContacto',
            'path'	=>	'includes/js/ckeditor',

            //Optionnal values
            'config' => array(
                'toolbar'   => 	'Full', //Using the Full toolbar
                'width'     => 	'100%',	//Setting a custom width
                'height'    => 	'300px',//Setting a custom height

                # kcFinder
                'filebrowserImageBrowseUrl' => base_url().'includes/js/ckeditor/kcfinder/browse.php?Type=images',
                'filebrowserImageUploadUrl' => base_url().'includes/js/ckeditor/kcfinder/upload.php?Type=images',
            )
	);
        $this->arrDatos['ckeditor_txtaTextoGuiaProductos'] = array(
            //ID of the textarea that will be replaced
            'id' 	=> 	'txtaTextoGuiaProductos',
            'path'	=>	'includes/js/ckeditor',

            //Optionnal values
            'config' => array(
                'toolbar'   => 	'Full', //Using the Full toolbar
                'width'     => 	'100%',	//Setting a custom width
                'height'    => 	'300px',//Setting a custom height

                # kcFinder
                'filebrowserImageBrowseUrl' => base_url().'includes/js/ckeditor/kcfinder/browse.php?Type=images',
                'filebrowserImageUploadUrl' => base_url().'includes/js/ckeditor/kcfinder/upload.php?Type=images',
            )
	);
        $this->arrDatos['ckeditor_txtaTextoProductos'] = array(
            //ID of the textarea that will be replaced
            'id' 	=> 	'txtaTextoProductos',
            'path'	=>	'includes/js/ckeditor',

            //Optionnal values
            'config' => array(
                'toolbar'   => 	'Full', //Using the Full toolbar
                'width'     => 	'100%',	//Setting a custom width
                'height'    => 	'300px',//Setting a custom height

                # kcFinder
                'filebrowserImageBrowseUrl' => base_url().'includes/js/ckeditor/kcfinder/browse.php?Type=images',
                'filebrowserImageUploadUrl' => base_url().'includes/js/ckeditor/kcfinder/upload.php?Type=images',
            )
	);
        # ckEditor *************************************************************
    }
}